
	<div id="main-content" class="landing">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="explore_icon" class="icon fl"></div>
					<h1>Explore</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<?php /*$this->category_featured_article(); */?>

			<div class="col-l">

				<?php $this->landing_balancing_act($articles['balancing_act']); ?>

				<div class="divider"></div>

				<?php $this->landing_career_and_money($articles['career_and_money']); ?>

			</div>

			<div class="col-r">

				<?php $this->landing_pregnancy_and_parenting($articles['pregnancy_and_parenting']); ?>

				<div class="divider"></div>

				<?php $this->landing_your_business($articles['your_business']); ?>

			</div>

			<div class="clear"></div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array(
				'news', 'slideshow_featured', 'marketplace'
			)); ?>
		</div>

		<div class="clear"></div>
	</div>
