<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security(); ?>
<form action="<?php echo $myname; ?>" method="POST">
<input type="hidden" name="page" value="setup" />
<input type="hidden" name="action" value="global" />
  <table border="0" cellspacing="0" cellpadding="3" class="box-table">
    <tr>
      <td colspan="2" align="center" valign="middle" class="table-header">Global Settings</td>
    </tr>
    <tr>
      <th valign="middle">Default Messages Per Page:</th>
      <td valign="middle">
        <input type="Text" name="new_DefaultDisplay" value="<?php echo $DefaultDisplay; ?>" size="10" style="width: 200px;" class="TEXT" />
      </td>
    </tr>
    <tr>
      <th valign="middle">Default Email:</th>
      <td valign="middle">
        <input type="Text" name="new_DefaultEmail" value="<?php echo $DefaultEmail; ?>" size="10" style="width: 200px;" class="TEXT" />
      </td>
    </tr>
    <tr>
      <th valign="middle">Reply Form Location:</th>
      <td valign="middle">
        <select name="new_ReplyLocation" class=big>
          <option value="0" <?php if($ReplyLocation==0) echo "selected"; ?>>On Read Page</option>
          <option value="1" <?php if($ReplyLocation==1) echo "selected"; ?>>On Separate Page</option>
        </select>
      </td>
    </tr>
    <tr>
      <th valign="middle">PhorumMail Code:</th>
      <td valign="middle">
        <input type="Text" name="new_PhorumMailCode" value="<?php echo $PhorumMailCode; ?>" size="10" style="width: 200px;" class="TEXT" />
      </td>
    </tr>
    <tr>
      <th valign="middle">Cookies:</th>
      <td valign="middle">
        <select name="new_UseCookies" class=big>
          <option value="0" <?php if($UseCookies==0) echo "selected"; ?>>Do Not
          Use Cookies</option>
          <option value="1" <?php if($UseCookies==1) echo "selected"; ?>>Use Cookies</option>
        </select>
      </td>
    </tr>
    <tr>
      <th valign="middle">Confirm Registration via Email:</th>
      <td valign="middle">
        <select name="new_ConfirmRegister" class=big>
          <option value="0" <?php if($ConfirmRegister==0) echo "selected"; ?>>No</option>
          <option value="1" <?php if($ConfirmRegister==1) echo "selected"; ?>>Yes</option>
        </select>
      </td>
    </tr>
    <tr>
      <th valign="middle">Sorting:</th>
      <td valign="middle">
        <select name="new_SortForums" class=big>
          <option value="0" <?php if($SortForums==0) echo "selected"; ?>>Do Not
          Sort Forums</option>
          <option value="1" <?php if($SortForums==1) echo "selected"; ?>>Sort
          Forums</option>
        </select>
      </td>
    </tr>
    <tr>
      <th valign="middle">Default Language:</th>
      <td valign="middle">
        <select name="new_default_lang">
          <?php
$aryLangs = array();
$strLangDir = "lang/";
$dirCurrent = dir($strLangDir);
while($strFile=$dirCurrent->read()) {
  if (is_file($strLangDir.$strFile)) {
    $aryLangs[] = $strLangDir.$strFile;
  }
}
$dirCurrent->close();

if (count($aryLangs) > 1) { sort ($aryLangs); }

$file = current($aryLangs);
while ($file) {
  if($file!="$strLangDir"."blank.php"){
    $intStartLang = strpos($file, '/') + 1;
    $intLengthLang = strrpos($file, '.') - $intStartLang;
    $text=ucwords(substr($file,$intStartLang,$intLengthLang));
    echo "<option value=\"$file\"";
    if($file==$default_lang) echo ' selected';
    echo ">$text</option>\n";
  }
  $file = next($aryLangs);
}
?>
        </select>
      </td>
    </tr>
    <tr>
      <th valign="middle">TimeZone Offset (from server)</th>
      <td valign="middle">
        <select name="new_default_timezone_offset">
<?php
for($x=23;$x>-23;$x--){
echo '          <option value="'.$x.'"'.($x==$TimezoneOffset?' selected':'').'>'.$x."</option>\n";
}
?>
        </select>
      </td>
    </tr>
    <tr>
      <th valign="middle">Allow Moderators to edit Forum-Properties:</th>
      <td valign="middle">
        <select name="new_ModEditProps" class=big>
          <option value="1" <?php if($ModEditProps==1) echo "selected"; ?>>Yes, they are allowed to</option>
          <option value="0" <?php if($ModEditProps==0) echo "selected"; ?>>No, they are not allowed to</option>
        </select>
      </td>
    </tr>
    <tr>
      <th valign="middle">Allow Visitors to create Accounts:</th>
      <td valign="middle">
        <select name="new_VisCreateAcc" class=big>
          <option value="1" <?php if($VisCreateAcc==1) echo "selected"; ?>>Yes, they are allowed to</option>
          <option value="0" <?php if($VisCreateAcc==0) echo "selected"; ?>>No, they are not allowed to</option>
        </select>
      </td>
    </tr>
    <tr>
      <th valign="middle">Allow Users to modify their Profiles:</th>
      <td valign="middle">
        <select name="new_UserModifyProf" class=big>
          <option value="1" <?php if($UserModifyProf==1) echo "selected"; ?>>Yes, they are allowed to</option>
          <option value="0" <?php if($UserModifyProf==0) echo "selected"; ?>>No, they are not allowed to</option>
        </select>
      </td>
    </tr>
  </table>
<br />
<center><input type="Submit" name="submit" value="Update" class="BUTTON" /></center>
</form>
