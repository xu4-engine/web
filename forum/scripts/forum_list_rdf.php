<?php
// +----------------------------------------------------------------------+
// | This file is used for the syndication of forums via RDF.             |
// +----------------------------------------------------------------------+
// |                                                                      |
// | Edit the path to common.php and some information about your site     |
// |                                                                      |
// | This was done by editing the original forum_list.php                 |
// |                                                                      |
// | Example:                                                             |
// | http://www.server.com/forumlist_rdf.php                              |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Mark Kronsbein <mk@php-homepage.de>                          |
// +----------------------------------------------------------------------+

    $sitename = ""; // e.g. Phorum.org

    chdir("./");  // Path to common.php.

    @require "common.php";

    // From original forum_list.php
    // Set this to true and fill in the array section below if you want:
    //    A) only certain forums
    //    B) want them in a certain order
    //    C) or want to use a different name other than what is in the database

    $CUSTOMLIST=false;

    if($CUSTOMLIST){

        // From original forum_list.php
        // Example Array
        // $forums[1]=array("name" => "Users Forum", "table_name" => "phorum");
        // $forums[3]=array("name" => "Wishlist", "table_name" => "phorum_wish");
        // $forums[4]=array("name" => "Phorum Hacks", "table_name" => "phorum_hackers");

    } else {
        $sSQL = "Select id, name, table_name from ".$PHORUM["main_table"]." where active = 1 and folder = 0";
        $q->query($DB, $sSQL);
        $rec = $q->getrow();

        while(is_array($rec)){
            $forums[$rec["id"]]["name"] = $rec["name"];
            $forums[$rec["id"]]["table_name"] = $rec["table_name"];
            $rec=$q->getrow();
        }
        $q->free();
    }

    $data="";

    print "<?xml version=\"1.0\"?>\n";
    print "<rdf:RDF
            xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
            xmlns=\"http://my.netscape.com/rdf/simple/0.9/\">\n\n";
    print "\t<channel>\n";
    print "\t\t<title>".$sitename." - Forum</title>\n";
    print "\t\t<link>".$PHORUM["forum_url"]."</link>\n";
    print "\t\t<description>Phorum at ".$PHORUM["forum_url"]."</description>\n";
    print "\t\t<language>en-US</language>\n";
    print "\t</channel>\n\n";

    while(list($id, $array)=each($forums)){
        $forum_name = $array["name"];
        $url = $forum_url."/".$list_page.".".$ext."?f=".$id."";
        $sSQL = "select count(*) as posts from ".$array["table_name"]."";
        $q->query($DB, $sSQL);
        $rec = $q->getrow();
        if(isset($rec)){
            $num_posts = $rec["posts"];
        } else {
            $num_posts = 0;
        }

        $data .= "<item>\n";
        $data .= "\t<title>".$forum_name." (".$num_posts." posts)</title>\n";
        $data .= "\t<link>".$url."</link>\n";
        $data .= "</item>\n\n";

    }

    echo $data;

    print "</rdf:RDF>\n";

?>