<?php

    if ( !defined( "_COMMON_PHP" ) ) return;

    // tell phorum that we use the phorum-scheme
    define("PHORUM_LOGIN",1);

// login-abstraction
// use and replace this file if you want another userlogin-mechanism

////////////////////////////////////////////////////////////
// start user functions

  // returns the $phorum_user-array, called from common.php
  // this function is the most important, it calls many of the following

  function phorum_return_user() {
    global $phorum_uriauth,$HTTP_COOKIE_VARS,$num;
    $phorum_user=phorum_check_session();

    if(isset($phorum_user["id"])){
      if(!phorum_check_permissions($phorum_user))
        unset($phorum_user);

      // check if he is a moderator
      $phorum_user["moderator"] = phorum_check_moderator($phorum_user[id]);

      // add get/post var if the cookie is not set.
      if(!isset($HTTP_COOKIE_VARS["phorum_cookieauth"]) && isset($phorum_user)){
        AddGetPostVars("phorum_uriauth", urldecode($phorum_uriauth));
      }

    } else {

        // login failed!
        if(isset($phorum_uriauth))  unset($phorum_uriauth);
    
        if(isset($phorum_cookieauth)){
            unset($phorum_cookieauth);
            SetCookie("phorum_cookieauth",'');
        }
    }

    return $phorum_user;
  }

  // check permissions, groups and levels, just as needed by the settings
  function phorum_check_permissions($phorum_user) {
    global $PHORUM;
    $userid=$phorum_user["id"];
    /*  Permission-Options
        0 = no groups or levels
        1 = groups only
        2 = levels only
        3 = groups OR levels (either group or level matches)
    */
    if($PHORUM['ForumPermissions']==1) {
        return phorum_check_group($userid);
    } elseif($PHORUM['ForumPermissions']==2) {
        return phorum_check_levels($phorum_user);
    } elseif($PHORUM['ForumPermissions']==3) {
        return (phorum_check_levels($phorum_user) || phorum_check_group($userid));
    } elseif($PHORUM['ForumPermissions']==4) {
        return (phorum_check_levels($phorum_user) && phorum_check_group($userid));
    }
    return true;
  }

  function phorum_check_levels($phorum_user) {
    global $PHORUM;
    if($PHORUM['ForumRequiredLevel'] <= $phorum_user['permission_level'] || $PHORUM['ForumRequiredLevel'] <= $phorum_user['max_group_permission_level']) {
        return true;
    } else {
        return false;
    }
  }

  // checks if the current user is in a group, allowed to access this forum
  function phorum_check_group($userid) {
    global $PHORUM;

    // first check if the forum is restricted at all
        if(!isset($PHORUM["ForumAllowedGroup"]) || count($PHORUM["ForumAllowedGroup"])==0) {
        return true;
        }

    //check if the cache_file exists, otherwise create it
    if(!file_exists($PHORUM["group_cache"])) {
        phorum_generate_group_cache();
    }
    // include the cache_file
    include $PHORUM["group_cache"];

    // check if the user is in a group which is allowed to access
    foreach ($PHORUM["ForumAllowedGroup"] as $key => $group) {
        if(isset($group2user[$group][$userid]))
            return true;
    }
    return false;
  }

  // generates a cache-file, needed to determine the user->group xref
  function phorum_generate_group_cache() {
        global $q, $DB,$PHORUM;

    $SQL="SELECT * FROM ".$PHORUM["main_table"]."_user2group ORDER BY group_id";
    $q->query($DB,$SQL);
    $data="<?php\n";
    $data.="// group2user-cache-file\n";
        $rec=$q->getrow();
    while(is_array($rec)) {
        $data.="\$group2user[".$rec["group_id"]."][".$rec["user_id"]."]=1;\n";
                $rec=$q->getrow();
    }
    $data.="?>";
    $fd=fopen($PHORUM["group_cache"],"w");
    fwrite ($fd, $data);
    fclose($fd);

    return true;
  }

  // generates a cache-file for the moderator xref
  function phorum_generate_moderator_cache() {
        global $q, $DB,$PHORUM;

        $SQL="SELECT * FROM ".$PHORUM["mod_table"]." ORDER BY forum_id";
    $q->query($DB,$SQL);
        $data="<?php\n";
        $data.="// moderator-cache-file\n";
    $rec=$q->getrow();
        while(is_array($rec)) {
                $data.="\$moderator2forum[".$rec["forum_id"]."][".$rec["user_id"]."]=1;\n";
        $rec=$q->getrow();
        }
        $data.="?>";
        $fd=fopen($PHORUM["moderator_cache"],"w");
        fwrite ($fd, $data);
        fclose($fd);

        return true;
  }

  function debug_out($arr) {
    print "DEBUG: <pre>";
    print_r($arr);
    print "</pre><br />";
  }

  // takes the session-id and returns the needed cookie or uri-vars
  // to keep the user logged in
  function phorum_login_user($sessid) {
    global $q, $DB, $PHORUM;
    // **TODO: We should make this time configurable
    if(isset($GLOBALS['remember_login']) && $GLOBALS['remember_login']==1) {
      SetCookie("phorum_cookieauth", "$sessid", time()+86400*365);
    }
      // generate the new uriauth
      list($user, $pass)=explode(":", $sessid);
      $first=$pass;
      $second=md5($sessid.microtime());
      $combined=md5($first.$second);      
      $q->query($DB,"UPDATE ".$PHORUM['auth_table']." SET combined_token='$combined' WHERE username='$user' AND password='$pass'");
      AddGetPostVars("phorum_uriauth", $user.":".$second);
  }

  // check user and pass against the database, called from login
  function phorum_check_login($user, $pass)
  {
    global $q, $DB, $PHORUM, $PhorumLoginErr;

    if(!get_magic_quotes_gpc()) $user=addslashes($user);

    $md5_pass=md5($pass);

    // check for original pass
    $SQL="Select id from $PHORUM[auth_table] where username='$user' and password='$md5_pass'";

    $q->query($DB, $SQL);

    $id = ($q->numrows()) ? $q->field("id", 0) : 0;


    if($id==0){

        // check for tmp-pass
        $SQL="Select id from $PHORUM[auth_table] where username='$user' and password_tmp='$md5_pass'";

        $q->query($DB, $SQL);

        $id = ($q->numrows()) ? $q->field("id", 0) : 0;

        if($id!=0){

           // update original pass with tmp-pass
           $SQL="UPDATE $PHORUM[auth_table] set password='$md5_pass',password_tmp='' where username='$user' and password_tmp='$md5_pass'";

           $q->query($DB, $SQL);

           $PhorumLoginErr='NewPass';

        } elseif(function_exists("crypt")) {
            // check for old crypt system
            $crypt_pass=crypt($pass, substr($pass, 0, CRYPT_SALT_LENGTH));

            $SQL="Select id from $PHORUM[auth_table] where username='$user' and password='$crypt_pass'";

            $q->query($DB, $SQL);

            $id = ($q->numrows()) ? $q->field("id", 0) : 0;

            if($id!=0){
                // update password to md5.
                $SQL="Update $PHORUM[auth_table] set password='$md5_pass' where username='$user'";
                $q->query($DB, $SQL);
            }

        }

    }

    return $id;
  }

  // checks if the current user is a moderator
  function phorum_check_moderator($userid) {
    global $DB,$q,$PHORUM, $f;

    //check if the cache_file exists, otherwise create it
    if(!file_exists($PHORUM["moderator_cache"])) {
             phorum_generate_moderator_cache();
    }
    // include the cache_file
    include $PHORUM["moderator_cache"];

    // check if the user is moderator for THIS forum
    if(isset($moderator2forum[$f][$userid]) || isset($moderator2forum[0][$userid]))
               return true;

    return false;
  }

  // generates the session-id
  function phorum_session_id($username, $password)
  {
    return "$username:".md5($password);
  }

  // checks the session for the currently logged in user
  function phorum_check_session($admin_session='')
  {
      global $q, $DB, $PHORUM, $HTTP_COOKIE_VARS, $phorum_uriauth;
      $phorum_uriauth=urldecode($phorum_uriauth);
      if(!empty($admin_session)) {
        list($user, $pass)=explode(":", $admin_session);
        $user=addslashes($user);
      } elseif(isset($HTTP_COOKIE_VARS['phorum_cookieauth'])) {
        // part for cookieauth
      	list($user, $pass)=explode(":", $HTTP_COOKIE_VARS['phorum_cookieauth']);
      	$user=addslashes($user);
      } elseif(isset($phorum_uriauth)) {
        // part for uriauth
        list($user, $second)=explode(":",$phorum_uriauth);
	
	if(!empty($user) && empty($second))
	    list($user, $second)=explode("%3A",$phorum_uriauth);
	    
	$SQL="Select password,combined_token from ".$PHORUM['auth_table']." where username='$user'";
	$q->query($DB, $SQL);	
        $r=$q->getrow();
	if(md5($r['password'].$second)==$r['combined_token']) {
	    $pass=$r['password'];
	    $phorum_uriauth="$user:$second";
	}
      }
      $SQL="Select * from $PHORUM[auth_table] where username='$user' and password='$pass'";
      $q->query($DB, $SQL);      
      $phorum_user=$q->getrow();
      return $phorum_user;
  }

  // returns the loginurl
  function phorum_loginurl($requesturi) {
    global $forum_url,$ext;
    $uri="$forum_url/login.$ext?target=".urlencode($requesturi);
    return $uri;
  }

  // returns some userdata (id, name, email, signature) for given userids
  function phorum_get_users($ids) {
    global $DB,$q,$PHORUM;
     // Get the user info.  I curse PG for not having Left Joins.
     $SQL="select id, username, email, signature from ".$PHORUM["auth_table"]." where id in (".implode(",", $ids).")";
     $q->query($DB, $SQL);
     $rec=$q->getrow();
     While(is_array($rec)){
        $users[$rec["id"]]=$rec;
    $rec=$q->getrow();
     }
     return $users;
  }

   // returns array of moderator-ids for given userids
  function phorum_get_modstatus($ids) {
    global $DB,$q,$PHORUM, $f;
    $SQL="select user_id from ".$PHORUM["mod_table"]." where forum_id=$f or forum_id=0 and user_id in (".implode(",", $ids).")";
    $q->query($DB, $SQL);
    $rec=$q->getrow();
    $moderators=array();
    While(is_array($rec)){
        $moderators[$rec["user_id"]]=true;
        $rec=$q->getrow();
    }
    return $moderators;
  }

    // Check author against registration.
    // Returns true if ok, false if bad.
    function check_register($author)
    {
        global $phorum_user, $q, $DB, $PHORUM, $ForumSecurity;
        $ret=true;
        if($ForumSecurity!=SEC_NONE && !isset($phorum_user['id'])){
            $SQL="Select id from ".$PHORUM["auth_table"]." where username = '$author'";
            $q->query($DB, $SQL);
            if($q->numrows()>0){
                $ret=false;
            }
        }
        return $ret;
    }

    // log-out the user
    // no returns, just logging out ;-)
    function phorum_logout() {
        global $PHORUM,$phorum_user,$phorum_cookieauth,$phorum_uriauth,$q,$DB;

        if(!empty($phorum_uriauth)) {
            $SQL="update ".$PHORUM['auth_table']." set combined_token='' where id=".$phorum_user['id'];
    	    $q->query($DB, $SQL);
            unset($phorum_uriauth);
	} elseif(isset($phorum_cookieauth)) {
            SetCookie("phorum_cookieauth",'');
	    unset($phorum_cookieauth);
	}
    }

    // called if a user ask for his or a new password cause he forgot his pass
    function phorum_forgot_pass($lookup) {
    global $q,$DB,$pho_main,$Error,$lNewPassMailed,$lNewPassError,$lNewPassBody,$DefaultEmail,$lNewPassChange,$lUserName,$lNewPassword,$lPassword;

        if(!get_magic_quotes_gpc($lookup)) $lookup = str_replace("'", "\\'", $lookup);
        $email_lookup=strtolower($lookup);
        $SQL="select username, email from $pho_main"."_auth where username='$lookup' or email='$email_lookup'";
        $q->query($DB, $SQL);
        $rec=$q->getrow();
        if(!empty($rec["username"])){
            $username=$rec["username"];
            $newpass=substr(md5($username.microtime()), 0, 8);
            $crypt_pass=md5($newpass);
            $SQL="update $pho_main"."_auth set password_tmp='$crypt_pass' where username='$rec[username]'";
            $q->query($DB, $SQL);
            mail($rec["email"], $lNewPassword, "$lNewPassBody:\n\n  $lUserName: $rec[username]\n  $lPassword:  $newpass\n\n$lNewPassChange", "From: <$DefaultEmail>");
            $Error=$lNewPassMailed;
        } else {
            $Error=$lNewPassError;
        }
    }

// end user functions
////////////////////////////////////////////////////////////

?>
