<?php

/**
 * BluApplication Configuration Settings
 *
 * @package BluApplication
 */
class Config {
	
	/* Database settings */
	var $databaseHost = 'localhost';
	var $databaseUser = 'root';
	var $databasePass = 'Puli0bee';
	var $databaseName = 'recipe4living';
	
	/* Server settings */
	var $baseUrl = '';

	/* Data cache */
	var $memcacheHost = '192.168.0.200';
	var $memcachePort = '12582';
	
	/* Session cache */
	var $memcacheSessionHost = '192.168.0.200';
	var $memcacheSessionPort = '12583';
	
	/* Site settings */
	var $siteId = 'recipe4living';
	
	/* Product/news commenting permissions */
	const COMMENT_ANON				= 1;  // Allow comments from anonymous users
	const COMMENT_REGISTERED		= 2;  // Allow comments from registered users
	const COMMENT_CAPTCHA_ANON		= 4;  // Require a captcha for anonymous users
	const COMMENT_CAPTCHA_REGISTERED = 8;  // Require a captcha for registered users

	const COMMENT_ALL				= 3;  // COMMENT_ANON | COMMENT_REGISTERED
	const COMMENT_CAPTCHA_ALL		= 12; // COMMENT_CAPTCHA_ANON | COMMENT_CAPTCHA_REGISTERED
	
}

?>
