<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php
  if(!get_magic_quotes_gpc()){
    $author = addslashes($author);
    $email = addslashes($email);
    $subject = addslashes($subject);
    $attachment = @addslashes($attachment);
    $body = addslashes($body);
  }

  if(is_array($attachments)){

    $del=array();
    while(list($key, $name)=each($attachments)){
      if(isset($del_attachment[$key])){
        $del[]=$key;
        unlink("$AttachmentDir/$ForumTableName/$key".strtolower(strrchr($name, ".")));
      }
      elseif($new_attachment[$key]!=$name){
        $SQL="Update $ForumTableName"."_attachments set filename='$new_attachment[$key]' where id=$key";
        $q->query($DB, $SQL);
      }
    }
    if(count($del)>0){
      $SQL="Delete from $ForumTableName"."_attachments where id in (".implode(",", $del).")";
      $q->query($DB, $SQL);
    }
  }

  $sSQL="Update $ForumTableName set author='$author', email='$email', subject='$subject' where id=$id";
  $q->query($DB, $sSQL);
  $sSQL="Update ".$ForumTableName."_bodies set body='$body' where id=$id";
  $q->query($DB, $sSQL);
  QueMessage("Message $id updated!");
?>