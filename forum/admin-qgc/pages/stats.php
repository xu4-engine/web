<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security(); ?>
<?php
//  authors: Rich Davey (rich@atari.org)
//           Brian Moon (brian@phorum.org)
?>
<form action="<?php echo $PHP_SELF; ?>" method="GET">
<input type="hidden" name="page" value="stats" />
<font size="+2"><b>Phorum Stats</b></font><br />
<hr width="100%" size="1" noshade>
<?php

/* Generate drop down list of Phorums */

    $sql="Select id, name from ".$pho_main." where folder=0";
    $q->query($DB, $sql);
    echo $q->error();
    echo "Select a forum : ";
    echo "<select name=\"f\" size=\"1\">\n";

    $forum=$q->firstrow();
    while ($forum) {
        echo "<option value=\"$forum[id]\"";
        if($forum["id"]==$f) echo " selected";
        echo ">" . $forum["name"] . "</option>\n";
        $forum=$q->getrow();
    }

    echo "</select>\n";
    echo " Show data from : ";
    echo "<select name=\"lastdays\" size=\"1\">\n";
    echo "<option value=\"30\">30 Days</option>\n";
    echo "<option value=\"60\">60 Days</option>\n";
    echo "<option value=\"180\">180 Days</option>\n";
    echo "<option value=\"365\">365 Days</option>\n";
    echo "<option value=\"all\">All Time</option>\n";
    echo "</select> &nbsp; \n";
    echo "<input type=\"submit\" name=\"submit\" value=\"Show\">\n";
    echo "<hr width=\"100%\" size=\"1\" noshade>\n";

/* Show all the info */

    if ($ForumName) {

        echo "<font size=\"+1\"><b>Forum: $ForumName</b></font>\n";
        echo "<table width=\"500\" border=\"0\" cellspacing=\"1\" cellpadding=\"10\" bordercolor=\"#000000\" bgcolor=\"#000000\">\n";

        // set up all our dates
        $sql="SELECT max(datestamp) AS max, min(datestamp) AS min FROM ".$ForumTableName;
        $q->query($DB, $sql);
        $row=$q->getrow();

        $last_day=$row["max"];
        $first_day=$row["min"];

        $rpt_end_ts=mktime(23,59,59,substr($last_day, 5,2),substr($last_day, 8,2),substr($last_day, 0,4));
        if($rpt_end_ts == -1) {
           $rpt_end_ts=time();
        }

        $rpt_end_ds=date("Y-m-d H:i:s", $rpt_end_ts);
        $nice_end_date=date("M j, Y", $rpt_end_ts);

        $rpt_start_ts=mktime(0,0,0,substr($last_day, 5,2),substr($last_day, 8,2)-$lastdays,substr($last_day, 0,4));
        $rpt_start_ds=date("Y-m-d H:i:s", $rpt_start_ts);
        if($rpt_start_ds<$first_day || $lastdays=="all"){
            $rpt_start_ts=mktime(0,0,0,substr($first_day, 5,2),substr($first_day, 8,2),substr($first_day, 0,4));
            $nice_start_date=date("M j, Y", $first_day);
            $rpt_start_ds=date("Y-m-d H:i:s", $rpt_start_ts);
        }
        $nice_start_date=date("M j, Y", $rpt_start_ts);

        // get a total for all time
        $sql="SELECT count(*) AS total FROM ".$ForumTableName;
        $q->query($DB, $sql);
        $row=$q->getrow();
        $grand_total=$row["total"];

        // get a total for the slice
        $sql="SELECT count(*) AS total FROM $ForumTableName where datestamp >='$rpt_start_ds'";
        $q->query($DB, $sql);
        $row=$q->getrow();
        $slice_total=$row["total"];

        $avg = number_format($slice_total/(($rpt_end_ts-$rpt_start_ts)/86400), 2, '.', '');

        echo "<tr><td nowrap=\"nowrap\" bgcolor=#F0F0F0 valign=top><strong>Analyzed Dates:</strong></td><td nowrap=\"nowrap\" bgcolor=#F0F0F0>$nice_start_date to $nice_end_date</td></tr>\n";

/* Total Posts */

        echo "<tr><td nowrap=\"nowrap\" bgcolor=#F0F0F0 valign=top><strong>Number of messages:</strong></td><td nowrap=\"nowrap\" bgcolor=#F0F0F0>$slice_total</td></tr>";
        echo "<tr><td nowrap=\"nowrap\" bgcolor=#F0F0F0 valign=top><strong>Average per day:</strong></td><td nowrap=\"nowrap\" bgcolor=#F0F0F0>$avg</td></tr>";

/* Total Unique Posters */

        $sql="SELECT DISTINCT author, email FROM $ForumTableName where datestamp >='$rpt_start_ds'";
        $q->query($DB, $sql);

        if ($q->numrows()>0) {
            $num_authors=$q->numrows();
            echo "<tr><td nowrap=\"nowrap\" bgcolor=#F0F0F0>Total Unique Authors:</td><td nowrap=\"nowrap\" bgcolor=#F0F0F0><strong>$num_authors</strong></td></tr>\n";
        }

/* Total Unique Threads */

        $sql="SELECT count(*) as count FROM $ForumTableName where parent=0 and datestamp >='$rpt_start_ds'";
        $q->query($DB, $sql);

        if ($q->numrows()>0) {
            $num_threads=$q->field("count", 0);
            echo "<tr><td nowrap=\"nowrap\" bgcolor=#F0F0F0>Total Unique Threads:</td><td nowrap=\"nowrap\" bgcolor=#F0F0F0><strong>$num_threads</strong></td></tr>\n";
        }

/* The Top Thread */

        $sql="SELECT thread,count(*) AS cnt FROM $ForumTableName where datestamp >='$rpt_start_ds' GROUP BY thread ORDER BY cnt DESC LIMIT 1";
        $q->query($DB, $sql);

        if ($q->numrows() > 0) {
            $row=$q->getrow();
            $count = $row["cnt"];
            $sql="SELECT subject FROM $ForumTableName where thread=$row[thread] and datestamp >='$rpt_start_ds'";
            $q->query($DB, $sql);
            $subject=$q->field("subject", 0);
            echo "<tr><td nowrap=\"nowrap\" bgcolor=#F0F0F0 valign=top>Most Popular Thread:</td><td nowrap=\"nowrap\" bgcolor=#F0F0F0><strong>$subject</strong><br /><font size=-1>There are $count posts in this thread</td></tr>\n";
        }

/* Top 10 posters */

        $sql="SELECT author,email,count(*) AS cnt FROM $ForumTableName where datestamp >='$rpt_start_ds' GROUP BY author,email ORDER BY cnt DESC LIMIT 10";
        $q->query($DB, $sql);

        if ($q->numrows()>0) {
            if ($q->numrows()>10) {
                $num_authors=$max_authors;
            } else {
                $num_authors=$q->numrows();
            }

            $row=$q->firstrow();
            echo "<tr><td nowrap=\"nowrap\" bgcolor=#F0F0F0 valign=top>Top $num_authors posters:</td><td nowrap=\"nowrap\" bgcolor=#F0F0F0>";

            if ($row) {
                while(is_array($row)){
                    echo $row["author"]."<<a href=\"mailto:$row[email]\">$row[email]</a>><br />";
                    $row=$q->getrow();
                }
            } else {
                echo "No messages";
            }

            echo "</td></tr>";

        }

/* End of table */

        echo "</table>";
    }
?>
</form>
