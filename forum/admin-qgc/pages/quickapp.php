<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security(); ?>
<form action="<?php echo $myname; ?>" method="POST">
<input type="hidden" name="page" value="managemenu" />
<input type="hidden" name="action" value="moderate" />
<input type="hidden" name="num" value="<?php echo $num; ?>">
<center>
<table border="0" cellspacing="0" cellpadding="3" class="box-table">
<tr>
    <td align="center" valign="middle" class="table-header">Quick Approve: <?php echo $ForumName; ?></td>
</tr>
<tr>
    <td align="center" valign="middle">Approve <input CHECKED TYPE="RADIO" name="approved" VALUE="N" /> &nbsp; | &nbsp; Disable <input TYPE="RADIO" name="approved" VALUE="Y" /></td>
</tr>
    <td align="center" valign="middle">Ids/Threads: <input type="Text" name="id" size="10" class="TEXT" /></td>
</tr>
</table>
<p><center><input type="Submit" name="submit" value="Update" class="BUTTON" /></center>
</form>
