#!/usr/local/bin/php -q
<?php

// This script will remove messages from a forum that have been orphaned
// because of deleting their parents or inserts not happening properly.

// You will need to change ./ to the path where phorum is located.
chdir("../");

@require "common.php";
include "./include/delete_message.php";

$sql="select table_name from $pho_main";

$q->query($DB, $sql);

while($rec=$q->getrow()){
    $tables[]=$rec["table_name"];
}

foreach($tables as $table){

    $ids=array();

    // find orphaned messages
    $sql="select t1.id from $table t1 left join $table t2 on t1.thread=t2.id where t2.id is NULL";

    $q->query($DB, $sql);

    while($rec=$q->getrow()){
        $ids[]=$rec["id"];
    }

    // find orphaned bodies
    $sql="select t1.id from $table"."_bodies t1 left join $table t2 on t1.id=t2.id where t2.id is NULL";

    $q->query($DB, $sql);

    while($rec=$q->getrow()){
        $ids[]=$rec["id"];
    }

    $PHORUM["ForumTableName"]=$table;

    if(!empty($ids)){
        echo "Deleting from $table:";
        print_r($ids);
        delete_messages($ids);
    }

}


?>