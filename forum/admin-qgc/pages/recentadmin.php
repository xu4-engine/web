<?php if(!defined("PHORUM_ADMIN")) return; ?>
<?php check_security($num); ?>
<table border="0" cellspacing="0" cellpadding="3" class="box-table">
<tr>
    <td height=21 width="100%" class="table-header">&nbsp;<?php echo $lTopics;?></td>
    <td height=21 nowrap="nowrap" width=150 class="table-header"><?php echo $lAuthor;?>&nbsp;</td>
    <td height=21 nowrap="nowrap" width=40 class="table-header"><?php echo $lDate;?></td>
    <td height=21 nowrap="nowrap" width=40 class="table-header">Actions</td>
</tr>

<?php
require "./common.php";
$nav = '';
if (!isset($navigate) || empty($navigate)) $navigate = 0;

if (isset($q)) {
  $sSQL="SELECT id, name, table_name, parent, folder, description FROM ".$pho_main." WHERE active=1 AND id=$num";
  if ($SortForums) $sSQL.=" ORDER BY name";
  $q->query($DB, $sSQL);
  $rec=$q->getrow();
}
else {
  $rec = '';
}

if (is_array($rec)) {
  $empty=false;
  $name=$rec["name"];
  $table=$rec["table_name"];
  $i++;
  $num=$rec["id"];
  if (!$rec["folder"]) {
    $sSQL = "SELECT * from $table WHERE approved='N' ORDER BY datestamp DESC";
    $pq =& new query($DB, $sSQL);
    $x=1;
    while ($tam=$pq->getrow()) {
      $subject=$tam["subject"];
      $id=$tam["id"];
      $topic=$tam["thread"];
      $person=$tam["author"];
      $datestamp = date_format($tam["datestamp"]);
      $approved = $tam["approved"];
      if (($x%2)==0) { $bgcolor=$ForumTableBodyColor1; }
      else { $bgcolor=$ForumTableBodyColor2; }
      $x++;
      $nav.='<tr><td '.bgcolor($bgcolor).'>';
      $nav.="<a HREF=\"$forum_url/$read_page.$ext?admview=1&f=$num&i=".$tam["id"]."&t=${topic}\">";
      $nav.="$subject</a></td>";
      $nav.='<td '.bgcolor($bgcolor).">$person</td><td ".bgcolor($bgcolor).">";
      $nav.="$datestamp</td>";
      $nav.='<td '.bgcolor($bgcolor)."><a HREF=\"${myname}?page=recentadmin&action=del&type=quick&id=${id}";
      $nav.="&num=${num}&navigate=${navigate}&thread=${topic}\">Delete</a>&nbsp;|&nbsp;";
      $nav.="<a HREF=\"${myname}?page=edit&srcpage=recentadmin&id=${id}&num=${num}&navigate=${navigate}&mythread=${topic}\">";
      $nav.="Edit</a>&nbsp;|&nbsp;";
      $nav.="<a HREF=\"${myname}?page=recentadmin&action=moderate&approved=${approved}&id=${id}&num=${num}&navigate=${navigate}";
      $nav.="&mythread=${topic}\">";
      if ($approved == 'Y') { $nav.="Hide"; } else { $nav.="Approve"; }
      $nav.="</a></td></tr>\n";
    }
  }
  $rec=$q->getrow();
}
else {
  $nav.="No active forums";
}

$nav.='</table>';
print "$nav";
?>
