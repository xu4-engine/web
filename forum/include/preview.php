<?php

    if ( !defined( "_COMMON_PHP" ) ) return;

    include_once "$include_path/read_functions.php";
    include_once "$include_path/post_functions.php";

    $attach_ids=array();
    $thread=$t;
    $parent=$p;

    if($AllowAttachments && $ForumAllowUploads == 'Y'){
        $IsError=check_attachments($HTTP_POST_FILES);
        if(!$IsError){
            save_attachments($HTTP_POST_FILES, $attach_ids);
        }
    }

    $IsError = @check_data($host, $author, $subject, $body, $email);

    include phorum_get_file_name("header");

    $IsPreview=true;

    $strip_author=str_replace("\"", "&quot;", $author);
    $strip_email=str_replace("\"", "&quot;", $email);
    $strip_subject=str_replace("\"", "&quot;", $subject);
    $strip_body=str_replace("\"", "&quot;", $body);

    settype($email_reply, "integer");
    settype($use_sig, "integer");

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
        } else {
          addnav($menu, $lLogIn, "login.$ext?f=$f$GetVars");
        }
      }

    $nav=getnav($menu);
    $TopLeftNav=$nav;

    //////////////////////////
    // END NAVIGATION       //
    //////////////////////////


  if(empty($IsError)){

    if(get_magic_quotes_gpc()){
        $strip_subject=stripslashes($strip_subject);
        $strip_author=stripslashes($strip_author);
        $strip_body=stripslashes($strip_body);
    }
?>
<form action="<?php echo "$post_page.$ext"; ?>" method="post" onSubmit="post.disabled=true;">
<input type="hidden" name="t" value="<?php  echo $t; ?>" />
<input type="hidden" name="a" value="<?php echo $a; ?>" />
<input type="hidden" name="f" value="<?php echo $f; ?>" />
<input type="hidden" name="p" value="<?php echo $p; ?>" />
<input type="hidden" name="attach_ids" value="<?php echo implode(",", $attach_ids); ?>" />
<input type="hidden" name="author" value="<?php echo $strip_author; ?>" />
<input type="hidden" name="email" value="<?php echo $email; ?>" />
<input type="hidden" name="subject" value="<?php echo $strip_subject; ?>" />
<input type="hidden" name="body" value="<?php echo $strip_body; ?>" />
<input type="hidden" name="email_reply" value="<?php echo $email_reply; ?>" />
<input type="hidden" name="use_sig" value="<?php echo $use_sig; ?>" />
<?php echo $PostVars; ?>
<table width="<?php echo $ForumTableWidth; ?>" cellspacing="0" cellpadding="3" border="0">
<tr>
    <td width="100%" align="left" <?php echo bgcolor($ForumNavColor); ?>><?php echo $TopLeftNav; ?></td>
</table>
<table class="PhorumListTable" width="<?php echo $ForumTableWidth; ?>" cellspacing="0" cellpadding="2" border="0">
<tr>
    <td <?php echo bgcolor($ForumTableHeaderColor); ?>><FONT class="PhorumTableHeader" color="<?php echo $ForumTableHeaderFontColor; ?>">&nbsp;<?php echo $strip_subject; ?></font></td>
</tr>
<tr>
    <td <?php echo bgcolor($ForumTableBodyColor2); ?> valign="TOP"><table width="100%" cellspacing="0" cellpadding="5" border="0">
<tr>
    <td <?php echo bgcolor($ForumTableBodyColor2); ?> width="100%" valign="top"><font class="PhorumMessage" color="<?php echo $ForumTableBodyFontColor2; ?>">
<?php echo $lAuthor;?>:&nbsp;<?php echo $strip_author; ?><br />
<?php

    echo '<br />';

    if(!empty($phorum_user["sig"])){
        $strip_body=str_replace(PHORUM_SIG_MARKER, $phorum_user["sig"], $strip_body);
    }

    $strip_body=format_body($strip_body);
?>
<?php echo $strip_body; ?></font><p>
<?php  if ($AllowAttachments && $ForumAllowUploads == 'Y' && $ForumMaxUploads>3) { ?>
    <input type="Submit" name="attach" value=" <?php echo $lFormAttach;?> " />&nbsp;
<?php } ?>
<input type="Submit" name="post" value=" <?php echo $lFormPost;?> " />
</td>
</tr>
</table>
</td>
</tr>
</table>
</form>
<?php

    }

    include "$include_path/form.php";
    include phorum_get_file_name("footer");

?>
