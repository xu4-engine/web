<?php

  if(!defined("PHORUM_ADMIN")) return;

  $message.="Altering table $pho_main<br />\n";
  flush();
  $SQL="ALTER TABLE $pho_main change id id int UNSIGNED DEFAULT '0' NOT NULL AUTO_INCREMENT";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main change parent parent int UNSIGNED DEFAULT '0' NOT NULL AUTO_INCREMENT";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main change display display int UNSIGNED DEFAULT '0' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main change check_dup check_dup smallint unsigned DEFAULT '0' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main change multi_level multi_level smallint(5) unsigned DEFAULT '0' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main change collapse collapse smallint(5) unsigned DEFAULT '0' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main change flat flat smallint(5) unsigned DEFAULT '0' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD allow_uploads char(1) DEFAULT 'N' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD email_list char(50) DEFAULT '' NOT NULL after mod_pass";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD email_return char(50) DEFAULT '' NOT NULL after email_list";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD email_tag char(50) DEFAULT '' NOT NULL after email_return";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD config_suffix char(50) DEFAULT '' NOT NULL after description";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD upload_types char(100) DEFAULT '' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD upload_size int unsigned DEFAULT '0' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD max_uploads int unsigned DEFAULT '0' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD security int unsigned DEFAULT '0' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD showip smallint(5) unsigned DEFAULT 1 NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD emailnotification smallint(5) unsigned DEFAULT '0' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD body_color char(7) DEFAULT '' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD body_link_color char(7) DEFAULT '' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD body_alink_color char(7) DEFAULT '' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD body_vlink_color char(7) DEFAULT '' NOT NULL";
  $q->query($DB, $SQL);
  // since version 3.4
  $SQL="ALTER TABLE $pho_main ADD required_level smallint DEFAULT 0 NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD permissions smallint DEFAULT 0 NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD allow_edit smallint DEFAULT 1 NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD allow_langsel smallint DEFAULT 0 NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main ADD displayflag smallint DEFAULT 0 NOT NULL";
  $q->query($DB, $SQL);

  $SQL="ALTER TABLE ".$PHORUM['auth_table']." ADD max_group_permission_level int unsigned DEFAULT 0 NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE ".$PHORUM['auth_table']." ADD permission_level int unsigned DEFAULT 0 NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE ".$PHORUM['auth_table']." DROP sess_id";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE ".$PHORUM['auth_table']." ADD hide_email tinyint(3) unsigned NOT NULL default '0'";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE ".$PHORUM['auth_table']." ADD lang varchar(50) DEFAULT '' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE ".$PHORUM['auth_table']." ADD password_tmp varchar(50) DEFAULT '' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE ".$PHORUM['auth_table']." ADD combined_token varchar(50) DEFAULT '' NOT NULL";
  $q->query($DB, $SQL);
  $SQL="CREATE INDEX userpass ON ".$PHORUM['auth_table']." (username,password)";
  $q->query($DB, $SQL);
  // end of 3.4-part


  $SQL="ALTER TABLE $pho_main DROP mod_email";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main DROP mod_pass";
  $q->query($DB, $SQL);
  $SQL="ALTER TABLE $pho_main DROP staff_host";
  $q->query($DB, $SQL);

  $SQL="DROP TABLE ".$pho_main."_seq";
  $q->query($DB, $SQL);
  create_table($DB, "auth", $PHORUM['main_table']);
  create_table($DB, "moderators", $PHORUM['main_table']);
  create_table($DB, "forum2group", $PHORUM['main_table']);
  create_table($DB, "groups", $PHORUM['main_table']);
  create_table($DB, "user2group", $PHORUM['main_table']);


  $SQL="Select id, name, table_name from $pho_main WHERE folder = '0'";
  $query = new query($DB, $SQL);

  $rec=$query->getrow();

  while(is_array($rec)){
    $message.="Altering tables for $rec[name]<br />\n";
    flush();
    $SQL="ALTER TABLE $rec[table_name]_bodies CHANGE id id int unsigned DEFAULT '0' NOT NULL auto_increment";
    $q->query($DB, $SQL);
    $SQL="ALTER TABLE $rec[table_name]_bodies CHANGE thread thread int unsigned DEFAULT '0' NOT NULL";
    $q->query($DB, $SQL);
    $SQL="ALTER TABLE $rec[table_name] CHANGE host host varchar(255) DEFAULT '' NOT NULL";
    $q->query($DB, $SQL);


     $SQL="DROP TABLE $rec[table_name]_seq";
     $q->query($DB, $SQL);

    $SQL="ALTER TABLE $rec[table_name] CHANGE id id int unsigned DEFAULT '0' NOT NULL";
    $q->query($DB, $SQL);
    $SQL="ALTER TABLE $rec[table_name] CHANGE thread thread int unsigned DEFAULT '0' NOT NULL";
    $q->query($DB, $SQL);
    $SQL="ALTER TABLE $rec[table_name] CHANGE parent parent int unsigned DEFAULT '0' NOT NULL";
    $q->query($DB, $SQL);
    $SQL="ALTER TABLE $rec[table_name] CHANGE subject subject char(255) DEFAULT '' NOT NULL";
    $q->query($DB, $SQL);
    $SQL="ALTER TABLE $rec[table_name] CHANGE email email char(200) DEFAULT '' NOT NULL";
    $q->query($DB, $SQL);
    $SQL="ALTER TABLE $rec[table_name] ADD msgid char(100) DEFAULT '' NOT NULL, ADD KEY msgid (msgid)";
    $q->query($DB, $SQL);
    $SQL="ALTER TABLE $rec[table_name] ADD modifystamp int(10) unsigned DEFAULT '0' NOT NULL";
    $q->query($DB, $SQL);
    $SQL="ALTER TABLE $rec[table_name] ADD KEY modifystamp (modifystamp)";
    $q->query($DB, $SQL);
    $SQL="ALTER TABLE $rec[table_name] ADD userid int(10) unsigned DEFAULT '0' NOT NULL";
    $q->query($DB, $SQL);
    $SQL="ALTER TABLE $rec[table_name] ADD closed tinyint DEFAULT '0' NOT NULL";
    $q->query($DB, $SQL);
    $SQL="ALTER TABLE $rec[table_name] ADD KEY userid (userid)";
    $q->query($DB, $SQL);
    $message.="Updating modifystamp for $rec[name]<br />\n";
    flush();
    $SQL="select thread, max(datestamp) as datestamp from $rec[table_name] group by thread";
    $q->query($DB, $SQL);

    $q2 = new query($DB);
    echo "<!-- ";
    while($rec2=$q->getrow()){
        list($date,$time) = explode(" ", $rec2["datestamp"]);
        list($year,$month,$day) = explode("-", $date);
        list($hour,$minute,$second) = explode(":", $time);
        $tstamp = mktime($hour,$minute,$second,$month,$day,$year);
        $SQL="update $rec[table_name] set modifystamp=$tstamp where thread=$rec2[thread]";
        $q2->query($DB, $SQL);
        echo ".";
        flush();
    }
    echo " -->";
    $message.="<br />\n";
    $SQL="select id, attachment from $rec[table_name]";
    $q->query($DB, $SQL);
    if($q->numrows()>0 && !empty($AttachmentDir)){
        $message.="Converting Attachments (from version 3.2.x) for $rec[name]<br />\n";
        while($rec2=$q->getrow()){

            $id=$DB->nextid("$rec[table_name]_attachments");
            if($id==0 && $DB->type!="mysql"){
              $err.="Could not get an id for the attachment.<br />\n";
            }
            else{
              if ($rec2[attachment]) {
                  $SQL="Insert into $rec[table_name]_attachments (id, message_id, filename) values($id, $rec2[id], '$rec2[attachment]')";
                  $q2->query($DB, $SQL);
                  $error=$q2->error();
                  if($error==""){
                    if($DB->type=="mysql"){
                      $id=$DB->lastid();
                    }

                    $new_name = "$AttachmentDir/$rec[table_name]/$id".strtolower(strrchr($rec2["attachment"], "."));
                    if(! rename("$AttachmentDir/$rec[table_name]/$rec2[attachment]", $new_name)){
                      $err.="Can't save upload file.<br />\n";
                    }
                  }
              }
              else{
                $err.="Error adding attachment.  DB said: $error<br />\n";
              }
            }

        }
        $SQL="ALTER TABLE $rec[table_name] DROP attachment";
        $q->query($DB, $SQL);
        create_table($DB, "attachments", "$rec[table_name]_attachments");
    } elseif(!empty($AttachmentDir)) {
      $SQL="describe ".$rec[table_name]."_attachments";
      $q->query($DB, $SQL);
      if($q->numrows()>0) { // ok we seem to have a table with 3.3.x-attachments 
         $message.="Converting attachments for an 3.3.x-phorum (or trying for an 3.4.x) ... <br />\n";
         $SQL="SELECT id,message_id,filename FROM ".$rec[table_name]."_attachments";
         $q->query($DB, $SQL);
         if($q->numrows()>0) { // hmm, attachments-table seems to contain data
           while($row=$q->getrow()) {
              $oldfile="$AttachmentDir/".$rec['table_name']."/".$row['message_id']."_".$row['id'].strtolower(strrchr($row["filename"], "."));
              $newfile="$AttachmentDir/".$rec['table_name']."/".$row['id'].strtolower(strrchr($row['filename'], "."));
              if(!file_exists($oldfile) && file_exists($newfile)) {
                 $message.="Seems like we have 3.4.x-attachments already ;-).<br />\n";
                 break;
              } elseif(!file_exists($oldfile)) {
                 $message.="Old file $oldfile does not exists and new file $newfile doesn't exist too :-(.<br />\n";
              } else {
                 rename($oldfile,$newfile);
              }
           }
         } else {
           $message.="Attachments-table is empty.<br />\n";
         }
      }
    }

    $rec=$query->getrow();
  }

  create_table($DB, "groups", "$pho_main");
  create_table($DB, "user2group", "$pho_main");
  create_table($DB, "forum2group", "$pho_main");

  return 1;
?>
