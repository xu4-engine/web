<?php /*move threads */

    function move_thread($idt,$targetf)
    {
        GLOBAL $PHORUM, $DB, $q, $pho_main;

      // check that $targetf exists
      $sSQL="Select table_name,allow_uploads from $pho_main where folder=0 and id=$targetf";
      $q->query($DB, $sSQL);
      $rec=$q->getrow();
      if($q->numrows()==0)
      {
         return "failed: forum does not exist or is a folder!";
      }

      $move_attachments=0;

      $targettable=$rec["table_name"];
      $ForumTableName=$PHORUM["ForumTableName"]; //current table!

      //attachments
      if($PHORUM['AllowAttachments'] && $PHORUM['ForumAllowUploads'] == 'Y') {
         //attachments are active in the current forum, so we need to check for them
         $move_attachments=1;
         if($rec["allow_uploads"]=='Y') // attachments allowed in the target-forum too, no problem here
         {
            $target_allow_uploads=1;
         }
         else // oops, they would get lost in the target-forum
         {
            $target_allow_uploads=0;
         }
      }

      // read all messages from thread
      $sSQL = "SELECT * from $ForumTableName, ".$ForumTableName."_bodies AS bodies WHERE $ForumTableName.thread = ".$idt." AND $ForumTableName.id = bodies.id ORDER BY $ForumTableName.id";

      $msg = new query($DB, $sSQL);

      // check that $idt (thread id) exists
      if($msg->numrows()==0)
      {
         return "failed: thread does not exist!";
      }

      $rec=$msg->getrow();
      while(is_array($rec)){
          $messages[]=$rec;
          $rec=$msg->getrow();
      }
      $msg->free();

      //$msg_rows=count($messages);
      @reset($messages);
      $msg_row=@current($messages);

      $list="";
      $count=0;
      while(is_array($msg_row)) {
         $parents[$msg_row["id"]]=$count++;
         $list.=$msg_row["id"].","; // list of ids for attachments
         $msg_row=next($messages);
      }

      // remove last colon
      if(strlen($list)>0) $list=substr($list,0,-1);


      if($move_attachments) {
      // attachments
         $SQL="Select id,message_id, filename from $ForumTableName"."_attachments where message_id in ($list)";
         $q->query($DB, $SQL);
         if($q->numrows()>0)
         {
            if($target_allow_uploads==0) {
               return "failed: The target forum does not support attachments! Please remove the attachment(s) from this thread before moving it or enable attachments in the target forum!";
         }

         $arow=$q->getrow();
         while(is_array($arow)){
            $attachs[]=$arow;
            $arow=$q->getrow();
         }

         @reset($attachs);

         }
         else // no result, i.e. no attachment(s) available
         {
            $move_attachments=0;
         }
      }


      @reset($messages);
      $msg_row=@current($messages);


       while(list($key, $value)=each($msg_row)){
          $$key=$msg_row[$key];
       }

       $author = addslashes($author);
       $email = addslashes($email);
       $subject = addslashes($subject);
       $body = addslashes($body);

       $currentid=$DB->nextid($targettable);
       if($currentid==0 && $DB->type!="mysql") {
         return("Error getting nextval.");
       }

       //bodies (first message in thread only)
       $sSQL = "Insert Into $targettable"."_bodies (id, body, thread) values ($currentid, '$body', $currentid)";
       $q->query($DB, $sSQL);
       if($err=$q->error()){
                 echo($err."<br>$sSQL");
       }

       if($DB->type=="mysql"){ // the autoincrement may return a new message-id
          $currentid=$DB->lastid();
          $thread=$currentid;
          $sSQL = "Update $targettable"."_bodies SET thread = id WHERE id = $currentid";
          $q->query($DB, $sSQL);
          if($err=$q->error()){
            echo($err."<br>$sSQL");
          }
       } else { // we need this for pgsql too
               $thread=$currentid;
       }

       $oldid=$id; // old threadid

       // thread data (first message in thread only)
       $sSQL = "Insert Into $targettable (id, author, userid, email, datestamp, modifystamp, subject, host, thread, parent, email_reply, approved, msgid) values ('$currentid', '$author', '$userid', '$email', '$datestamp','$modifystamp', '$subject', '$host', '$thread', '$parent', '$email_reply', '$approved', '$msgid')";
       $q->query($DB, $sSQL);
       if($err=$q->error()){
          echo($err."<br>$sSQL");
       }


       // attachments (first message in thread only)
       if($move_attachments) {
          $curattach=@current($attachs);
          while(is_array($curattach)) {
          if($curattach["message_id"]==$oldid)
          {
             $sSQL = "Insert Into $targettable"."_attachments (id, message_id, filename) values (0, '$currentid', '".$curattach["filename"]."')";
             $q->query($DB, $sSQL);
             if($err=$q->error()){
                    echo($err."<br>$sSQL");
              }
             $lastattachid=$DB->lastid();
              $sourcefilename="$PHORUM[AttachmentDir]/$PHORUM[ForumTableName]/$curattach[message_id]_$curattach[id]".strtolower(strrchr($curattach["filename"], "."));
             $targetfilename="$PHORUM[AttachmentDir]/$targettable/$currentid"."_$lastattachid".strtolower(strrchr($curattach["filename"], "."));

	     if(!file_exists($sourcefilename)) { //verify file exists
			// we might have the old style naming here then...
			$sourcefilename="$PHORUM[AttachmentDir]/$PHORUM[ForumTableName]/$curattach[id]".strtolower(strrchr($curattach["filename"], "."));
			$targetfilename="$PHORUM[AttachmentDir]/$targettable/$lastattachid".strtolower(strrchr($curattach["filename"], "."));

			if(!file_exists($sourcefilename)) {
				//both old and new naming haven't worked
				//so error out
				return("$sourcefilename doesn't exist!");
				}//end error if
			}//ends file existence checking

             if (!copy ($sourcefilename, $targetfilename)) {
              	echo("failed to copy $sourcefilename... <br>\n");
             }
             else
                unlink($sourcefilename);

          }
          $curattach=@next($attachs);
          } //end while (all attachments moved)
       }

       $newid=$currentid;
       $newid++;

       $msg_row=next($messages);

while(is_array($msg_row)) {
   $oldid=$msg_row["id"];
   $msg_row["id"]=$newid++;
   $msg_row["thread"]=$currentid;


   $msg_row["parent"]=$parents[$msg_row["parent"]]+$currentid;

   while(list($key, $value)=each($msg_row)){
      $$key=$msg_row[$key];
   }

   $author = addslashes($author);
   $email = addslashes($email);
   $subject = addslashes($subject);
   $body = addslashes($body);

   //bodies
   $sSQL = "Insert Into $targettable"."_bodies (id, body, thread) values ($id, '$body', $thread)";
   $q->query($DB, $sSQL);
   if($err=$q->error()){
         echo($err."<br>$sSQL");
   }


   //rest
   $sSQL = "Insert Into $targettable (id, author, userid, email, datestamp, modifystamp, subject, host, thread, parent, email_reply, approved, msgid) values ('$id', '$author', '$userid', '$email', '$datestamp', '$modifystamp', '$subject', '$host', '$thread', '$parent', '$email_reply', '$approved', '$msgid')";
   $q->query($DB, $sSQL);
   if($err=$q->error()){
          echo($err."<br>$sSQL");
   }

   //attachment
   if($move_attachments) {
      @reset($attachs);
      $curattach=@current($attachs);
      while(is_array($curattach)) {
         if($curattach["message_id"]==$oldid)
         {
            $sSQL = "Insert Into $targettable"."_attachments (id, message_id, filename) values (0, '$id', '".$curattach["filename"]."')";
            $q->query($DB, $sSQL);
            if($err=$q->error()){
                   echo($err."<br>$sSQL");
             }
            $lastattachid=$DB->lastid();
             $sourcefilename="$PHORUM[AttachmentDir]/$PHORUM[ForumTableName]/$curattach[message_id]_$curattach[id]".strtolower(strrchr($curattach["filename"], "."));
            $targetfilename="$PHORUM[AttachmentDir]/$targettable/$id"."_$lastattachid".strtolower(strrchr($curattach["filename"], "."));

	     if(!file_exists($sourcefilename)) { //verify file exists
			// we might have the old style naming here then...
			$sourcefilename="$PHORUM[AttachmentDir]/$PHORUM[ForumTableName]/$curattach[id]".strtolower(strrchr($curattach["filename"], "."));
			$targetfilename="$PHORUM[AttachmentDir]/$targettable/$lastattachid".strtolower(strrchr($curattach["filename"], "."));
		}//end source check if

            if (!copy ($sourcefilename, $targetfilename)) {
             echo ("failed to copy $sourcefilename...<br>\n");
            }
            else
               unlink($sourcefilename);
         }
         $curattach=@next($attachs);
      }// end while
   }


    $msg_row=next($messages);
   }

   // delete posts

   $sSQL = "Delete from ".$ForumTableName."_bodies WHERE ".$ForumTableName."_bodies.thread = ".$idt;
   $q->query($DB, $sSQL);

   $sSQL = "Delete from $ForumTableName WHERE $ForumTableName.thread = ".$idt;
   $q->query($DB, $sSQL);

   if($move_attachments) {
      $sSQL = "Delete from $ForumTableName"."_attachments WHERE message_id in ($list)";
      $q->query($DB, $sSQL);
   }

   return "";
}

?>
