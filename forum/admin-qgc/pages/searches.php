<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security(); ?>
<?php
if($gid) {
  $pagelength=50;
  if(!$sort) {
    $sort='username';
  }
  $order = " order by $sort";

  $SQL="select id,email,username from $pho_main"."_auth";
  if($where){
    $SQL.=" where username like '%$where%' or email like '%$where%'";
  }
  $SQL.=$order;

  $q->query($DB, $SQL);

  $users_found=$q->numrows();

  $maxpages=$users_found/$pagelength;
  if($maxpages > intval($maxpages))
    $maxpages=intval(++$maxpages);

?>
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



    $SQL="select name from ".$PHORUM["group_table"]." where id=$gid";
    $q->query($DB, $SQL);
    $row=$q->getrow();
    $groupname=$row["name"];

    $SQL="select count(*) as count from $pho_main"."_auth";
    $q->query($DB, $SQL);
    $row=$q->getrow();
    $total_users=$row['count'];
    if($st==0) {
      $backlink="&nbsp;";
    } else {
      $backlink="<a href=\"$myname?page=searches&gid=$gid&sort=$sort&where=$where&st=".($st-$pagelength)."\">back</a>";
    }
    if(($st+$pagelength)>=$total_users) {
      $forwardlink="&nbsp;";
    } else {
      $forwardlink="<a href=\"$myname?page=searches&gid=$gid&sort=$sort&where=$where&st=".($st+$pagelength)."\">forward</a>";
    }

    $page=$st/$pagelength+1;
?>
<br />
<table width="600" border="0" cellspacing="0" cellpadding="3">
<tr>
    <td nowrap width="50%" align="left" valign="middle">Add User to Group named <a href="<?php echo "$myname?page=groups&subaction=editgroup&gid=$gid";?>"><?php echo $groupname;?></a><br />showing <?php echo $users_found; ?> of <?php echo $total_users; ?> total users</td>
    <td nowrap width="50%" align="right" valign="middle"><form style="align: right; display: inline;" action="<?php echo $myname; ?>" method="get"><input type="hidden" name="page" value="searches" /><input type="hidden" name="gid" value="<?php echo $gid;?>" /><input type="text" name="where" size="20" value="<?php echo $where;?>" /><input type="submit" value="Search" /></form></td>

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
<td class="table-header"><a href="<?php echo $myname."?page=searches&subaction=$subaction&where=$where&sort=username"?>">Username</a></td>
<td class="table-header"><a href="<?php echo $myname."?page=searches&subaction=$subaction&where=$where&sort=email"?>">EMail</a></td>
<td class="table-header">Action</td>
</tr>
<?php
$i=0;
while($row=$users[$i]) {
?>
<tr>
<td valign="middle"><?php echo $row['username'];?></td>
<td valign="middle"><?php echo $row['email'];?></td>
<td valign="middle"><a href="<?php echo $myname."?page=searches&action=searches&subaction=adduser&sort=$sort&st=$st&gid=$gid&uid=".$row['id'];?>">add to group</a></td>
</tr>
<?php
$i++;
   }
?>
</table>
<?php } elseif ($fid) { // add a group to forum (allow its members access)
  $pagelength=50;
  $order = " order by name";

  $SQL="select id,name from ".$PHORUM["group_table"];
  if($where){
    $SQL.=" where name like '%$where%'";
  }
  $SQL.=$order;

  $q->query($DB, $SQL);

  $users_found=$q->numrows();

  $maxpages=$users_found/$pagelength;
  if($maxpages > intval($maxpages))
    $maxpages=intval(++$maxpages);

?>
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



    $SQL="select name from ".$PHORUM["main_table"]." where id=$fid";
    $q->query($DB, $SQL);
    $row=$q->getrow();
    $forumname=$row["name"];

    $SQL="select count(*) as count from ".$PHORUM["group_table"];
    $q->query($DB, $SQL);
    $row=$q->getrow();
    $total_users=$row['count'];
    if($st==0) {
      $backlink="&nbsp;";
    } else {
      $backlink="<a href=\"$myname?page=searches&fid=$fid&where=$where&st=".($st-$pagelength)."\">back</a>";
    }
    if(($st+$pagelength)>=$total_users) {
      $forwardlink="&nbsp;";
    } else {
      $forwardlink="<a href=\"$myname?page=searches&fid=$fid&where=$where&st=".($st+$pagelength)."\">forward</a>";
    }

    $page=$st/$pagelength+1;
?>
<br />
<table width="600" border="0" cellspacing="0" cellpadding="3">
<tr>
    <td nowrap width="50%" align="left" valign="middle">Add Group to Forum named <a href="<?php echo "$myname?page=props&f=$fid";?>"><?php echo $forumname;?></a><br />showing <?php echo $users_found; ?> of <?php echo $total_users; ?> total groups</td>
    <td nowrap width="50%" align="right" valign="middle"><form style="align: right; display: inline;" action="<?php echo $myname; ?>" method="get"><input type="hidden" name="page" value="searches" /><input type="hidden" name="fid" value="$fid" /><input type="text" name="where" size="20" value="<?=$where?>" /><input type="submit" value="Search" /></form></td>

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
<td class="table-header">Groupname</td>
<td class="table-header">Action</td>
</tr>
<?php
$i=0;
while($row=$users[$i]) {
?>
<tr>
<td valign="middle"><?php echo $row['name'];?></td>
<td valign="middle"><a href="<?php echo $myname."?page=searches&action=searches&subaction=addgroup&st=$st&gid=".$row['id']."&fid=$fid";?>">add this group to forum</a></td>
</tr>
<?php
$i++;
   }
?>
</table>
<?php } ?>
