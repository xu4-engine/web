<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security(); ?>
<?php

    if($authopt=="="){
        $cond="='$match'";
    } else {
        $cond=" like '%$auth%'";
    }
    $sSQL="Select id from $ForumTableName where author $cond";
    $q->query($DB, $sSQL);
    $rec=$q->getrow();
    while(is_array($rec)){
        $ids[]=$rec["id"];
        $rec=$q->getrow();
    }

    if(is_array($ids)){
        include "$include_path/delete_message.php";
        $count=delete_messages($ids);
        QueMessage("$count message(s) deleted.");
    } else {
        QueMessage("No messages selected for deletion.");
    }

?>