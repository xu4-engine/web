<?php

if (!defined("_COMMON_PHP")) return;

if(isset($_SERVER)){

    $arrays=array(
                $_SERVER,
                $_ENV,
                $_GET,
                $_POST,
                $_COOKIE
    );

} else {

    $arrays=array(
                $HTTP_SERVER_VARS,
                $HTTP_ENV_VARS,
                $HTTP_GET_VARS,
                $HTTP_POST_VARS,
                $HTTP_COOKIE_VARS
    );

}

foreach($arrays as $array){

    foreach($array as $var=>$val){
		if(!isset($GLOBALS[$var])){
			$$var = $val;
		}
	}
}

?>