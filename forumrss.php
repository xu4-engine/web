<?PHP
  // This is a no frills script.  Alter at will.
  // pass or set num to the forum number you want displayed and
  // then include this script.
  // modified for rss output - 03/29/2001 - Joe Stewart <joe@beststuff.com>

  // options:  [default]
  // num = id number of forum
  // max = max threads or messages to return
  // mode = messages - displays the most recent messages
  //        blank or any other mode - displays most recent threads [default]

  chdir("./forum");  // path to where common.php is.  you may want to hard code
                 // this if you are going to be including this script from
                 // several places.

  @require "common.php";

  if( !$max ) {
     $max=10;   // number of messages to show.
  }

  if( !$mode ) {
     $mode="messages";
  }

  if( !$approved ) {
    $approved = "approved = 'Y'";
  }
  else {
    $approved = "1";
  }

  // There are a few SQL statements to choose from here.
  // $mode = set to message or [threads]

  if ( $mode == "messages") {
     // newest $max messages
     $SQL="select id, thread, subject from $ForumTableName where $approved order by datestamp desc limit $max";
  } else {
     // newest $max threads
     $SQL="select id, thread, subject from $ForumTableName where $approved and thread=id order by thread desc limit $max";
  }
 header('Content-type: text/xml');
 echo "<?xml version=\"1.0\"?>\n";
 echo "<!DOCTYPE rss PUBLIC \"-//Netscape Communications//DTD RSS 0.91//EN\" \"http://my.netscape.com/publish/formats/rss-0.91.dtd\">\n\n";

 echo "<rss version=\"0.91\">\n\n";

 echo "<channel>\n\n";

 echo "<description>xu4 Forum</description>\n";
 echo "<language>en-us</language>\n\n";

 echo "<title>xu4 Forum</title>\n";
 echo "<link>http://xu4.sourceforge.net/forum/</link>\n\n";


  $q->query($DB, $SQL);
  $rec=$q->getrow();
  while(is_array($rec)){
    echo "<item>\n";
    $title = strip_tags(preg_replace("/&/", "&amp;", $rec[subject]));
    echo "<title>$title</title>\n";
    echo "<link>$forum_url/$read_page.$ext?f=$num&amp;i=$rec[id]&amp;t=$rec[thread]</link>\n";
    echo "</item>\n";
   // echo "<a href=\"$forum_url/$read_page.$ext?f=$num&i=$rec[id]&t=$rec[thread]\">$rec[subject]</a><br>\n";
    $rec=$q->getrow();
  }

  echo "</channel>\n";
  echo "</rss>\n";
