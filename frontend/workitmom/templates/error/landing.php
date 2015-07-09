
	<div id="main-content" class="404">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="balancing_act_icon" class="icon fl"></div>
					<h1>Page not found...</h1>
					<h2>Sorry, the content you were looking for could not be found</h2>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="col-l">

			</div>

			<div class="col-r">

			</div>

			<div class="clear"></div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
