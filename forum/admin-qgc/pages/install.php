<?php if(!defined("PHORUM_ADMIN")) return;

if(!empty($forum_url) || $PHORUM["started"]) exit();

$myname = $PHP_SELF;
settype($upgrade, "integer");

if(isset($HTTP_POST_VARS)){
    reset($HTTP_POST_VARS);
    while(list($var, $val) = each($HTTP_POST_VARS)){
        $$var = $val;
    }
}
if((isset($HTTP_POST_VARS["language_file"])) && (file_exists("$admindir/" . $HTTP_POST_VARS["language_file"]))){
    include($admindir . "/" . $HTTP_POST_VARS["language_file"]);
}

switch($step){
    case 1:
        $message = $lStep1_end;
        $message .= $lAdmin_Perm_Check;
        $message .= check_fileperms("$settings_dir/forums.php", "forums.php");
        $message .= check_fileperms("$settings_dir/", "settings dir");
        break;
    case 2:
        $message = $lStep2_end;
        $PHORUM['dbtype'] = $dbType;
        writefile();
        break;
    case 3:
        if(!file_exists("./db/$dbType.php")){
            $err = $lErrorFile;
        }else{
            if(!$DB->open($dbName, implode(':', explode(':', $dbServer)), $dbUser, $dbPass)){
                $err = $lErrorDB;
            }else{
                $PHORUM['main_table'] = $mainTable;
                $PHORUM['dbtype'] = $dbType;
                $PHORUM['DatabaseServer'] = $dbServer;
                $PHORUM['DatabaseName'] = $dbName;
                $PHORUM['DatabaseUser'] = $dbUser;
                $PHORUM['DatabasePassword'] = $dbPass;
                writefile();
                $message = $lDB_Ok;
                if($upgrade){
                    $message .= $lDB_Upgrade;
                    $pho_main = $PHORUM['main_table'];
                    $PHORUM['auth_table'] = $PHORUM['main_table'] . "_auth";
                    if($dbType == "mysql"){
                        include("$admindir/upgrade.php");
                    }else{
                        include("$admindir/upgrade_pg.php");
                    }
                    writefile("all");
                }else{
                    $message .= $lDB_Create;
                    if(($errors = create_table($DB, "forums", $PHORUM["main_table"])) || ($errors = create_table($DB, "auth", $PHORUM["main_table"])) || ($errors = create_table($DB, "moderators", $PHORUM["main_table"])) || ($errors = create_table($DB, "groups", $PHORUM["main_table"])) || ($errors = create_table($DB, "user2group", $PHORUM["main_table"])) || ($errors = create_table($DB, "forum2group", $PHORUM["main_table"]))){
                        $err = $lErrorTables;
                    }else{
                        $message .= $lDB_Create_done;
                    }
                }
            }
            $message .= $lStep3_end;
        }
        break;
    case 4:
        if(empty($AdminUser) || empty($AdminPass)){
            $err = $lErrorFields;
        }elseif($AdminPass != $AdminPass2){
            $err = $lErrorPass;
        }else{
            $crypt_pass = md5($AdminPass);
            $SQL = "SELECT id, password,email FROM $PHORUM[auth_table] WHERE username='$AdminUser'";
            $q->query($DB, $SQL);
            $rec = $q->getrow();
            $DefaultEmail = $rec['email'];
            if($rec['password'] == $crypt_pass){
                $message = $lUserExists;
                $admin_id = $rec['id'];
                $SQL = "SELECT forum_id FROM $PHORUM[mod_table] WHERE user_id=" . $admin_id;
                $q->query($DB, $SQL);
                $isadmin = true;
                if($rec = $q->getrow()){
                    $message .= $lUserIsAdmin;
                }else{
                    $SQL = "Insert into $PHORUM[mod_table] (user_id, forum_id) values (" . $admin_id . ", 0)";
                    if($q->query($DB, $SQL))
                        $message .= $lUserAdmin;
                }
            }elseif(is_array($rec) && $rec['password'] != $crypt_pass){
                $err = $lErrorWrongPass;
            }else{
                $id = $DB->nextid($PHORUM["auth_table"]);
                $SQL = "Insert into $PHORUM[auth_table] (id, username, password) values ($id, '$AdminUser', '$crypt_pass')";
                $isadmin = false;
                if($q->query($DB, $SQL)){
                    if($DB->type == "mysql") $id = $DB->lastid();
                    $SQL = "Insert into $PHORUM[mod_table] (user_id, forum_id) values ($id, 0)";
                    if($q->query($DB, $SQL)){
                        $message = $lAdminCreated;
                    }else{
                        $err = $lErrorDBAdmin . $q->error();
                    }
                }
            }
            $message .= $lStep4_end;
        }

        break;
    case 5:
        $url = parse_url($PhorumURL);
        if($isadmin){
            if(!is_array($url)){
                $err = $lErrorURL; 
                // Needs Fixage
                // } elseif(!is_email($DefaultEmail)) {
                // $err=$lErrorEmail;
            }else{
                $forum_url = $PhorumURL;
                if(substr($forum_url, -1) == "/") $forum_url = substr($forum_url, 0, -1);
                $AdminEmail = strtolower($DefaultEmail);
                writefile();
            }
        }else{
            if(empty($AdminName)) $err = $lErrorName;
        }

        if(!is_array($url)){
            $err = $lErrorURL; 
            // Needs Fixage
            // }elseif(!is_email($AdminEmail)){
            // $err=$lErrorEmail;
        }else{
            $forum_url = $PhorumURL;
            if(substr($forum_url, -1) == "/") $forum_url = substr($forum_url, 0, -1);
            $AdminEmail = strtolower($AdminEmail);
            $DefaultEmail = $AdminEmail;
            $PHORUM["started"]=1;
            writefile();
            phorum_del_caches();
            $SQL = "update $PHORUM[auth_table] set email='$AdminEmail',name='$AdminName' where id=$id";
            $q->query($DB, $SQL);
            $message = $lFINAL;
        }
        break;
}

if(isset($help) && isset($lang)){
    include $admindir . "/" . $lang;
    $title = $lHelpTitle;
}elseif(isset($help) && !isset($lang)){
    $title = "Phorum Installation: Help";
}elseif(!isset($help) && isset($language_file)){
    $title = $lTitle;
}else{
    $title = "Phorum Installation";
}

?>
<html>
<head>
<title><?php echo $title;
?></title>
<?php if(!strstr($HTTP_USER_AGENT, "Mozilla/4") || strstr($HTTP_USER_AGENT, "MSIE")){
    ?>
<style>
    body
    {
        color: #000000;
        background: #e1e1e1;
        font-size: 13px;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        padding:5px;
        margin:0px;
    }

    th
    {
        font-size: 13px;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-weight: bold;
        text-align: left;
        background-color: #F0F0F0;
        border-collapse: collapse;
        border-bottom-width : 1px;
        border-top-width : 0px;
        border-left-width : 0px;
        border-right-width : 0px;
        border-style : solid;
        border-color : Gray;
    }

    td
    {
        font-size: 13px;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        text-align: left;
    }

    a
    {
        font-weight:bold;
        color:Blue;
        text-decoration:none;
        outline:none;
    }

    p
    {
        padding: 0px 0px 0px 0px;
        margin: 0px 0px 10px 0px;
    }

    img
    {
        border:none;
    }

    input, select
    {
        font-size : 13px;
        font-family: Verdana, Arial, Helvetica, sans-serif;
    }

    input.submit
    {
        border-width : 1px;
        border-style : solid;
        border-color : Gray;
        font-family : "Lucida Sans","Lucida Grande",Arial;
        font-size : 11px;
    }

    table.box-table
    {
        border-width : 1px;
        border-style : solid;
        border-color : Gray;
        background-color: White;
        border-collapse: collapse;
    }
    table.error
    {
        border-width : 1px;
        border-style : solid;
        border-color : Red;
        background-color: #DD0000;
        border-collapse: collapse;
    }


    table.box-table td
    {
        border-collapse: collapse;
        border-bottom-width : 1px;
        border-top-width : 0px;
        border-left-width : 0px;
        border-right-width : 0px;
        border-style : solid;
        border-color : Gray;
    }

    td.table-header
    {
        font-size: 13px;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-weight: bold;
        background-color: Navy;
        color: White;
        text-align: center;
    }

    td.table-header a
    {
        color: White;
    }

    .nav
    {
        font-size: 11px;
    }

    #message
    {
        width: 300px;
        border-width: 1px;
        border-style: solid;
        padding: 3px;
        background-color: White;
    }

    #title
    {
        font-size: 14px;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-weight: bold;
        display: inline;
    }
    .check_ok
    {
    font-size: 13px;
    font-family: Courier;
    color: #04CC09;
    font-weight: bold;
     }
    .check_bad
    {
    font-size: 13px;
    font-family: Courier;
    color: red;
    font-weight: bold;
     }
     .error
     {
    font-size: 12px;
    font-family: Courier;
    color: #DD0000;
    font-weight: bold;
     }
</style>
<?php }
?>
<script type="text/javascript">
function openwindow(page)
{
OpenWin = this.open(page,"my_new_window","toolbar=no,location=not,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width=550,height=160")
}
</script>
</head>
<body>
<?php
if($help){
    // Some one time lang vars: not in lang file, because it havent been selected yet.
    if(!isset($lang)){
        $lCloseWindow = "Close me.";
        $lStep1_help = "First off, welcome to Phourm Installation Script.  I will try guiding a novice Phorum Admin through installation. <br \> All you need to on the 1st step is to choose the language of Installation, and my language as well.";
    }else{
        include $admindir . "/" . $lang;
    }
    echo $HTTP_POST_VARS["language_file"];
    $lang = $$help;

    ?>
<table border="0" cellspacing="0" cellpadding="2" class="box-table" width='520' align="center">
    <tr>
     <td align="left" valign="middle"><?php echo $lang;
    ?></td>
    </tr>
    <tr>
    <tr>
     <td valign="middle"><div align='center'><a href="javascript:window.close();"><?php echo $lCloseWindow;
    ?></a></div></td>
    </tr>
    <tr>
</table>
</body>
</html>
<?php
    exit();
}

if($err){
    $message = "<font class=\"error\">Error: $err</font>";
    $step--;
}

?>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td width="50%" class="nav" valign="top"></td>
    <td width="50%" align="right" valign="top" class="nav" style="text-align: right;"><div id="title">PHORUM INSTALLATION</div><br />

</td>
</tr>
</table>
<center>
<?php
switch($step){
    case 0:
        ?>
<form action="<?php echo $myname;
        ?>" method="POST">
<input type="hidden" name="page" value="install" />
<input type="hidden" name="step" value="1" />
<table border="0" cellspacing="8" cellpadding="0">
<tr>
  <td valign="top">
    <table border="0" cellspacing="0" cellpadding="2" class="box-table">
    <tr>
     <td align="center" valign="middle" class="table-header" colspan=2>Step 1</td>
    </tr>
    <tr>
     <th>Select Installation-Language:  </th>
     <td><select name="language_file">
<?php
        $oldir = getcwd();
        chdir("$admindir");
        $aryLangs = array();
        $strLangDir = "lang/";
        $dirCurrent = dir($strLangDir);
        while($strFile = $dirCurrent->read()){
            if (is_file($strLangDir . $strFile)){
                $aryLangs[] = $strLangDir . $strFile;
            }
        }
        $dirCurrent->close();

        if (count($aryLangs) > 1){
            sort ($aryLangs);
        }

        $file = current($aryLangs);
        while ($file){
            if($file != "$strLangDir" . "blank.php"){
                $intStartLang = strpos($file, '/') + 1;
                $intLengthLang = strrpos($file, '.') - $intStartLang;
                $text = ucwords(substr($file, $intStartLang, $intLengthLang));
                echo "<option value=\"$file\">$text</option>\n";
            }
            $file = next($aryLangs);
            chdir($oldir);
        }

        ?></select></td>
    </tr>
    <tr>
     <td><a href="javascript:openwindow('<?php echo $PHP_SELF;
        ?>?help=lStep1_help')">[help]</a></td>
     <td><div align=right><input class="submit" type="submit" value="-Next Step-" /></div></td>
    </tr>
   </table>
  </td>
</tr>
</table>
</form>
<?php
        break;
    case 1:
        ?>
<table border="0" cellspacing="8" cellpadding="0">
<tr>
  <td valign="top">
    <table border="0" cellspacing="0" cellpadding="2" class="box-table">
    <tr>
     <td align="center" valign="middle" colspan=2><?php echo $message;
        ?>
</td>
</tr>
<form action="<?php echo $myname;
        ?>" method="POST">
<input type="hidden" name="page" value="install" />
<input type="hidden" name="step" value="2" />
<input type="hidden" name="language_file" value="<?php echo $language_file?>" />
    <tr>
     <td align="center" valign="middle" class="table-header" colspan=2><?php echo $lStep2;
        ?></td>
    </tr>
    <tr>
     <th><?php echo $lDBType;
        ?></th>
     <td>    <select name="dbType">
     <?php
        while(list($key, $var) = each($dbtypes)){
            print "<option value='$key'";
            if($key == $PHORUM['dbtype']){
                print " selected";
            }
            print ">$var\n";
        }

        ?>
   </select></td>
    </tr>
    <tr>
     <td><a href="javascript:openwindow('<?php echo $myname;
        ?>?help=lStep2_help&lang=<?php echo $language_file;
        ?>')">[<?php echo $lHelp;
        ?>]</a></td>
     <td><div align=right><input class="submit" type="submit" value="<?php echo $lNextStep;
        ?>" /></div></td>
    </tr>
   </table>
  </td>
</tr>
</table>
</form>
<?php
        break;
    case 2:
        ?>
<table border="0" cellspacing="8" cellpadding="0">
<tr>
  <td valign="top">
    <table border="0" cellspacing="0" cellpadding="2" class="box-table">
    <tr>
     <td align="center" valign="middle" colspan=2><?php echo $message;
        ?>
</td>
</tr>
<form action="<?php echo $myname;
        ?>" method="POST">
<input type="hidden" name="page" value="install" />
<input type="hidden" name="step" value="3" />
<input type="hidden" name="language_file" value="<?php echo $language_file;
        ?>" />
<input type="hidden" name="dbType" value="<?php echo $dbType;
        ?>" />
<tr>
  <td align="center" valign="middle" class="table-header" colspan=2><?php echo $lStep3;
        ?></td>
</tr>
<tr>
  <th align="left" valign="middle"><?php echo $lDBServer;
        ?></th>
  <td valign="middle"><input type="Text" name="dbServer" value="<?php echo $dbServer;
        ?>" size="20" /></td>
</tr>
<tr>
  <th align="left" valign="middle"><?php echo $lDBName;
        ?></th>
  <td valign="middle"><input type="Text" name="dbName" value="<?php echo $dbName;
        ?>" size="20" /></td>
</tr>
<tr>
  <th align="left" valign="middle"><?php echo $lDBUser;
        ?></th>
  <td valign="middle"><input type="Text" name="dbUser" value="<?php echo $dbUser;
        ?>" size="20" /></td>
</tr>
<tr>
  <th align="left" valign="middle"><?php echo $lDBPass;
        ?></th>
  <td valign="middle"><input type="Text" name="dbPass" value="<?php echo $dbPass;
        ?>" size="20" /></td>
</tr>
<tr>
  <th align="left" valign="middle"><?php echo $lPhorumTable;
        ?></th>
  <td valign="middle"><input type="Text" name="mainTable" value="<?php echo $PHORUM['main_table'];
        ?>" size="20" /></td>
</tr>
<tr>
  <th align="left" valign="middle">&nbsp;</th>
  <td valign="middle"><input type="checkbox" name="upgrade" value="1" /> <?php echo $lUpdate;
        ?></td>
</tr>
<tr>
  <th align="left" valign="middle">&nbsp;</th>
  <td valign="middle"><input type="text" name="AttachmentDir" /> <?php echo $lAttachmentDir;
        ?></td>
</tr>

    <tr>
     <td><a href="javascript:openwindow('<?php echo $myname;
        ?>?help=lStep3_help&lang=<?php echo $language_file;
        ?>')">[<?php echo $lHelp;
        ?>]</a></td>
     <td align="right"><div align=right><input class="submit" type="submit" value="<?php echo $lNextStep;
        ?>" /></div></td>
    </tr>
   </table>
  </td>
</tr>
</table>
</form>
<br />
<br />
<strong><?php echo $lDBNote;
        ?></strong>
<?php
        break;
    case 3:
        ?>
<table border="0" cellspacing="8" cellpadding="0">
<tr>
  <td valign="top">
    <table border="0" cellspacing="0" cellpadding="2" class="box-table">
    <tr>
     <td align="center" valign="middle" colspan=2><?php echo $message;
        ?>
</td>
</tr>
<form action="<?php echo $myname;
        ?>" method="POST">
<input type="hidden" name="page" value="install" />
<input type="hidden" name="step" value="4" />
<input type="hidden" name="language_file" value="<?php echo $language_file;
        ?>" />
<tr>
  <td align="center" valign="middle" class="table-header" colspan=2><?php echo $lStep4;
        ?></td>
</tr>
<tr>
  <th align="left" valign="middle"><?php echo $lAdminUser;
        ?></th>
  <td valign="middle"><input type="text" name="AdminUser" value="<?php echo $AdminUser;
        ?>" maxlength="50" size="20" /></td>
</tr>
<tr>
  <th align="left" valign="middle"><?php echo $lAdminPass;
        ?></th>
  <td valign="middle"><input type="password" name="AdminPass" maxlength="50" size="20" /></td>
</tr>
<tr>
  <th valign="middle" align="right"><?php echo $lAdminPass2;
        ?></th>
  <td valign="middle"><input type="password" name="AdminPass2" maxlength="50" size="20" /></td>
</tr>
<tr>
 <td><a href="javascript:openwindow('<?php echo $myname;
        ?>?help=lStep4_help&lang=<?php echo $language_file;
        ?>')">[<?php echo $lHelp;
        ?>]</a></td>
 <td align="right"><div align=right><input class="submit" type="submit" value="<?php echo $lNextStep;
        ?>" /></div></td>
</tr>
</table>
</td>
</tr>
</table>
</form>
<?php
        break;
    case 4:
        if(empty($PhorumURL)) $PhorumURL = "http://$HTTP_HOST" . dirname(dirname($PHP_SELF));

        ?>
<table border="0" cellspacing="8" cellpadding="0">
<tr>
  <td valign="top">
    <table border="0" cellspacing="0" cellpadding="2" class="box-table">
    <tr>
     <td align="center" valign="middle" colspan=2><?php echo $message;
        ?>
</td>
</tr>
<form action="<?php echo $myname;
        ?>" method="POST">
<input type="hidden" name="page" value="install" />
<input type="hidden" name="step" value="5" />
<input type="hidden" name="isadmin" value="<?php echo $isadmin;
        ?>" />
<input type="hidden" name="id" value="<?php echo $id;
        ?>" />
<input type="hidden" name="language_file" value="<?php echo $language_file;
        ?>" />
<tr>
  <td align="center" valign="middle" class="table-header" colspan=2><?php echo $lStep5;
        ?></td>
</tr>
<tr>
  <th align="left" valign="middle"><?php echo $lPhorum_URL;
        ?></th>
  <td valign="middle"><input type="text" name="PhorumURL" value="<?php echo $PhorumURL;
        ?>" size="40" /></td>
</tr>
<?php if($isadmin == false){
            ?>
<tr>
  <th align="left" valign="middle"><?php echo $lAdminEmail;
            ?></th>
  <td valign="middle"><input type="text" name="AdminEmail" value="<?php echo $AdminEmail;
            ?>" size="20" /></td>
</tr>
<tr>
  <th align="left" valign="middle"><?php echo $lAdminName;
            ?></th>
  <td valign="middle"><input type="text" name="AdminName" value="<?php echo $AdminName;
            ?>" size="20" /></td>
</tr>
<?php }else{
            ?>
<tr>
  <th align="left" valign="middle"><?php echo $lDefaultEmail;
            ?></th>
  <td valign="middle"><input type="text" name="DefaultEmail" value="<?php echo $DefaultEmail;
            ?>" size="20" /></td>
</tr>
<?php }
        ?>
<tr>
 <td><a href="javascript:openwindow('<?php echo $myname;
        ?>?help=lStep5_help&lang=<?php echo $language_file;
        ?>')">[<?php echo $lHelp;
        ?>]</a></td>
 <td align="right"><div align=right><input class="submit" type="submit" value="<?php echo $lFinish;
        ?>" /></div></td>
</tr>
</table>
</td>
</tr>
</table>
</form>
<?php
        break;
    case 5:
        ?>
<table border="0" cellspacing="8" cellpadding="0">
<tr>
  <td valign="top">
    <table border="0" cellspacing="0" cellpadding="2" class="box-table">
    <tr>
     <td align="center" valign="middle" colspan=2><?php echo $message;
        ?>
</td>
</tr>
</table>
</td>
</tr>
</table>
</form>
<?php
        break;
}

?>
</center>
</body>
</html>
