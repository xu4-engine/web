<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security(); ?>
<form action="<?php echo $myname; ?>" method="POST">
<input type="hidden" name="page" value="managemenu" />
<input type="hidden" name="action" value="del" />
<input type="hidden" name="num" value="<?php echo $num; ?>" />
<input type="hidden" name="type" value="quick" />
<center>
<table border="0" cellspacing="0" cellpadding="3" class="box-table">
<tr>
<td align="center" valign="middle" class="table-header">Quick Delete: <?php echo $ForumName; ?></td>
</tr>
<tr>
<td align="center" valign="middle">Ids/Threads: <input type="Text" name="id" size="10" class="TEXT" /></td>
</tr>
</table>
<p><center><input type="Submit" name="submit" value="Delete" class="BUTTON" /></center>
</form>
