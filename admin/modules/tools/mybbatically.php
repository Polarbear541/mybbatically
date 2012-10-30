<?php
if(!defined("IN_MYBB"))
{
	die("You Cannot Access This File Directly. Please Make Sure IN_MYBB Is Defined.");
}

if($mybb->settings['mybbatically_global_switch'] == 1)
{
	$page->add_breadcrumb_item("MyBBaticaly", "index.php?module=tools-mybbatically");
	$sub_tabs['statistics'] = array(
		'title' => 'Statistics',
		'link' => "index.php?module=tools-mybbatically&amp;action=statistics",
		'description' => "Information about your board, including the version you're currently running &amp; the currently available version."
	);
	
	$sub_tabs['upgrade'] = array(
		'title' => 'Upgrade',
		'link' => "index.php?module=tools-mybbatically&amp;action=upgrade",
		'description' => "Upgrade your board to the latest version of MyBB."
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
			flash_message("Your forum is currently running MyBB ".$mybb->version_code." while the latest available version is ".$latest_code.". We advise you upgrade to the latest version as soon as possible.", "error");
			$mybbversion = "<span style='color: red;'><strong>$mybb->version</strong></span>";
		}
		else
		{
			flash_message("Congratulations! Your forum is currently running the latest version of MyBB.", "success");
			$mybbversion = "<span style='color: green;'><strong>$mybb->version</strong></span>";
		}
		
		$page->output_header('MyBBatically');
		$page->output_nav_tabs($sub_tabs, 'statistics');
		
		$table = new Table;
		$table->construct_header('MyBB Version Statistics', array("colspan" => 0));
		$table->construct_header('', array("colspan" => 0));
		
		$table->construct_cell("<strong>Your forum is currenty running on MyBB version:</strong>", array('width' => '50%'));
		$table->construct_cell($mybbversion, array('width' => '50%'));
		$table->construct_row();

		$table->construct_cell("<strong>Latest version available</strong>", array('width' => '50%'));
		$table->construct_cell($latest_version, array('width' => '50%'));
		$table->construct_row();
		
		$table->output('Version Statistics');
		$page->output_footer();
	}

	if ($mybb->input['action'] == "upgrade")
	{
		$page->output_header('MyBBatically');
		$page->output_nav_tabs($sub_tabs, 'upgrade');
		$form = new Form("index.php?module=tools-mybbatically&amp;action=upgrade", "post", "mybbatically");
		$table = new Table;
		$table->construct_header('Upgrade your board', array("colspan" => 0));
		$table->construct_header('', array("colspan" => 0));
		$table->construct_cell("<strong>Updating from MyBB Version:</strong>", array('width' => '70%'));
		$table->construct_cell("$mybb->version", array('width' => '20%'));
		$table->construct_row();
		$table->construct_cell("<strong>Updating to MyBB Version:</strong>", array('width' => '70%'));
		$table->construct_cell("$latest_version", array('width' => '20%'));
		$table->construct_row();
		$table->construct_cell("<span style='font-size: 25px;'><strong><u>Important Notice:</u></strong></span><br /> This tool will automatically download the latest version of MyBB directly from mybb.com. After clicking submit, your board will be temporarily set offline until you have finished upgrading. We strongly advise you take a backup of your site &amp; database before upgrading. Please make note of the current version of MyBB you're running from above. Please check the checkbox on the right to ensure you want to upgrade.", array('width' => '70%'));
		$table->construct_cell("<div align='center'>Yes, upgrade my board!<br />".$form->generate_check_box('upgrade_true','upgrade_checked')."</div>", array('width' => '20%'));
		$table->construct_row();
		$table->output('Version Statistics');
		
		$buttons[] = $form->generate_submit_button('Update');
		$form->output_submit_wrapper($buttons);
		
		if ($mybb->request_method == "post" && $mybb->input['upgrade_true'] != 'upgrade_checked')
		{
			flash_message('Please check the checkbox to confirm the upgrade process.', 'error');
			admin_redirect('index.php?module=tools-mybbatically&amp;action=upgrade');
			exit;
		}

		elseif ($mybb->request_method == "post" && $mybb->input['upgrade_true'] == 'upgrade_checked')
		{
			require_once MYBB_ROOT."inc/plugins/mybbatically.php";
			mybbatically_run();
			flash_message('Upgrading', 'success');
			admin_redirect('index.php?module=tools-mybbatically&amp;action=upgrade');
		}
		
		$form->end();
		$page->output_footer();
	}
}
?>