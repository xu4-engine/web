<?php
if ( !defined( "_COMMON_PHP" ) ) return;
if(!file_exists($PHORUM['settings_dir']."/smiley.php")) return;
include($PHORUM['settings_dir']."/smiley.php");

function smiley_read_body ($body) {
  global $pluginsmiley,$PHORUM,$f;
  if(is_array($pluginsmiley)) {
      reset($pluginsmiley);
      while(list($key,$val) = each($pluginsmiley)) {
        $safekey = htmlentities($key,ENT_QUOTES); // we don't want < and other characters to break our IMG tag
        $body = str_replace($key,'<img src="'.$PHORUM['forum_url']."/smileys/$val\" alt=\"$safekey\">",$body);
      }
  }
  if(file_exists($PHORUM['include']."/smiley/$f.php")) {
     $pluginsmiley_ext=array();
     include $PHORUM['include']."/smiley/$f.php";
     reset($pluginsmiley_ext);
     while(list($key,$val) = each($pluginsmiley_ext)) {
       $body = str_replace($key,'<img src="'.$val.'" border="0">',$body);
     }  
  }
  return $body;
}

$plugins["read_body"]["smiley"]="smiley_read_body";
?>
