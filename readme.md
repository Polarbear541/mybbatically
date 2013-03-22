### MyBBatically
## Plugin Info:
Description: An automatic updater for MyBB.*
Author: Polarbear541 & Vernier
Version: 1.4
Compatibility: 1.6.x
Extended Description: This plugin allows you to automatically update/reinstall MyBB with the touch of a button. This plugin automatically downloads, unzips and overwrites your MyBB files and even removes your install lock file ready for upgrading/reinstalling (if you choose to).
+ Files: 3 (1 plugin file, 1 language file and 1 admin module)
+ There are no database changes in this plugin.
+ There are no template changes in this plugin.

## Install Instructions:
Upload the MyBBatically files to your MyBB install:

+ files/admin/modules/tools/mybbatically.php to yourforum/admin/modules/tools/mybbatically.php
+ files/inc/languages/english/admin/mybbatically.lang.php to yourforum/inc/languages/english/admin/mybbatically.lang.php
+ files/inc/plugins/mybbatically.php to yourforum/inc/plugins/mybbatically.php

Go to ACP > Plugins > Activate MyBBatically
Then MyBBatically will appear on the sidebar in the Tools section of your ACP.

## Permissions:
MyBBatically allows you to specify which admins can upgrade the board. To allow a specific admin to upgrade the board, navigate to ACP > Users & Groups > Admin Permissions (whichever admin you wish to allow/disallow to upgrade the board) > Tools & Maintenance > Allowed to upgrade board?
You can set this to 'Yes' or 'No' for whichever admins you wish (default is no, super admins will ignore this and be able to upgrade the board regardless).

## Using MyBBatically:
Firstly navigate to the MyBBatically module. This can be found in ACP > Tools & Maintenance > MyBBatically
While on the 'Statistics' tab, you will see an overview of the version of MyBB you're currently running and the latest version of MyBB currently available. While on the 'Upgrade' tab, you will see the version of MyBB you're upgrading from, the version of MyBB you're upgrading to, an option to automatically delete the lock file (also redirects you to the upgrade page), an option to overwrite your graphics and an important notice regarding the upgrade process. While on the 'Backup' tab, you will have the option to backup your files and your database prior to upgrading.

## Plugin Licence:
```
This program is free software: you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License along with this program. If not, see <http://www.gnu.org/licenses/
```
