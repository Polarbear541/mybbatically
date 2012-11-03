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
$l['mybb_version_stats'] = 'MyBB Version Statistics';

// Upgrading Variables
$l['upgrade'] = 'Upgrade';
$l['upgrade_desc'] = 'Upgrade your board to the latest version of MyBB.';

// Message Variables
$l['currently_running'] = 'Your forum is currently running MyBB ';
$l['latest_version'] = ' while the latest available version is ';
$l['advise_upgrade'] = '. We advise you upgrade to the latest version as soon as possible.';
$l['running_latest_version'] = 'Congratulations! Your forum is currently running the latest version of MyBB.';
$l['error_confirm_upgrade'] = 'Please check the checkbox to confirm the upgrade process.';
$l['upgraded'] = 'Congratulations! Your board has successfully been upgraded to MyBB version ';

// Version Variables
$l['currently_running_version'] = '<strong>Your forum is currently running on MyBB version:</strong>';
$l['latest_version_available'] = '<strong>Latest version available</strong>';
$l['version_stats'] = 'Version Statistics';
$l['error_already_latest_version'] = 'You are already on the Latest Version';

// Upgrade Variables
$l['upgrade_your_board'] = 'Upgrade your board';
$l['upgrading_from_version'] = '<strong>Updating from MyBB Version:</strong>';
$l['upgrading_to_version'] = '<strong>Updating to MyBB Version:</strong>';
$l['button_upgrade'] = 'Upgrade';

// Notice Variables
$l['important_notice'] = '<span style=\'font-size: 25px;\'><strong><u>Important Notice:</u></strong></span><br /> This tool will automatically download the latest version of MyBB directly from mybb.com. After clicking submit, the latest version of MyBB will be downloaded and will overwrite your current files. If you have made any core edits to files they will be overwritten. Therefore we advise listing the edits made and re-applying the edits after the upgrade process is complete. We strongly advise you take a backup of your site &amp; database before upgrading. Please make note of the current version of MyBB you\'re running from above. Please check the checkbox on the right to ensure you want to upgrade.';
$l['upgrade_my_board'] = '<div align=\'center\'>Yes, upgrade my board!<br />';
$l['upgrading_notice'] = '<span style=\'font-size: 25px;\'><strong><u>Upgrading:</u></strong></span><br /> (<strong><u>If you are not sure if the upgrade script needs to be run, please leave the checkbox checked</u></strong>) Some versions don\'t require running the upgrade script, however the majority do. Please uncheck the checkbox on the right to leave the lock file intact.';
$l['delete_lock_file'] = '<div align=\'center\'>Delete the lock file to enable upgrade script<br />';
$l['overwrite_image_files_notice'] = '<span style=\'font-size: 25px;\'><strong><u>Overwriting image files</u></strong></span><br /> This option allows you to select whether you\'d like to overwrite the image files or not. You may wish to de-select this if you have uploaded your theme\'s files to the images directory ad this will stop them being overwritten by the upgrader.';
$l['overwrite_image_files'] = '<div align=\'center\'>Overwrite image files.<br />'

// Error Variables
$l['could_not_create_zip'] = '<br />Could not create .zip archive.';
$l['could_not_open_zip'] = '<br />Could not open $file_zipped.';
$l['could_not_extract_zip'] = '<br>Could not extract $file_zipped.';

// Admin Log Variables
$l['upgraded_board_on'] = 'Upgraded board to the latest available version on ';
$l['at'] =  ' at ';
$l['allowed_to_upgrade_board'] = 'Allowed to updgrade board?'
?>