<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php  // Edit Message
    $sSQL="Select author, email, subject, body from $ForumTableName as t, $ForumTableName"."_bodies as b where t.id=b.id and t.id=$id";
    $q->query($DB, $sSQL);
    $mtext = $q->getrow();

    if(empty($mtext["subject"])){
        QueMessage("Message $id not found");
        return;
    }


    if (isset($srcpage)) {
        $page = $srcpage;
    } else {
        $page = "managemenu";
    }

    $mtext["subject"]=htmlspecialchars($mtext["subject"]);
    $mtext["author"]=htmlspecialchars($mtext["author"]);
    $mtext["body"]=htmlspecialchars($mtext["body"]);


    // don't mess with this.  It is here just for old phorums.  We don't do this stuff anymore.
    $mtext["body"]=str_replace("<HTML>", "", $mtext["body"]);
    $mtext["body"]=str_replace("</HTML>", "", $mtext["body"]);

    // don't mess with this.  It is here just for old phorums.  We don't do this stuff anymore.
    $mtext["subject"]=str_replace("<b>", "", $mtext["subject"]);
    $mtext["subject"]=str_replace("</b>", "", $mtext["subject"]);
    $mtext["author"]=str_replace("<b>", "", $mtext["author"]);
    $mtext["author"]=str_replace("</b>", "", $mtext["author"]);


?>
<form action="<?php echo $myname; ?>" method="POST">
<input type="hidden" name="action" value="edit" />
<input type="hidden" name="num" value="<?php echo $num; ?>" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="html" value="<?php echo $html; ?>" />
<input type="hidden" name="bold" value="<?php echo $bold; ?>" />
<?php
if (isset($mythread)) { ?>
<input type="hidden" name="mythread" value="<?php echo $mythread; ?>" />
<?php
}
?>

<table border="0" cellspacing="0" cellpadding="3" class="box-table">
<tr>
    <td colspan="2" align="center" class="table-header">Edit Message: <?php echo $ForumName; ?></td>
</tr>
<tr>
    <th>Author:</th>
    <td><input type="Text" name="author" value="<?php echo $mtext["author"]; ?>" size="10" style="width: 300px;" class="TEXT" /></td>
</tr>
<tr>
    <th>Email:</th>
    <td><input type="Text" name="email" value="<?php echo $mtext["email"]; ?>" size="10" style="width: 300px;" class="TEXT" /></td>
</tr>
<tr>
    <th>Subject:</th>
    <td><input type="Text" name="subject" value="<?php echo $mtext["subject"]; ?>" size="10" style="width: 300px;" class="TEXT" /></td>
</tr>
<?php
if($PHORUM['AllowAttachments'] && $PHORUM['ForumAllowUploads'] == 'Y') {
  $SQL="Select id, filename from $ForumTableName"."_attachments where message_id=$id";
  $q->query($DB, $SQL);
  while($rec=$q->getrow()){
?>
<input type="hidden" name="attachments[<?php echo $rec["id"]; ?>]" value="<?php echo $rec["filename"]; ?>" />
<tr>
  <th>Attachment [<?php echo $rec["id"]; ?>]:</th>
  <td><input type="Text" name="new_attachment[<?php echo $rec["id"]; ?>]" value="<?php echo $rec["filename"]; ?>" size="10" style="width: 300px;" class="TEXT" />&nbsp;&nbsp;<input TYPE="checkbox" name="del_attachment[<?php echo $rec["id"]; ?>]" VALUE="true" /> delete attachment</td>
</tr>
<?php
     }
  }
?>
<tr>
    <td colspan=2><textarea name="body" cols="60" rows="20" wrap="VIRTUAL"><?php echo $mtext["body"]; ?></textarea></td>
</tr>
</td>
</tr>
</table>
<p>
<center><input type="Submit" name="submit" value="Update" class="BUTTON" /></center>
</form>
