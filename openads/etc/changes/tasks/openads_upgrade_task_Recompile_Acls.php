<?php
/*
+---------------------------------------------------------------------------+
| OpenX v2.8                                                                |
| ==========                                                                |
|                                                                           |
| Copyright (c) 2003-2009 OpenX Limited                                     |
| For contact details, see: http://www.openx.org/                           |
|                                                                           |
| This program is free software; you can redistribute it and/or modify      |
| it under the terms of the GNU General Public License as published by      |
| the Free Software Foundation; either version 2 of the License, or         |
| (at your option) any later version.                                       |
|                                                                           |
| This program is distributed in the hope that it will be useful,           |
| but WITHOUT ANY WARRANTY; without even the implied warranty of            |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
| GNU General Public License for more details.                              |
|                                                                           |
| You should have received a copy of the GNU General Public License         |
| along with this program; if not, write to the Free Software               |
| Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
+---------------------------------------------------------------------------+
$Id: openads_upgrade_task_Recompile_Acls.php 62345 2010-09-14 21:16:38Z chris.nutting $
*/

require_once MAX_PATH . '/lib/max/other/lib-acl.inc.php';
//$oMessages initialized by runner OA_Upgrade::runPostUpgradeTask

$oMessages->logInfo('Recompiling Acls');
if (PEAR::isError($result))
{
    $oMessages->logError($result->getCode().': '.$result->getMessage());
}
else
{
    $oMessages->logInfo('OK');
}
$oMessages->logInfo('Starting Acls Recompilation');
$upgradeTaskResult = MAX_AclReCompileAll(true);
if (PEAR::isError($upgradeTaskResult)) {
    $oMessages->logError($upgradeTaskResult->getCode().': '.$upgradeTaskResult->getMessage());
}
$oMessages->logInfo('Acls Recompilation: '.($upgradeTaskResult ? 'Complete' : 'Failed'));
