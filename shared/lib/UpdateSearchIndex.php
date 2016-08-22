<?php
define('BLUPATH_BASE', '/var/www/html/www.workitmom.com');
define('STAGING','');
require_once(BLUPATH_BASE.'/config.php');
require_once(BLUPATH_BASE.'/shared/lib/Database.php');
require_once(BLUPATH_BASE.'/shared/lib/Cache.php');
require_once(BLUPATH_BASE.'/shared/lib/BluApplication.php');

$db = BluApplication::getDatabase();
$startTime = microtime(true);

// Update index
$searchModel = BluApplication::getModel('search');
$searchModel->updateIndex();

// Done!
$endTime = microtime(true);
$time = round($endTime - $startTime, 3);
$str = 'Search index updated in '.$time.' seconds';

?>
