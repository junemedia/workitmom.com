
	<div id="main-content" class="groups">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="groups_icon" class="icon fl"></div>
					<div style="width: 400px">
						<h1><?= $group['name'] ?></h1>
					</div>
				</div>

				<div class="bot"></div>
			</div>
			<a class="button_bright fr" style="margin: -60px 10px 0 0" href="<?= SITEURL ?>/groups/create"><span>Create your own group!</span></a>

			<?= Messages::getMessages(); ?>

			<?php include(BLUPATH_TEMPLATES.'/groups/details/header.php'); ?>

			<?php include(BLUPATH_TEMPLATES.'/groups/details/discussions.php'); ?>

			<div class="divider"></div>

			<?php include(BLUPATH_TEMPLATES.'/groups/details/members.php'); ?>

			<div class="clear" style="height:26px"></div>
			<?php include(BLUPATH_TEMPLATES.'/groups/details/photos.php'); ?>
			<div class="clear" style="height:26px"></div>
			<?php include(BLUPATH_TEMPLATES.'/groups/details/links.php'); ?>


			<div class="clear"></div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(Array('slideshow_featured', 'marketplace', 'catch_your_breath'
)); ?>
		</div>

		<div class="clear"></div>
	</div>
