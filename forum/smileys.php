<?php
////////////////////////////////////////////////////////////////////////////////
//                                                                            //
//   Copyright (C) 2000  Phorum Development Team                              //
//   http://www.phorum.org                                                    //
//                                                                            //
//   This program is free software. You can redistribute it and/or modify     //
//   it under the terms of either the current Phorum License (viewable at     //
//   phorum.org) or the Phorum License that was distributed with this file    //
//                                                                            //
//   This program is distributed in the hope that it will be useful,          //
//   but WITHOUT ANY WARRANTY, without even the implied warranty of           //
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                     //
//                                                                            //
//   You should have received a copy of the Phorum License                    //
//   along with this program.                                                 //
////////////////////////////////////////////////////////////////////////////////

  $sTitle=" smileys";
  require "./common.php";

  if(empty($pluginsmiley)) exit();

  include phorum_get_file_name("header");
?>
<table class="PhorumListTable" border="0" cellspacing="0" cellpadding="2" align="center">
  <tr>
    <td <?php echo bgcolor($ForumTableHeaderColor); ?> valign="TOP" nowrap colspan="2" align="center"><font color="<?php echo $ForumTableHeaderFontColor; ?>">&nbsp;<?php echo $lSmileyTitle; ?></font></td>
  </tr>
  <tr>
    <td <?php echo bgcolor($ForumTableBodyColor1); ?> valign="TOP" nowrap align="center"><font color="<?php echo $ForumTableBodyFontColor1; ?>">&nbsp;<?php echo $lSmileyCode; ?></font></td>
    <td <?php echo bgcolor($ForumTableBodyColor1); ?> valign="TOP" nowrap align="center"><font color="<?php echo $ForumTableBodyFontColor1; ?>">&nbsp;<?php echo $lSmileyImage; ?></font></td>
  </tr>
<?php
  reset($pluginsmiley);
  while(list($key,$val) = each($pluginsmiley)) {
    echo "<tr><td align=\"CENTER\" valign=\"MIDDLE\" ".bgcolor($ForumTableBodyColor1).">";
    echo "<font color=\"$ForumTableBodyFontColor2\">$key</font></td><td align=\"CENTER\" valign=\"MIDDLE\" ".bgcolor($ForumTableBodyColor2)."><img src=\"".$PHORUM['forum_url']."/smileys/".$val."\">";
    echo "</td></tr>\n";
  }
  if(file_exists($PHORUM['include']."/smiley/$f.php")) {
  ?>
   <tr>
    <td <?php echo bgcolor($ForumTableBodyColor1); ?> valign="TOP" nowrap align="center" colspan="2"><font color="<?php echo $ForumTableBodyFontColor1; ?>">&nbsp;<?php echo $lSmileyLocal; ?></font></td>
  </tr> 
  <?php
    $pluginsmiley_ext=array();
     include $PHORUM['include']."/smiley/$f.php";
     reset($pluginsmiley_ext);
     while(list($key,$val) = each($pluginsmiley_ext)) {
    echo "<tr><td align=\"CENTER\" valign=\"MIDDLE\" ".bgcolor($ForumTableBodyColor1).">";
    echo "<font color=\"$ForumTableBodyFontColor2\">$key</font></td><td align=\"CENTER\" valign=\"MIDDLE\" ".bgcolor($ForumTableBodyColor2)."><img src=\"".$val."\">";
    echo "</td></tr>\n";
     }  
  }  
?>
</table>
<?php

  include phorum_get_file_name("footer");

?>
