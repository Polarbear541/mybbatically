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
	global $mybb;
	
	if ($mybb->settings['mybbatically_global_switch'] == 1)
	{
		$sub_menu[] = array('id' => 'mybbatically', 'title' => 'MyBBatically', 'link' => 'index.php?module=tools-mybbatically');
	}
}

function mybbatically_admin_tools_action_handler(&$actions)
{
	$actions['mybbatically'] = array('active' => 'mybbatically', 'file' => 'mybbatically.php');
}

function mybbatically_admin_tools_permissions(&$admin_permissions)
{
	global $lang;
	$lang->load('mybbatically');
	$admin_permissions['mybbatically'] = $lang->allowed_to_upgrade_board;
} 

function recursive_move($dirsource, $dirdest)
{
	global $mybb;

	if(is_dir($dirsource))$dir_handle=opendir($dirsource);
	$dirname = substr($dirsource,strrpos($dirsource,"/")+1);
	
	if ($mybb->request_method == "post")
	{
		while($file=readdir($dir_handle))
		{
			if($file!="." && $file!="..")
			{
				if(!is_dir($dirsource."/".$file))
				{
					@copy($dirsource."/".$file, $dirdest."/".$dirname."/".$file);
					unlink($dirsource."/".$file);
				}
				else
				{
					$dirdest1 = $dirdest."/".$dirname;
					recursive_move($dirsource."/".$file, $dirdest1);
				}
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

function rmdir_recursive_images()
{
	$dir = './mybbatically/Upload/images';
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

	$lang->load('mybbatically');

	require_once MYBB_ROOT."inc/class_xml.php";
	$contents = fetch_remote_file("http://www.mybb.com/version_check.php");
	$parser = new XMLParser($contents);
	$tree = $parser->get_tree();
	$latest_code = $tree['mybb']['version_code']['value'];
	
	$download_url = "http://cloud.github.com/downloads/mybb/mybb16/mybb_$latest_code.zip";  
	$file_zipped = "mybbatically.zip";
	$file_unzipped = "mybbatically";
	$fetch_file = fetch_remote_file($download_url);
	$fp = fopen($file_zipped, "w");
	fwrite($fp,$fetch_file); 
	fclose($fp);
	
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

	if($mybb->request_method == "post" && $mybb->input['overwrite_images_true'] != 'overwrite_images_checked')
	{
		rmdir_recursive_images();
	}
	
	// Move files
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

function zipfiles($source)
{
	//Check if source exists
    if(!file_exists($source)) 
	{
		return false;
    }
	
	//Start writing to file zip on server
    $zip = new ZipArchive();
    if(!$zip->open('files.zip', ZIPARCHIVE::CREATE)) 
	{
		return false;
    }
	
    $source = str_replace('\\', '/', realpath($source));
	
    if(is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
		
        foreach($files as $file)
        {
            $file = str_replace('\\', '/', $file);
			
            //Ignore '.' and '..'
            if(in_array(substr($file, strrpos($file, '/')+1), array('.', '..'))) continue;
			
            $file = realpath($file);
			
            if(is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            elseif(is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    elseif(is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }
	
    return $zip->close();
}

function mybbatically_backup_files()
{
	zipfiles('../');
	header('Content-Type: application/zip');
	header('Content-Disposition: attachment; filename=MyBBatically_filebackup_'.microtime().'.zip');
	readfile("./files.zip");
	unlink("./files.zip");
}

function mybbatically_backup_db()
{
	global $db, $config;
	$fp = fopen('./db.sql', 'w');
	$db->set_table_prefix('');
	$tables = $db->list_tables($config['database']['database'], $config['database']['table_prefix']);
	$time = date('dS F Y \a\t H:i', TIME_NOW);
	$header = "-- MyBB Database Backup\n-- Generated: {$time}\n-- -------------------------------------\n\n";
	$contents = $header;
	foreach($tables as $table)
	{
		$field_list = array();
		$fields_array = $db->show_fields_from($table);
		foreach($fields_array as $field)
		{
			$field_list[] = $field['Field'];
		}
		
		$fields = "`".implode("`,`", $field_list)."`";

		$structure = $db->show_create_table($table).";\n";
		$contents .= $structure;
		fwrite($fp, $contents);
		$contents = '';	
		
		$query = $db->simple_select($table);
		while($row = $db->fetch_array($query))
		{
			$insert = "INSERT INTO {$table} ($fields) VALUES (";
			$comma = '';
			foreach($field_list as $field)
			{
				if(!isset($row[$field]) || is_null($row[$field]))
				{
					$insert .= $comma."NULL";
				}
				else
				{
					$insert .= $comma."'".$db->escape_string($row[$field])."'";
				}
				$comma = ',';
			}
			$insert .= ");\n";
			$contents .= $insert;
			fwrite($fp, $contents);
			$contents = '';	
		}
	}
	$db->set_table_prefix(TABLE_PREFIX);
	fwrite($fp, $contents);
	fclose($fp);

	header('Content-Type: application/sql');
	header('Content-Disposition: attachment; filename=MyBBatically_dbbackup_'.microtime().'.sql');
	readfile("./db.sql");
	unlink("./db.sql");
}
?>