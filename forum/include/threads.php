<?php
    if ( !defined( "_COMMON_PHP" ) ) return;

  settype($read, "boolean");

  if (!isset($$phcollapse)) {
    $$phcollapse=0;
  }
?>
<table class="PhorumListTable" width="<?php echo $ForumTableWidth; ?>" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="PhorumListHeader" <?php echo bgcolor($ForumTableHeaderColor); ?>><FONT color="<?php echo $ForumTableHeaderFontColor; ?>">&nbsp;<?php echo $lTopics;?><img src="images/trans.gif" border=0 width=1 height=24 align="absmiddle"></font></td>
    <td class="PhorumListHeader" <?php echo bgcolor($ForumTableHeaderColor); ?> width="150" nowrap="nowrap"><FONT color="<?php echo $ForumTableHeaderFontColor; ?>"><?php echo $lAuthor;?>&nbsp;</font></td>
<?php if ( empty($read) && $$phcollapse != 0) { ?>
    <td class="PhorumListHeader" align="center" <?php echo bgcolor($ForumTableHeaderColor); ?> width="80" nowrap="nowrap"><FONT color="<?php echo $ForumTableHeaderFontColor; ?>"><?php echo $lReplies;?>&nbsp;</font></td>
    <td class="PhorumListHeader" <?php echo bgcolor($ForumTableHeaderColor); ?> width="120" nowrap="nowrap"><FONT color="<?php echo $ForumTableHeaderFontColor; ?>"><?php echo $lLatest;?></font></td>
<?php }else{ ?>
    <td class="PhorumListHeader" <?php echo bgcolor($ForumTableHeaderColor); ?> width="120" nowrap="nowrap"><FONT color="<?php echo $ForumTableHeaderFontColor; ?>"><?php echo $lDate;?></font></td>
<?php } ?>
</tr>
<?php
  $x=0;
  $loc=0;
  @reset($headers);
  $message = @current($headers);
  if(empty($read) && !empty($message)){
    $trec=$threads[$message["thread"]];
  }

  while (is_array($message)){
    if(($x%2)==0){
      $bgcolor=$ForumTableBodyColor1;
      $fcolor=$ForumTableBodyFontColor1;
    }
    else{
      $bgcolor=$ForumTableBodyColor2;
      $fcolor=$ForumTableBodyFontColor2;
    }
    $t_id=$message["id"];
    $t_thread=$message["thread"];
    $t_subject=htmlspecialchars(chop($message["subject"]));
    if(!empty($users[$message["userid"]])){
        $t_author=htmlspecialchars($users[$message["userid"]]["username"]);
        if(isset($moderators[$message["userid"]])){
            $t_author="<strong>$t_author</strong>";
        }
    } else {
        $t_author=htmlspecialchars(chop($message["author"]));
    }
    $t_datestamp = date_format($message["datestamp"]);

    if( ($$phcollapse != 0) && (empty($read)) ){
      $t_latest=date_format($trec["latest"]);
      $t_maxid=$trec["maxid"];
    }
    $message = next($headers);

    if($t_thread!=$t_id){
      $img = '<img src="images/l.gif" border=0 width=12 align="top">';
      if(is_array($message)){
        if($t_thread==$message["thread"]){
          $img='<img src="images/t.gif" border=0 width=12 align="top">';
        }
      }
    }
    else{
      $img="<img src=\"images/trans.gif\" border=0 width=1 height=21 align=\"absmiddle\">";
      $loc=0;
    }

    if($read==true && $id==$t_id){
      $t_subject = "<strong>$t_subject</strong>";
      $t_author = "<strong>$t_author</strong>";
      $t_datestamp = "<strong>$t_datestamp</strong>";
    }
    else{
      $t_subject="<a href=\"$read_page.$ext?f=$num&i=$t_id&t=$t_thread$GetVars\">$t_subject</a>";
    }

    $color=bgcolor($bgcolor);
    echo "<tr>\n";
    echo '  <td class="PhorumListRow" '.$color.'><FONT color="'.$fcolor.'">&nbsp;'.$img.'&nbsp;'.$t_subject."&nbsp;</font>";

    if($UseCookies){
      $isnew=false;
      if($$phcollapse != 0 && empty($read)){
        // collapsed code
        if($use_haveread){
          if ($old_message<$t_maxid) {
            if(!isset($haveread[$t_maxid])) {
              $isnew=true;
            }
          }
        }
        elseif($old_message<$t_maxid){
          $isnew=true;
        }
      } else {
        // expanded code
        if ($use_haveread) {
          if ($old_message<$t_id) {
            if(!isset($haveread[$t_id])) {
              $isnew=true;
            }
          }
        } elseif ($old_message<$t_id) {
          $isnew=true;
        }
      }
      if($isnew){
        echo "<font class=\"PhorumNewFlag\">".$lNew."</font>";
      }
    }

    echo "</td>\n";
    echo '  <td class="PhorumListRow" width="150" '.$color.' nowrap="nowrap"><FONT color="'.$fcolor.'">'.$t_author.'&nbsp;</font></td>'."\n";
    if( $$phcollapse != 0 && empty($read) ){
      $t_count=$trec["tcount"]-1;
      if(!empty($message)) $trec=$threads[$message["thread"]];
      echo '  <td class="PhorumListRow" align="center" width="80" '.$color.' nowrap="nowrap"><FONT color="'.$fcolor.'" size=-1>'.$t_count."&nbsp;</font></td>\n";
      echo '  <td class="PhorumListRow" width="120" '.$color.' nowrap="nowrap"><FONT color="'.$fcolor.'" size=-1>'.$t_latest."&nbsp;</font></td>\n";
    }
    else{
      echo '  <td class="PhorumListRow" width="120" '.$color.' nowrap="nowrap"><FONT color="'.$fcolor.'" size=-1>'.$t_datestamp.'&nbsp;</font></td>'."\n";
    }
    echo "</tr>\n";
    $x++;
    $loc++;
  } // end while
?>
</table>
