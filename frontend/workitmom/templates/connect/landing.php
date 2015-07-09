
	<div id="main-content" class="landing">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="connect_icon" class="icon fl"></div>
					<h1>Connect</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="block rounded-630-orange" id="page-intro">
				<div class="top"></div>

				<div class="content">
					<div class="img"><img src="<?= ASSETURL; ?>/landingimages/0/0/1/landing_connect.jpg"/></div>

					<div class="body">
						<h2>Never feel alone!</h2>
						<p>Find support in our community by joining a group discussion, asking a question, or connecting with another mom in your area.</p>
					</div>

					<div class="clear"></div>

				</div>

				<div class="bot"></div>
			</div>

			<div class="col-l">

				<?php $this->member_question(); ?>

				<div class="divider"></div>

				<?php $siteModules->newest_members(); ?>

			</div>
			<? // END >> LEFT COLUMN  ?>

			<div class="col-r">

				<?php $siteModules->sound_off(6); ?>

				<div class="divider"></div>

				<?php $siteModules->working_mom_interviews(); ?>

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
