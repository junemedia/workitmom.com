
	<div id="main-content" class="slideshows">
		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="slideshows_icon" class="icon fl"></div>
					<h1>Slideshows</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="col-l">
				<?php $this->landing_featured_main(); /* Different template to BluApplication::getModules('site')->featured_slideshow()  */ ?>
			</div>

			<div class="col-r">
				<?php $this->landing_featured_others(); ?>
			</div>

			<div class="clear"></div>

			<div class="divider"></div>

			<div id="slideshow_listing">

				<?php Template::startScript(); ?>
					var slideshowListing = new BrowseArea('slideshow_listing');
				<?php Template::endScript(); ?>

				<?php $this->listing(); ?>

			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
