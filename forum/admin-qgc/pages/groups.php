<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security(); ?>
<?php
if($subaction == "") {
  $pagelength=50;
  $SQL="select id,name from ".$PHORUM["group_table"];
  if($where){
    $SQL.=" where name like '%$where%'";
  }
  $SQL.=" order by name";

  $q->query($DB, $SQL);

  $groups_found=$q->numrows();

  $maxpages=$groups_found/$pagelength;
  if($maxpages > intval($maxpages))
    $maxpages=intval(++$maxpages);

?>
<script language="JavaScript" type="text/javascript">

function delgroup(url){

  ans=window.confirm("You are about to delete this group.  Do you want to continue?");
  if(ans){
    window.location.replace(url);
  }
}
</script>
<?php
    $i=0;
  $j=0;
 if(!$st)
   $st=0;
    while($row=$q->getrow()) {
      $i++;
      if(($i-1)<$st)
    continue;
      if(($i-1)>=($st+$pagelength))
    break;
      $users[$j]=$row;
      $j++;
    }

    $SQL="select count(*) as count from ".$PHORUM["group_table"];
    $q->query($DB, $SQL);
    $row=$q->getrow();
    $total_groups=$row['count'];
    if($st==0) {
      $backlink="&nbsp;";
    } else {
      $backlink="<a href=\"$myname?page=groups&where=$where&st=".($st-$pagelength)."\">back</a>";
    }
    if(($st+$pagelength)>=$total_groups) {
      $forwardlink="&nbsp;";
    } else {
      $forwardlink="<a href=\"$myname?page=groups&where=$where&st=".($st+$pagelength)."\">forward</a>";
    }

    $page=$st/$pagelength+1;
?>
<br />
<table width="600" border="0" cellspacing="0" cellpadding="3">
<tr>
    <td nowrap width="50%" align="left" valign="middle"><a href="<?php echo $myname."?page=groups&subaction=addgroup&st=$st";?>">Add Group</a><br />showing <?php echo $groups_found; ?> of <?php echo $total_groups; ?> total groups</td>
    <td nowrap width="50%" align="right" valign="middle"><form style="align: right; display: inline;" action="<?php echo $myname; ?>" method="get"><input type="hidden" name="page" value="groups" /><input type="text" name="where" size="20" value="<?=$where?>" /><input type="submit" value="Search" /></form></td>
</tr>
</table>
<hr width="600"/>
<table width="600" border="0" cellspacing="0" cellpadding="3">
<tr>
    <td nowrap width="10%" align="center" valign="middle"><?php echo $backlink;?></td>
    <td nowrap width="80%" align="center" valign="middle"><center><?php print "Page $page of $maxpages";?></center></td>
    <td nowrap width="10%" align="center" valign="middle"><?php echo $forwardlink;?></td>
</tr>
</table>
<table width="600" border="0" cellspacing="0" cellpadding="3" class="box-table">
<tr>
<td class="table-header"><a href="<?php echo $myname."?page=useradmin&where=$where"?>">Groupname</a></td>
<td class="table-header">Action</td>
</tr>
<?php
$i=0;
while($row=$users[$i]) {
?>
<tr>
<td valign="middle"><?php echo $row['name'];?></td>
<td valign="middle"><a href="javascript:delgroup('<?php echo $myname."?page=groups&action=groups&subaction=delete&st=$st&gid=".$row['id'];?>')">delete</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $myname."?page=groups&subaction=editgroup&st=$st&gid=".$row['id'];?>">edit</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $myname."?page=searches&gid=".$row['id'];?>">add users</a></td>
</tr>
<?php
$i++;
   }
?>
</table>

<?php
} elseif($subaction=="editgroup" || $subaction=="addgroup") {
    if($subaction=="editgroup"){
        $group_id=$gid;
        $SQL="Select * from ".$PHORUM["group_table"]." where id='$group_id'";
        $q->query($DB, $SQL);
        $rec=$q->getrow();
        if(!is_array($rec))
          $error=$lNoGroup;
    }
?>
<SCRIPT LANGUAGE="JavaScript">
    function textlimit(field, limit) {
        if (field.value.length > limit)
            field.value = field.value.substring(0, limit);
    }
</script>
<form action="<?php echo $myname; ?>" method="post">
<input type="hidden" name="page" value="groups">
<input type="hidden" name="action" value="groups">
<input type="hidden" name="subaction" value="save_group">
<input type="hidden" name="gid" value="<?php echo $gid; ?>">
<input type="hidden" name="st" value="<?php echo $st; ?>">
<input type="hidden" name="sort" value="<?php echo $sort; ?>">

<table cellspacing="0" cellpadding="2" border="0" class="box-table" width="600">
<tr>
    <td class="table-header" colspan="2"><?php echo ($subaction=="editgroup") ? "Edit Group" : "Add Group"; ?> :</td>
</tr>
<tr>
    <th nowrap>&nbsp;Groupname:&nbsp;&nbsp;</th>
    <td><input type="text" name="name" size="30" maxlength="50" value="<?php echo $rec['name']; ?>"></td>
</tr>
<tr>
    <th nowrap>&nbsp;Permission Level:&nbsp;&nbsp;</th>
    <td><input type="text" name="permission_level" size="30" maxlength="50" value="<?php echo $rec['permission_level']; ?>"></td>
</tr>
</table>
<br /><br />
<input type="submit" name="submit" value="Save">
</form>
The following users are members of this group:<br />
<a href="<?php echo $myname."?page=searches&gid=$gid";?>">Add users to this group</a><br /><br />
<?php
$q->query($DB,"SELECT b.id,b.username,b.email FROM ".$PHORUM["user2group_table"]." as a,".$PHORUM["auth_table"]." as b WHERE a.group_id=$gid AND b.id=a.user_id");
?>
<table width="600" border="0" cellspacing="0" cellpadding="3" class="box-table">
<tr>
<td class="table-header">Username</td>
<td class="table-header">EMail</td>
<td class="table-header">Action</td>
</tr>
<?php
while($row=$q->getrow()) {
?>
<tr>
<td valign="middle"><?php echo $row['username'];?></td>
<td valign="middle"><?php echo $row['email'];?></td>
<td valign="middle"><a href="<?php echo $myname."?page=groups&action=groups&subaction=removeuser&st=$st&gid=$gid&uid=".$row['id'];?>">remove</a></td>
</tr>
<?php
}
?>
</table>
<?php } ?>
