<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php /* move thread */

   $sSQL="Select id, name from $pho_main where folder=0 and table_name!='$ForumTableName' order by name";

   $q->query($DB, $sSQL);

   $rec=$q->getrow();

   if($q->numrows())

   {

?>

<form action="<?php echo $myname; ?>" method="post">

<center>

<table border="0" cellspacing="0" cellpadding="3" class="box-table">

<tr>

<td align="center" valign="middle" class="table-header">Move Thread</td>

</tr>

<tr>

<td align="center" valign="middle">Target forum: <select name="targetf" id="targetf">

<?php

      while(is_array($rec))

      {

         echo "<option value=\"".$rec["id"]."\">".$rec["name"]."</option>\n";

         $rec=$q->getrow();

      }

?>

   </select>

   </td>

</tr>

</table>

   <p><center><input type="submit" value="Move" /></center>

   <input type="hidden" name="page" value="easyadmin" />

   <input type="hidden" name="num" value="<?php echo $num;?>" />

   <input type="hidden" name="t" value="<?php echo $t;?>" />

   <input type="hidden" name="action" value="move" />

<?php

if (isset($navigate)) { ?>

<input type="hidden" name="navigate" value="<?php echo $navigate; ?>" />

<?php

}

if (isset($mythread)) { ?>

<input type="hidden" name="mythread" value="<?php echo $mythread; ?>" />

<?php

}

?>

   </form>

<?php

   }

   else echo "There's only one forum available, you cannot move threads!";

?>

