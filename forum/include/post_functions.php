<?php

    if ( !defined( '_COMMON_PHP' ) ) return;

    function violation()
    {
        global $num,$author,$email,$subject,$body,$ip,$host,$violation_page,$ext,$GetVars, $ForumName,$PhorumMail,$PHORUM,$q,$DB;

        $SQL='Select b.email as email from '.$PHORUM[mod_table].' as a,'.$PHORUM[auth_table]." as b where a.user_id=b.id and a.forum_id=$num";
        $q->query($DB, $SQL);
        while($row=$q->getrow()) {
        mail($row['email'], "Phorum Violation", "A forum violation has occured:\n\nauthor: $author\nemail:  $email\nhost:   $host ($ip)\nforum:  $ForumName\n\n$body", "From: Phorum <".$row['email'].">");
        }

        if(!$PhorumMail){
            Header("Location: $violation_page.$ext?f=$num&$GetVars");
        }
        exit();
    }


    // Check host against bad hosts list.
    // Returns true if ok, false if bad.
    function check_host($host)
    {
        global $include_path, $ForumConfigSuffix;
        if(file_exists("$include_path/bad_hosts_$ForumConfigSuffix.php")){
            include "$include_path/bad_hosts_$ForumConfigSuffix.php";
        } else {
            include "$include_path/bad_hosts.php";
        }
        if(@is_array($hosts)){
            reset($hosts);
            while (list(, $badhost) = each($hosts)) {
                if (preg_match("/$badhost/", $host)) {
                    return(false);
                }
            }
        }
        return(true);
    }


    // Check author against bad names list.
    // Returns true if ok, false if bad.
    function check_name($author)
    {
        global $include_path, $ForumConfigSuffix;
        if(file_exists("$include_path/bad_names_$ForumConfigSuffix.php")){
            include "$include_path/bad_names_$ForumConfigSuffix.php";
        } else {
            include "$include_path/bad_names.php";
        }
        if(@is_array($names)){
            reset($names);
            while (list(, $badname) = each($names)) {
                if (strstr($author, $badname)) {
                    return(false);
                }
            }
        }
        return(true);
    }


    // check register moved to userlogin.php

    // Check email against bad emails list.
    // Returns true if ok, false if bad.
    function check_email($email)
    {
        global $include_path, $ForumConfigSuffix;
        if(file_exists("$include_path/bad_emails_$ForumConfigSuffix.php")){
            include "$include_path/bad_emails_$ForumConfigSuffix.php";
        } else {
            include "$include_path/bad_emails.php";
        }
        if(@is_array($emails)){
            reset($emails);
            while (list(, $bademail) = each($emails)) {
                if (strstr($email, $bademail)) {
        return(false);
                }
            }
        }
        return(true);
    }

    function check_closed($thread) {
        global $q, $DB, $PHORUM;
    if($thread > 0) {
         $SQL='Select closed from '.$PHORUM['ForumTableName']." where thread=$thread";
             $q->query($DB, $SQL);
         if($q->numrows()>0){

                $rec=$q->getrow();
        if($rec['closed'] == 1) {
           return false;
        }
         }
    }
    return true;
    }


    // Does various checks on new message data.
    // Returns an empty string if ok, and error string if a problem exists.
    // May also call violation() and exit.
    function check_data($host, $author, $subject, $body, $email)
    {
        global $lNoAuthor, $lNoSubject, $lNoBody, $lNoEmail, $lRegisteredName, $lThreadClosed;
        global $Password, $ModPass, $email_reply, $plugins, $thread, $PhorumMail;
        $IsError = '';

        if (!check_host($host)) {
            violation();
        }

        // exec all post-check plugins
        @reset($plugins['post_check']);
        while(list($key,$val) = each($plugins['post_check'])) {
            $IsError = $val();
        }

        $author = @trim($author);
        if (empty($author)) {
            $IsError=$lNoAuthor;
        } elseif (!check_name($author)) {
            violation();
        } elseif (!$PhorumMail && !check_register($author)){
            $IsError=$lRegisteredName;
        } elseif (!check_closed($thread)) {
            $IsError=$lThreadClosed;
        }


        if(trim($subject)==''){
            $IsError=$lNoSubject;
        }

        if(trim($body)==''){
            $IsError=$lNoBody;
        }


        if(!is_email($email)){
                $email='';
        }

        if (!check_email($email)) {
                    violation();
        }

        if(empty($email) && $email_reply){
                $IsError=$lNoEmail;
        }


        return($IsError);
    }


    // Applies censoring to message.
    // Returns censored data.
    function censor($author, $subject, $email, $body)
    {
        global $include_path, $ForumConfigSuffix;
        if(file_exists("$include_path/censor_$ForumConfigSuffix.php")){
            include "$include_path/censor_$ForumConfigSuffix.php";
        } else {
            include "$include_path/censor.php";
        }

        if (is_array($profan)) {
            reset($profan);
            foreach($profan as $bad_word){
                $blurb=str_pad("", strlen($bad_word), '*');
                $author=preg_replace("/(\b)$bad_word(\b)/i", "\\1$blurb\\2", $author);
                $email=preg_replace("/(\b)$bad_word(\b)/i", "\\1$blurb\\2", $email);
                $subject=preg_replace("/(\b)$bad_word(\b)/i", "\\1$blurb\\2", $subject);
                $body=preg_replace("/(\b)$bad_word(\b)/i", "\\1$blurb\\2", $body);
            }
        }
        return(array($author, $subject, $email, $body));
    }


    // Checks for censored data and returns true if it is found.
    function censor_check($vars)
    {
        global $include_path, $ForumConfigSuffix;

        if(file_exists("$include_path/censor_$ForumConfigSuffix.php")){
            include "$include_path/censor_$ForumConfigSuffix.php";
        } else {
            include "$include_path/censor.php";
        }

        if (is_array($profan)) {
            reset($profan);
            foreach($profan as $bad_word){
                foreach($vars as $var){
                    if(preg_match("/(\b)$bad_word(\b)/i", $var)){
                        return true;
                    }
                }
            }
        }
        return false;
    }

    // Check for duplicate message.
    // Returns true if dup, false if unique.
    function check_dup()
    {
        global $q, $DB, $ForumCheckDup, $ForumTableName;
        global $author, $subject, $body;

        if($ForumCheckDup){
            $minutes=120;
            $date=explode(',', date('H,i,s,m,d,Y'));
            $dupdate=date('Y-m-d H:i:s', mktime($date[0],$date[1]-$minutes,$date[2],$date[3],$date[4],$date[5]));
            $sSQL="Select id from $ForumTableName where author='$author' and subject = '$subject' and datestamp > '$dupdate'";
            $q->query($DB, $sSQL);
            if($q->numrows()>0){
                $rec=$q->getrow();
                $ids="";
                while($rec){
                    if($ids!='') $ids.=',';
                    $ids.=$rec['id'];
                    $rec=$q->getrow();
                }
                $sSQL="Select id from $ForumTableName"."_bodies where id in ($ids) and body='$body'";
                $q->query($DB, $sSQL);
                if($q->numrows()>0) {
                        return(true);
                }
            }
        }
        return(false);
    }


    // checks that the parent of a posted message still exists
    function check_parent($parent)
    {
        if(!$parent) return true;
        global $ForumTableName, $q, $DB;
        $ret=false;
        $SQL="Select id from $ForumTableName where id=$parent";
        $q->query($DB, $SQL);
        if($q->numrows()>0) $ret=true;
        return $ret;
    }

    // Add a message to the Phorum database.
    // Returns an error message on error, empty string otherwise.
    function post_to_database()
    {
        global $q, $DB, $ForumTableName, $ForumModeration, $phorum_user;
        global $ForumName, $PhorumMailCode, $PhorumMail;
        global $phorumver, $SERVER_name;
        global $thread, $subject, $inreplyto, $parent, $author, $body, $email;
        global $image, $datestamp, $host, $email_reply, $attachment_name, $msgid;
        global $plain_author, $plain_subject, $plain_body;
        global $admin_url, $admin_page, $forum_url, $read_page, $ext, $num, $id, $PHORUM;

        $id=$DB->nextid($ForumTableName);
        if ($id==0 && $DB->type!='mysql') {
            return('Error getting nextval.');
        }

        // If the message is coming from PhorumMail and doesn't have a thread id,
        // we have some work to do...
        if ($PhorumMail && ($thread==0)) {
            // We will try to match a message to its parent using the Subject: field.
            // The basic idea is to remove any "RE: " and search for the result.
            // Things to watch out for:
            //   1.  Occasionally I have seen a space inserted before the "RE: ".
            //   2.  Some list servers will rewrite the subject so that the list
            //       tag (in brackets - []) appears at the beginning of the
            //       subject - BEFORE any "RE: " that appears.
            //   3.  Some people (Germans?) use "AW" instead of "RE".
            //   4.  Some clients will insert a reply level (as in "Re[2]: ").
            eregi('^[[:space:]]*(\[[^]]+\][[:space:]]+)?((re|aw)(\[[[:digit:]]+\])?:[[:space:]]+)*(.+)$', $subject, $threadsubj);
            if (empty($threadsubj[2])) {
                // Sometimes people will start a new thread by replying to an old
                // message.  In this case, there may be an In-Reply-To: header but we
                // need to ignore it.  With no 'Re: ' in the subject, this is probably
                // meant to be a new thread unless the subject is identical to the
                // original, so we just check for an identical subject.
                $sSQL = "Select min(id) as id from $ForumTableName where subject = '$subject'";
            } elseif(!empty($inreplyto)) {
                // If there is a In-Reply-To: header, we search for the message ID...
                $sSQL = "Select id from $ForumTableName where msgid='$inreplyto'";
            } else {
                // ...otherwise, we try to match the subject.
                // (We don't want to get too aggressive with wildcards, since
                //  unrelated messages might have very similar subjects.)
                $sSQL = "Select max(id) as id from $ForumTableName where subject = '";
                $sSQL .= empty($threadsubj[1]) ? '' : $threadsubj[1];
                $sSQL .= empty($threadsubj[5]) ? '' : $threadsubj[5];
                $sSQL .= "' or subject = '$subject'";
            }
            $q->query($DB, $sSQL);
            if($q->numrows()>0){
                $row=$q->getrow();
                $parent=$row['id'];
                $sql="Select thread from $ForumTableName where id=$parent";
                $q->query($DB, $sql);
                $row=$q->getrow();
                $thread=$row['thread'];
            } else {
                $parent=0;
                $thread=0;
            }
        }

        if($thread==0){
            $thread=$id;
        }

        $sSQL = "Insert Into $ForumTableName"."_bodies (id, body, thread) values ($id, '$body', $thread)";
        $q->query($DB, $sSQL);
        if($err=$q->error()){
            echo($err."<br />$sSQL");
        }

        if($DB->type=='mysql'){
            $id=$DB->lastid();
            if($thread==0) {
                $thread=$id;
                $sSQL = "Update $ForumTableName"."_bodies SET thread = id WHERE id = $id";
                $q->query($DB, $sSQL);
                if($err=$q->error()){
                    echo($err."<br />$sSQL");
                }
            }
        }

        if(isset($image)){
            if($image!="none"){
                $is_image=true;
            }
        }

        // if this is a moderator, approve it.
        if($phorum_user['moderator']){
                $approved='Y';
                $email_mod=false;
        } else {
                switch($ForumModeration){
                    case 'a':
                        $email_mod=true;
                        $approved='N';
                        break;
                    case 'r':
                        $email_mod=true;
                        $approved='Y';
                        break;
                    default:
                        $email_mod=false;
                        $approved='Y';
                        break;
                }
        }


        $userid = (isset($phorum_user['id'])) ? $phorum_user['id'] : 0;

        $sSQL = "Insert Into $ForumTableName (id, author, userid, email, datestamp, subject, host, thread, parent, email_reply, approved, msgid) values ('$id', '$author', '$userid', '$email', '$datestamp', '$subject', '$host', '$thread', '$parent', '$email_reply', '$approved', '$msgid')";
        $q->query($DB, $sSQL);
        if($err=$q->error()){
            echo($err."<br />$sSQL");
        }

        $NOW=time();
        $sSQL = "UPDATE $ForumTableName SET modifystamp = $NOW WHERE thread = $thread";
        $q->query($DB, $sSQL);
        if($err=$q->error()){
            echo($err."<br />$sSQL");
        }

        if($email_mod==true){
            $ebody ="Subject: $plain_subject\n";
            $ebody.="Author: $plain_author\n";
            $ebody.="Message: $forum_url/$read_page.$ext?f=$num&i=$id&t=$thread&admview=1\n\n";
            $ebody.=wordwrap($plain_body)."\n\n";
            if($ForumModeration=='a'){
                $ebody.="To approve this message use this URL:\n";
                $ebody.="$admin_url?page=easyadmin&action=moderate&approved=$approved&id=$id&num=$num&mythread=$thread\n\n";
            }
            $ebody.="To delete this message use this URL:\n";
            $ebody.="$admin_url?page=easyadmin&action=del&type=quick&id=$id&num=$num&thread=$thread\n\n";
            $ebody.="To edit this message use this URL:\n";
            $ebody.="$admin_url?page=edit&srcpage=easyadmin&id=$id&num=$num&mythread=$thread\n\n";

            $SQL="Select b.email as email from ".$PHORUM['mod_table']." as a,".$PHORUM['auth_table']." as b where a.user_id=b.id and a.forum_id=$num";
            $q->query($DB, $SQL);
            while($row=$q->getrow()) {
                  mail($row['email'], "Moderate for $ForumName at $SERVER_name Message: $id.", stripslashes($ebody), "From: Phorum <".$row['email'].">\nReturn-Path: <".$row['email'].">\nX-Phorum-$PhorumMailCode-Version: Phorum $phorumver");
            }

        }

        return $id;
    }


    // Post a message to email.
    function post_to_email()
    {
        global $q, $DB, $ForumModeration, $ForumEmailReturnList;
        global $ForumEmailList, $ForumTableName, $ForumName, $PhorumMailCode, $PhorumMail;
        global $email, $thread, $parent, $plain_subject, $plain_body, $plain_author;
        global $forum_url, $read_page, $ext, $num, $id, $phorumver, $msgid, $PHORUM;

        if(is_email($email)){
            $from_email=$email;
        } else {
            $from_email=$PHORUM['DefaultEmail'];
        }
        if(is_email($ForumEmailReturnList)){
            $return=$ForumEmailReturnList;
        } else {
            $return=$from_email;
        }
        $replies='';
        if($thread!=0){
            $sSQL = "Select distinct email from $ForumTableName where thread=$thread and email_reply='Y' and email<>'$email'";
            $q->query($DB, $sSQL);
            if($q->numrows()>0){
                while($row=$q->getrow()){
                    $replies.=trim($row["email"]).',';
                }
                $replies=substr($replies, 0, strlen($replies)-1);
            }
        }

        // If the message is going to a mailing list, it hasn't gone into the
        // database yet, so there is no point in trying to build a link to it.
        // On the other hand, if it is coming from PhorumMail, then PhorumMail
        // has already put it in the database.
        // We can check whether it is in the database by $id.
        $ebody = '';
        if ($id) {
            $ebody.="This message was sent from: $ForumName.\n";
            $ebody.="<$forum_url/$read_page.$ext?f=$num&i=$id&t=$thread> \n";
            $ebody.="----------------------------------------------------------------\n\n";
        }
        $ebody.=wordwrap($plain_body)."\n\n";
        $ebody.="----------------------------------------------------------------\n";
        $ebody.="Sent using Phorum software version $phorumver <http://phorum.org> ";
        $headers="Message-ID: $msgid" .
                 "\nX-Phorum-$PhorumMailCode-Version: Phorum $phorumver" .
                 "\nX-Phorum-$PhorumMailCode-Forum: $ForumName" .
                 "\nX-Phorum-$PhorumMailCode-Thread: $thread" .
                 "\nX-Phorum-$PhorumMailCode-Parent: $parent";

        if(!empty($parent)) {
            $sSQL = "Select msgid from $ForumTableName where id='$parent'";
            $q->query($DB, $sSQL);
            if($q->numrows()>0){
                $row=$q->getrow();
                if (!empty($row['msgid'])) {
                    $headers .= "\nIn-reply-to: " . $row['msgid'];
                }
            }
        }

        // Only send to mailing list if NOT coming from PhorumMail!
        if(!$PhorumMail && is_email($ForumEmailList) && is_email($return)){
            $listheaders=$headers."\nFrom: $return\nReturn-Path: <$return>\nReply-To: $return";
            mail("$ForumName <$ForumEmailList>", $plain_subject, $ebody, $listheaders);
        }
        if($replies){
            $regheaders=$headers."\nFrom: $plain_author <$from_email>\nReturn-Path: <$from_email>\nReply-To: $from_email\nBCC: $replies";
            mail('', "$plain_subject [$num:$thread:$id]", $ebody, $regheaders);
        }

    }


    function check_attachments ($files)
    {
        global $PHORUM;

        $IsError="";

        foreach($files as $field=>$file){

            if(is_uploaded_file($file["tmp_name"])){

                $min_size=1024*min((int)$PHORUM['ForumUploadSize'], (int)$PHORUM['AttachmentSizeLimit']);
                $ext=strtoupper(substr(strrchr($file['name'], '.'), 1));

                $typeok=false;
                foreach(explode(';', $PHORUM['ForumUploadTypes']) as $type){
                    if(strtoupper(trim($type))==$ext){
                        $typeok=true;
                        break;
                    }
                }

                if(!empty($PHORUM['ForumUploadTypes']) && !$typeok){

                    $IsError=$GLOBALS['lInvalidType'].strtoupper(str_replace(';', ' ', $PHORUM['ForumUploadTypes']));

                } elseif($file["size"]>$min_size) {

                    $IsError=$GLOBALS['lInvalidSize1'].$file['name']."<br />".$GLOBALS['lInvalidSize2'].(string)min($PHORUM['ForumUploadSize'], $PHORUM['AttachmentSizeLimit']).'k';

                }

            } elseif($file['tmp_name']!='none' && !empty($file['tmp_name'])) {
                echo 'Spoofing attempt.  Posting halted.';
                exit();
            }
        }

        return $IsError;
    }


    function save_attachments ($files, &$attach_ids, $message_id=0)
    {
        global $PHORUM, $DB, $q;

        foreach($files as $attachment){

            if($attachment['tmp_name']!="none" && !empty($attachment['tmp_name'])){
                $id=$DB->nextid("$PHORUM[ForumTableName]_attachments");
                if($id==0 && $DB->type!='mysql'){
                    $Err='Could not get an id for the new attachment.';
                } else {
                    $SQL="Insert into $PHORUM[ForumTableName]_attachments (id, message_id, filename) values($id, $message_id, '$attachment[name]')";
                    $q->query($DB, $SQL);
                    $err=$q->error();
                    if($err==''){
                        if($DB->type=='mysql'){
                            $id=$DB->lastid();
                        }

                        $attach_ids[]=$id;
                        $new_name = "$PHORUM[AttachmentDir]/$PHORUM[ForumTableName]/$id".strtolower(strrchr($attachment["name"], '.'));
                        if(move_uploaded_file($attachment["tmp_name"], $new_name)){
                            chmod($new_name, 0666);
                        } else {
                            echo "Can't save upload file. $attachment[tmp_name] -> $new_name";
                        }
                    } else {
                        $Err="Error adding attachment.  DB said: $err";
                    }
                }

                if(isset($Err)){
                    echo $Err;
                    return $Err;
                }
            }
        }
    }

    function mail_edit_to_moderators()
    {
        //this function mails the contents of a user-edited post to the moderators
        global $q, $DB, $ForumTableName, $ForumModeration, $phorum_user;
        global $ForumName, $PhorumMail, $PhorumMailCode;  // although I'm not sure where $PhorumMailCode comes from...
        global $phorumver, $SERVER_name;
        global $subject, $author, $body, $email;
        global $email_reply, $msgid;
        global $admin_url, $admin_page, $forum_url, $read_page, $ext, $num, $id, $PHORUM, $t;
        global $ForumEmailReturnList;

        // first lets see if we need to mail details of this edit to anyone
        if($phorum_user['moderator']){
            $email_mod=false;
        } else {
            switch($ForumModeration){
                case 'a':
                    $email_mod=true;
                    break;
                case 'r':
                    $email_mod=true;
                    break;
                default:
                    $email_mod=false;
                    $approved='Y';
                    break;
            }
        }
        if($email_mod==true){
            // get return info for our mail headers
            if(is_email($email)){
                $from_email=stripslashes($email);
            } else {
                $from_email=$PHORUM['DefaultEmail'];
            }
            if(is_email($ForumEmailReturnList)){
                $return=$ForumEmailReturnList;
            } else {
                $return=$from_email;
            }

            // we are using plain text for our mail so make these variables text-friendly
            $plain_author=stripslashes($author);
            $plain_subject=stripslashes(strip_tags($subject));
            $plain_body=wordwrap(stripslashes(strip_tags($body)));
            $thread=$t;

            // build our e-mail headers
            $headers='Message-ID: $msgid' .
                "\nX-Phorum-$PhorumMailCode-Version: Phorum $phorumver" .
                "\nX-Phorum-$PhorumMailCode-Forum: $ForumName" .
                "\nX-Phorum-$PhorumMailCode-Thread: $thread" .
                "\nReturn-Path: <$from_email>" .
                "\nReply-To: $from_email" .
                "\nFrom: Phorum (user: $plain_author) <$from_email>";  // this is what moderators will see in the 'From:' field of our mail

            // now make e-mail body
            $ebody ="A user-edit has been made in forum: $ForumName.\r\n";
            $ebody .="Subject: $plain_subject\r\n";
            $ebody.="Author: $plain_author <$from_email>\r\n";
            // if someone is editing a mail it must already be in the database so we will add a link to it
            $ebody.="Message: $forum_url/$read_page.$ext?f=$num&i=$id&t=$thread\n\n";
            $ebody.=$plain_body . "\n\n"; // append the user's edits

            $ebody.="To delete this message use this URL:\n";
            $ebody.="$admin_url?page=easyadmin&action=del&type=quick&id=$id&num=$num&thread=$thread\n\n";
            $ebody.="To edit this message use this URL:\n";
            $ebody.="$admin_url?page=edit&srcpage=easyadmin&id=$id&num=$num&mythread=$thread\n\n";

            // loop through moderators for this forum
            $SQL="Select b.email as email from ".$PHORUM['mod_table']." as a,".$PHORUM['auth_table']." as b where a.user_id=b.id and a.forum_id=$num";
            $q->query($DB, $SQL);
            while($row=$q->getrow()) {
                $recipient = $row['email']; // moderator's address to send mail to
                if (is_email($recipient)){
                    mail($recipient, "User-edit for $ForumName ($SERVER_name Message: $id)", stripslashes($ebody), $headers);
                }
            }
        } // end if moderation requires that we e-mail details of this user-edit
    }


    function phorum_strip_tags($string)
    {
        while(preg_match("|</*[a-z][^>]*>|i", $string)){
            $string = preg_replace("|</*[a-z][^>]*>|i", "", $string);
        }

        return $string;
    }

?>
