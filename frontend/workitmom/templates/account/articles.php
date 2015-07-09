
	<div id="main-content" class="account">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="articles_icon" class="icon fl"></div>
					<h1>My Articles</h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="tab_menu">
				<ul>
					<li<?= $tab == 'saved' ? ' class="on"' : ''; ?>><a href="?tab=saved">Articles I've Saved</a></li>
					<li<?= $tab == 'owned' ? ' class="on"' : ''; ?>><a href="?tab=owned">Articles I've Written</a></li>
				</ul>
				<div class="clear"></div>
			</div>
			<?php
				switch ($tab) {
					case 'saved':
						include(BLUPATH_TEMPLATES.'/account/articles/saved.php');
						break;
					case 'owned':
						include(BLUPATH_TEMPLATES.'/account/articles/owned.php');
						break;
				}
			?>
		</div>
		<div class="panel-right">
			<?php $this->sidebar(array('newsletter', 'account', array('ad_mini', 'account')), false); ?>
		</div>

		<div class="clear"></div>
	</div>
