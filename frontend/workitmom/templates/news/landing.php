
	<div id="main-content" class="news">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="news_icon" class="icon fl"></div>
					<h1>Latest News</h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<?php include(BLUPATH_TEMPLATES.'/news/landing/header.php'); ?>

			<div id="news_listing">

				<? Template::startScript(); ?>
					var newsListing = new BrowseArea('news_listing');
				<? Template::endScript(); ?>

				<? $this->listing(); ?>

			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
