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

  include_once "./common.php";

  if(isset($preview)){
    include "$include_path/preview.php";
    exit();
  }

  if($num==0 || $ForumName==''){
    Header("Location: $forum_url?$GetVars");
    exit;
  }

  if($ForumSecurity > SEC_OPTIONAL && !isset($phorum_user['id'])){
    header("Location: $forum_url/login.$ext?target=$PHP_SELF&f=$num");
    exit();
  }

  require "$include_path/post_functions.php";

  settype($t, "integer");
  settype($a, "string");
  settype($i, "integer");
  settype($p, "integer");
  settype($r, "integer");

  $thread=$t;
  $action=$a;
  $id=$i;
  $parent=$p;

  if(count($HTTP_POST_VARS)>0){

      if (getenv('REMOTE_ADDR') != "") {
         $host = @GetHostByAddr(getenv('REMOTE_ADDR'));
      } else {
         $host = @GetHostByAddr($_SERVER['REMOTE_ADDR']);
      }

      // option space on a Mac creates an ascii 160 that trim won't catch.
      // But it shows up as a space.
      $author=str_replace(chr(160), " ", $author);

      // strip &nbsp;
      $author=str_replace("&nbsp;", " ", $author);
      $subject=str_replace("&nbsp;", " ", $subject);

      $author=phorum_strip_tags($author);      
      $email=phorum_strip_tags($email);      
      $subject=phorum_strip_tags($subject);      

      if(preg_match("/[^0-9,]/", $attach_ids)){
          $attach_ids = "";
      }

      $author=trim($author);
      $subject=trim($subject);
      $email=trim($email);
      $body=chop($body);

      $IsError = @check_data($host, $author, $subject, $body, $email);

      // PHP4 style
      if(!$IsError && $AllowAttachments && $ForumAllowUploads == 'Y'){
        $IsError=check_attachments($HTTP_POST_FILES, $attach_ids);
      }

  }

  if(!empty($IsError) || $action!="post"){

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
    // Search
    addnav($menu, $lSearch, "$search_page.$ext?f=$num$GetVars");

    // Log Out/Log In
      if($ForumSecurity){
        if(isset($phorum_user['id'])){
          addnav($menu, $lLogOut, "login.$ext?logout=1$GetVars");
          addnav($menu, $lMyProfile, "profile.$ext?f=$f&id=$phorum_user[id]$GetVars");
        }else{
          addnav($menu, $lLogIn, "login.$ext?f=$f$GetVars");
        }
      }

    $nav=getnav($menu);
    $TopLeftNav=$nav;

  //////////////////////////
  // END NAVIGATION       //
  //////////////////////////

    if(!empty($r) && $ReplyLocation==1){
        $sql="select $PHORUM[ForumTableName].thread, author, subject, body from $PHORUM[ForumTableName], $PHORUM[ForumTableName]_bodies where $PHORUM[ForumTableName].id=$PHORUM[ForumTableName]_bodies.id and $PHORUM[ForumTableName].id=$r";
        $q->query($DB, $sql);
        echo $q->error();
        $row=$q->getrow();
        $qauthor=$row["author"];
        $qsubject=$row["subject"];
        $qbody=$row["body"];
        $thread=$row["thread"];
        $parent=$r;
    }

    include "$include_path/form.php";
    include phorum_get_file_name("footer");
    exit();
  }

  if($UseCookies){
    $name_cookie="phorum_name";
    $email_cookie="phorum_email";

    if((!IsSet($$name_cookie)) || ($$name_cookie != $author)) {
      SetCookie($name_cookie,stripslashes($author),time()+ 31536000);
    }
    if((!IsSet($$email_cookie)) || ($$email_cookie != $email)) {
      SetCookie($email_cookie,stripslashes($email),time()+ 31536000);
    }
  }

  list($author, $subject, $email, $body) = censor($author, $subject, $email, $body);

  if(!get_magic_quotes_gpc()){
    $author = addslashes($author);
    $email = addslashes($email);
    $subject = addslashes($subject);
    $body = addslashes($body);
  }

  $datestamp = date("Y-m-d H:i:s");
  if(isset($phorum_user['id']) && isset($use_sig)) {
       $email_body=$body."\n\n".$phorum_user['signature'];
  } else {
       $email_body=$body;
  }

  $plain_author=stripslashes($author);
  $plain_subject=stripslashes($subject);
  
  // work around strip_tags issues 
  $plain_body=stripslashes(preg_replace("|</*[a-z][^>]*>|i", "", $body));

  $more="";

     // add the users signature if requested
    if(isset($use_sig)){
        $body.="\n\n".PHORUM_SIG_MARKER;
    }

    if(isset($body))
        $body=str_replace("\r\n", "\n", $body);

  if (!check_dup() && check_parent($parent)) {
    // generate a message id for the email if needed.
    $msgid="<".md5(uniqid(rand())).".".preg_replace("/[^a-z0-9]/i", "", $ForumName).">";

    // exec all post-append plugins
    @reset($plugins["post_append"]);
    while(list($key,$val) = each($plugins["post_append"])) {
        $val();
    }

    // This will add the message to the database, and email the
    // moderator if required.
    $id = post_to_database();
    if (!$id) {
      echo $error;
      exit();
    }

    // mark this message as read in their cookies since they wrote it.
    $haveread_cookie="phorum-haveread-$ForumTableName";
    if(empty($$haveread_cookie)){
      $$haveread_cookie=$id;
    }
    else{
      $$haveread_cookie.=".";
      $$haveread_cookie.="$id";
    }
    SetCookie("phorum-haveread-$ForumTableName",$$haveread_cookie,0);

    // if it is not a new message and not float to top
    // send them to the message.
    if($thread!=$id && $ForumMultiLevel!=2){
      $more = $thread+1;
      $more = "&a=2&t=$more";
    }

    // Attachment handling:
    if(!empty($attach_ids)){
        // preview
        if(!is_array($attach_ids)) $attach_ids=explode(",", $attach_ids);
        foreach($attach_ids as $aid){
            $sql="update $PHORUM[ForumTableName]_attachments set message_id=$id where id=$aid";
            $q->query($DB, $sql);
        }
    } elseif(isset($HTTP_POST_FILES)) {
        // posting
        save_attachments($HTTP_POST_FILES, $attach_ids, $id);
    }

    // This will send email to the mailing list, if applicable,
    // and send email replies to earlier posters, if necessary.
    // Note that when posting to a mailing list, active moderation
    // does not apply.
    post_to_email();
  }

  if(isset($attach)){
    Header ("Location: $forum_url/$attach_page.$ext?f=$num&i=$id$GetVars");
  }
  else{
    Header ("Location: $forum_url/$list_page.$ext?f=$num$more$GetVars");
  }
?>
