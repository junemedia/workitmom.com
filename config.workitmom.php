<?php

/**
 * BluApplication Configuration Settings
 *
 * @package BluApplication
 */
class Config {
	
	/* Database settings */
	var $databaseHost = 'localhost';
	var $databaseUser = 'workitmom';
	var $databasePass = 'w0rkit';
	var $databaseName = '333213_workitmom';
	
	/* Server settings */
	var $baseUrl = '';

	/* Data cache */
	var $memcacheHost = '192.168.0.200';
	var $memcachePort = '12582';
	
	/* Session cache */
	var $memcacheSessionHost = '192.168.0.200';
	var $memcacheSessionPort = '12583';
	
	/* Site settings */
	var $siteId = 'workitmom';
	var $articleLength = 450;	// Article word length (same as old WIM)
	var $listingLength = 9;		// Number of items in a listing. Odd number, so that colour scheme looks ok.
	var $bigListingLength = 5;	// Same as above, but used for taller listings.
	var $commentListingLength = 12; // Number of comments to show on per page.
	var $listingSort = 'date';			// Default listing sort criteria - sort by latest. Used by ItemsController and ConnectController.
	var $errorMessage = 'Sorry, there was an error. Please try again.';		// Default error message.
	var $titleSeparator = ' | ';
	var $dateFormat = 'jS F Y';
	var $guestUsername = 'melinda';	// The user account to associate with 'guest' interaction.
	var $adminUsername = 'workitmom';	// Default user to use for admin correspondence (i.e. sending messages to users.)
	
	/* Backend settings */
	var $backendListingLength = 30;	// Number of rows to display in a table.
	
	var $newsletterUsername = 'workit';		// Used for Constant Contact newsletter signup - see UserModel::newsletter().
	var $newsletterPassword = 'wim123';		// [see above]
	
	/* Product/news commenting permissions */
	const COMMENT_ANON				= 1;  // Allow comments from anonymous users
	const COMMENT_REGISTERED		= 2;  // Allow comments from registered users
	const COMMENT_CAPTCHA_ANON		= 4;  // Require a captcha for anonymous users
	const COMMENT_CAPTCHA_REGISTERED = 8;  // Require a captcha for registered users

	const COMMENT_ALL				= 3;  // COMMENT_ANON | COMMENT_REGISTERED
	const COMMENT_CAPTCHA_ALL		= 12; // COMMENT_CAPTCHA_ANON | COMMENT_CAPTCHA_REGISTERED
	
}

?>
