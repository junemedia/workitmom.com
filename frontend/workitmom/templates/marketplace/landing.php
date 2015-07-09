
	<div id="main-content" class="marketplace">
		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="marketplace_icon" class="icon fl"></div>
					<h1>Marketplace</h1>
				</div>

				<div class="bot"></div>
			</div>
			<a class="button_bright fr" style="margin: -60px 10px 0 0" href="<?= SITEURL ?>/marketplace/create"><span>Promote your business!</span></a>

			<?= Messages::getMessages(); ?>

			<?php include(BLUPATH_TEMPLATES . '/marketplace/landing/heading.php'); ?>

			<?php BluApplication::getModules('site')->search('products'); ?>

			<div id="overview_title">
				<h2>Marketplace Listings</h2>
			</div>
			<div class="clear"></div>

			<div id="marketplace_listing">
				<?php Template::startScript(); ?>
					var marketplaceListing = new BrowseArea('marketplace_listing');
				<?php Template::endScript(); ?>

				<?php $this->listing(); ?>
			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('promote_your_business', 'slideshow_featured', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
