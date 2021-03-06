<?php
if(!defined("IN_MYBB"))
{
	die("You Cannot Access This File Directly. Please Make Sure IN_MYBB Is Defined.");
}

global $lang;
$lang->load('mybbatically');

if($mybb->settings['mybbatically_global_switch'] == 1)
{
	$page->add_breadcrumb_item($lang->mybbatically, "index.php?module=tools-mybbatically");
	$sub_tabs['statistics'] = array(
		'title' => $lang->stats,
		'link' => "index.php?module=tools-mybbatically&amp;action=statistics",
		'description' => $lang->stats_desc
	);
	
	$sub_tabs['upgrade'] = array(
		'title' => $lang->upgrade,
		'link' => "index.php?module=tools-mybbatically&amp;action=upgrade",
		'description' => $lang->upgrade_desc
	);
	
	$sub_tabs['backup'] = array(
		'title' => $lang->backup,
		'link' => "index.php?module=tools-mybbatically&amp;action=backup",
		'description' => $lang->backup_desc
	);
	
	$sub_tabs['update_plugin'] = array(
		'title' => $lang->update_plugin,
		'link' => "index.php?module=tools-mybbatically&amp;action=update_plugin",
		'description' => $lang->update_plugin_desc
	);

	require_once MYBB_ROOT."inc/class_xml.php";
	$contents = fetch_remote_file("http://www.mybb.com/version_check.php");
	
	$parser = new XMLParser($contents);
	$tree = $parser->get_tree();
	
	$latest_code = $tree['mybb']['version_code']['value'];
	$latest_version = "<strong>".$tree['mybb']['latest_version']['value']."</strong> (".$latest_code.")";
	
	if($mybb->input['action'] == "statistics" || $mybb->input['action'] == "")
	{
		if($latest_code > $mybb->version_code)
		{
			flash_message($lang->currently_running.$mybb->version.$lang->latest_version.$latest_version.$lang->advise_upgrade, "error");
			$mybbversion = "<span style='color: red;'><strong>$mybb->version</strong></span>";
		}
		else
		{
			flash_message($lang->running_latest_version, "success");
			$mybbversion = "<span style='color: green;'><strong>$mybb->version</strong></span>";
		}

		$mybbatically_get_latest_version = mybbatically_get_latest_version();
		$mybbatically_get_current_version = mybbatically_get_current_version();

		if($mybbatically_get_latest_version > $mybbatically_get_current_version)
		{
			flash_message($lang->plugin_currently_running.$mybbatically_get_current_version.$lang->latest_version.$mybbatically_get_latest_version.$lang->advise_upgrade, "error");
			$mybbatically_get_current_version = "<span style='color: red;'><strong>$mybbatically_get_current_version</strong></span>";
		}
		else
		{
			$mybbatically_get_current_version = "<span style='color: green;'><strong>$mybbatically_get_current_version</strong></span>";
		}


		$page->output_header($lang->mybbatically);
		$page->output_nav_tabs($sub_tabs, 'statistics');

		$table = new Table;
		
		$table->construct_header($lang->mybb_version_stats_desc, array("colspan" => 2));
		$table->construct_cell($lang->currently_running_mybb_version, array('width' => '50%'));
		$table->construct_cell($mybbversion, array('width' => '50%'));
		$table->construct_row();
		
		$table->construct_cell($lang->latest_mybb_version_available, array('width' => '50%'));
		$table->construct_cell($latest_version, array('width' => '50%'));
		$table->construct_row();


		$table->output($lang->mybb_version_stats);

// MyBBatically Version Statistics

		$table2 = new Table;
		
		$table2->construct_header($lang->mybbatically_version_stats_desc, array("colspan" => 2));
		
		$table2->construct_cell($lang->currently_running_mybbatically_version, array('width' => '50%'));
		$table2->construct_cell($mybbatically_get_current_version, array('width' => '50%'));
		$table2->construct_row();

		$table2->construct_cell($lang->latest_mybbatically_version_available, array('width' => '50%'));
		$table2->construct_cell('<strong>'.$mybbatically_get_latest_version.'</strong>', array('width' => '50%'));
		$table2->construct_row();


		$table2->output($lang->mybbatically_version_stats);

		$page->output_footer();
	}
	
	if($mybb->input['action'] == "upgrade")
	{
		$page->output_header($lang->mybbatically);
		$page->output_nav_tabs($sub_tabs, 'upgrade');
		$form = new Form("index.php?module=tools-mybbatically&amp;action=upgrade", "post", "mybbatically");
		$table = new Table;
		
		if($latest_code != $mybb->version_code)
		{
			$table->construct_header($lang->upgrade_your_board, array("colspan" => 2));
			$table->construct_cell($lang->upgrading_from_version, array('width' => '70%'));
			$table->construct_cell("$mybb->version", array('width' => '20%'));
			$table->construct_row();
			$table->construct_cell($lang->upgrading_to_version, array('width' => '70%'));
			$table->construct_cell("$latest_version", array('width' => '20%'));
			$table->construct_row();
			$table->construct_cell($lang->upgrading_notice, array('width' => '70%'));
			$table->construct_cell($lang->delete_lock_file.$form->generate_check_box('lock_true','lock_checked', '', array('checked' => 1))."</div>", array('width' => '20%'));
			$table->construct_row();
			$table->construct_cell($lang->overwrite_image_files_notice, array('width' => '70%'));
			$table->construct_cell($lang->overwrite_image_files.$form->generate_check_box('overwrite_images_true','overwrite_images_checked')."</div>", array('width' => '20%'));
			$table->construct_row();
			$table->construct_cell($lang->important_notice, array('width' => '70%'));
			$table->construct_cell($lang->upgrade_my_board.$form->generate_check_box('upgrade_true','upgrade_checked')."</div>", array('width' => '20%'));
			$table->construct_row();
			$table->output($lang->upgrade_your_board);
			
			$buttons[] = $form->generate_submit_button($lang->button_upgrade);
		}
		else
		{
			$table->construct_header($lang->reinstall_your_board, array("colspan" => 2));
			$table->construct_cell($lang->reinstall_version, array('width' => '70%'));
			$table->construct_cell("$latest_version", array('width' => '20%'));
			$table->construct_row();
			$table->construct_cell($lang->reinstall_notice, array('width' => '70%'));
			$table->construct_cell($lang->complete_reinstall.$form->generate_check_box('remove_true','remove_checked', '', array('checked' => 1))."</div>", array('width' => '20%'));
			$table->construct_row();
			$table->construct_cell($lang->overwrite_image_files_notice, array('width' => '70%'));
			$table->construct_cell($lang->overwrite_image_files.$form->generate_check_box('overwrite_images_true','overwrite_images_checked')."</div>", array('width' => '20%'));
			$table->construct_row();
			$table->construct_cell($lang->important_notice2, array('width' => '70%'));
			$table->construct_cell($lang->reinstall_my_board.$form->generate_check_box('reinstall_true','reinstall_checked')."</div>", array('width' => '20%'));
			$table->construct_row();
			$table->output($lang->reinstall_your_board);
			
			$buttons[] = $form->generate_submit_button($lang->button_reinstall);
		}
		
		$form->output_submit_wrapper($buttons);
		
		if($mybb->request_method == "post" && $mybb->input['upgrade_true'] == 'upgrade_checked')
		{
			require_once MYBB_ROOT."inc/plugins/mybbatically.php";
			if($mybb->input['lock_true'] == 'lock_checked')
			{
				mybbatically_run();
				unlink('../install/lock');
				admin_redirect('../install/upgrade.php');
				exit;
			}
			elseif($mybb->input['lock_true'] != 'lock_checked')
			{
				mybbatically_run();
				flash_message($lang->upgraded, 'success');
				admin_redirect('index.php?module=tools-mybbatically&amp;action=upgrade');
			}
		}
		elseif($mybb->request_method == "post" && $mybb->input['reinstall_true'] == 'reinstall_checked')
		{
			require_once MYBB_ROOT."inc/plugins/mybbatically.php";
			if($mybb->input['remove_true'] == 'remove_checked')
			{
				mybbatically_run();
				unlink('../inc/config.php');
				unlink('../install/lock');
				admin_redirect('../install/index.php');
				exit;
			}
			elseif($mybb->input['remove_true'] != 'remove_checked')
			{
				mybbatically_run();
				flash_message($lang->reinstalled, 'success');
				admin_redirect('index.php?module=tools-mybbatically&amp;action=upgrade');
			}
		}
		elseif($mybb->request_method == "post" && $mybb->input['upgrade_true'] != 'upgrade_checked')
		{
			flash_message($lang->error_confirm_upgrade, 'error');
			admin_redirect('index.php?module=tools-mybbatically&amp;action=upgrade');
			exit;
		}
		elseif($mybb->request_method == "post" && $mybb->input['reinstall_true'] != 'reinstall_checked')
		{
			flash_message($lang->error_confirm_reinstall, 'error');
			admin_redirect('index.php?module=tools-mybbatically&amp;action=upgrade');
			exit;
		}
		
		$form->end();
		$page->output_footer();
	}
	
	if($mybb->input['action'] == "backup" && $mybb->request_method == "post")
	{
		if(isset($mybb->input['do_filebackup']))
		{
			mybbatically_backup_files();
		}
		elseif(isset($mybb->input['do_dbbackup']))
		{
			mybbatically_backup_db();
		}
	}
	elseif($mybb->input['action'] == "backup")
	{
		$page->output_header($lang->mybbatically);
		$page->output_nav_tabs($sub_tabs, 'backup');
		$form = new Form("index.php?module=tools-mybbatically&amp;action=backup", "post", "mybbatically");
		$table = new Table;
		$table->construct_header($lang->backup_desc, array("colspan" => 0));
		$table->construct_header('', array("colspan" => 0));
		
		$table->construct_cell($lang->backup_files, array('width' => '50%'));
		$table->construct_cell($form->generate_submit_button($lang->button_files, array("name" => "do_filebackup")), array('width' => '50%'));
		$table->construct_row();
		
		$table->construct_cell($lang->backup_database, array('width' => '50%'));
		$table->construct_cell($form->generate_submit_button($lang->button_db, array("name" => "do_dbbackup")), array('width' => '50%'));
		$table->construct_row();
		
		$table->output($lang->backup_header);
		$form->end();
		$page->output_footer();
	}

	if($mybb->input['action'] == "update_plugin")
	{
		$page->output_header($lang->mybbatically);
		$page->output_nav_tabs($sub_tabs, 'update_plugin');
		$form = new Form("index.php?module=tools-mybbatically&amp;action=update_plugin", "post", "mybbatically");
		$table = new Table;
		$table->construct_header($lang->update_plugin_desc, array("colspan" => 0));
		$table->construct_header('', array("colspan" => 0));
		
		$table->construct_cell($lang->update_plugin_info, array('width' => '50%'));
		$table->construct_cell($form->generate_submit_button($lang->button_update_plugin, array("name" => "do_pluginupdate")), array('width' => '50%'));
		$table->construct_row();

		$table->output($lang->update_plugin_header);
		$form->end();
		if($mybb->request_method == "post" && isset($mybb->input['do_pluginupdate']))
		{
			mybbatically_update_plugin();
			if($mybbatically_get_latest_version == $mybbatically_get_current_version)
			{
				flash_message($lang->update_plugin_success.$mybbatically_get_current_version, "success");
				admin_redirect("index.php?module=tools-mybbatically&amp;action=statistics");
				exit;
			}
			else
			{
				flash_message($lang->update_plugin_error, "error");
				admin_redirect("index.php?module=tools-mybbatically&amp;action=update_plugin");
				exit;
			}

		}
		$page->output_footer();
	}
}
?>
