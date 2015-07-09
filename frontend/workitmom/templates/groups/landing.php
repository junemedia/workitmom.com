
	<div id="main-content" class="groups">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="groups_icon" class="icon fl"></div>
					<h1>Join Group Discussions</h1>
				</div>
				<div class="bot"></div>
			</div>
			<a class="button_bright fr" style="margin: -60px 10px 0 0" href="<?= SITEURL ?>/groups/create"><span>Create your own group!</span></a>

			<?= Messages::getMessages(); ?>

			<?php include(BLUPATH_TEMPLATES.'/groups/landing/header.php'); ?>

			<h2>Latest Group Discussions</h2>
			<div id="groups_listing">
				<?php $this->listing(); ?>
			</div>
			<?php Template::startScript(); ?>
				var groupsListing = new BrowseArea('groups_listing');
			<?php Template::endScript(); ?>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
<?php $this->sidebar(array('create_group','slideshow_featured', 'marketplace', 'catch_your_breath')); ?>
		</div>

		<div class="clear"></div>
	</div>
