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
  $title = "Phorum Help";
  include phorum_get_file_name("header");

  //////////////////////////
  // START NAVIGATION     //
  //////////////////////////

    $menu=array();

    // Forum List
    if($ActiveForums>1)
      addnav($menu2, $lForumList, "$forum_page.$ext?f=$ForumParent$GetVars");

    // Go To Top
    addnav($menu1, $lGoToTop, "$list_page.$ext?f=$num$GetVars");

    // New Topic
    addnav($menu1, $lStartTopic, "$post_page.$ext?f=$num$GetVars");

    // Search
    addnav($menu1, $lSearch, "$search_page.$ext?f=$num$GetVars");

    // Log Out/Log In
    if($ForumSecurity){
      if(!empty($phorum_auth)){
        addnav($menu2, $lLogOut, "login.$ext?logout=1&f=$f$GetVars");
        addnav($menu2, $lMyProfile, "profile.$ext?f=$f&id=$phorum_user[id]$GetVars");
      }
      else{
        addnav($menu1, $lLogIn, "login.$ext?f=$f$GetVars");
      }
    }

    // $nav=getnav($menu);

    $TopLeftNav=getnav($menu1);

    $LowLeftNav=getnav($menu2);

  //////////////////////////
  // END NAVIGATION       //
  //////////////////////////

?>
<table width="<?php echo $ForumTableWidth; ?>" cellspacing="0" cellpadding="3" border="0">
<tr>
  <td nowrap="nowrap" <?php echo bgcolor($ForumNavColor); ?>><?php echo $TopLeftNav; ?></td>
</tr>
</table>
<table class="PhorumListTable" width="<?php echo $default_table_width; ?>" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="PhorumTableHeader" <?php echo bgcolor($default_table_header_color); ?> valign="TOP" nowrap="nowrap"><font color="<?php echo $default_table_header_font_color; ?>">&nbsp;Phorum Help</font></td>
  </tr>
  <tr>
    <td width="100%" align="LEFT" valign="MIDDLE" <?php echo bgcolor($default_table_body_color_2); ?>><font color="<?php echo $default_table_body_font_color_1; ?>">
<div style="font-weight: bold;">How do I link text or insert links into my messages?<br /><br /></div>You can link urls by enclosing them in &lt; &gt;.  To link text to a url, use this method: [url=http://phorum.org]phorum.org[/url]. To simply link the url itself, you can also use [url]http://phorum.org[/url].  To insert an email link, use [email]email@address.foo[/email].<br /><br />
<div style="font-weight: bold;">Can I insert images into my messages?<br /><br /></div>You can insert images by enclosing the url to the image in [img] and [/img].<br /><br />
<div style="font-weight: bold;">Can I format my message with bolding, italics, and other decoration?<br /><br /></div>You can use the special [ ] codes in your message to apply different style to your message.  For example to bold something, you would enter [b]bold text[/b].  Other styles include [i]italics[/i], [u]underline[/u], [center]center[/center] and [quote]quoting[/quote].<br /><br />
<div style="font-weight: bold;">How do I build a forum like yours?<br /><br /></div>To build ours, we started with <a href="http://phorum.org/">Phorum</a> and built on it to make what you see today.<br /><br />
</font></td>
  </tr>
</table>
<?php

  include phorum_get_file_name("footer");
?>