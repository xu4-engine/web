<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security(); ?>
<?php
    $sql="select table_name from $PHORUM[main_table]";
    $q->query($DB, $sql);
    $q2 = new query($DB);
    while($rec=$q->getrow()){
        $sql="select * from $rec[table_name]_attachments where message_id=0 order by id";
        $q2->query($DB, $sql);
        $rows=$q2->numrows();
        while($rec2=$q2->getrow() && $x<$rows-5){
            $filename="$AttachmentDir/$rec[table_name]/$rec2[id]".strtolower(strrchr($rec2["filename"], "."));
            unlink($filename);
            $x++;
        }
        $sql="delete from $rec[table_name]_attachments where message_id=0";
        $q2->query($DB, $sql);
    }

    QueMessage("Orphaned attachments purged.");

?>