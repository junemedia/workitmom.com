
	<div id="main-content" class="lifesavers">
		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<?php $this->page_heading(); ?>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="col-l">
				<?php $this->landing_submit_lifesaver(); ?>
			</div>

			<div class="col-r">
				<?php $siteModules->featured_lifesaver(false); ?>
			</div>

			<div class="clear"></div>
			<div class="divider"></div>


			<div id="lifesaver_listing">

				<?php Template::startScript(); ?>
					var lifesaverListing = new BrowseArea('lifesaver_listing');
				<?php Template::endScript(); ?>

				<?php $this->listing(); ?>

			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>
		<? // END >> RIGHT COLUMN  ?>

		<div class="clear"></div>
	</div>
