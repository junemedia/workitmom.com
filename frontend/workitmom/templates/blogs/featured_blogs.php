
	<div id="main-content" class="blogs">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="blogs_icon" class="icon fl"></div>
					<h1>Featured Blogs</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="block" id="featured_blogs">

				<ul class="item_list blog_list">
					<? if(Utility::is_loopable($blogs)) { foreach((array)$blogs as $blog) {
						$this->featured_individual($blog);
					}} ?>
				</ul>
			</div>
			
			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
