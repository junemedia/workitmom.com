<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?= $storename ?> Admin</title>
	<meta name="Description" content="<?= $description ?>" />
	<meta name="Keywords" content="<?= $keywords ?>" />
	<meta name="author" content="blubolt Design, www.blubolt.com" />	
	
	<script type="text/javascript">
		/* Define global static variables. */
		DEBUG = <?= DEBUG ? 'true' : 'false' ?>;
		SITEURL = '<?= SITEURL ?>';
		SITESECUREURL = '<?= SITESECUREURL ?>';
		SITEINSECUREURL = '<?= SITEINSECUREURL ?>';
		ASSETURL = '<?= ASSETURL ?>';
		COREASSETURL = '<?= COREASSETURL ?>';
		SITEASSETURL = '<?= SITEASSETURL ?>';
	</script>
	<script type="text/javascript" src="<?= COREASSETURL ?>/plugins/tiny_mce/tiny_mce.js?v=3"></script>
	<script type="text/javascript" src="<?= COREASSETURL ?>/js/TinyMCE.js"></script>
	<script type="text/javascript" src="<?= COREASSETURL; ?>/js/mootoolsCore.js,mootoolsMore.js,Interface.js,HistoryManager.js,StickyWin.js,Forms.js,BrowseArea.js,Table.js"></script>
	
	<link rel="stylesheet" href="<?= COREASSETURL; ?>/css/adminstyles.css,site.css,Table.css,stickywin.css" />

	<?php
	/* Page-specific script includes */
	echo $includeScript;
	?>
	
	<script type="text/javascript">
	window.addEvent('domready', function(){
		
		AJAXIMAGE = new Asset.image('<?=COREASSETURL;?>/images/site/ajax.gif', {id: 'Ajax Loading image', title: 'Loading...'}).setStyles({
			'position': 'absolute',
			'top': '50%',					// Centre the height
			'left': '50%',					// Centre the width
			'margin-top': '-33px',				// Half the height of the image
			'margin-left': '-33px'				// Half the width of the image
		});
		
		admin_started = true; // Used for ajax calls when the admin is logged out when still on a page
		
		/* Popups */
		var infoPopups = new InfoPopups($(document.body).getElements('a.info-popup'));
		
		<?= $domreadyScript; ?>
		
	});
	</script>

</head>
<body>
	<?php /* until whole thing goes live....
	<h1><?= $storename ?> Admin</h1>
	*/ ?>

	<?php if ($breadcrumbs = BluApplication::getBreadcrumbs()->get(false, array('include-home' => false))) { ?>
	<div class="breadcrumbs">
		<?= $breadcrumbs; ?>
	</div>
	<?php } ?>
	
	<?= Messages::getMessages(); ?>