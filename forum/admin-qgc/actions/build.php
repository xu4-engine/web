<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security(); ?>
<?php
  writefile("all");
  QueMessage("All files rebuilt.");
?>
