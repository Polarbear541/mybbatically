<?php
// Plugin Variables
$l['mybbatically'] = 'MyBBatically';
$l['mybbatically_desc'] = 'Automatically upgrade your board to the latest released MyBB Version';

// Settings Variables
$l['settings_desc'] = 'Configuration settings for the MyBBatically plugin';
$l['enable'] = 'Enable MyBBatically?';
$l['enable_desc'] = 'If you set this option to on, MyBBatically will function on your board.';

// Statistics Variables
$l['stats'] = 'Statistics';
$l['stats_desc'] = 'Information about your board, including the version you\'re currently running &amp; the currently available version.';

// Upgrading Variables
$l['upgrade'] = 'Upgrade/Reinstall';
$l['upgrade_desc'] = 'Upgrade/Reinstall your board to ensure you have the latest version of MyBB.';

// Backup Variables
$l['backup'] = 'Backup';
$l['backup_desc'] = 'Backup your boards files and database.';
$l['backup_header'] = 'Backup your board';
$l['backup_files'] = 'Backup your files';
$l['backup_database'] = 'Backup your database';
$l['button_files'] = 'Download File Backup';
$l['button_db'] = 'Download DB Backup';

// Message Variables
$l['currently_running'] = 'Your forum is currently running MyBB ';
$l['plugin_currently_running'] = 'Your forum is currently running MyBBatically ';
$l['latest_version'] = ' while the latest available version is ';
$l['advise_upgrade'] = '. We advise you upgrade to the latest version as soon as possible.';
$l['running_latest_version'] = 'Congratulations! Your forum is currently running the latest version of MyBB.';
$l['error_confirm_upgrade'] = 'Please check the checkbox to confirm the upgrade process.';
$l['error_confirm_reinstall'] = 'Please check the checkbox to confirm the reinstall process.';
$l['upgraded'] = 'Congratulations! Your board has successfully been upgraded';
$l['reinstalled'] = 'Congratulations! Your board has successfully been reinstalled';
$l['update_plugin_success'] = 'MyBBatically has been successfully updated to version ';
$l['update_plugin_error'] = 'An error occured while updating the MyBBatically plugin. Please try again.';

// Version Variables
$l['currently_running_mybb_version'] = '<strong>Your forum is currently running MyBB version:</strong>';
$l['currently_running_mybbatically_version'] = '<strong>Your forum is currently running MyBBatically version:</strong>';
$l['latest_mybb_version_available'] = '<strong>Latest version of MyBB available:</strong>';
$l['latest_mybbatically_version_available'] = '<strong>Latest version of MyBBatically available:</strong>';
$l['mybb_version_stats'] = 'MyBB Version Statistics';
$l['mybb_version_stats_desc'] = 'The statistics below show you the current version of MyBB you\'re running and the latest available version.';
$l['mybbatically_version_stats'] = 'MyBBatically Version Statistics';
$l['mybbatically_version_stats_desc'] = 'The statistics below show you the current version of MyBBatically you\'re running and the latest available version.';


// Upgrade Variables
$l['upgrade_your_board'] = 'Upgrade your board';
$l['reinstall_your_board'] = 'Reinstall your board';
$l['upgrading_from_version'] = '<strong>Upgrading from MyBB Version:</strong>';
$l['upgrading_to_version'] = '<strong>Upgrading to MyBB Version:</strong>';
$l['reinstall_version'] = '<strong>Reinstalling MyBB Version:</strong>';
$l['button_upgrade'] = 'Upgrade';
$l['button_reinstall'] = 'Reinstall';

// Notice Variables
$l['important_notice'] = '<span style=\'font-size: 25px;\'><strong><u>Important Notice:</u></strong></span><br /> This tool will automatically download the latest version of MyBB directly from mybb.com. After clicking submit, the latest version of MyBB will be downloaded and will overwrite your current files. If you have made any core edits to files they will be overwritten. Therefore we advise listing the edits made and re-applying the edits after the upgrade process is complete. We strongly advise you take a backup of your site &amp; database before upgrading. Please make note of the current version of MyBB you\'re running from above. Please check the checkbox on the right to ensure you want to upgrade.';
$l['important_notice2'] = '<span style=\'font-size: 25px;\'><strong><u>Important Notice:</u></strong></span><br /> This tool will automatically download the latest version of MyBB directly from mybb.com. After clicking submit, the latest version of MyBB will be downloaded and will overwrite your current files. If you have made any core edits to files they will be overwritten. Therefore we advise listing the edits made and re-applying the edits after the reinstall process is complete. Please check the checkbox on the right to ensure you want to reinstall.';
$l['upgrade_my_board'] = '<div align=\'center\'>Yes, upgrade my board!<br />';
$l['reinstall_my_board'] = '<div align=\'center\'>Yes, reinstall my board!<br />';
$l['upgrading_notice'] = '<span style=\'font-size: 25px;\'><strong><u>Upgrading:</u></strong></span><br /> (<strong><u>If you are not sure if the upgrade script needs to be run, please leave the checkbox checked</u></strong>) Some versions don\'t require running the upgrade script, however the majority do. Please uncheck the checkbox on the right to leave the lock file intact.';
$l['reinstall_notice'] = '<span style=\'font-size: 25px;\'><strong><u>Reinstalling:</u></strong></span><br /> (<strong><u>If you wish to completely reinstall MyBB then please leave the checkbox checked</u></strong>) If you only want to restore the original files then please uncheck this box.';
$l['complete_reinstall'] = '<div align=\'center\'>Delete the config and lock files to completely install MyBB<br />';
$l['delete_lock_file'] = '<div align=\'center\'>Delete the lock file to enable upgrade script<br />';
$l['overwrite_image_files_notice'] = '<span style=\'font-size: 25px;\'><strong><u>Overwriting image files</u></strong></span><br /> This option allows you to select whether you\'d like to overwrite the image files or not. You may wish to de-select this if you have uploaded your theme\'s files to the images directory ad this will stop them being overwritten by the upgrader.';
$l['overwrite_image_files'] = '<div align=\'center\'>Overwrite image files.<br />';

// Error Variables
$l['could_not_create_zip'] = '<br />Could not create .zip archive.';
$l['could_not_open_zip'] = '<br />Could not open $file_zipped.';
$l['could_not_extract_zip'] = '<br>Could not extract $file_zipped.';

// Admin Log Variables
$l['upgraded_board_on'] = 'Upgraded board to the latest available version on ';
$l['updated_plugin_on'] = 'Updated MyBBatically to the latest available version on ';
$l['at'] =  ' at ';
$l['allowed_to_upgrade_board'] = 'Allowed to updgrade board?';

// Update Plugin Variables
$l['update_plugin'] = 'Update Plugin';
$l['update_plugin_desc'] = 'Update MyBBatically to the latest version.';
$l['update_plugin_header'] = 'Update MyBBatically';
$l['update_plugin_info'] = '<span style=\'font-size: 25px;\'><strong><u>Updating MyBBatically</u></strong></span><br /> MyBBatically, like any other software, requires updates from time to time. These may be simple bug fixes, security fixes or feature updates. It is very important to keep your version of MyBBatically up to date to ensure MyBBatically works to it\'s full potential. This updater allows you to update MyBBatically directly from your Admin Control Panel to ensure you\'re never running an outdated version of MyBBatically. Below is some of the current details about the version of MyBBatically currently installed on your board and the current readily available version.';
$l['button_update_plugin'] = 'Update MyBBatically';

?>