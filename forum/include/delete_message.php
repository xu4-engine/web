<?php

    if ( !defined( "_COMMON_PHP" ) ) return;

    function delete_messages($ids)
    {
        GLOBAL $PHORUM, $DB, $q;

        if(!is_array($ids)) {
           $id_array=explode(",", $ids);
        } else {
           $id_array=$ids;
        }

        while(list($key, $id)=each($id_array)){
            $arr[]=_get_message_tree($id);
        }

        $lists=implode(",", $arr);

        // get all involved threads
        $SQL="SELECT DISTINCT(thread) from $PHORUM[ForumTableName] where id in ($lists)";
        $q->query($DB, $SQL);
        while($rec=$q->getrow()){
                $threads[]=$rec['thread'];
        }
        $threads=implode(",",$threads);

        // delete headers
        $SQL="Delete from $PHORUM[ForumTableName] where id in ($lists)";
        $q->query($DB, $SQL);

        // delete bodies
        $SQL="Delete from $PHORUM[ForumTableName]_bodies where id in ($lists)";
        $q->query($DB, $SQL);

        // delete attachments
        $SQL="Select message_id,id,filename from $PHORUM[ForumTableName]_attachments where message_id in ($lists)";

        $q->query($DB, $SQL);

        while($rec=$q->getrow()){
            $filename="$PHORUM[AttachmentDir]/$PHORUM[ForumTableName]/$rec[id]".strtolower(strrchr($rec["filename"], "."));
            unlink($filename);
        }

        // delete attachments from attachments-table
        $SQL="Delete from $PHORUM[ForumTableName]_attachments where message_id in ($lists)";
        $q->query($DB, $SQL);

        // reset the modifystamp
        $SQL="SELECT thread,max(datestamp) as datestamp from $PHORUM[ForumTableName] where thread in ($threads) group by thread";
        $q->query($DB, $SQL);

        $q2= new query($DB);

        while($rec=$q->getrow()){
            list($date,$time) = explode(" ", $rec["datestamp"]);
            list($year,$month,$day) = explode("-", $date);
            list($hour,$minute,$second) = explode(":", $time);
            $tstamp = mktime($hour,$minute,$second,$month,$day,$year);
            $SQL="update $PHORUM[ForumTableName] set modifystamp=$tstamp where thread=$rec[thread]";
            $q2->query($DB, $SQL);
        }
        return count($arr);
    }

    function approve_messages($ids)
    {
        GLOBAL $PHORUM, $DB, $q;

        if(!is_array($ids)) $id_array=explode(",", $ids);

        while(list($key, $id)=each($id_array)){
            $arr[]=_get_message_tree($id);
        }

        $list=implode(",", $arr);

        // set unapproved messages to approved status
        $SQL="update $PHORUM[ForumTableName] set approved='Y' where approved='N' and id in ($list)";
        $q->query($DB, $SQL);
    }

    function hide_messages($ids)
    {
        GLOBAL $PHORUM, $DB, $q;

        if(!is_array($ids)) $id_array=explode(",", $ids);

        while(list($key, $id)=each($id_array)){
            $arr[]=_get_message_tree($id);
        }

        $list=implode(",", $arr);

        // set approved messages to hidden status.
        $SQL="update $PHORUM[ForumTableName] set approved='H' where approved='Y' and id in ($list)";
        $q->query($DB, $SQL);
    }



    function show_messages($ids)
    {
        GLOBAL $PHORUM, $DB, $q;

        if(!is_array($ids)) $id_array=explode(",", $ids);

        while(list($key, $id)=each($id_array)){
            $arr[]=_get_message_tree($id);
        }

        $list=implode(",", $arr);


        // set hidden messages to approved status.
        $SQL="update $PHORUM[ForumTableName] set approved='Y' where approved='H' and id in ($list)";
        $q->query($DB, $SQL);

    }



    function _get_message_tree($id)
    {
        global $PHORUM, $DB;
        $q = new query($DB);
        $SQL="Select id from $PHORUM[ForumTableName] where parent=$id";
        $q->query($DB, $SQL);
        $tree="$id";
        while($rec=$q->getrow()){
            $tree.=","._get_message_tree($rec["id"]);
        }
        return $tree;
    }

?>
