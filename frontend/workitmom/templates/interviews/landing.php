
	<div id="main-content" class="interviews">
		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

					<div class="content">
					<div id="interviews_icon" class="icon fl"></div>
					<h1>Mom Interviews</h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			
			<div class="col-l">
				<?php $this->landing_featured_individual(array_shift($featureditems)); ?>
			</div>

			<div class="col-r">
				<?php $this->landing_featured_individual(array_shift($featureditems)); ?>
			</div>

			<div class="clear"></div>
						
			<div class="divider"></div>

			<div id="interview_listing">

				<?php Template::startScript(); ?>
					var interviewListing = new BrowseArea('interview_listing');
				<?php Template::endScript(); ?>

				<?php $this->listing(); ?>

			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
