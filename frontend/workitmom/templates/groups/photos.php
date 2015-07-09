
	<div id="main-content" class="groups">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="groups_icon" class="icon fl"></div>
					<h1>Group Photos</h1>
				</div>
				<div class="bot"></div>
			</div>

			<div class="tab_menu">
				<ul>
					<li<?= $tab == 'browse' ? ' class="on"' : ''; ?>><a href="?tab=browse">Browse Photos</a></li>
					<li<?= $tab == 'upload' ? ' class="on"' : ''; ?>><a href="?tab=upload">Upload</a></li>
				</ul>
				<div class="clear"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<?php switch ($tab) {
				case 'browse':
					?>
			<div id="groupphotos_listing">				
				<?php $this->photos_listing(); ?>
				
				<?php Template::startScript(); ?>
				new BrowseArea('groupphotos_listing', {updateTask: 'photos_listing'});
				<?php Template::endScript(); ?>
			</div>
					<?php
					break;
				case 'upload':
					$this->photos_upload_tab();
					break;
			} ?>
		</div>

		<div class="panel-right">
			<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?>
		</div>

		<div class="clear"></div>
	</div>
