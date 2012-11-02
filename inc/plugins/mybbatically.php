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
	global $lang;

	$lang->load('mybbatically');

	return array(
		"name"  => $lang->mybbatically,
		"description"=> $lang->mybbatically_desc,
		"website"        => "http://community.mybb.com/thread-128545.html",
		"author"        => "Polarbear541 & Vernier",
		"version"        => "1.0",
		"guid"             => "31d01c38e6f2dc1a790065801975eab6",
		"compatibility" => "16*"
	);
}

function mybbatically_activate()
{
	global $db, $lang;
	
	$lang->load('mybbatically');

	$mybbatically_group = array(
		'gid'    => 'NULL',
		'name'  => 'mybbatically',
		'title'      => $lang->mybbatically,
		'description'    => $lang->settings_desc,
		'disporder'    => "1",
		'isdefault'  => "0",
	);
	
	$db->insert_query('settinggroups', $mybbatically_group);
	$gid = $db->insert_id(); 
	
	$mybbatically_setting_1 = array(
		'sid'            => 'NULL',
		'name'        => 'mybbatically_global_switch',
		'title'            => $lang->enable,
		'description'    => $lang->enable_desc,
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
	global $mybb, $lang;
	
	$lang->load('mybbatically');

	if ($mybb->settings['mybbatically_global_switch'] == 1)
	{
		$sub_menu[] = array('id' => 'mybbatically', 'title' => $lang->mybbatically, 'link' => 'index.php?module=tools-mybbatically');
	}
}

function mybbatically_admin_tools_action_handler(&$actions)
{
	$actions['mybbatically'] = array('active' => 'mybbatically', 'file' => 'mybbatically.php');
}

function mybbatically_admin_tools_permissions(&$admin_permissions)
{
	$admin_permissions['mybbatically'] = $lang->allowed_to_upgrade_board;
} 

function recursive_move($dirsource, $dirdest)
{
	if(is_dir($dirsource))$dir_handle=opendir($dirsource);
	$dirname = substr($dirsource,strrpos($dirsource,"/")+1);
	
	while($file=readdir($dir_handle))
	{
		if($file!="." && $file!="..")
		{
			if(!is_dir($dirsource."/".$file))
			{
				copy ($dirsource."/".$file, $dirdest."/".$dirname."/".$file);
				unlink($dirsource."/".$file);
			}
			else
			{
				$dirdest1 = $dirdest."/".$dirname;
				recursive_move($dirsource."/".$file, $dirdest1);
			}
		}
	}
	closedir($dir_handle);
	rmdir($dirsource);
}

function rmdir_recursive($dir) 
{
	$files = scandir($dir);
	array_shift($files);    //Remove '.' from array
	array_shift($files);    //Remove '..' from array
	
	foreach($files as $file) 
	{
		$file = $dir . '/' . $file;
		if(is_dir($file)) 
		{
			rmdir_recursive($file);
			if(file_exists($file))
			{
				rmdir($file);
			}
		} 
		else
		{
			unlink($file);
		}
	}
	
	rmdir($dir);
}

function mybbatically_run()
{
	global $config, $lang, $mybb;

	$lang->load('mybbatically')

	require_once MYBB_ROOT."inc/class_xml.php";
	$contents = fetch_remote_file("http://www.mybb.com/version_check.php");
	$parser = new XMLParser($contents);
	$tree = $parser->get_tree();
	$latest_code = $tree['mybb']['version_code']['value'];
	
	$download_url = "http://cloud.github.com/downloads/mybb/mybb16/mybb_$latest_code.zip";  
	$file_zipped = "mybbatically.zip";
	$file_unzipped = "mybbatically";
	
	$ch = curl_init();  
	$fp = fopen("$file_zipped", "w");
	
	curl_setopt($ch, CURLOPT_URL,$download_url);  
	curl_setopt($ch, CURLOPT_FAILONERROR, true);  
	curl_setopt($ch, CURLOPT_HEADER,0);    
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);  
	curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);  
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);  
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);   
	curl_setopt($ch, CURLOPT_FILE, $fp);
	$page = curl_exec($ch);
	
	if (!$page) 
	{  
		echo $lang->curl_error_number.curl_errno($ch);  
		echo $lang->curl_error. curl_error($ch);  
		exit;  
	}  

	curl_close($ch);  


	//Unzip the file  
	$zip = new ZipArchive;

	if(!$zip) 
	{  
		echo $lang->could_not_create_zip;
		exit;  
	}  
	if($zip->open("$file_zipped") != "true") 
	{  
		echo $lang->could_not_open_zip;  
	}  
	if(!$zip->extractTo("$file_unzipped"))
	{
		echo $lang->could_not_extract_zip;
	}


	//Move files
	$srcDir = './mybbatically/Upload/';
	$destDir = '../';

	rename($srcDir.'/admin', $srcDir.'/'.$config['admin_dir']);

	recursive_move($srcDir,$destDir);
	
	$zip->close();
	
	//Delete remaining directories
	$dir = 'mybbatically';
	rmdir_recursive($dir);

	//Remove zip
	unlink('mybbatically.zip');

	log_admin_action(array('do' => $lang->upgraded_board_on.date($mybb->settings['dateformat']).$lang->at.date($mybb->settings['timeformat'])));
}

?>