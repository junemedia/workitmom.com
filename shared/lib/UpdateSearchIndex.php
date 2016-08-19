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


if($str != '')
{
	mail(
			'johns@junemedia.com',
			'WIM.Update.Search.Report - ' . date("Y-m-d H:m:s", time()),
			$str,
			'From: WIM.Update.Search <johns@junemedia.com>',
			"Return-Path:<johns@junemedia.com>\r\n"
		);
}
?>
