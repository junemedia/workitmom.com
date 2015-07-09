
	<div id="main-content" class="blogs">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="blogs_icon" class="icon fl"></div>
					<h1>Member Blogs</h1>
				</div>

				<div class="bot"></div>
			</div>
			
			<a class="button_bright fr" style="margin: -60px 10px 0 0" href="<?= SITEURL ?>/contribute/blog"><span>Write a blog post</span></a>
			
			<div class="clear"></div>
			
			<?= Messages::getMessages(); ?>

			<div id="member_blogs">
				<?php $this->members_listing(); ?>
				
				<?php Template::startScript(); ?>
				new BrowseArea('member_blogs', {updateTask: 'members_listing'});
				<?php Template::endScript(); ?>		
			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?>
		</div>

		<div class="clear"></div>
	</div>
