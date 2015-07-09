
	<div id="main-content" class="memberlisting">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="members_icon" class="icon fl"></div>
					<h1>Meet Members</h1>
				</div>

				<div class="bot"></div>
			</div>
			<a class="button_bright fr" style="margin: -60px 10px 0 0" href="<?= SITEURL ?>/connect/member_search"><span>Search for members</span></a>

			<?php $this->tab_members(); ?>
			
			<div class="clear" style="height:25px;"></div>
			
			<?php $this->tab_photos(); ?>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>
		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>
		<? // END >> RIGHT COLUMN  ?>

		<div class="clear"></div>
	</div>
