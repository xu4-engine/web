<?php

  if ( defined( '_COMMON_PHP' ) ) return;
  define('_COMMON_PHP', 1 );

//////////////////////////////////////////////////////////////////////////////////////////
// Security stuff
//////////////////////////////////////////////////////////////////////////////////////////

  // handle configs that have register_globals turned off.
  // we use $PHP_SELF as the test since it should always be there.
  // We might need to consider not using globals soon.
  if(!isset($PHP_SELF)) {
     include ("./include/register_globals.php");
  }

  // other than body and subject, there is no reason for a script tag
  // to exist in any user input field in Phorum.  Anyone doing so is
  // obviously trying to attack the server.  body and subject are handled
  // in post.php.
  phorum_check_xss();

  // Check that this file is not loaded directly.
  if(basename(__FILE__)==basename($PHP_SELF)) exit();

//////////////////////////////////////////////////////////////////////////////////////////
// These variables may be altered as needed:
//////////////////////////////////////////////////////////////////////////////////////////

  // location where settings are stored
  $settings_dir='admin-qgc/settings';  // no ending slash

  // If you have dynamic vars for GET and POST to pass on:
  // AddGetPostVars("dummy", $dummy);



//////////////////////////////////////////////////////////////////////////////////////////
// End of normally user-defined variables
//////////////////////////////////////////////////////////////////////////////////////////


  // See the FAQ on what this does.  Normally not important.
  // **TODO: make this a define and figure out where we really need it.
  $cutoff = 800;

  $phorumver='3.4.4';

  // all available db-files
  $dbtypes = array(
           'mysql' => "MySQL",
           'postgresql65' => "PostgreSQL 6.5 or newer",
           'postgresql' => "PostgreSQL (older than 6.5)"
           );

  // *** Some Defines ***

  // security
  define('SEC_NONE', 0);
  define('SEC_OPTIONAL', 1);
  define('SEC_POST', 2);
  define('SEC_ALL', 3);

  // signature
  define('PHORUM_SIG_MARKER', '[%sig%]');

  // **TODO: move all this into the admin
  settype($GetVars, 'string');
  settype($PostVars, 'string');

  function AddGetPostVars($var, $value){
    global $GetVars;
    global $PostVars;
    $enc_var=urlencode($var);
    $enc_value=urlencode($value);
    $GetVars.='&';
    $GetVars.="$enc_var=$enc_value";
    $PostVars.="<input type=\"hidden\" name=\"$var\" value=\"$value\">\n";
  }

  function AddPostVar($var, $value){
    AddGetPostVars($var, $value);
  }

  function AddGetVar($var, $value){
    AddGetPostVars($var, $value);
  }

  // **TODO: switch to get_html_translation_table
  function undo_htmlspecialchars($string){

    $string = str_replace('&amp;', '&', $string);
    $string = str_replace('&quot;', '"', $string);
    $string = str_replace('&lt;', '<', $string);
    $string = str_replace('&gt;', '>', $string);

    return $string;
  }

  function htmlencode($string){
    $ret_string='';
    $len=strlen($string);
    for($x=0;$x<$len;$x++){
      $ord=ord($string[$x]);
      $ret_string .= "&#$ord;";
    }
    return $ret_string;
  }

  function my_nl2br($str){
    return str_replace('><br />', '>', nl2br($str));
  }

  function bgcolor($color){
    return ($color!='') ? " bgcolor=\"".$color."\"" : "";
  }

  // **TODO: replace with a better function that optionally checks the MX record
  function is_email($email){
    $ret=false;
    if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $email)){
      $ret=true;
    }
    return $ret;
  }

  // these two function would be better served as a class.
  function addnav(&$var, $text, $url){
    $var[$text]=$url;
  }

  function getnav($var, $splitter='&nbsp;&nbsp;|&nbsp;&nbsp;', $usefont=true){
    global $default_nav_font_color, $ForumNavFontColor;
    if(isset($ForumNavFontColor)){
      $color=$ForumNavFontColor;
    }
    else{
      $color=$default_nav_font_color;
    }
    if(is_array($var)) {
       $menu=array();
       while(list($text, $url)=each($var)){
         if($usefont) $text="<FONT color='$color' class=\"PhorumNav\">$text</font>";
         $menu[]="<a href=\"$url\">$text</a>";
       }
       $nav=implode($splitter, $menu);
       if($usefont)
         $nav="<FONT color='$color' class=\"PhorumNav\">&nbsp;".$nav."&nbsp;</font>";
       return $nav;
     } else {
       return "";
     }
  }

  function phorum_get_file_name($type)
  {
    global $PHORUM;
    settype($PHORUM['ForumConfigSuffix'], 'string');
    switch($type){
        case 'css':
            $file='phorum.css';
            $custom="phorum_$PHORUM[ForumConfigSuffix].css";
            break;
        case 'header':
            $file="$PHORUM[include]/header.php";
            $custom="$PHORUM[include]/header_$PHORUM[ForumConfigSuffix].php";
            break;
        case 'footer':
            $file="$PHORUM[include]/footer.php";
            $custom="$PHORUM[include]/footer_$PHORUM[ForumConfigSuffix].php";
            break;
    }

    return (file_exists($custom)) ? $custom : $file;
  }

  
  // other than body and subject, there is no reason for a script tag
  // to exist in any user input field in Phorum.  Anyone doing so is
  // obviously trying to attack the server.  body and subject are handled
  // in post.php.
  function phorum_check_xss()
  {
    foreach($GLOBALS["HTTP_GET_VARS"] as $key=>$value){
      if(!is_array($value) && stristr($value, "<script")) exit();
    }
    foreach($GLOBALS["HTTP_POST_VARS"] as $key=>$value){
      if(!is_array($value) && $key!="body" && $key!="subject" && $key!="hide" && stristr($value, "<script")){
        echo "script detected in $key";
        exit();
      }
    }
    foreach($GLOBALS["HTTP_COOKIE_VARS"] as $key=>$value){
      if(!is_array($value) && stristr($value, "<script")) exit();
    }
  }


  // set a sensible error level for including some stuff:
  $old_err_level = error_reporting (E_ERROR | E_WARNING | E_PARSE);

  // go ahead and unset/check these to evade hack attempts.
  unset($phorum_user);
  unset($PHORUM);
  settype($f, 'integer');
  settype($num, 'integer');
  $num = (empty($num)) ? $f : $num;
  $f = (empty($f)) ? $num : $f;

  // include forums.php

  // the most important variables
  $PHORUM['settings']="$settings_dir/forums.php";
  $PHORUM['settings_backup']="$settings_dir/forums.bak.php";

  if(!file_exists($PHORUM['settings'])){
    echo "<html><head><title>Phorum Error</title></head><body>Phorum could not load the settings file ($PHORUM[settings]).<br />If you are just installing Phorum, please go to the admin to complete the install.  Otherwise, see the faq for other reasons you could see this message.</body></html>";
    exit();
  }

  include ($PHORUM['settings']);

  // set some PHORUM vars
  $PHORUM['auth_table']=$PHORUM['main_table'].'_auth';
  $PHORUM['mod_table']=$PHORUM['main_table'].'_moderators';
  $PHORUM['user2group_table']=$PHORUM['main_table'].'_user2group';
  $PHORUM['forum2group_table']=$PHORUM['main_table'].'_forum2group';
  $PHORUM['group_table']=$PHORUM['main_table'].'_groups';

  $PHORUM['settings_dir']=$settings_dir;
  $PHORUM['include']='./include';
  $PHORUM['group_cache']="$settings_dir/cache_user2group.php";
  $PHORUM['moderator_cache']="$settings_dir/cache_moderators.php";

  // **TODO: remove legacy code
  $include_path=$PHORUM['include'];
  $pho_main=$PHORUM['main_table'];

  // include abstraction layer and check if its defined
  if(!defined('PHORUM_ADMIN') && (empty($PHORUM['dbtype']) || !file_exists("./db/$PHORUM[dbtype].php"))){
    echo "<html><head><title>Phorum Error</title></head><body>You didn't setup your database. Go to the admin (default <a href=\"admin/index.php\">admin/index.php</a>) and complete the installation.</body></html>";
    exit();
  }

  if(file_exists("./db/$dbtype.php")){

      include ("./db/$dbtype.php");

      // create database classes
      $DB = new db();

      // check if database is already configured or if we are in the admin
      if ( defined( '_DB_LAYER' ) && $PHORUM['DatabaseName']!=''){
        // this code below has to be this way for some weird reason.  Otherwise\n";
        // connecting on a different port won't work.\n";
        $DB->open($PHORUM['DatabaseName'], implode(':', explode(':', $PHORUM['DatabaseServer'])), $PHORUM['DatabaseUser'], $PHORUM['DatabasePassword']);
      } elseif(!defined('PHORUM_ADMIN')) {
        echo '<html><head><title>Phorum Error</title></head><body>You need to go to the admin and fix your database settings.</body></html>';
        exit();
      }

      //dummy query for generic operations
      $q = new query($DB);
      if(!is_object($q)){
        echo "<html><head><title>Phorum Error</title></head><body>Unknown error creating $q.</body></html>";
        exit();
      }
  } elseif(!defined('PHORUM_ADMIN')) {
    echo '<html><head><title>Phorum Error</title></head><body>You need to go to the admin and fix your database settings.</body></html>';
    exit();
  }


  if(!empty($f)){
    if(file_exists("$PHORUM[settings_dir]/$f.php")){
      include "$PHORUM[settings_dir]/$f.php";
      // changed for user-selected language, done later
      /*
      if($ForumLang!=""){
        include ("./".$ForumLang);
      } else {
        include ("./".$default_lang);
      }
      */
    }
    else{
      header("Location: $forum_url/$forum_page.$ext");
      exit();
    }
  }
  else {
    // changed for user-selected language, done later
    // include ("./".$default_lang);
    include ("$include_path/blankset.php");
  }

  if(!$PHORUM['started'] && !defined('PHORUM_ADMIN')){
    Header("Location: $forum_url/$down_page.$ext");
    exit();
  }

  // including the user-authentication
  include ("$include_path/userlogin.php");

  if(!defined('PHORUM_ADMIN') && $DB->connect_id){
     // check security
    if($ForumFolder==1 || $f==0){
        $SQL="Select max(security) as sec from $pho_main";
        $q->query($DB, $SQL);
        $max_sec=$q->field('sec', 0);
    }

    $phorum_user=phorum_return_user();

    if(isset($phorum_user) && !$phorum_user['moderator']){
        if((isset($ForumSecurity) && $ForumSecurity==SEC_NONE) || (($ForumFolder==1 || $f==0) && $max_sec==0)){
            unset($phorum_user);
        }
    }

    if($ForumSecurity==SEC_ALL && !isset($phorum_user['id'])){
      header('Location: '.phorum_loginurl($REQUEST_URI));
      exit();
    }

    // load plugins
    unset($plugins);
    $plugins = array(
             'read_body'   => array(),
             'read_header' => array(),
             'post_check'  => array(),
             'post_append' => array()
             );

    if(isset($PHORUM['plugins'])){
      $dir = opendir('./plugin/');
      while($plugindirname = readdir($dir)) {
        if($plugindirname[0] != '.' && @file_exists("./plugin/$plugindirname/plugin.php") && !empty($PHORUM['plugins'][$plugindirname])){
          include("./plugin/$plugindirname/plugin.php");
        }
      }
    }
  }
  // user-selected language ... only if allowed
  if($ForumAllowLang && isset($phorum_user['lang']) && $phorum_user['lang']!='') 
         $ForumLang = $phorum_user['lang'];

  if($ForumLang!=""){
    // user has specified a language
    if (!include ("./$ForumLang")) // include will return false if the lang file is missing
    {
        $ForumLang = $default_lang;
        include ("./$ForumLang"); // lets try again!
    }
  } else {
    include ("./$default_lang");
  }

  // set the error level back to what it was.
  error_reporting ($old_err_level);

?>
