<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security(); ?>
<?php
// collect users of this group, maybe we delete the group so we need them later
if(!empty($gid)) {
  $SQL="SELECT user_id FROM ".$PHORUM["user2group_table"]." where group_id=$gid";
  $q->query($DB, $SQL);
  while($row=$q->getrow()) {
   	$cur_user[]=$row['user_id'];	
  }
  if(count($cur_user)>0) {
  	$upd_users=implode(",",$cur_user);
  }
}
switch($subaction) {
    case 'removegroup':
        $SQL="delete from ".$PHORUM["forum2group_table"]." where group_id=$gid and forum_id=$fid";
        $q->query($DB, $SQL);
	$subaction='';
	$f=$fid;
	$num=$fid;
        phorum_del_groupcache();
	break;
    case 'removeuser':
	$SQL="delete from ".$PHORUM["user2group_table"]." where group_id=$gid AND user_id=$uid";
	$q->query($DB, $SQL);
	$page="groups";
	$action="groups";
	$subaction="editgroup";
	$err="User successfully removed from group";
        phorum_del_groupcache();
	break;
    case 'delete':
        $SQL="delete from ".$PHORUM["group_table"]." where id=$gid";
        $q->query($DB, $SQL);
        $SQL="delete from ".$PHORUM["user2group_table"]." where group_id=$gid";
        $q->query($DB, $SQL);
        $SQL="delete from ".$PHORUM["forum2group_table"]." where group_id=$gid";
        $q->query($DB, $SQL);

        $err="Group $gid has been deleted.";
	phorum_del_groupcache();
        break;
    case 'save_group':
        if(!$err) {
            $name=htmlspecialchars($name);

            if(!get_magic_quotes_gpc()) {
                $name=addslashes($name);
            }

	    // its time to del the groupcache
	    phorum_del_groupcache();

            if(!empty($gid)){
                $sSQL="UPDATE ".$PHORUM["group_table"]." SET name='$name',permission_level='$permission_level' WHERE id='$gid'";
                $q->query($DB, $sSQL);
            } else {
                $gid=$DB->nextid($PHORUM["group_table"]);
                if ($permission_level=='') $permission_level=0;
                $sSQL="Insert into ".$PHORUM["group_table"]." (
                            id,
                            name,
                            permission_level
                        ) VALUES (
                            '$gid',
                            '$name',
                            '$permission_level'
                        )";

                $q->query($DB, $sSQL);
                if(!$err=$q->error()){
                    if($DB->type=="mysql"){
                      $gid=$DB->lastid();
                    }
                }
            }

            if(!$err){
                $err="Group successfully updated.";
                $subaction="";
            } else {
                $subaction="addgroup";
            }
        } else {
            $subaction="editgroup";
        }
        break;
}
// max-group-levels for each user in this group
if(count($cur_user)>0) {
  $SQL="SELECT b.user_id,max(a.permission_level) as maxperm FROM ".$PHORUM["group_table"]." as a, ".$PHORUM["user2group_table"]." as b WHERE b.user_id IN ($upd_users) AND a.id=b.group_id GROUP BY b.user_id";
  $q->query($DB, $SQL);
  $r=new query($DB);
  while($row=$q->getrow()) {
	  $sSQL="UPDATE ".$PHORUM["auth_table"]." SET max_group_permission_level=".$row['maxperm']." WHERE id=".$row['user_id'];
	  $r->query($DB,$sSQL);
  }
}


QueMessage($err);
?>
