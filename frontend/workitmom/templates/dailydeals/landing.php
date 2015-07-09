
	<div id="main-content" class="dailydeal">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<? $this->page_heading(); ?>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<?php include(BLUPATH_TEMPLATES.'/dailydeals/landing/featured.php'); ?>

			<h2>Browse Daily Deals...</h2>

			<div id="dailydeals_listing">

				<? Template::startScript(); ?>
					var dailydealsListing = new BrowseArea('dailydeals_listing');
				<? Template::endScript(); ?>

				<? $this->listing(); ?>

			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
<?php $this->sidebar(array('daily_grommet', 'slideshow_featured', 'marketplace', 'catch_your_breath')); ?>
		</div>

		<div class="clear"></div>
	</div>
