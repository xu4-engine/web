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

  settype($Error, "string");

  //Thats for all those ppl who likes to use different colors in different forums
  if($f>0){
    $table_width=$ForumTableWidth;
    $table_header_color=$ForumTableHeaderColor;
    $table_header_font_color=$ForumTableHeaderFontColor;
    $table_body_color_1=$ForumTableBodyColor1;
    $table_body_font_color_1=$ForumTableBodyFontColor1;
    $nav_color=$ForumNavColor;
  }
  else{
    $table_width=$default_table_width;
    $table_header_color=$default_table_header_color;
    $table_header_font_color=$default_table_header_font_color;
    $table_body_color_1=$default_table_body_color_1;
    $table_body_font_color_1=$default_table_body_font_color_1;
    $nav_color=$default_nav_color;
  }


  if(empty($target) || !preg_match("|^$forum_url|", $target)){
    if(isset($HTTP_REFERER)){
      $target=$HTTP_REFERER;
    }
    else{
      $target="$forum_url/$forum_page.$ext";
    }
  }

  if(isset($logout)){
    phorum_logout();
    header("Location: $target");
    exit();
  }

  if(empty($forgotpass) && !empty($username) && !empty($password)){

    /* AT - changed $username to $HTTP_POST_VARS['username'] to avoid cookie conflicts with sourceforge.net */
    $uid=phorum_check_login($HTTP_POST_VARS['username'], $password);
    
    if($uid) {
      $sess_id=phorum_session_id($HTTP_POST_VARS['username'], $HTTP_POST_VARS["password"]);
      phorum_login_user($sess_id);
      
      if($PhorumLoginErr=='NewPass') {
        $target="$forum_url/profile.$ext?f=$num&id=$uid&EditError=NewPass$GetVars";
      } elseif (!strstr($target, "?")){
        $target.="?f=0$GetVars";
      } else {
        $target.="$GetVars";
      }
      header("Location: $target");
      exit();
    } else {
        $Error=$lLoginError;
    }
  } elseif (!empty($forgotpass)) {
    phorum_forgot_pass($lookup);
  }

  if(basename($PHP_SELF)=="login.$ext"){
    $title = " - $lLoginCaption";
    include phorum_get_file_name("header");
  }

  //////////////////////////
  // START NAVIGATION     //
  //////////////////////////

    $menu="";
    if($ActiveForums>1){
      addnav($menu, $lForumList, "$forum_page.$ext?f=0$GetVars");
    }
    if ($VisCreateAcc)
      addnav($menu, $lRegisterLink, "register.$ext?f=$f&target=$target$GetVars");
    $nav=getnav($menu);

  //////////////////////////
  // END NAVIGATION       //
  //////////////////////////


  if($Error){
    echo "<p><strong>$Error</strong>";
  }
?>
<div>
<form action="<?php echo "login.$ext"; ?>" method="post">
<input type="hidden" name="f" value="<?php echo $f; ?>" />
<input type="hidden" name="target" value="<?php echo $target; ?>" />
<?php echo $PostVars; ?>
<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td <?php echo bgcolor($nav_color); ?>>
      <table cellspacing="0" cellpadding="2" border="0">
        <tr>
          <td><?php echo $nav; ?></td>
        </tr>
      </table>
    </td>
</tr>
<tr>
    <td <?php echo bgcolor($nav_color); ?>>
        <table class="PhorumListTable" cellspacing="0" cellpadding="2" border="0">
        <tr>
            <td class="PhorumTableHeader" height="21" colspan="2" <?php echo bgcolor($table_header_color); ?>><FONT color="<?php echo $table_header_font_color; ?>">&nbsp;<?php echo $lLoginCaption; ?></font></td>
        </tr>
        <tr>
            <td <?php echo bgcolor($table_body_color_1); ?> nowrap="nowrap"><font color="<?php echo $table_body_font_color_1; ?>">&nbsp;<?php echo $lUserName;?>:</font></td>
            <td <?php echo bgcolor($table_body_color_1); ?>><input type="Text" name="username" size="30" maxlength="50" /></td>
        </tr>
        <tr>
            <td <?php echo bgcolor($table_body_color_1); ?> nowrap="nowrap"><font color="<?php echo $table_body_font_color_1; ?>">&nbsp;<?php echo $lPassword;?>:</font></td>
            <td <?php echo bgcolor($table_body_color_1); ?>><input type="Password" name="password" size="30" maxlength="20" /></td>
        </tr>
        <tr>
            <td <?php echo bgcolor($table_body_color_1); ?> nowrap="nowrap"><font color="<?php echo $table_body_font_color_1; ?>">&nbsp;<?php echo $lRememberLogin;?>:</font></td>
            <td <?php echo bgcolor($table_body_color_1); ?>><input type="checkbox" value="1" name="remember_login" checked="" /></td>
        </tr>	
        <tr>
            <td <?php echo bgcolor($table_body_color_1); ?> nowrap="nowrap">&nbsp;</td>
            <td <?php echo bgcolor($table_body_color_1); ?>><input type="submit" value="<?php echo $lLogin; ?>" />&nbsp;<br /><img src="images/trans.gif" width=3 height=3 border=0></td>
        </tr>
        </table>
    </td>
</tr>
</table>
</form>

<form action="<?php echo "login.$ext"; ?>" method="post">
<input type="hidden" name="f" value="<?php echo $f; ?>" />
<input type="hidden" name="target" value="<?php echo $target; ?>" />
<input type="hidden" name="forgotpass" value="1" />
<?php echo $PostVars; ?>
<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td <?php echo bgcolor($nav_color); ?>>

        <table class="PhorumListTable" width="400" cellspacing="0" cellpadding="2" border="0">
        <tr>
            <td class="PhorumTableHeader" height="21" <?php echo bgcolor($table_header_color); ?>><FONT color="<?php echo $table_header_font_color; ?>">&nbsp;<?php echo $lForgotPass; ?></font></td>
        </tr>
        <tr>
            <td <?php echo bgcolor($table_body_color_1); ?>><font color="<?php echo $table_body_font_color_1; ?>"><?php echo $lLostPassExplain; ?></font></td>
        </tr>
        <tr>
            <td align="center" <?php echo bgcolor($table_body_color_1); ?>><input type="Text" name="lookup" size="30" maxlength="50"> <input type="submit" value="<?php echo $lSubmit; ?>" /></td>
        </tr>
        </table>
    </td>
</tr>
</table>
</form>
</div>
<?php
  if(basename($PHP_SELF)=="login.$ext"){
    include phorum_get_file_name("footer");
  }
?>
