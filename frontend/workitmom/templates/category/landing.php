
	<div id="main-content" class="landing">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="<?= $categorySlug ?>_icon" class="icon fl"></div>
					<h1><?= $category ?></h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<? $this->category_featured_article(); ?>

			<div class="col-l">

				<?php //$siteModules->from_our_bloggers(); //removed at 20170717?>

				<!--removed at 20170717 <div class="divider"></div>-->

				<?php $siteModules->sound_off(); ?>

				<?php if ($category != 'Balancing Act') { ?>

					<div class="divider"></div>
					<?php $siteModules->featured_lifesaver(); ?>

				<?php } ?>

			</div>

			<div class="col-r">

				<?php $siteModules->featured_question(); ?>

				<div class="divider"></div>

				<?php  include(BLUPATH_TEMPLATES.'/category/landing/meet_a_member.php'); ?>

				<div class="divider"></div>

				<?php $siteModules->daily_inspiration(); ?>

			</div>

			<div class="clear"></div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array(
				'slideshow_featured',
				'marketplace',
				'catch_your_breath'
			)); ?>
		</div>
		<div class="clear"></div>
	</div>
