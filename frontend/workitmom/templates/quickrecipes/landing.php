
	<div id="main-content" class="quickrecipe">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="recipes_icon" class="icon fl"></div>
					<h1>Quick Recipes</h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<?php $this->landing_featured(); ?>

			<h2>Browse Quick Recipes...</h2>

			<div id="quickrecipes_listing">

				<? Template::startScript(); ?>
					var quickrecipesListing = new BrowseArea('quickrecipes_listing');
				<? Template::endScript(); ?>

				<? $this->listing(); ?>

			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
