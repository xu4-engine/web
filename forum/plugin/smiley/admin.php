<?php
if ( !defined( "_COMMON_PHP" ) ) return;
if($do!="props") return;

if(isset($update)) {
  $data="";
  if(isset($HTTP_POST_VARS["key1"])) {
    $num=1;
    $data="<?php\n\$pluginsmiley = array();\n";
    while(isset(${"key".$num})) {
      if(!empty(${"key".$num}) && !empty(${"val".$num})) {
        $key=${"key".$num};
        $val=${"val".$num};

        if(get_magic_quotes_gpc()){
            $key=stripslashes($key);
            $val=stripslashes($val);
        }
        $key=str_replace("'", "\\'", $key);
        $data .= "\$pluginsmiley['$key']='$val';\n";
        $pluginsmiley[$key]=$val;
      }
      $num++;
    }
    $data .= "?>\n";
  }
  if($fp = @fopen($PHORUM['settings_dir']."/smiley.php","w")) {
    fputs($fp,$data);
    fclose($fp);
  } else {
    echo("Permission denied for writing \"".$PHORUM['settings_dir']."/settings.php\", please check file permissions.\n<br>");
    echo("Optionally, you may issue the following command from the phorum directory:<br>\n");
    echo("<p><div align=\"left\">cat &lt;&lt;EOF &gt; $PHORUM[settings_dir]/settings.php<br>\n".nl2br(htmlspecialchars(str_replace("\$","\\$",$data)))."EOF</div></p>\n");
  }
}

if(file_exists($PHORUM['settings_dir']."/smiley.php")) {
    include($PHORUM['settings_dir']."/smiley.php");
}

?>

<form action="<?php $PHP_SELF; ?>" method="POST">
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="plugin" value="<?php echo $plugin; ?>" />
<input type="hidden" name="do" value="<?php echo $do; ?>" />
<input type="hidden" name="update" value="1" />

<table border="0" cellspacing="0" cellpadding="3">
<tr>
    <td align="center" valign="middle" bgcolor="#000080" colspan="2">
        <font face="Arial,Helvetica" color="#FFFFFF"><b>Smiley Plugin Admin</b><br />
	If you want to show the users which smileys are available, just use add a link to the smileys.php in the phorum-dir (i.e. smileys.php?f=1 ).<br />
	If you use that file you have to add the following vars to your language-file:<br />
	<ul>
	<li>$lSmileyTitle = ""; // should contain i.e. Smileys
	<li>$lSmileyCode  = ""; // is the smiley code, what should be entered
	<li>$lSmileyImage = ""; // is the image which is shown instead
	<li>$lSmileyLocal = ""; // only used if you use forum-specific smileys, means Local Smileys
	</ul>
    </td>
</tr>

<?php
$d = dir("./smileys");
while($entry=$d->read()) {
  if($entry != '.' && $entry !='..') {
    $arr_smiley[]=$entry;
  }
}
echo "<tr><td align=center>The following smileys are in the smileys dir:<br>\n";
echo "<table>";
reset($arr_smiley);
while(list($id,$entry)=each($arr_smiley)) {
  echo("<tr><td align=center>$entry</td>");
  echo("<td><img src=\"../smileys/$entry\"><br></td></tr>\n");
}
echo ("</table></td><td valign=\"top\"><table border=\"1\" cellspacing=\"0\" cellpadding=\"3\" valign=\"top\">");

$num=1;
while(list($key,$val) = @each($pluginsmiley)) {
  echo("<tr>\n<td align=\"right\" valign=\"middle\" bgcolor=\"#FFFFFF\">\n<font face=\"Arial,Helvetica\">");

  echo("<b>$num</b>. Replace <INPUT TYPE=\"text\" NAME=\"key$num\" VALUE=\"".htmlspecialchars($key)."\">");
  echo("</font>\n</td>\n<td align=\"left\" valign=\"middle\" bgcolor=\"#FFFFFF\">");
  echo("<select name=\"val$num\" onChange=\"show_smiley($num)\">\n");
  reset($arr_smiley);
  while(list($id,$entry)=each($arr_smiley)) {
    if($entry == $val) {
      $sel=' selected';
    } else {
      $sel='';
    }
    echo("<option value=\"$entry\"$sel>$entry\n");
  }
  echo("</select>\n");
  echo("</font>\n</td>\n</tr>\n\n");
  $num++;
}


$i=$num+5;
while($i>$num) {
  echo("<tr><td align=\"right\" valign=\"middle\" bgcolor=\"#FFFFFF\"><font face=\"Arial,Helvetica\">");
  echo("<b>New</b>. Replace <INPUT TYPE=\"text\" NAME=\"key$num\">");
  echo("</font></td><td align=\"left\" valign=\"middle\" bgcolor=\"#FFFFFF\">");
  echo("<select name=\"val$num\">\n");
  reset($arr_smiley);
  while(list($id,$entry)=each($arr_smiley)) {
    echo("<option value=\"$entry\">$entry\n");
  }
  echo("</select></td></tr>\n");
  $num++;
}

  echo("<tr><td align=\"center\" bgcolor=\"#FFFFFF\" colspan=\"2\"><font face=\"Arial,Helvetica\">");

echo("<input type=\"submit\" value=\" Apply \">\n");
echo("</form>\n</td></tr></table></td>");
echo("</tr>\n</table>");

?>
