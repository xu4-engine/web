<?php
////////////////////////////////////////////////////////////////////////////////
//                                                                            //
//   Copyright (C) 2000  Phorum Development Team                              //
//   http://www.phorum.org                                                    //
//                                                                            //
//   This program is free software. You can redistribute it and/or modify     //
//   it under the terms of either the current Phorum License (viewable at     //
//   phorum.org) or the Phorum License that was distributed with this file    //
//                                                                            //
//   This program is distributed in the hope that it will be useful,          //
//   but WITHOUT ANY WARRANTY, without even the implied warranty of           //
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                     //
//                                                                            //
//   You should have received a copy of the Phorum License                    //
//   along with this program.                                                 //
////////////////////////////////////////////////////////////////////////////////

  require "./common.php";
  require "$include_path/post_functions.php";
  require "$include_path/read_functions.php";

  settype($t, "integer");
  settype($i, "integer");
  settype($more, "string");

  $id=$i;
  $thread=$t;

  if($num==0 || $ForumName==''){
    Header("Location: $forum_url?$GetVars");
    exit;
  }

  // Error Checking.
  // We don't want to allow attachments if:
  //   the message was posted over 5 minutes ago.
  //   the host of the post does not match this users host.
  //   or there are already the max number of attachments attached.

  $SQL="Select $ForumTableName.thread, author, datestamp, host, subject, body from $ForumTableName, $ForumTableName"."_bodies where $ForumTableName.id=$ForumTableName"."_bodies.id and $ForumTableName.id=$id";
  $q->query($DB, $SQL);
  $row=$q->getrow();

  $noattach=false;

  list($date,$time) = explode(" ",$row["datestamp"]);
  list($year,$month,$day) = explode("-",$date);
  list($hour,$minute,$second) = explode(":",$time);

  if (getenv('REMOTE_ADDR') != "") {
     $host = @GetHostByAddr(getenv('REMOTE_ADDR'));
  } else {
     $host = @GetHostByAddr($_SERVER['REMOTE_ADDR']);
  }

  $datestamp = date_format($row["datestamp"]);
  $author = chop($row["author"]);

  $SQL="Select count(*) as count from $ForumTableName"."_attachments where message_id=$id";
  $q->query($DB, $SQL);

  if($q->field("count", 0)>0){
    $count=$q->field("count", 0);
  }
  else{
    $count=0;
  }

  if( (time()-mktime($hour,$minute,$second,$month,$day,$year))>300 || $host!=trim($row["host"]) || $count>=$ForumMaxUploads ){
    $noattach=true;
  }

  if(isset($post) && !$noattach){
    // Attachment handling:
    if(is_array($HTTP_POST_FILES) && count($HTTP_POST_FILES)>0){
      // PHP4 style
      $attachments=&$HTTP_POST_FILES;
    }
    $IsError=check_attachments($attachments);

    reset($attachments);

    // Attachment handling:
    if(!empty($attachments) && is_array($attachments) && empty($IsError)){
      save_attachments($attachments, $attach_ids, $id);
    }

    if(empty($IsError) && @is_array($attachments)){
      // if it is not a new message and not float to top
      // send them to the message.
      if($thread!=$id && $ForumMultiLevel!=2){
        $more = $thread+1;
        $more = "&a=2&t=$more";
      }
      Header ("Location: $forum_url/$list_page.$ext?f=$num$more$GetVars");
      exit();
    }
  }

  include phorum_get_file_name("header");

  //////////////////////////
  // START NAVIGATION     //
  //////////////////////////

    $menu=array();
    if($ActiveForums>1)
      // Forum List
      addnav($menu, $lForumList, "$forum_page.$ext?f=$ForumParent$GetVars");
    // Go To Top
    addnav($menu, $lGoToTop, "$list_page.$ext?f=$num$GetVars");
    // New Topic
    addnav($menu, $lStartTopic, "$post_page.$ext?f=$num$GetVars");
    // Search
    addnav($menu, $lSearch, "$search_page.$ext?f=$num$GetVars");
    // Log Out/Log In
    if($ForumSecurity){
      if(isset($phorum_user['id'])){
        addnav($menu, $lLogOut, "login.$ext?logout=1$GetVars");
        addnav($menu, $lMyProfile, "profile.$ext?f=$f&id=$phorum_user[id]$GetVars");
      }
      else{
        addnav($menu, $lLogIn, "login.$ext$GetVars");
      }
    }

    $TopLeftNav=getnav($menu);

  //////////////////////////
  // END NAVIGATION       //
  //////////////////////////

  if(isset($IsError)){
    echo "<p><strong>$IsError</strong>";
  }

?>
<table cellspacing="0" cellpadding="2" border="0" width="<?php echo $ForumTableWidth; ?>">
<tr>
    <td colspan="2" <?php echo bgcolor($ForumNavColor); ?>>
      <table cellspacing="0" cellpadding="1" border="0">
        <tr>
          <td><?php echo $TopLeftNav; ?></td>
        </tr>
      </table>
    </td>
</tr>
</table>
<table class="PhorumListTable" cellspacing="0" cellpadding="2" border="0" width="<?php echo $ForumTableWidth; ?>">
<tr>
    <td class="PhorumTableHeader" colspan="2" <?php echo bgcolor($ForumTableHeaderColor); ?>><FONT  class="PhorumTableHeader" color="<?php echo $ForumTableHeaderFontColor; ?>">&nbsp;<?php echo $row["subject"]; ?></font></td>
</tr>
<tr>
    <td colspan=2 <?php echo bgcolor($ForumTableBodyColor1); ?>>
<table width="100%" cellspacing="0" cellpadding="5" border="0">
<tr><td>
<?php

    if($noattach){
        echo $lCannotAttach;
    } else {
?>
<font class="PhorumMessage" color="<?php echo $ForumTableBodyFontColor1; ?>">
<?php echo $lAuthor;?>:&nbsp;<?php echo $row["author"]; ?>&nbsp;(<?php echo $host; ?>)<br />
<?php echo $lDate;?>:&nbsp;&nbsp;&nbsp;<?php echo $datestamp; ?><br /><br />
<?php echo format_body($row["body"]); ?>
<?php
    }
?>
</td></tr>
</table>
    </td>
</tr>
</table>

<?php if(!$noattach){ ?>
<form action="<?php echo "$attach_page.$ext"; ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="t" value="<?php echo $row["thread"]; ?>" />
<input type="hidden" name="f" value="<?php echo $num; ?>" />
<input type="hidden" name="i" value="<?php echo $id; ?>" />
<input type="hidden" name="post" value="1" />
<?php echo $PostVars; ?>
<table class="PhorumListTable" cellspacing="0" cellpadding="2" border="0" width="<?php echo $ForumTableWidth; ?>">
<tr>
    <td class="PhorumTableHeader" colspan="2" <?php echo bgcolor($ForumTableHeaderColor); ?>><FONT  class="PhorumTableHeader" color="<?php echo $ForumTableHeaderFontColor; ?>">&nbsp;<?php echo $lFormAttach; ?></font></td>
</tr>
<?php
  if($count<$ForumMaxUploads){
    for($x=0;$x<$ForumMaxUploads-$count;$x++){
      echo "<tr>\n";
      echo '    <td ' . bgcolor($ForumTableBodyColor1) . ' nowrap="nowrap"><font color="' . $ForumTableBodyFontColor1 . '">&nbsp;' . $lFormAttachment . ':</font></td>';
      echo '    <td ' . bgcolor($ForumTableBodyColor1) . ' width="100%"><input type="File" name="attachment_'.$x.'" size="30" maxlength="64"></td>';
      echo "</tr>\n";
    }
  }
  else{
    echo "<tr><td ". bgcolor($ForumTableBodyColor1) ." width=\"100%\" colspan=\"2\">$lNoMoreUploads</td></tr>\n";
  }
?>
<tr>
    <td width="100%" colspan="2" align="RIGHT" <?php echo bgcolor($ForumTableBodyColor1); ?>><input type="Submit" name="post" value=" <?php echo $lFormPost;?> ">&nbsp;<br /><img src="images/trans.gif" width=3 height=3 border=0></td>
</tr>
</table>
</form>
<?php
  }

  include phorum_get_file_name("footer");
  exit();

?>
