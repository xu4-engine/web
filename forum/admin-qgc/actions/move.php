<?php
   if ( !defined( "_COMMON_PHP" ) ) return;

   require "$include_path/move_thread.php";

   $ret="";
   $ret = move_thread($t,$targetf);

   if(empty($ret))
      QueMessage("Thread $t was moved successfully.");
   else
      QueMessage($ret);
?>
