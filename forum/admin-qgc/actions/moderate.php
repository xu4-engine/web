<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php
  include "$include_path/delete_message.php";
  
  if($approved=='Y'){
    hide_messages($id);
    $word = "hidden";
  } elseif($approved=='N'){
    approve_messages($id);
    $word = "approved";
  } elseif($approved=='H'){
    $approved='Y';
    show_messages($id);
    $word = "unhidden/shown again";
  }
  QueMessage("Message $id $word.");
?>
