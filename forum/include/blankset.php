<?php

    if ( !defined( "_COMMON_PHP" ) ) return;

// Blank out potentially dangerous per-forum values

$aryUnset = array(
              'ForumId',
              'ForumActive',
              'ForumName',
              'ForumDescription',
              'ForumConfigSuffix',
              'ForumFolder',
              'ForumParent',
              'ForumLang',
              'ForumDisplay',
              'ForumTableName',
              'ForumModeration',
              'ForumModPass',
              'ForumEmailList',
              'ForumEmailReturnList',
              'ForumEmailTag',
              'ForumCheckDup',
              'ForumMultiLevel',
              'ForumCollapse',
              'ForumFlat',
              'ForumStaffHost',
              'ForumAllowHTML',
              'ForumAllowUploads',
              'ForumTableBodyColor2',
              'ForumTableBodyFontColor2',
              'ForumTableWidth',
              'ForumNavColor',
              'ForumNavFontColor',
              'ForumTableHeaderColor',
              'ForumTableHeaderFontColor',
              'ForumTableBodyColor1',
              'ForumTableBodyFontColor1'
);

reset($aryUnset);
while (list($key, $value) = each($aryUnset)) {
  if(isset($$value)) {
    unset($$value);
  }
}

settype($ForumConfigSuffix, "string");
settype($ForumLang, "string");
settype($ForumName, "string");
settype($ForumParent, "integer");

?>
