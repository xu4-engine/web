<?php
  // xu4 Discussion forum
  $PHORUM['ForumId']=1;
  $PHORUM['ForumActive']='1';
  $PHORUM['ForumName']='xu4 Discussion';
  $PHORUM['ForumDescription']='xu4 General Discussion forum';
  $PHORUM['ForumConfigSuffix']='';
  $PHORUM['ForumFolder']='0';
  $PHORUM['ForumParent']='0';
  $PHORUM['ForumLang']='lang/english.php';
  $PHORUM['ForumAllowLang']='0';
  $PHORUM['ForumAllowEdit']='1';
  $PHORUM['ForumDisplay']='30';
  $PHORUM['ForumTableName']='xu4discuss';
  $PHORUM['ForumModeration']='n';
  $PHORUM['ForumSecurity']='1';
  $PHORUM['ForumEmailList']='';
  $PHORUM['ForumEmailReturnList']='';
  $PHORUM['ForumEmailTag']='';
  $PHORUM['ForumCheckDup']='1';
  $PHORUM['ForumMultiLevel']='2';
  $PHORUM['ForumCollapse']='1';
  $PHORUM['ForumFlat']='1';
  $PHORUM['ForumAllowHTML']='1';
  $PHORUM['ForumAllowUploads']='N';
  $PHORUM['ForumUploadTypes']='';
  $PHORUM['ForumUploadSize']='0';
  $PHORUM['ForumMaxUploads']='0';
  $PHORUM['ForumTableBodyColor2']='#e0ebf5';
  $PHORUM['ForumTableBodyFontColor2']=$PHORUM['default_table_body_font_color_2'];
  $PHORUM['ForumShowIP']='1';
  $PHORUM['ForumAllowEMailNotify']='0';
  $PHORUM['ForumPermissions']='0';
  $PHORUM['ForumRequiredLevel']='0';
  $PHORUM['ForumCheckDisp']='0';
  $PHORUM['ForumBodyColor']=$PHORUM['default_body_color'];
  $PHORUM['ForumBodyLinkColor']=$PHORUM['default_body_link_color'];
  $PHORUM['ForumBodyALinkColor']=$PHORUM['default_body_alink_color'];
  $PHORUM['ForumBodyVLinkColor']=$PHORUM['default_body_vlink_color'];
  $PHORUM['ForumTableWidth']=$PHORUM['default_table_width'];
  $PHORUM['ForumTableHeaderColor']=$PHORUM['default_table_header_color'];
  $PHORUM['ForumTableHeaderFontColor']='#ff6600';
  $PHORUM['ForumTableBodyColor1']='#d0dbe5';
  $PHORUM['ForumTableBodyFontColor1']=$PHORUM['default_table_body_font_color_1'];
  $PHORUM['ForumNavColor']='#e0ebf5';
  $PHORUM['ForumNavFontColor']=$PHORUM['default_nav_font_color'];

  // expand the array into vars for legacy code.
  reset($PHORUM);  while(list($key, $value)=each($PHORUM)){
    $$key=$PHORUM[$key];
  }

?>