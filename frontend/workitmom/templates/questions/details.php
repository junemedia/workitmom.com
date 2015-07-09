
	<div id="main-content" class="<?= $cssClass; ?>">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<?php $this->page_heading(); ?>

				<div class="bot"></div>
			</div>
			<a class="button_bright fr" style="margin: -60px 10px 0 0" href="<?= SITEURL ?>/questions"><span>Ask a question</span></a>

			<?= Messages::getMessages(); ?>

			<?php $this->detail_title(); ?>

			<?php $this->comments_add(); ?>

			<?php $this->comments_view(array('type' => 'question')); ?>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
