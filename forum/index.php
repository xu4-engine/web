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

  if(empty($f)) $f="0";

  if($ActiveForums==1){
    $sSQL="Select id, folder from $pho_main where active=1";
    $q->query($DB, $sSQL);
    $rec=$q->getrow();
    if($rec["folder"]==0){
      header("Location: $forum_url/$list_page.$ext?f=$rec[id]$GetVars");
      exit();
    }
  }

  $title = " - $lForumList";
  include phorum_get_file_name("header");

  if($f!=0){
    $level='';
    if($ForumParent!=0){
      $level="f=$ForumParent";
    } else {
      $level="f=0";
    }
    $table_width=$ForumTableWidth;
    $table_header_color=$ForumTableHeaderColor;
    $table_header_font_color=$ForumTableHeaderFontColor;
    $table_body_color_1=$ForumTableBodyColor1;
    $table_body_font_color_1=$ForumTableBodyFontColor1;
    $nav_color=$ForumNavColor;
    $nav_font_color=$ForumNavFontColor;

    addnav($menu, $lUpLevel, "$forum_page.$ext?$level$GetVars");
  }
  else{
    $table_width=$default_table_width;
    $table_header_color=$default_table_header_color;
    $table_header_font_color=$default_table_header_font_color;
    $table_body_color_1=$default_table_body_color_1;
    $table_body_font_color_1=$default_table_body_font_color_1;
    $nav_color=$default_nav_color;
    $nav_font_color=$default_nav_font_color;
  }

  //////////////////////////
  // START NAVIGATION     //
  //////////////////////////

    // Log Out/Log In
    if(isset($phorum_user['id'])){
      addnav($menu, $lLogOut, "login.$ext?f=$f&logout=1$GetVars");
      addnav($menu, $lMyProfile, "profile.$ext?f=$f&id=$phorum_user[id]$GetVars");
    } else{
      $SQL="Select max(security) as sec from $pho_main";
      $q->query($DB, $SQL);
      if($q->field("sec", 0)){
          $url="login.$ext?f=$f$GetVars";
          addnav($menu, $lLogIn, $url);
      }
    }

  //////////////////////////
  // END NAVIGATION       //
  //////////////////////////


  if(isset($menu) && is_array($menu)){
    $TopNav=getnav($menu);
?>
<table width="<?php echo $table_width; ?>" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td <?php echo bgcolor($nav_color); ?> valign="TOP" nowrap="nowrap"><?php echo $TopNav; ?></td>
  </tr>
</table>
<?php
  }
?>
<table class="PhorumListTable" width="<?php echo $table_width; ?>" cellspacing="0" cellpadding="2" border="0">
<tr>
    <td class="PhorumTableHeader" width="100%" colspan="3" <?php echo bgcolor($table_header_color); ?>><FONT color="<?php echo $table_header_font_color; ?>">&nbsp;<?php echo $lAvailableForums;?></font></td>
</tr>
<?php
  if(isset($q)){
    // get all parents
    $all_parents=$f;
    $act_id=$f;
    while($act_id!=0){
      $sSQL="Select parent from $pho_main where id=$act_id";
      $q->query($DB, $sSQL);
      $rec=$q->getrow();
      $act_id=$rec["parent"];
      $all_parents.=",".$act_id;
    }
    $sSQL="Select id, name, table_name, parent, folder, description from ".$pho_main." where active=1 and (parent=$f or displayflag=1 or (displayflag=2 and parent in (".$all_parents."))) order by folder desc";
    if($SortForums) $sSQL.=", name";
    $q->query($DB, $sSQL);
    $rec=$q->getrow();
  } else {
    $rec = "";
  }

  if(is_array($rec)){
    while(is_array($rec)){
      $empty=false;
      $name=$rec["name"];
      $num=$rec["id"];
      $description=$rec["description"];
      if(!$rec["folder"]){
        $sSQL="select count(*) as posts from $rec[table_name]";
        $tq = new query($DB, $sSQL);
        if($tq->numrows()){
           $trec=$tq->getrow();
           $num_posts=$trec["posts"];
           $sSQL="select count(*) as nonposts from $rec[table_name] where approved='N'";
           $tq = new query($DB, $sSQL);
           if ($tq->numrows()){
              $trec=$tq->getrow();
              $num_posts -= $trec["nonposts"];
           }
        } else{
           $num_posts='0';
        }
        $sSQL="select max(datestamp) as max_date from $rec[table_name] where approved='Y'";
        $tq->query($DB, $sSQL);
        $trec=$tq->getrow();
        if(empty($trec["max_date"])){
          $last_post_date="";
        } else {
          $last_post_date=date_format($trec["max_date"]);
        }
        $posts="$lNumPosts: <strong>$num_posts</strong>&nbsp;&nbsp;";
        $last="$lLastPostDate: <strong>$last_post_date</strong>";
        $url="$list_page.$ext?f=$num$GetVars";
      }

      else{
        $last=$lForumFolder;
        $url="$forum_page.$ext?f=$num$GetVars";
        $posts="";
      }

?>
<tr>
  <td nowrap="nowrap" bgcolor="<?php echo $table_body_color_1; ?>"><FONT color="<?php echo $table_body_font_color_1; ?>" class="PhorumForumTitle">&nbsp;<a href="<?php echo $url; ?>"><?php echo $name; ?></a></font></td>
  <td nowrap="nowrap" bgcolor="<?php echo $table_body_color_1; ?>"><FONT color="<?php echo $table_body_font_color_1; ?>">&nbsp;&nbsp;<?php echo $posts; ?></FONT></td>
  <td nowrap="nowrap" bgcolor="<?php echo $table_body_color_1; ?>"><FONT color="<?php echo $table_body_font_color_1; ?>">&nbsp;&nbsp;<?php echo $last; ?></FONT></td>
</tr>
<tr>
  <td colspan=3 bgcolor="<?php echo $table_body_color_1; ?>"><FONT color="<?php echo $table_body_font_color_1; ?>"><blockquote><br /><?php echo $description; ?></blockquote></font></td>
</tr>
<?php
      $rec=$q->getrow();

    }

  }

  else{
?>
<tr>
    <td width="100%" colspan=3 <?php echo bgcolor($table_body_color_1); ?>><FONT color="<?php echo $table_body_font_color_1; ?>">&nbsp;<?php echo $lNoActiveForums;?></font></td>
</tr>
<?php
  }
?>
</table>
<?php
  include phorum_get_file_name("footer");
?>
