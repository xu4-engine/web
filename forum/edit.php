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
//
//      EDIT HACK v 0.2 by Gertjan de Back <gertjan@deback.nl>
//      Contributions by Age Manocchia, B Spade and Rob Walls.

    require "./common.php";

    if(empty($i) || empty($t)) {
      header("Location: $HTTP_REFERER");
      exit;
    }

    require "$include_path/post_functions.php";

    settype($i, "integer");
    settype($mod, "string");

    $halt=false;

    $id=$i;

    //Thats for all those ppl who likes to use different colors in different forums
    if($num!=0){
        $table_width=$ForumTableWidth;
        $table_header_color=$ForumTableHeaderColor;
        $table_header_font_color=$ForumTableHeaderFontColor;
        $table_body_color_1=$ForumTableBodyColor1;
        $table_body_font_color_1=$ForumTableBodyFontColor1;
        $nav_color=$ForumNavColor;
    }
    else{
        $table_width=$default_table_width;
        $table_header_color=$default_table_header_color;
        $table_header_font_color=$default_table_header_font_color;
        $table_body_color_1=$default_table_body_color_1;
        $table_body_font_color_1=$default_table_body_font_color_1;
        $nav_color=$default_nav_color;
    }


    //////////////////////////
    // START NAVIGATION     //
    //////////////////////////

    addnav($menu, $lGoToPost, "$read_page.$ext?f=$num&i=$i&t=$t$GetVars");

    if($ActiveForums>1){
        // Forum List
        addnav($menu, $lForumList, "$forum_page.$ext?f=$f$GetVars");
    }

    // Go To Top
    addnav($menu, $lGoToTop, "$list_page.$ext?f=$num$GetVars");

    // New Topic
    addnav($menu, $lStartTopic, "$post_page.$ext?f=$num$GetVars");

    // Search
    addnav($menu, $lSearch, "$search_page.$ext?f=$num$GetVars");

    // Log Out/Log In
    if(isset($phorum_user["id"])){
        // Log Out
        addnav($menu, $lLogOut, "login.$ext?f=$f&logout=1$GetVars");

        //The profile of the logged in user
        if($id!=$phorum_user["id"])
          addnav($menu, $lMyProfile, "profile.$ext?f=$f&id=$phorum_user[id]$GetVars");
    }
    else{

        // Register
	if ($VisCreateAcc)
          addnav($menu, $lRegisterLink, "register.$ext?f=$f$GetVars");
        // Log In
        addnav($menu, $lLogIn, "login.$ext?f=$f$GetVars");
    }

    $nav=getnav($menu);

    //////////////////////////
    // END NAVIGATION       //
    //////////////////////////


    // check that the current user is editing his post and not someone elses.

    $sql = "select userid from $PHORUM[ForumTableName] where id=$id";
    $q->query($DB, $sql);
    $row = $q->getrow();

    if($row["userid"]==$phorum_user["id"]){


        switch($mod) {
          case "update":

            $body=trim($body);

            // add the users signature if requested
            if(isset($use_sig)){
                $body.="\n\n".PHORUM_SIG_MARKER;
            }
            if(!isset($email_reply) || $email_reply!='Y')
                $email_reply='N';

            $body.="\n\n$lPostEdited (".date_format(date("Y-m-d H:i:s")).")";

            if(!get_magic_quotes_gpc()){
              $author = addslashes($author);
              $email = addslashes($email);
              $subject = addslashes($subject);
              $attachment = @addslashes($attachment);
              $body = addslashes($body);
            }

            if ($subject == '') {$subject='No Subject';}

                if(isset($attachments) && is_array($attachments)){

                    $del=array();
                    while(list($key, $name)=each($attachments)){
                        if(isset($del_attachment[$key])){
                            $del[]=$key;
                            unlink("$AttachmentDir/".$PHORUM['ForumTableName']."/$key".strtolower(strrchr($name, ".")));
                        }
                        elseif($new_attachment[$key]!=$name){
                            $SQL="Update ".$PHORUM['ForumTableName']."_attachments set filename='$new_attachment[$key]' where id=$key";
                            $q->query($DB, $SQL);
                        }
                    }
                    if(count($del)>0){
                        $SQL="Delete from ".$PHORUM['ForumTableName']."_attachments where id in (".implode(",", $del).")";
                        $q->query($DB, $SQL);
                    }
                }

                $sSQL="Update $ForumTableName set author='$author', email='$email', subject='$subject', email_reply='$email_reply' where id=$id";
                $q->query($DB, $sSQL);
                $sSQL="Update ".$PHORUM['ForumTableName']."_bodies set body='$body' where id=$id";
                $q->query($DB, $sSQL);
                mail_edit_to_moderators(); // inform moderators of edit by e-mail if necessary
                header("Location: $read_page.$ext?f=$num&i=$i&t=$t$GetVars");
                exit;

        }

        if(!empty($i)){
            $sSQL="Select author, email, subject, email_reply, body from ".$PHORUM['ForumTableName']." as t, ".$PHORUM['ForumTableName']."_bodies as b where t.id=b.id and t.id=$i and userid=$phorum_user[id]";
            $q->query($DB, $sSQL);
            $mtext = $q->getrow();
            if(!empty($mtext["body"])){
                if(substr($mtext["body"], 0, 6)=="<HTML>"){
                    $mtext["body"]=trim(preg_replace("|</*HTML>|i", "", $mtext["body"]));
                }

                if(substr($mtext["subject"], 0, 3)=="<b>"){
                    $mtext["subject"]=trim(preg_replace("|</*b>|i", "", $mtext["subject"]));
                    $mtext["author"]=trim(preg_replace("|</*b>|i", "", $mtext["author"]));
                }

                if(strstr($mtext["body"], PHORUM_SIG_MARKER)){
                    $mtext["body"]=trim(str_replace(PHORUM_SIG_MARKER, "", $mtext["body"]));
                    $use_sig=1;
                } else {
                    $use_sig=0;
                }

                if($pos=strpos($mtext["body"], "\n\n$lPostEdited")){
                    $mtext["body"]=trim(substr($mtext["body"], 0, $pos));
                }

            }

        }

        include phorum_get_file_name("header");

        // is there a message for the user?
        if(!empty($msg)) {
            print "<font class=PhorumForumTitle><b>$msg</b></font>";
        }

?>
<form action="<?php echo $PHP_SELF; ?>" method="POST">
<input type="Hidden" name="mod" value="update">
<input type="Hidden" name="f" value="<?php echo $num; ?>">
<input type="Hidden" name="i" value="<?php echo $i; ?>">
<input type="Hidden" name="t" value="<?php echo $t; ?>">
<input type="Hidden" name="use_sig" value="<?php echo $use_sig; ?>">
<?php echo $PostVars; ?>
<?php
if (isset($mythread)) { ?>
<input type="Hidden" name="mythread" value="<?php echo $mythread; ?>">
<?php
}
?>

<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td <?php echo bgcolor($ForumNavColor); ?>>
      <table cellspacing="0" cellpadding="2" border="0">
        <tr>
          <td><?php echo empty($nav) ? "&nbsp;" : $nav; ?></td>
        </tr>
      </table>
    </td>
</tr>
<tr>
  <td <?php echo bgcolor($ForumNavColor); ?>>
    <table class="PhorumListTable" cellspacing="0" cellpadding="2" border="0">
<tr>
    <td colspan="2"  height="23" align="left" class="PhorumTableHeader" <?php echo bgcolor($ForumTableHeaderColor); ?>><FONT color="<?php echo $ForumTableHeaderFontColor; ?>">&nbsp;<?php echo $lEditPost?></font></td>
</tr>

<tr>
    <td height="23" <?php echo bgcolor($ForumTableBodyColor1); ?> nowrap><font color="<?php echo $ForumTableBodyFontColor1; ?>">&nbsp;<?php echo $lFormName;?>:</font></td>
    <td height="23" <?php echo bgcolor($ForumTableBodyColor1); ?>><font color="<?php echo $ForumTableBodyFontColor1; ?>"><?php echo $mtext["author"]; ?></font><input type="hidden" name="author" value="<?php echo $mtext["author"]; ?>"></td>
</tr>

<tr>
    <td height="23" <?php echo bgcolor($ForumTableBodyColor1); ?> nowrap><font color="<?php echo $ForumTableBodyFontColor1; ?>">&nbsp;<?php echo $lFormEmail;?>:</font></td>
    <td height="23" <?php echo bgcolor($ForumTableBodyColor1); ?>><font color="<?php echo $ForumTableBodyFontColor1; ?>"><?php echo $mtext["email"]; ?></font><input type="hidden" name="email" value="<?php echo $mtext["email"]; ?>"></td>
</tr>

<tr>
    <td height="23" <?php echo bgcolor($ForumTableBodyColor1); ?> nowrap><font color="<?php echo $ForumTableBodyFontColor1; ?>">&nbsp;<?php echo $lFormSubject;?>:</font></td>
    <td <?php echo bgcolor($ForumTableBodyColor1); ?>><input type="Text" name="subject" value="<?php echo $mtext["subject"]; ?>" size="10" style="width: 300px;" class="TEXT"></td>
</tr>
<?php
if($PHORUM['AllowAttachments'] && $PHORUM['ForumAllowUploads'] == 'Y') {
  $SQL="Select id, filename from ".$PHORUM['ForumTableName']."_attachments where message_id=$id";
  $q->query($DB, $SQL);
  while($rec=$q->getrow()){
?>
<input type="hidden" NAME="attachments[<?php echo $rec["id"]; ?>]" value="<?php echo $rec["filename"]; ?>">
<tr>
  <td <?php echo bgcolor($ForumTableBodyColor1); ?>><font color="<?php echo $ForumTableBodyFontColor1; ?>">&nbsp;<?php echo $lFormAttachment?> [<?php echo $rec["id"]; ?>]:</font></td>
  <td <?php echo bgcolor($ForumTableBodyColor1); ?>><input type="Text" NAME="new_attachment[<?php echo $rec["id"]; ?>]" value="<?php echo $rec["filename"]; ?>" size="10" style="width: 300px;" class="TEXT">&nbsp;&nbsp;<INPUT TYPE="checkbox" NAME="del_attachment[<?php echo $rec["id"]; ?>]" VALUE="true"> delete attachment</td>
</tr>
<?php
  }
}
?>
<tr>

    <td <?php echo bgcolor($ForumTableBodyColor1); ?> colspan=2 width="100%" nowrap align="left"><table cellpadding="5" cellspacing="0" border="0"><tr><td align="CENTER" valign="TOP"><font face="courier"><textarea class="PhorumBodyArea" name="body" cols="45" rows="20" wrap="VIRTUAL"><?php echo htmlspecialchars($mtext["body"]); ?></textarea></font></td></tr></table></td>
</tr>
<!--</td>
</tr>-->
    <tr>
        <td <?php echo bgcolor($ForumTableBodyColor1); ?> colspan=2 width="100%" nowrap="nowrap" align="left"><font color="<?php echo $ForumTableBodyFontColor1; ?>"><input type="checkbox" name="email_reply" value="Y"<?php if($mtext['email_reply']=='Y') echo ' checked';?>><?php echo $lEmailMe; ?></font></td>
    </tr>

<tr>
<td <?php echo bgcolor($ForumTableBodyColor1); ?> colspan="2" align="RIGHT">
    <input type="Submit" name="post" value=" <?php echo $lFormUpdate;?> ">&nbsp;<br><img src="images/trans.gif" width=3 height=3 border=0></td>
    </tr>
    </table>
  </td>
</tr>
</table>
</form>
<?php

    } else { // $row[userid]

        include phorum_get_file_name("header");

        echo "<font class=PhorumForumTitle><b>$lCantEdit</b></font>";

    }

    include phorum_get_file_name("footer");
?>
