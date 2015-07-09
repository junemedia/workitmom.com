
	<div id="main-content" class="account">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="photos_icon" class="icon fl"></div>
					<h1>My Photos</h1>
				</div>
				<div class="bot"></div>
			</div>

			<div class="tab_menu">
				<ul>
					<li<?= $tab == 'manage' ? ' class="on"' : ''; ?>><a href="?tab=manage">Manage Photos</a></li>
					<li<?= $tab == 'upload' ? ' class="on"' : ''; ?>><a href="?tab=upload">Upload Photos</a></li>
				</ul>
				<div class="clear"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<?php
				switch ($tab) {
					case 'manage':
						include(BLUPATH_TEMPLATES.'/account/photos/manage.php');
						break;
					case 'upload':
						include(BLUPATH_TEMPLATES.'/account/photos/upload.php');
						break;
				}
			?>
		</div>
		<div class="panel-right">
			<?php $this->sidebar(array('newsletter', 'account', array('ad_mini', 'account')), false); ?>
		</div>

		<div class="clear"></div>
	</div>