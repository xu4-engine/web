<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security(); ?>
<form action="<?php echo $myname; ?>" method="POST">
<input type="hidden" name="page" value="managemenu" />
<input type="hidden" name="action" value="authdel" />
<input type="hidden" name="num" value="<?php echo $num; ?>" />
<input type="hidden" name="frompage" value="authdel" />
<center>
<table border="0" cellspacing="0" cellpadding="3" class="box-table">
<tr>
<td colspan=2 align="center" valign="middle" class="table-header">Author Delete: <?php echo $ForumName; ?></td>
</tr>
<tr>
<td valign="middle">Where Author: </td>
<td valign="middle"><select name="authopt" class=big>
  <option value="=" <?php if($moderation=='=') echo 'selected'; ?>>Is Equal To</option>
  <option value="=" <?php if($moderation=='~') echo 'selected'; ?>>Contains</option>
</select></td>
</tr>
<tr>
<td valign="middle">Match:</td>
<td valign="middle"><input type="Text" name="match" size="10" class="TEXT" /></td>
</tr>
</table>
<p><center><input type="Submit" name="submit" value="Delete" class="BUTTON" /></center>
</form>
