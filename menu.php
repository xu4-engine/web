<?php

function showMenu($selected) {
  
  $items = array(array('title' => "Home", 'url' => "index.php"),
		 array('title' => "Sourceforge project page", 'url' => "http://sourceforge.net/projects/xu4"),
		 array('title' => "Screenshots", 'url' => "screenshots.html"),
		 array('title' => "Discussion Forum", 'url' => "forum"),
		 array('title' => "Download", 'url' => "download.php"),
		 array('title' => "Links", 'url' => "links.html"));

  echo "<div class=\"menu\">";
  $first = 1;
  foreach ($items as $item) {
    if ($first) {
      $first = 0;
    } else {
      echo " | ";
    }
    if (strcmp($selected, $item['title']) == 0) {
      echo "  <span class=\"selectedmenuitem\">".$item['title']."</span>";
    } else {
      echo "  <span class=\"menuitem\"><a href=\"".$item['url']."\">".$item['title']."</a></span>";
    }
  }
  echo "</div>";

}

?>
