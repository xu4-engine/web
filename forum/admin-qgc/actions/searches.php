<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security(); ?>
<?php
switch($subaction) {
    case 'addgroup':
	$SQL="INSERT INTO ".$PHORUM["forum2group_table"]." (group_id,forum_id) VALUES($gid,$fid)";
	$q->query($DB, $SQL);
	$err="Group successfully added to Forum";
	$page="searches";
	unset($gid);
        writefile($fid);
	break;
    case 'adduser':
	$SQL="INSERT INTO ".$PHORUM["user2group_table"]." (user_id,group_id) VALUES($uid,$gid)";
	$q->query($DB, $SQL);
	$err="User successfully added to group";
        phorum_del_groupcache();
	$subaction="";
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
}
QueMessage($err);
?>
