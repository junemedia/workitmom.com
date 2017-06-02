<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?= $title; ?></title>
	<?php if (strstr($_SERVER['REQUEST_URI'],'/links')) { ?><meta name="robots" content="nofollow" /><?php } ?>
	
	<meta name="keywords" content="working moms, work it mom, community, connect with others, professional" />
	<meta name="description" content="Work It, Mom! is an online community for working moms. Whether you work outside the home, at home, or run your own business, you can come to Workitmom.com to connect with other moms similar to you and share advice on anything from balancing work and family, finding quick dinner recipes, getting better organized, growing your business, finding time for yourself, and much more." />
	<meta name="author" content="blubolt Design, www.blubolt.com" />
	<meta name="google-site-verification" content="zqNnlb9kPVvoStriY2UvMdrJOlErSd9XEEoUNPw-h_M" />
	<meta name="norton-safeweb-site-verification" content="arar48u-a250q41u9655u9t1z9axdz3t7d3plfhs988y31w2evk59bhfc2dp9l94ruetqihbdcfvaci8j52qkp3n82b3kiy7fnbvn1f99vwvflf2t81vapd9a6ye5gfx" />
	<link rel="stylesheet" href="<?= SITEASSETURL; ?>/css/global.css,sifrScreen.css?v=3" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?= SITEASSETURL; ?>/css/sifrPrint.css?v=3" type="text/css" media="print" />
	<link rel="stylesheet" href="<?= SITEASSETURL; ?>/css/print.css?v=3" type="text/css" media="print" />
	<!--[if IE 6]><link href="<?= SITEASSETURL; ?>/css/ie6.css?v=3" rel="stylesheet" type="text/css" /><![endif]-->
	<!--[if IE 7]><link href="<?= SITEASSETURL; ?>/css/ie7.css?v=3" rel="stylesheet" type="text/css" /><![endif]-->
	<link rel="shortcut icon" href="<?= SITEASSETURL; ?>/images/favicon.ico" type="image/vnd.microsoft.icon" />
	<link rel="icon" href="<?= SITEASSETURL; ?>/images/favicon.ico" type="image/vnd.microsoft.icon" />
	<link rel="stylesheet" href="<?= SITEASSETURL; ?>/css/rotator.css" type="text/css"/>

	<?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'CrazyEgg Robot') !== 0) { ?>
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
	<script type="text/javascript" src="<?= COREASSETURL ?>/js/mootoolsCore.js,mootoolsMore.js,StickyWin.js,Interface.js,Nav.js,HistoryManager.js,Forms.js,BrowseArea.js,Autocompleter.js,Milkbox.js,Wizard.js,sifr.js,TinyMCE.js?v=3"></script>
	<script type="text/javascript" src="<?= SITEASSETURL ?>/js/sifrConfig.js,Color.js?v=3"></script>

	<?php
		// Page-specific script includes
		echo $includeScript;

		// Generic bits of header
		echo $genericHeader;
	?>

	<script type="text/javascript">
	window.addEvent('load', function(){

		/* Init history manager */
		HistoryManager.initialize();

		/* Get reference to body content */
		var bodyContent = $('content-wrapper');

		/* Top nav */
		var topNav = new TopNav($('nav-top'));

		/* Standard forms */
		bodyContent.getElements('div.standardform, fieldset.standardform').each(function(formcontainer) {
			var standardForm = new StandardForm(formcontainer);
		});

		<?= $domreadyScript; ?>

		/* Popups */
		var infoPopups = new InfoPopups(bodyContent.getElements('a.info-popup'));

		/* Page scroll */
		var pageScroll = new PageScroll(bodyContent.getElements('a.scroll'), {
			wheelStops: false
		});

		/* Start history manager */
		HistoryManager.start();

		/* Input over text */
		var overText = new OverText($$('input.overtext, textarea.overtext'));

	});
	</script>
	<?php } ?>
<meta name="google-site-verification" content="Xh7dE8-tonX-jlc7xX0zTH10ML0m82yrn7lyLbVLtww" />
	<!-- Yieldbot.com Intent Tag LOADING -->
    <script type="text/javascript" src="https://cdn.yldbt.com/js/yieldbot.intent.js"></script>
    <!-- Yieldbot.com Intent Tag ACTIVATION -->
    <script type="text/javascript">
        yieldbot.pub('a173');
        yieldbot.defineSlot('LB_ATF');
        yieldbot.defineSlot('MR_ATF');
        yieldbot.defineSlot('MR_Mid');
        yieldbot.defineSlot('LB_BTF');
        yieldbot.go();
    </script>
    <!-- END Yieldbot.com Intent Tag -->
</head>

<body>

<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-W4CRGD"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-W4CRGD');</script>
<!-- End Google Tag Manager -->

<?php
//if ($_SERVER['REMOTE_ADDR'] == '216.180.167.121') {
include_once("dhtml.php");
//}
?>

<div id="site-wrapper">

	<div id="ad-header">
    <?php include BLUPATH_TEMPLATES.'/site/ads/openx_728x90atf.html.php'; ?>
	</div>

	<div id="nav-header">

		<div id="logo">
			<a href="/"><img class="screenonly" alt="Work it, Mom!" src="<?= SITEASSETURL; ?>/images/site/logo.png" /></a>
			<a href="/"><img class="printonly" alt="Work it, Mom!" src="<?= SITEASSETURL; ?>/images/site/logo.png" /></a>
		</div>

		<div id="tagline" class="screenonly">
			<img src="<?= SITEASSETURL; ?>/images/site/tagline.png" alt="A place where working moms connect" />
		</div>

		<div class="fr header-right screenonly">
			<div id="nav-links" class="screenonly">
				<ul>
					<li><a href="<?=SITEURL; ?>/about">About Us</a></li>
					<li><a href="<?=SITEURL; ?>/help">Help &amp; FAQs</a></li>
					<li><a href="<?=SITEURL; ?>/sitebadges">Site Badges</a></li>
					<li><a href="<?=SITEURL; ?>/tellafriend">Tell a Friend</a></li>
					<li><a href="<?=SITEURL; ?>/gettingstarted">Getting Started</a></li>
				</ul>
			</div>

			<div class="clear"></div>

			<form id="nav-top-form-search" action="<?= SITEURL; ?>/search" method="post" class="search" style="position: relative;"><div>
				<div class="input-wrapper"><input class="textinput overtext" type="text" title="Search" autocomplete="off" name="search" value="<?php // if (Session::get('search_terms')) { echo implode(' ', Session::get('search_terms')); } ?>" /></div>
				<button class="but-find" type="submit" title="Find"></button>
				<a href="http://www.facebook.com/workitmom" target="_blank"><img class="" alt="Facebook" src="<?= SITEASSETURL; ?>/images/site/icon_fb.gif" /></a>
				<a href="http://www.twitter.com/work_it_mom" target="_blank"><img class="" alt="Twitter" src="<?= SITEASSETURL; ?>/images/site/icon_tw.gif" /></a>
				<a href="http://www.pinterest.com/workitmomcom" target="_blank"><img class="" alt="Pinterest" src="<?= SITEASSETURL; ?>/images/site/icon_pi.gif" /></a>
				</div>
			</form>

		</div>

		<div class="clear"></div>
		<div class="top_giveaway"> 
			<a href="/giveaway" target="_blank"><img class="screenonly" alt="Work it, Mom!" src="<?= SITEASSETURL; ?>/images/site/giveaway.png" /></a> 
		</div>

		<?= $topNav; ?>

	</div>

	<div id="content-wrapper">

		<?php if ($breadcrumbs) { ?>
		<div class="breadcrumbs">
			<?= $breadcrumbs; ?>
		</div>
		<?php } ?>
