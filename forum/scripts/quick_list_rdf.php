<?php
// +----------------------------------------------------------------------+
// | This file is used for the syndication of forum topics via RDF.       |
// +----------------------------------------------------------------------+
// |                                                                      |
// | Edit the path to common.php and some information about your site     |
// |                                                                      |
// | Options:                                                             |
// | $num = ID of the forum                                               |
// | $m = Maximum of messages or threads to show.                         |
// | $mode = Mode to show where "t" displays the topics of the last $m    |
// |         threads. If you don't use $mode (default), the topics of     |
// |         the 10 most recent threads will be shown.                    |
// |                                                                      |
// | Examples:                                                            |
// | http://www.server.com/quicklist_rdf.php?num=1&m=20&mode=t            |
// | shows the topics of the 10 most recent 20 threads in forum 1.        |
// |                                                                      |
// | http://www.server.com/quicklist_rdf.php?num=1 shows the topics of    |
// | the 10 most recent messages in forum 1.                              |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Mark Kronsbein <mk@php-homepage.de>                          |
// +----------------------------------------------------------------------+

    $sitename = ""; // e.g. Phorum.org
    chdir("./");  // Path to common.php.


    @require "common.php";

    if (!isset($num)){
        print "Please choose a forum by using \$num!";
        exit;
    }

    if(!isset($m)) {
        $m = 10;   // Number of messages to show in the RDF.
    }

    if ($mode == "t") {
        // Show $m threads
        $SQL="select id, thread, subject from $ForumTableName where thread=id order by thread desc limit $m";
    } else {
        // Show $m mesages (default)
        $SQL="select id, thread, subject from $ForumTableName order by thread desc limit $m";
    }

    print "<?xml version=\"1.0\"?>\n";
    print "<rdf:RDF
            xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
            xmlns=\"http://my.netscape.com/rdf/simple/0.9/\">\n\n";
    print "\t<channel>\n";
    print "\t\t<title>".$sitename." - Forum</title>\n";
    print "\t\t<link>".$PHORUM["forum_url"]."</link>\n";
    print "\t\t<description>Phorum at ".$PHORUM["forum_url"]."</description>\n";
    print "\t\t<language>en-US</language>\n";
    print "\t</channel>\n\n";

    $q->query($DB, $SQL);
    $rec=$q->getrow();
    while(is_array($rec)){
        $title = strip_tags($rec[subject]);
        print "<item>\n";
        print "\t<title>$title</title>\n";
        print "\t<link>".$PHORUM["forum_url"]."/".$PHORUM["read_page"].".".$PHORUM["ext"]."?f=".$n."&amp;i=".$rec["id"]."&amp;t=".$rec["thread"]."</link>\n";
        print "</item>\n\n";
        $rec=$q->getrow();
    }

    print "</rdf:RDF>\n";

?>