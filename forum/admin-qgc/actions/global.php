<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security(); ?>
<?php
  $DefaultDisplay=$new_DefaultDisplay;
  $DefaultEmail=$new_DefaultEmail;
  $ReplyLocation=$new_ReplyLocation;
  $PreviewPosts=$new_PreviewPosts;
  $PhorumMailCode=$new_PhorumMailCode;
  $UseCookies=$new_UseCookies;
  $SortForums=$new_SortForums;
  $default_lang=$new_default_lang;
  $TimezoneOffset=$new_default_timezone_offset;
  $ConfirmRegister=$new_ConfirmRegister;
  $ModEditProps=$new_ModEditProps;
  $VisCreateAcc=$new_VisCreateAcc;
  $UserModifyProf=$new_UserModifyProf;
  writefile();
  QueMessage("The Global properties have been updated.");
?>
