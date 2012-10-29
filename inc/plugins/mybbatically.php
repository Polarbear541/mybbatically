<?php
if(!defined("IN_MYBB"))
{
    die("You Cannot Access This File Directly. Please Make Sure IN_MYBB Is Defined.");
}

$plugins->add_hook('admin_tools_menu', 'mybbatically_admin_tools_menu');
$plugins->add_hook('admin_tools_action_handler', 'mybbatically_admin_tools_action_handler');
$plugins->add_hook('admin_tools_permissions', 'mybbatically_admin_tools_permissions');

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

$db->insert_query('settinggroups', $mybbatically_group);
 $gid = $db->insert_id(); 

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

function mybbatically_admin_tools_menu(&$sub_menu)
{
    global $mybb;

    if ($mybb->settings['mybbatically_global_switch'] == 1){
    $sub_menu[] = array('id' => 'mybbatically', 'title' => 'MyBBatically', 'link' => 'index.php?module=tools-mybbatically');
}
}

function mybbatically_admin_tools_action_handler(&$actions)
{
    $actions['mybbatically'] = array('active' => 'mybbatically', 'file' => 'mybbatically.php');
}

function mybbatically_admin_tools_permissions(&$admin_permissions)
{
    $admin_permissions['mybbatically'] = 'Allowed to update board?';
} 

function mybbatically_run()
{
    global $db;

    $db->update_query('settings', array('value'=>'1'), "name='boardclosed'");
    rebuild_settings();
    $download_url = "http://mybb.com/download/latest";  
 $file_zipped = "mybbatically.zip";
 $file_unzipped = "mybbatically";  

 $ch = curl_init();  
 $fp = fopen("$file_zipped", "w");

 curl_setopt($ch, CURLOPT_URL,$download_url);  
 curl_setopt($ch, CURLOPT_FAILONERROR, true);  
 curl_setopt($ch, CURLOPT_HEADER,0);  
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  
 curl_setopt($ch, CURLOPT_AUTOREFERER, true);  
 curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);  
 curl_setopt($ch, CURLOPT_TIMEOUT, 10);  
 curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
 curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);   
 curl_setopt($ch, CURLOPT_FILE, $fp);
 $page = curl_exec($ch);
 if (!$page) {  
   echo "<br />cURL error number:" .curl_errno($ch);  
   echo "<br />cURL error:" . curl_error($ch);  
   exit;  
 }  
 curl_close($ch);  
 echo "Downloaded file<br />"; 
 echo "Saved file<br />";
 echo "Unzipping...<br />";
 // Un zip the file  
 $zip = new ZipArchive;  
   if (! $zip) {  
     echo "<br>Could not create .zip archive";
     exit;  
   }  
   if($zip->open("$file_zipped") != "true") {  
       echo "<br>Could not open $file_zipped";  
         }  
   $zip->extractTo("$file_unzipped");
$dir = "/mybbatically/Upload/";
$dirto = "/";
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if($file == "."||$file == "..") continue;
            rename($dir.$file, $dirto.$file);
        }
        closedir($dh);
    }
}

   $zip->close();

   // unzipped file
 echo "Successfully unzipped file";

 // delete the documentation & empty directorys

 function rmdir_recursive($dir) {
    $files = scandir($dir);
    array_shift($files);    // remove '.' from array
    array_shift($files);    // remove '..' from array
   
    foreach ($files as $file) {
        $file = $dir . '/' . $file;
        if (is_dir($file)) {
            rmdir_recursive($file);
            if (file_exists($file))
            {
            rmdir($file);
          }
        } else {
            unlink($file);
        }
    }
    rmdir($dir);
}
 
// remove directory
$dir = 'mybbatically';
rmdir_recursive($dir);

// Unlink lock file

unlink('/install/lock');
}
 ?>