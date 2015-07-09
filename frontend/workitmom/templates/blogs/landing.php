
	<div id="main-content" class="blogs">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="blogs_icon" class="icon fl"></div>
					<h1>From our bloggers</h1>
				</div>

				<div class="bot"></div>
			</div>
			<!--<a class="button_bright fr" style="margin: -60px 10px 0 0" href="<?= SITEURL ?>/contribute/blog"><span>Write a blog post</span></a>-->

			<?= Messages::getMessages(); ?>

			<div class="col-l">

			<div class="block" id="featured_blogs">
				<div class="header">
					<div class="title">
						<h2>Featured Blogs</h2>
					</div>
					<a href="<?= SITEURL; ?>/blogs/featured/" class="button_dark"><span>See all</span></a>
				</div>
				<div id="featured_blogs_landing_module">
					<?php $this->landing_featured(); ?>

					<?php Template::startScript(); ?>
					new BrowseArea('featured_blogs_landing_module', {updateTask: 'landing_featured'});
					<?php Template::endScript(); ?>
				</div>
			</div>

				<div class="divider"></div>
				<?php $this->landing_members(); ?>
			</div>

			<div class="col-r">
				<?php include(BLUPATH_TEMPLATES.'/blogs/landing/popular.php') ?>
				<?php BluApplication::getModules('site')->this_weeks_topic(); ?>
				<?php include(BLUPATH_TEMPLATES.'/blogs/landing/spotlight.php') ?>
			</div>

			<div class="clear"></div>
			
			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?>
		</div>

		<div class="clear"></div>
	</div>
