<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security(); ?>
<?php /* Main Menu */ ?>
<center>
<table border="0" cellspacing="8" cellpadding="0">
<tr>
  <td valign="top">
    <table border="0" cellspacing="0" cellpadding="5" class="box-table">
    <tr>
    <td align="center" valign="middle" class="table-header">Phorum Setup</td>
    </tr>
    <tr>
    <td align="left" valign="middle" nowrap="nowrap">

    <a href="<?php echo $myname; ?>?page=attachments">Attachment Settings</a><br />
    <a href="<?php echo $myname; ?>?page=db">Database Settings</a><br />
    <a href="<?php echo $myname; ?>?page=files">Files/Paths</a><br />
    <a href="<?php echo $myname; ?>?page=html">HTML Settings</a><br />
    <a href="<?php echo $myname; ?>?page=global">Global Options</a><br />
    <a href="<?php echo $myname; ?>?page=plugin">Plugins</a><br />
    </td>
    </tr>
    </table>
  </td>
  <td valign="top">
    <table border="0" cellspacing="0" cellpadding="5" class="box-table">
    <tr>
    <td align="center" valign="middle" class="table-header">Forum Maintenance</td>
    </tr>
    <tr>
    <td align="left" valign="middle" nowrap="nowrap">

    <a href="<?php echo $myname; ?>?page=manage">Manage Forums/Folders</a><br />
    <a href="<?php echo $myname; ?>?page=newfolder">New Folder</a><br />
    <a href="<?php echo $myname; ?>?page=newforum">New Forum</a><br />
    <?php
    if(defined("PHORUM_LOGIN")) {
    ?>
    <br />
    <a href="<?php echo $myname; ?>?page=useradmin">UserAdmin</a><br />
    <a href="<?php echo $myname; ?>?page=groups">GroupAdmin</a><br />
    <?php
    }
    ?>
    </td>
    </tr>
    </table>
  </td>
  <td valign="top">
    <table border="0" cellspacing="0" cellpadding="5" class="box-table">
    <tr>
    <td align="center" valign="middle" class="table-header">System Maintenance</td>
    </tr>
    <tr>
    <td align="left" valign="middle" nowrap="nowrap">
<?php /* i dont think we need that anymore, because there might be more than 1 admin
    <a href="<?php echo $myname; ?>?page=pass">Change Password</a><br />
*/ ?>
    <a href="<?php echo $myname; ?>?page=stats">Forum Statistics</a><br />
    <a href="<?php echo $myname; ?>?action=version">Check For New Version</a><br />
    <a href="<?php echo $myname; ?>?action=build">Rebuild INF File</a><br />
    <a href="<?php echo $myname; ?>?action=orphan">Purge Orphan Attachments</a><br />
    <?php if($DB->type!="mysql"){ ?>
      <a href="<?php echo $myname; ?>?action=seq">Reset Main Sequence</a><br />
    <?php
      }
      if(!$PHORUM["started"]){
        ?><a href="<?php echo $myname; ?>?action=start">Start Phorum</a><br />
        <?php
      }
      else{
        ?><a href="<?php echo $myname; ?>?action=stop">Stop Phorum</a><br />
        <?php
      }
    ?>
    </td>
    </tr>
    </table>
  </td>
</tr>
</table>
</center>
