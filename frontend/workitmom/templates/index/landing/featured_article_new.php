
	<?

	// Uses $homepageArticles, which should be an object with variables:
	// 	major: a single stdClass object, with your data.
	//	minors: an array of two (or less) stdClass objects, each of which contain your other data.
echo '<script type="text/javascript" src="<?= COREASSETURL ?>/js/Interface.js"></script>';
	?>	
		<div id="featured_recipe" class="rounded">

		<div class="content">
			
			<div id="panels-holder">
				<div id="panels-inner" style="width: <?= 650 * count($homepageArticles) + 50; // For good measure... ?>px;">
					<?php $i = 0; foreach ($homepageArticles as $item) { $i++; ?>
					<div id="panel-<?= $i; ?>" class="panel">
						<a href="<?= SITEURL.$item->url; ?>"><img alt="<?= $item->title; ?>" src="<?= ASSETURL; ?>/landingimages/610/200/3/<?= isset($item->image) ? $item->image : 'default.jpg'; ?>" /></a>
						
						<h3><a href="<?php echo SITEURL.$item->url; ?>"><?php echo $item->title; ?></a></h3>
						<p class="text-content"><?= Text::trim($item->body, 150); ?></p>
						<a href="<?= SITEURL.$item->url; ?>" class="text-content more fr">View full <?= 'article'; ?></a>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="clear"></div>
			
			<ul id="panels-nav" class="others" style="zoom:1;">
				<?php $i = 0; foreach ($homepageArticles as $item) { $i++; ?>
				<li class="panel-<?= $i; ?><?= $i == 1 ? ' first' : ($i == count($homepageArticles) ? ' last' : ''); ?>">
					<a href="#panel-<?= $i; ?>"><img alt="<?= $item->title; ?>" src="<?= ASSETURL; ?>/landingimages/90/57/3/<?= isset($item->image) ? $item->image : 'default.jpg'; ?>" width="90" height="57" /></a>
				</li>
				<?php } ?>
			</ul>
			<div class="clear2"></div>
		</div>
	</div>

	<?php //Template::startScript(); ?>
	<script language="javascript">
	var panels = new Panels('panels-holder', 'panels-nav', {
		defaultPanel: 'panel-1',
		transition: 'expo:out',
		rotate: 5000,
		updateHash: false
	});
	</script>
	<?php //Template::endScript(); ?>
