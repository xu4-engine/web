<?php

    if(!defined("PHORUM_ADMIN")) return;

  function phorum_pg_add_column($table, $column_name, $definition, $default) {
        GLOBAL $DB, $q;
        $SQL="ALTER TABLE $table ADD $column_name $definition";
        $q->query($DB, $SQL);
        $SQL="ALTER TABLE $table ALTER $column_name SET DEFAULT $default";
        $q->query($DB, $SQL);
        // does not work yet, we have to look at it :-(
        /*
        $SQL="ALTER TABLE $table ADD CONSTRAINT ".$column_name."nn CHECK ($column_name IS NOT NULL)";
        $q->query($DB, $SQL);
        */
  }

  $message.="Altering table $pho_main<br />\n";
  flush();
  phorum_pg_add_column($pho_main,"allow_uploads","char(1)","'N'");
  phorum_pg_add_column($pho_main,"email_list","char(50)","''");
  phorum_pg_add_column($pho_main,"email_return","char(50)","''");
  phorum_pg_add_column($pho_main,"email_tag","char(50)","''");
  phorum_pg_add_column($pho_main,"config_suffix","char(50)","''");
  phorum_pg_add_column($pho_main,"upload_types","char(100)","''");
  phorum_pg_add_column($pho_main,"upload_size","int4","'0'");
  phorum_pg_add_column($pho_main,"max_uploads","int4","'0'");
  phorum_pg_add_column($pho_main,"security","int4","'0'");
  phorum_pg_add_column($pho_main,"showip","int2","1");
  phorum_pg_add_column($pho_main,"emailnotification","int2","'0'");
  phorum_pg_add_column($pho_main,"body_color","char(7)","''");
  phorum_pg_add_column($pho_main,"body_link_color","char(7)","''");
  phorum_pg_add_column($pho_main,"body_alink_color","char(7)","''");
  phorum_pg_add_column($pho_main,"body_vlink_color","char(7)","''");
  phorum_pg_add_column($pho_main,"required_level","int4","'0'");
  phorum_pg_add_column($pho_main,"permissions","int2","'0'");
  phorum_pg_add_column($pho_main,"allow_edit","int2","'1'");
  phorum_pg_add_column($pho_main,"allow_langsel","int2","'0'");
  phorum_pg_add_column($pho_main,"displayflag","int2","'0'");

  // Not possible with PG - To remove an existing column the table must be recreated and reloaded
  //$SQL="ALTER TABLE $pho_main DROP mod_email";
  //$q->query($DB, $SQL);
  //$SQL="ALTER TABLE $pho_main DROP mod_pass";
  //$q->query($DB, $SQL);
  //$SQL="ALTER TABLE $pho_main DROP staff_host";
  //$q->query($DB, $SQL);
  $message.= "<strong>$pho_main now contains 3 redundant fields: mod_email, mod_pass & staff_host<br />\n";
  $message.= "these can be removed useing a tool such as phpPgAdmin</strong><br />\n";

  $SQL="Select username,email from ".$PHORUM["main_table"]."_auth";
  $query = new query($DB, $SQL);

  $rec=$query->getrow();

  while(is_array($rec)){
    $message.= "Converting Emails to lowercase<br />\n";
    flush();
    $email=strtolower($rec['email']);
    $SQL="update ".$PHORUM["main_table"]."_auth set email='$email' where username='$rec[username]'";
    $q->query($DB, $SQL);
    $rec=$query->getrow();
  }
  phorum_pg_add_column($pho_main."_auth","hide_email","int2","'0'");
  phorum_pg_add_column($pho_main."_auth","permission_level","int4","'0'");
  phorum_pg_add_column($pho_main."_auth","max_group_permission_level","int4","'0'");
  phorum_pg_add_column($pho_main."_auth","lang","varchar(50)","''");
  phorum_pg_add_column($pho_main."_auth","password_tmp","varchar(50)","''");
  phorum_pg_add_column($pho_main."_auth","combined_token","varchar(50)","''");

  create_table($DB, "auth", $PHORUM["main_table"]);
  create_table($DB, "moderators", $PHORUM["main_table"]);
  create_table($DB, "user2group", $PHORUM["main_table"]);
  create_table($DB, "forum2group", $PHORUM["main_table"]);
  create_table($DB, "group", $PHORUM["main_table"]);
  $SQL="DROP INDEX name_forums_auth_ukey";
  $q->query($DB, $SQL);
  $SQL="CREATE INDEX name_forums_auth_ukey ON ".$PHORUM["main_table"]."_auth (name)";
  $q->query($DB, $SQL);

  $SQL="Select id, name, table_name from $pho_main WHERE folder = '0'";
  $query = new query($DB, $SQL);

  $rec=$query->getrow();

  while(is_array($rec)){
    $message.= "Altering tables for $rec[name]<br />\n";
    flush();
    $SQL="ALTER TABLE $rec[table_name]_bodies ALTER id SET DEFAULT '0'";
    $q->query($DB, $SQL);
    $SQL="ALTER TABLE $rec[table_name]_bodies ALTER thread SET DEFAULT '0'";
    $q->query($DB, $SQL);

     // droping the sequence casues a duplicate key error
     //$SQL="DROP SEQUENCE $rec[table_name]_seq";
     //$q->query($DB, $SQL);

    phorum_pg_add_column($rec['table_name'],"msgid","char(100)","''");
    phorum_pg_add_column($rec['table_name'],"modifystamp","int4","'0'");
    phorum_pg_add_column($rec['table_name'],"userid","int4","'0'");
    PHORUM_PG_add_column($rec['table_name'],"closed","int2","'0'");
    $SQL="CREATE INDEX ".$rec[table_name]."_msgid on ".$rec['table_name']."(msgid)";
    $q->query($DB, $SQL);
    $SQL="CREATE INDEX ".$rec[table_name]."_modifystamp on ".$rec['table_name']."(modifystamp)";
    $q->query($DB, $SQL);
    $SQL="CREATE INDEX ".$rec[table_name]."_userid on $rec[table_name](userid)";
    $q->query($DB, $SQL);


    $message.= "Updating modifystamp for $rec[name]<br />\n";
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

    $SQL="select id, attachment from $rec[table_name]";
    $q->query($DB, $SQL);
    if($q->numrows()>0){
        $message.= "<br />\n";
        $message.= "Converting Attachments (3.2.x-table) for $rec[name]<br />\n";
        create_table($DB, "attachments", "$rec[table_name]_attachments");
        while($rec2=$q->getrow()){

            $id=$DB->nextid("$rec[table_name]_attachments");
            if($id==0 && $DB->type!="mysql"){
              $err="Could not get an id for the attachment.<br />\n";
            }
            else{
              $SQL="Insert into $rec[table_name]_attachments (id, message_id, filename) values($id, $rec2[id], '$rec2[attachment]')";
              $q->query($DB, $SQL);
              $error=$q->error();
              if($error==""){
                if($DB->type=="mysql"){
                  $id=$DB->lastid();
                }

                $new_name = "$AttachmentDir/$rec[name]/$rec2[id]"."_$id".strtolower(strrchr($rec2["attachment"], "."));
                if(rename("$AttachmentDir/$rec[name]/$rec2[attachment]", $new_name)){
                  $err.="Can't save upload file.";
                }
              }
              else{
                $err.="Error adding attachment.  DB said: $error<br />\n";
              }
            }

        }
 // Not possible with PG - To remove an existing column the table must be recreated and reloaded
        //$SQL="ALTER TABLE $rec[table_name] DROP attachment";
        //good_query($SQL) || return 0;

  $message.="<strong>$rec[table_name] contains 1 redundant field: attachment<br />\n";
  $message.="this can be removed useing a tool such as phpPgAdmin</strong><br />\n";

    } elseif(!empty($AttachmentDir)) {
      // need an equivalent for pgsql TODO!!!
      $SQL="SELECT a.attnum, a.attname, t.typname, a.attlen, a.atttypmod, a.attnotnull, a.atthasdef FROM pg_class c, pg_attribute a, pg_type t WHERE c.relname = '".$rec[table_name]."_attachments' and a.attnum > 0 and a.attrelid = c.oid and a.atttypid = t.oid ORDER BY attnum";
      $q->query($DB, $SQL);
      if($q->numrows()>0) { // ok we seem to have a table with 3.3.x-attachments
         echo "row 1: ".print_r($q->getrow())."<br />\n";
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

  return 1;
?>
