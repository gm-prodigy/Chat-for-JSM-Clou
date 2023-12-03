//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

/**
 * @mixin \IPS\Theme\class_core_front_global
 */
class jsmcloudchat_hook_jsmcloudchatwidget extends _HOOK_CLASS_
{

    /* !Hook Data - DO NOT REMOVE */
public static function hookData() {
 return array_merge_recursive( array (
  'globalTemplate' => 
  array (
    0 => 
    array (
      'selector' => '#ipsLayout_mainArea',
      'type' => 'add_inside_end',
      'content' => '{template="globalWidget" group="hooks" location="front" app="jsmcloudchat" params="\IPS\Member::loggedIn()"}',
    ),
  ),
), parent::hookData() );
}
/* End Hook Data */


}
