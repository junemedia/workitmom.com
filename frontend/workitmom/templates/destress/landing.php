
	<div id="main-content" class="landing">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="destress_icon" class="icon fl"></div>
					<h1>De-stress</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

				<div class="block rounded-630-orange" id="page-intro">
				<div class="top"></div>

				<div class="content">
					<div class="img"><img src="<?= ASSETURL; ?>/landingimages/0/0/1/landing_destress.jpg"/></div>

					<div class="body">
						<h2>Feeling stressed out?</h2>
						<p>Take a bit of time for yourself, read about other moms' sanity savers, and get things off your chest in our polls!</p>
					</div>

					<div class="clear"></div>

				</div>

				<div class="bot"></div>
			</div>


			<div class="col-l">

				<?php $siteModules->featured_lifesaver(); ?>

				<div class="divider"></div>

				<?php $this->landing_just_for_you($articles['justforyou']); ?>

			</div>
			<? // END >> LEFT COLUMN  ?>

			<div class="col-r">

				<?php $siteModules->featured_slideshow(); ?>

				<div class="divider"></div>

				<?php $this->landing_poll(); ?>

			</div>
			<? // END >> MIDDLE COLUMN  ?>

			<div class="clear"></div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array(
'slideshow_featured', 'marketplace', 'catch_your_breath'
			)); ?>
		</div>
		<? // END >> RIGHT COLUMN  ?>

		<div class="clear"></div>
	</div>
