<xmp>
<?php
// Please note that this script only works for upgrading from 3.0.x
// and it may fail if you have a current table set to the same name as $pho_main
// (default = "forums") or whose names start with "new"
// 1. READ docs/upgrade.txt before using this script
// 2. READ docs/upgrade.txt before using this script
// 3. READ docs/upgrade.txt before using this script ...

  $oldinfpath="./";
  if(file_exists("$oldinfpath/forums.inf")){
    include "$oldinfpath/forums.inf";

    if (isset($nDisplay) && ($nDisplay > 0)) {
      $def_display = $nDisplay;
    } else {
      $def_display = 30;
    }

    chdir("../");
    @require "common.php";
    error_reporting(0);

    // create "forums" table if it doesn't already exist
    $sSQL = "Select max(id) as m_id from ".$pho_main;
    $q->query($DB, $sSQL);
    if ($q->error()) {
      create_table($DB, "forums", $pho_main);
      $sSQL = "Select max(id) as m_id from ".$pho_main;
      $q->query($DB, $sSQL);
      if ($q->error()) {
        print "Problem accessing or creating main forums table (".$pho_main."). Exiting upgrade. <br />\n";
        print "Did you enter your database settings using the admin interface under Phorum Setup->Database Settings?<br />\n";
        exit();
      }
    }

    $f=current($forums);
    while(is_array($f)){
      if($f['email_mod']) $moderation="r";
      $f["name"]=addslashes($f["name"]);
      $f["description"]=addslashes($f["description"]);
      $f["staff_host"]=addslashes($f["staff_host"]);
      $f["lang"]=str_replace(".lang", ".php", $f["lang"]);
      $sSQL="INSERT INTO
               $pho_main
             SET
              id=$f[id],
              name='$f[name]',
              active=$f[active],
              description='$f[description]',
              display='$def_display',
              table_name='new$f[table]',
              moderation='$moderation',
              multi_level='$f[multi_level]',
              collapse='$f[collapse]',
              lang='lang/$f[lang]',
              html='$f[html]',
              table_width='$f[table_width]',
              table_header_color='$f[table_header_color]',
              table_header_font_color='$f[table_header_font_color]',
              table_body_color_1='$f[table_body_color_1]',
              table_body_color_2='$f[table_body_color_2]',
              table_body_font_color_1='$f[table_body_font_color_1]',
              table_body_font_color_2='$f[table_body_font_color_2]',
              nav_color='$f[nav_color]',
              nav_font_color='$f[nav_font_color]',
          showip='1',
          body_color='#FFFFFF',
          body_link_color='#0000FF',
          body_alink_color='#FF0000',
          body_vlink_color='#330000'
            ";
      echo "Inserting $f[name]...\n";
      flush();
      $q->query($DB, $sSQL);
      if($err=$q->error()) echo $err."\n";
      create_table($DB, "main", "new$f[table]");
      create_table($DB, "bodies", "new$f[table]");
      $sSQL="insert into new$f[table] (id, datestamp, thread, parent, author, subject, email, host, email_reply) select id, datestamp, thread, parent, author, subject, email, host, email_reply from $f[table]";
      echo "Copying message headers for $f[name]...\n";
      flush();
      $q->query($DB, $sSQL);
      if($err=$q->error()) echo "ERROR: $err\n";
      $sSQL="update new$f[table] set approved='Y'";
      echo "Updating approved field in $f[name]...\n";
      flush();
      $q->query($DB, $sSQL);
      if($err=$q->error()) echo "ERROR: $err\n";
      $sSQL="insert into new$f[table]"."_bodies select * from $f[table]"."_bodies";
      echo "Copying message bodies for $f[name]...\n";
      flush();
      $q->query($DB, $sSQL);
      if($err=$q->error()) echo "ERROR: $err\n";
      $sSQL="select max(id) as id from new$f[table]";
      $q->query($DB, $sSQL);
      $rec=$q->getrow();
      $DB->nextid("new$f[table]");
      if($rec["id"])  $DB->reset_sequence("new$f[table]", $rec["id"]+1);
      $f=next($forums);
      echo "\n-------------------\n\n";
    }
    echo "Attempting to rename $oldinfpath/forums.inf to $oldinfpath/forums.inf.old\n\n";
    rename("$oldinfpath/forums.inf", "$oldinfpath/forums.inf.old");
    echo "You now need to rebuild the inf files using the Phorum admin.\n";
  }
  else{
    echo "Could not find old forums.inf file at location: $oldinfpath/forums.inf";
  }

?>
</xmp>
