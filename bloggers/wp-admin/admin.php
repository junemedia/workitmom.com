<?php
define('WP_ADMIN', TRUE);

if ( defined('ABSPATH') )
	require_once( ABSPATH . 'wp-config.php');
else
    require_once('../wp-config.php');

if ( get_option('db_version') != $wp_db_version ) {
	$http_fopen = ini_get("allow_url_fopen");
	if($http_fopen) {
		$out = @file( get_option( "siteurl" ) . "/wp-admin/upgrade.php?step=1" ); // upgrade the db!
	} else {
		require_once('../wp-includes/class-snoopy.php');
		$client = new Snoopy();
		@$client->fetch( get_option( "siteurl" ) . "wp-admin/upgrade.php?step=1");
	}
}

require_once(ABSPATH . 'wp-admin/includes/admin.php');

auth_redirect();

nocache_headers();

update_category_cache();

$posts_per_page = get_option('posts_per_page');
$what_to_show = get_option('what_to_show');
$date_format = get_option('date_format');
$time_format = get_option('time_format');

wp_reset_vars(array('profile', 'redirect', 'redirect_url', 'a', 'popuptitle', 'popupurl', 'text', 'trackback', 'pingback'));

wp_admin_css_color('classic', __('Classic'), get_option( 'siteurl' ) . "/wp-admin/css/colors-classic.css", array('#07273E', '#14568A', '#D54E21', '#2683AE'));
wp_admin_css_color('fresh', __('Fresh'), get_option( 'siteurl' ) . "/wp-admin/css/colors-fresh.css", array('#464646', '#CEE1EF', '#D54E21', '#2683AE'));

wp_enqueue_script( 'common' );
wp_enqueue_script( 'jquery-color' );

$editing = false;

if (isset($_GET['page'])) {
	$plugin_page = stripslashes($_GET['page']);
	$plugin_page = plugin_basename($plugin_page);
}

require(ABSPATH . 'wp-admin/menu.php');

do_action('admin_init');

// Handle plugin admin pages.
if (isset($plugin_page)) {
	$page_hook = get_plugin_page_hook($plugin_page, $pagenow);

	if ( $page_hook ) {
		do_action('load-' . $page_hook);
		if (! isset($_GET['noheader']))
			require_once(ABSPATH . 'wp-admin/admin-header.php');

		do_action($page_hook);
	} else {
		if ( validate_file($plugin_page) ) {
			wp_die(__('Invalid plugin page'));
		}

		if (! file_exists(ABSPATH . PLUGINDIR . "/$plugin_page") && ! file_exists(ABSPATH . MUPLUGINDIR . "/$plugin_page"))
			wp_die(sprintf(__('Cannot load %s.'), htmlentities($plugin_page)));

		do_action('load-' . $plugin_page);

		if (! isset($_GET['noheader']))
			require_once(ABSPATH . 'wp-admin/admin-header.php');

		if ( file_exists(ABSPATH . MUPLUGINDIR . "/$plugin_page") )
			include(ABSPATH . MUPLUGINDIR . "/$plugin_page");
		else
			include(ABSPATH . PLUGINDIR . "/$plugin_page");
	}

	include(ABSPATH . 'wp-admin/admin-footer.php');

	exit();
} else if (isset($_GET['import'])) {

	$importer = $_GET['import'];

	if ( ! current_user_can('import') )
		wp_die(__('You are not allowed to import.'));

	if ( validate_file($importer) ) {
		wp_die(__('Invalid importer.'));
	}

	// Allow plugins to define importers as well
	if (! is_callable($wp_importers[$importer][2]))
	{
		if (! file_exists(ABSPATH . "wp-admin/import/$importer.php"))
		{
			wp_die(__('Cannot load importer.'));
		}
		include(ABSPATH . "wp-admin/import/$importer.php");
	}

	$parent_file = 'edit.php';
	$submenu_file = 'import.php';
	$title = __('Import');

	if (! isset($_GET['noheader']))
		require_once(ABSPATH . 'wp-admin/admin-header.php');

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	define('WP_IMPORTING', true);
	kses_init_filters();  // Always filter imported data with kses.

	call_user_func($wp_importers[$importer][2]);

	include(ABSPATH . 'wp-admin/admin-footer.php');

	exit();
} else {
	do_action("load-$pagenow");
}

?>
