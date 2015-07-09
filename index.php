<?php

/**
 * BluApplication site entry point
 *
 * Handles class autoloading and application creation, dispatch and render
 *
 * @package BluApplication
 */
/**
 * BluApplication base path
 */
define('BLUPATH_BASE', dirname(__FILE__));
setcookie("_test_a",'true', time()+1200,'/','workitmom.com');
// Load config
require_once(BLUPATH_BASE.'/config.php');

// Full error reporting for debug
if (DEBUG) { error_reporting(E_ALL | E_STRICT); }

// Record script execution start time
if (DEBUG) { $startTime = microtime(true); }

/**
 * Class autoloader for libraries
 */
function __autoload($className)
{
	// Miscellaneous
	if (file_exists(BLUPATH_BASE.'/shared/lib/'.$className.'.php')) {
		require_once(BLUPATH_BASE.'/shared/lib/'.$className.'.php');
		return;
	}
	if (file_exists(BLUPATH_BASE.'/shared/objects/'.$className.'.php')) {
		require_once(BLUPATH_BASE.'/shared/objects/'.$className.'.php');
		return;
	}
	if (file_exists(BLUPATH_BASE.'/shared/interfaces/'.$className.'.php')) {
		require_once(BLUPATH_BASE.'/shared/interfaces/'.$className.'.php');
		return;
	}
	
	// Allows more complex inheritance while staying sane.
	$fail = false;
	$siteId = BluApplication::getSetting('siteId');
	
	$path = BLUPATH_BASE.'/'.SITEEND;
	if (strpos($className, ucfirst($siteId)) === 0) {
		$path .= '/'.$siteId;
		$className = substr($className, strlen($siteId));
	} else {
		$path .= '/base';
	}
	
	if (strpos($className, 'Controller') !== false) {
		$path .= '/controllers';
	} else {
		$fail = true;
	}
	
	if (!$fail && file_exists($path.'/'.$className.'.php')) {
		require_once($path.'/'.$className.'.php');
		return;
	}
	
	// Fail
	trigger_error('Could not find '.$className, E_USER_ERROR);
}

// Include HTML purifier
if (PURIFY) {
	require_once 'HTMLPurifier.auto.php';
}

// Create application
$bluApplication = BluApplication::getInstance();

// Dispatch to requested option
$bluApplication->dispatch();

// Render option
$bluApplication->render();

?>
