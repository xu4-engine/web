<?php if ( !defined( "_COMMON_PHP" ) ) return; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "XHTML1-t.dtd">
<html>
<head>
<meta name="PhorumVersion" content="<?php echo $phorumver; ?>" />
<meta name="PhorumDB" content="<?php echo $DB->type; ?>" />
<meta name="PHPVersion" content="<?php echo phpversion(); ?>" />
<title>phorum<?php if(isset($ForumName)) echo " - $ForumName"; ?><?php if(isset($title)) echo $title; ?></title>
<link rel="STYLESHEET" type="text/css" href="<?php echo phorum_get_file_name("css"); ?>" />
</head>
<body bgcolor="<?php echo (empty($ForumBodyColor)) ? $default_body_color : $ForumBodyColor; ?>" link="<?php echo (empty($ForumBodyLinkColor)) ? $default_body_link_color : $ForumBodyLinkColor; ?>" alink="<?php echo (empty($ForumBodyALinkColor)) ? $default_body_alink_color : $ForumBodyALinkColor; ?>" vlink="<?php echo (empty($ForumBodyVLinkColor)) ? $default_body_vlink_color : $ForumBodyVLinkColor; ?>">
<div class="title"><h3><span class="heading"><?php echo $ForumName; ?></span></h3></div>
<div class="menu">
  <span class="menuitem"><a accesskey="1" href="/index.php">Home</a></span> |
  <span class="menuitem"><a accesskey="2" href="http://sourceforge.net/projects/xu4">Sourceforge project page</a></span> |
  <span class="menuitem"><a accesskey="3" href="/screenshots.html">Screenshots</a></span> |
  <span class="selectedmenuitem">Discussion Forum</span> |
  <span class="menuitem"><a accesskey="5" href="/download.php">Download</a></span> |
  <span class="menuitem"><a accesskey="6" href="/links.html">Links</a></span>
</div>
<div style="margin-left: 10%; margin-right: 10%;">
<a href="http://xu4.sourceforge.net/forumrss.php?num=1&mode=messages"><img src="/icon_xml2.gif" style="border-width: 0px"/></a>
</div>
<div style="margin-left: 10%; margin-right: 10%;">

