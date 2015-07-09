
	<div id="main-content" class="essentials">
		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<?php $this->page_heading(); ?>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="col-l">
				<?php $this->landing_intro(); ?>
			</div>

			<div class="col-r">
				<?php include(BLUPATH_TEMPLATES.'/essentials/blocks/search.php'); ?>
				<div class="divider"></div>
				<?php $this->landing_featured(); ?>
			</div>

			<div class="clear"></div>
			<div class="divider"></div>

			<div id="essentials_listing">

				<?php Template::startScript(); ?>
					var essentialsListing = new BrowseArea('essentials_listing');
				<?php Template::endScript(); ?>

				<?php $this->listing(); ?>

			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array(
			'slideshow_featured', 'marketplace', 'catch_your_breath'
)); ?>
		</div>

		<div class="clear"></div>
	</div>
