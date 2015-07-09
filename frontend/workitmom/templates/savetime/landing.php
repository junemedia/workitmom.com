
	<div id="main-content" class="landing">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="save_time_icon" class="icon fl"></div>
					<h1>Save Time</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="block rounded-630-orange" id="page-intro">
				<div class="top"></div>

				<div class="content">
					<div class="img"><img src="<?= ASSETURL; ?>/landingimages/0/0/1/landing_savetime.jpg"/></div>

					<div class="body">
						<h2>Short on time?</h2>
						<p>We've gathered helpful resources and tips all in one place to help make your daily juggle a bit more manageable.</p>

					</div>

					<div class="clear"></div>

				</div>

				<div class="bot"></div>
			</div>

			<div class="col-l">

				<?php $this->landing_quicktips(); ?>

				<div class="divider"></div>

				<?php $this->landing_essentials(); ?>


			</div>
			<? // END >> LEFT COLUMN  ?>

			<div class="col-r">

				<?php $this->landing_recipes(); ?>

				<div class="divider"></div>

				<?php $this->landing_checklists(); ?>

			</div>
			<? // END >> MIDDLE COLUMN  ?>

			<div class="clear"></div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>
		<? // END >> RIGHT COLUMN  ?>

		<div class="clear"></div>
	</div>
