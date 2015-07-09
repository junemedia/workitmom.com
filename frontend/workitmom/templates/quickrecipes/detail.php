
	<div id="main-content" class="quickrecipes">

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

			<div id="quickrecipe_title" class="rounded-630-blue">
				<div class="top"></div>
				<div class="content">
					<h2><?= $item->title ?></h2>
					<div class="im">
						<img src="<?php Template::image($item, 280, 0); ?>" />
					</div>
					<div class="body">
						<h3><?= $item->subtitle ?></h3>
						<div class="text-content"><?= $item->body ?></div>
						<?php if ($item->xlink) { ?>
						<p class="text-content"><a href="<?= $item->xlink ?>" target="_blank">Read full recipe</a></p>
						<?php } ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="bot"></div>
			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
