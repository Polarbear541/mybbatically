<?php
if(!defined("IN_MYBB"))
{
	die("You Cannot Access This File Directly. Please Make Sure IN_MYBB Is Defined.");
}

	global $lang;
	$lang->load('mybbatically');

if($mybb->settings['mybbatically_global_switch'] == 1)
{
	$page->add_breadcrumb_item("MyBBaticaly", "index.php?module=tools-mybbatically");
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
			flash_message($lang->currently_running.$mybb->version_code.$lang->latest_version.$latest_code.$lang->advise_upgrade, "error");
			$mybbversion = "<span style='color: red;'><strong>$mybb->version</strong></span>";
		}
		else
		{
			flash_message($lang->running_latest_version, "success");
			$mybbversion = "<span style='color: green;'><strong>$mybb->version</strong></span>";
		}

		$page->output_header($lang->mybbatically);
		$page->output_nav_tabs($sub_tabs, 'statistics');

		$table = new Table;
		$table->construct_header($lang->mybb_version_stats, array("colspan" => 0));
		$table->construct_header('', array("colspan" => 0));

		$table->construct_cell($lang->currently_running_version, array('width' => '50%'));
		$table->construct_cell($mybbversion, array('width' => '50%'));
		$table->construct_row();

		$table->construct_cell($lang->latest_version_available, array('width' => '50%'));
		$table->construct_cell($latest_version, array('width' => '50%'));
		$table->construct_row();

		$table->output($lang->version_stats);
		$page->output_footer();
	}
	
	if($mybb->input['action'] == "upgrade" && $mybb->version_code != $latest_code)
	{
		$page->output_header($lang->mybbatically);
		$page->output_nav_tabs($sub_tabs, 'upgrade');
		$form = new Form("index.php?module=tools-mybbatically&amp;action=upgrade", "post", "mybbatically");
		$table = new Table;
		$table->construct_header($lang->upgrade_your_board, array("colspan" => 0));
		$table->construct_header('', array("colspan" => 0));
		$table->construct_cell($lang->updating_from_version, array('width' => '70%'));
		$table->construct_cell("$mybb->version", array('width' => '20%'));
		$table->construct_row();
		$table->construct_cell($lang->updating_to_version, array('width' => '70%'));
		$table->construct_cell("$latest_version", array('width' => '20%'));
		$table->construct_row();
		$table->construct_cell($lang->upgrading_notice, array('width' => '70%'));
		$table->construct_cell($lang->delete_lock_file.$form->generate_check_box('lock_true','lock_checked', '', array('checked' => 1))."</div>", array('width' => '20%'));
		$table->construct_row();
		$table->construct_cell($lang->overwrite_image_files_notice, array('width' => '70%'));
		$table->construct_cell('$lang->overwrite_image_files'.$form->generate_check_box('overwrite_images_true','overwrite_images_checked')."</div>", array('width' => '20%'));
		$table->construct_row();
		$table->construct_cell($lang->important_notice, array('width' => '70%'));
		$table->construct_cell($lang->upgrade_my_board.$form->generate_check_box('upgrade_true','upgrade_checked')."</div>", array('width' => '20%'));
		$table->construct_row();
		$table->output($lang->version_stats);

		$buttons[] = $form->generate_submit_button($lang->button_upgrade);
		$form->output_submit_wrapper($buttons);

		if ($mybb->request_method == "post" && $mybb->input['upgrade_true'] != 'upgrade_checked')
		{
			flash_message($lang->error_confirm_upgrade, 'error');
			admin_redirect('index.php?module=tools-mybbatically&amp;action=upgrade');
			exit;
		}

		elseif ($mybb->request_method == "post" && $mybb->input['upgrade_true'] == 'upgrade_checked')
		{
			require_once MYBB_ROOT."inc/plugins/mybbatically.php";
		if ($mybb->input['lock_true'] == 'lock_checked')
		{
			mybbatically_run();
			unlink('../install/lock');
			admin_redirect('/install/upgrade.php');
			exit;
		}
		elseif ($mybb->input['lock_true'] != 'lock_checked')
		{
			mybbatically_run();
			flash_message($lang->upgrade_in_progress, 'success');
			admin_redirect('index.php?module=tools-mybbatically&amp;action=upgrade');
		}
		}

		$form->end();
		$page->output_footer();
	}
	else
	{
		$page->output_header($lang->mybbatically);
		$page->output_nav_tabs($sub_tabs, 'upgrade');
		$table = new Table;
		$table->construct_header($lang->error_already_latest_version, array("colspan" => 0));
		$table->construct_header('', array("colspan" => 0));
		$table->construct_cell($lang->congratulations_latest_version, array('width' => '70%'));
		$table->construct_cell("$mybb->version", array('width' => '20%'));
		$table->construct_row();
		$table->output($lang->version_stats);
		$page->output_footer();	
	}
}