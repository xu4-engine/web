<?php

    if ( !defined( "_COMMON_PHP" ) ) return;

function format_body($body)
{

    global $ForumAllowHTML, $plugins, $lQuote;

    // get rid of moderator HTML tags
    $body = str_replace('<HTML>', '', $body);
    $body = str_replace('</HTML>', '', $body);

    // replace all tag starts and ends
    $body=str_replace('<', '&lt;', $body);
    $body=str_replace('>', '&gt;', $body);

    if(function_exists('preg_replace')){
        // handle old legacy <> links by converting them into BB tags
        $body=preg_replace("/&lt;((http|https|ftp):\/\/[a-z0-9;\/\?:@=\&\$\-_\.\+!*'\(\),~%]+?)&gt;/i", "<a href=\"$1\" target=\"_blank\">$1</a>", $body);
        $body=preg_replace("/&lt;mailto:([a-z0-9\-_\.\+]+@[a-z0-9\-]+\.[a-z0-9\-\.]+?)&gt;/i", "<a href=\"mailto:$1\">$1</a>", $body);
    }

    if(function_exists('preg_replace')){

        if($ForumAllowHTML==1){
            // replace url/link items
            $body=preg_replace("/\[img\]((http|https|ftp):\/\/[a-z0-9;\/\?:@=\&\$\-_\.\+!*'\(\),~%]+?)\[\/img\]/i", "<img src=\"$1\" />", $body);
            $body=preg_replace("/\[url\]((http|https|ftp|mailto):\/\/[a-z0-9;\/\?:@=\&\$\-_\.\+!*'\(\),~%]+?)\[\/url\]/i", "<a href=\"$1\" target=\"_blank\">$1</a>", $body);
            $body=preg_replace("/\[url=((http|https|ftp|mailto):\/\/[a-z0-9;\/\?:@=\&\$\-_\.\+!*'\(\),~%]+?)\](.+?)\[\/url\]/i", "<a href=\"$1\" target=\"_blank\">$3</a>", $body);
            $body=preg_replace("/\[email\]([a-z0-9\-_\.\+]+@[a-z0-9\-]+\.[a-z0-9\-\.]+?)\[\/email\]/i", "<a href=\"mailto:$1\">$1</a>", $body);



            // replace simple tag replacements
            $search=array(
                          "/\[(b)\]/",
                          "/\[\/(b)\]/",
                          "/\[(u)\]/",
                          "/\[\/(u)\]/",
                          "/\[(i)\]/",
                          "/\[\/(i)\]/",
                          "/\[(center)\]/",
                          "/\[\/(center)\]/",
                          "/\[(code)\]/",
                          "/\[\/(code)\]/",
                          "/\[(quote)\]/",
                          "/\[\/(quote)\]/",
                          "/\[(hr)\]/",
                          "/\[(s)\]/",
                          "/\[\/(s)\]/"
                      );

            $replace=array(
                        '<strong>',
                        '</strong>',
                        '<u>',
                        '</u>',
                        '<i>',
                        '</i>',
                        '<center>',
                        '</center>',
                        '<pre>',
                        '</pre>',
                        "<blockquote>$lQuote:<br />\n",
                        '</blockquote>',
                        '<hr>',
                        '<strike>',
                        '</strike>'
                     );

            $body=preg_replace($search, $replace, $body);
        }



        // clean up badly formed tags or if not allowed

        $body=preg_replace("/\[url=.*?\]/", '', $body);


        $search_clean=array(
                        "/\[url\]/",
                        "/\[\/url\]/",
                        "/\[img\]/",
                        "/\[\/img\]/",
                        "/\[email\]/",
                        "/\[email\]/",
                        "/\[\/email\]/"
                      );

        $body=preg_replace($search_clean, '', $body);

    }

    // exec all read plugins
    @reset($plugins['read_body']);
    while(list($key,$val) = each($plugins['read_body'])) {
        $body = $val($body);
    }

    $body=nl2br($body);
    // fix for double-newlines in pre-tags
    if(preg_match_all("/((<pre>).+?(<\/pre>))/is", $body, $matches)) {
        foreach($matches[1] as $match) {
            $clean=preg_replace("/(<br>\n|<br \/>\n)/i", "\n", $match);
            $body=str_replace($match, $clean, $body);
        }
    }


    return $body;

}

function validate_url($url)
{
    // This function fixes invalid website addresses.  It is not meant to be a definitive 
    // validator - all it tries to fix are links entered by a user who forgets to add 'http://'
    // to the start of it.
    // This function returns the fixed or unchanged URL
    if (strlen(trim($url)) > 0) {
        if (strtolower((substr($url,0,7)) == 'http://') || (strtolower(substr($url,0,8)) == 'https://')) {
            $prepend = false; // user has entered a URL which seems OK
        }
        elseif ((substr($url,0,1) == '/') || (substr($url,0,2) == '\\')) {
            $prepend = false; // user has entered a URL which could be a local address or maybe a computer on a LAN
        }
        elseif (substr($url,0,1) == '.') {
            $prepend = false; // user has entered a URL which could be a relative address, eg "../"
        }
        elseif (strtolower(substr($url,0,7)) == 'file://'){
            $prepend = false; // user has entered a valid file:// URL
        }
        else {
            $prepend = true; // for all other address types we are going to assume the user forgot to add "http://"
        }

        if ($prepend) {
            // add "http://" to URL - this will still work for IP addresses.
            $url = 'http://' . $url;
        }
    }
    return $url;
}

?>
