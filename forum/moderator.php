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
    include "$include_path/delete_message.php";


    if($phorum_user["moderator"]!=true){
        if(!isset($phorum_user['id'])){
          header("Location: $list_page.$ext?f=$num$GetVars");
        } else {
          header("Location: $HTTP_REFERER");
        }
      exit;
    }

    if(empty($i) || empty($t)){
      header("Location: $HTTP_REFERER");
      exit;
    }

    settype($i, "integer");

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
        addnav($menu, $lForumList, "$forum_page.$ext?f=$ForumParent$GetVars");
    }

    // Go To Top
    addnav($menu, $lGoToTop, "$list_page.$ext?f=$num$GetVars");

    // New Topic
    addnav($menu, $lStartTopic, "$post_page.$ext?f=$num$GetVars");

    // Search
    addnav($menu, $lSearch, "$search_page.$ext?f=$num$GetVars");

    // Log Out/Log In
    if(isset($phorum_user['id'])){
        // Log Out
        addnav($menu, $lLogOut, "login.$ext?f=$f&logout=1$GetVars");

        //The profile of the logged in user
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

    switch($mod) {
        case "update":
            if(!get_magic_quotes_gpc()){
              $author = addslashes($author);
              $email = addslashes($email);
              $subject = addslashes($subject);
              $attachment = @addslashes($attachment);
              $body = addslashes($body);
            }
	    if(!isset($email_reply) || $email_reply!='Y')
                $email_reply='N';

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

            header("Location: $read_page.$ext?f=$num&i=$i&t=$t$GetVars");
            exit;
        case "delete":
            delete_messages($i);
            if($i==$t){
                header("Location: $list_page.$ext?f=$num$GetVars");
            } else {
                header("Location: $read_page.$ext?f=$num&i=$t&t=$t$GetVars");
            }
            exit;
            break;
	case "hide":
            hide_messages($i);
            if($i==$t){
                header("Location: $list_page.$ext?f=$num$GetVars");
            } else {
                header("Location: $read_page.$ext?f=$num&i=$t&t=$t$GetVars");
            }	    
	    break;
        case "close":
            $sSQL="Update $ForumTableName set closed=$closevar WHERE thread=$t";
            $q->query($DB, $sSQL);
            header("Location: $read_page.$ext?f=$num&i=$t&t=$t$GetVars");
            exit;
            break;
	 case "move":
            if(isset($move) && $move==1){
                include "$include_path/move_thread.php";
                $ret=move_thread($t,$targetf);
                if(!empty($ret))
                    echo $ret;
                else
                    header("Location: $list_page.$ext?f=$num$GetVars");
            } else {
                include phorum_get_file_name("header");
                $sSQL="Select id, name from $pho_main where folder=0 and table_name!='".$PHORUM['ForumTableName']."' order by name";
                $q->query($DB, $sSQL);
                $rec=$q->getrow();
                if($q->numrows()){
                    echo "<form action=\"$forum_url/moderator.$ext\" method=\"post\">\n";
                    echo $lModMoveThreads.": <select name=\"targetf\" id=\"targetf\">\n";
                    while(is_array($rec)){
                        echo "<option value=\"".$rec["id"]."\">".$rec["name"]."</option>\n";
                        $rec=$q->getrow();
                    }
?>
   </select>
   <input type="submit" value="Move" />
   <input type="hidden" name="mod" value="move" />
   <input type="hidden" name="f" value="<?php echo $f;?>" />
   <input type="hidden" name="i" value="<?php echo $i;?>" />
   <input type="hidden" name="t" value="<?php echo $t;?>" />
   <input type="hidden" name="move" value="1" />
<?php echo $PostVars; ?>   
   </form>
<?php
                } else {
                    echo $lModMoveNoForums;
                }
                include phorum_get_file_name("footer");
             }
             exit;
             break;
    }

    if(!empty($i)){
        $sSQL="Select author, email, subject, email_reply, body from ".$PHORUM['ForumTableName']." as t, ".$PHORUM['ForumTableName']."_bodies as b where t.id=b.id and t.id=$i";
        $q->query($DB, $sSQL);
        $mtext = $q->getrow();

        // don't mess with this.  It is here just for old phorums.  We don't do this stuff anymore.
        $mtext["body"]=str_replace("<HTML>", "", $mtext["body"]);
        $mtext["body"]=str_replace("</HTML>", "", $mtext["body"]);

        // don't mess with this.  It is here just for old phorums.  We don't do this stuff anymore.
        $mtext["subject"]=str_replace("<b>", "", $mtext["subject"]);
        $mtext["subject"]=str_replace("</b>", "", $mtext["subject"]);
        $mtext["author"]=str_replace("<b>", "", $mtext["author"]);
        $mtext["author"]=str_replace("</b>", "", $mtext["author"]);

    }


    include phorum_get_file_name("header");

    // is there a message for the user?
    if(!empty($msg)) {
        print "<font class=PhorumForumTitle><strong>$msg</strong></font>";
    }
?>
<form action="<?php echo $PHP_SELF; ?>" method="POST">
<input type="hidden" name="mod" value="update" />
<input type="hidden" name="num" value="<?php echo $num; ?>" />
<input type="hidden" name="i" value="<?php echo $i; ?>" />
<input type="hidden" name="t" value="<?php echo $t; ?>" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="html" value="<?php echo $html; ?>" />
<input type="hidden" name="bold" value="<?php echo $bold; ?>" />
<?php echo $PostVars; ?>
<?php
if (isset($mythread)) { ?>
<input type="hidden" name="mythread" value="<?php echo $mythread; ?>" />
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
    <td colspan="2" align="left" class="PhorumTableHeader" <?php echo bgcolor($ForumTableHeaderColor); ?>><FONT color="<?php echo $ForumTableHeaderFontColor; ?>">&nbsp;<?php echo $lEditPost?>:</font></td>
</tr>
<tr>
    <th <?php echo bgcolor($ForumTableBodyColor1); ?>><font color="<?php echo $ForumTableBodyFontColor1; ?>">&nbsp;<?php echo $lAuthor?></font></th>
    <td <?php echo bgcolor($ForumTableBodyColor1); ?>><input type="Text" name="author" value="<?php echo $mtext["author"]; ?>" size="10" style="width: 300px;" class="TEXT" /></td>
</tr>
<tr>
    <th <?php echo bgcolor($ForumTableBodyColor1); ?>><font color="<?php echo $ForumTableBodyFontColor1; ?>">&nbsp;<?php echo $lEmail?></font></th>
    <td <?php echo bgcolor($ForumTableBodyColor1); ?>><input type="Text" name="email" value="<?php echo $mtext["email"]; ?>" size="10" style="width: 300px;" class="TEXT" /></td>
</tr>
<tr>
    <th <?php echo bgcolor($ForumTableBodyColor1); ?>><font color="<?php echo $ForumTableBodyFontColor1; ?>">&nbsp;<?php echo $lFormSubject?></font></th>
    <td <?php echo bgcolor($ForumTableBodyColor1); ?>><input type="Text" name="subject" value="<?php echo $mtext["subject"]; ?>" size="10" style="width: 300px;" class="TEXT" /></td>
</tr>
<?php
if($PHORUM['AllowAttachments'] && $PHORUM['ForumAllowUploads'] == 'Y') {
  $SQL="Select id, filename from ".$PHORUM['ForumTableName']."_attachments where message_id=$id";
  $q->query($DB, $SQL);
  while($rec=$q->getrow()){
?>
<input type="hidden" name="attachments[<?php echo $rec["id"]; ?>]" value="<?php echo $rec["filename"]; ?>" />
<tr>
  <th <?php echo bgcolor($ForumTableBodyColor1); ?>><font color="<?php echo $ForumTableBodyFontColor1; ?>">&nbsp;<?php echo $lFormAttachment?> [<?php echo $rec["id"]; ?>]:</font></th>
  <td <?php echo bgcolor($ForumTableBodyColor1); ?>><input type="Text" name="new_attachment[<?php echo $rec["id"]; ?>]" value="<?php echo $rec["filename"]; ?>" size="10" style="width: 300px;" class="TEXT" />&nbsp;&nbsp;<input TYPE="checkbox" name="del_attachment[<?php echo $rec["id"]; ?>]" VALUE="true" /> delete attachment</td>
</tr>
<?php
  }
}
?>
<tr>

    <td <?php echo bgcolor($ForumTableBodyColor1); ?> colspan=2 width="100%" nowrap="nowrap" align="left"><table cellpadding="5" cellspacing="0" border="0"><tr><td align="CENTER" valign="TOP"><font face="courier"><textarea class="PhorumBodyArea" name="body" cols="45" rows="20" wrap="VIRTUAL"><?php echo htmlspecialchars($mtext["body"]); ?></textarea></font></td></tr></table></td>
</tr>
<tr>
    <td <?php echo bgcolor($ForumTableBodyColor1); ?> colspan=2 width="100%" nowrap="nowrap" align="left"><font color="<?php echo $ForumTableBodyFontColor1; ?>"><input type="checkbox" name="email_reply" value="Y"<?php if($mtext['email_reply']=='Y') echo ' checked';?>><?php echo $lEmailMe; ?></font></td>
</tr>
<tr>
<td <?php echo bgcolor($ForumTableBodyColor1); ?> colspan="2" align="RIGHT">
    <input type="Submit" name="post" value=" <?php echo $lFormUpdate;?> " />&nbsp;<br /><img src="images/trans.gif" width=3 height=3 border=0></td>
    </tr>
    </table>
  </td>
</tr>
</table>
</form>
<?php

  include phorum_get_file_name("footer");
?>
