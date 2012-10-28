<?php
if(!defined("IN_MYBB"))
{
    die("You Cannot Access This File Directly. Please Make Sure IN_MYBB Is Defined.");
}

function mybbatically_info()
{
	return array(
        "name"  => "MyBBatically",
        "description"=> "Automatically update your board to the latest released MyBB Version",
        "website"        => "",
        "author"        => "Polarbear541 & Vernier",
        "authorsite"    => "",
        "version"        => "1.0",
        "guid"             => "",
        "compatibility" => "16*"
    );
}

/**
*function mybbatically_is_installed()
*{
*	
*}
*
*/

/**
*function mybbatically_install()
*{
*
*}
*/

/**
*function mybbatically_uninstall()
*{
*
*}
*/

function mybbatically_activate()
{
	global $db;

$mybbatically_group = array(
        'gid'    => 'NULL',
        'name'  => 'mybbatically',
        'title'      => 'MyBBatically',
        'description'    => 'Configuration settings for the MyBBatically plugin',
        'disporder'    => "1",
        'isdefault'  => "0",
    );

$mybbatically_setting_1 = array(
        'sid'            => 'NULL',
        'name'        => 'mybbatically_global_switch',
        'title'            => 'Enable MyBBatically?',
        'description'    => 'If you set this option to on, MyBBatically will function on your board.',
        'optionscode'    => 'onoff',
        'value'        => '1',
        'disporder'        => 1,
        'gid'            => intval($gid),
    );

$db->insert_query('settinggroups', $mybbatically_group);
 $gid = $db->insert_id(); 

$db->insert_query('settings', $mybbatically_setting_1);
  rebuild_settings();
}

function mybbatically_deactivate()
  {
  global $db;
 $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN ('mybbatically_global_switch')");
    $db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='mybbatically'");
rebuild_settings();
 }
 ?>