<?php

  if(!empty($HTTP_SERVER_VARS["QUERY_STRING"])){

      $info=$HTTP_SERVER_VARS["QUERY_STRING"];

  } else {

      $info=$HTTP_SERVER_VARS["PATH_INFO"];

  } 

  $file=basename($info);

  $args=explode(",", basename(dirname($info)));

  if(count($args)==3){
    // old style urls
    $f=(int)$args[0];
    $fileid=(int)$args[2];
  } elseif(count($args)==2) {
    // new urls
    $f=(int)$args[0];
    $fileid=(int)$args[1];
  } else {
    exit();
  }
  
  include "./common.php";

  if(empty($info) || empty($AttachmentDir)){
    Header("Location: $forum_url/$forum_page.$ext?$GetVars");
    echo "Location: $forum_page.$ext?$GetVars";
    exit();
  }

  $filename="$AttachmentDir/$ForumTableName/$fileid".strtolower(strrchr($file, "."));

  if(!file_exists($filename)){    
    echo "File not found.";
    exit();
  }

  // Mime Types for Attachments

  $mime_types["default"]="text/plain";
  $mime_types["pdf"]="application/pdf";
  $mime_types["doc"]="application/msword";
  $mime_types["xls"]="application/vnd.ms-excel";
  $mime_types["gif"]="image/gif";
  $mime_types["png"]="image/png";
  $mime_types["jpg"]="image/jpeg";
  $mime_types["jpeg"]="image/jpeg";
  $mime_types["jpe"]="image/jpeg";
  $mime_types["tiff"]="image/tiff";
  $mime_types["tif"]="image/tiff";
  $mime_types["xml"]="text/xml";
  $mime_types["mpeg"]="video/mpeg";
  $mime_types["mpg"]="video/mpeg";
  $mime_types["mpe"]="video/mpeg";
  $mime_types["qt"]="video/quicktime";
  $mime_types["mov"]="video/quicktime";
  $mime_types["avi"]="video/x-msvideo";
  $mime_types["gz"]="application/x-gzip";
  $mime_types["tgz"]="application/x-gzip";
  $mime_types["zip"]="application/zip";
  $mime_types["tar"]="application/x-tar";
  $mime_types["exe"]="application/octet-stream";

  $type=strtolower(substr($file, strrpos($file, ".")+1));
  if(isset($mime_types[$type])){
    $mime=$mime_types[$type];
  }
  else{
    $mime=$mime_types["default"];
  }

  header("Content-Type: $mime");
  header("Content-Disposition: filename=\"$file\"");

  if ( strstr($mime, "text") ){
     $file_handle = fopen("$filename","r");
  }
  else{
     $file_handle = fopen("$filename","rb");
  }

  fpassthru($file_handle);

  exit();
?>
