
	<div id="main-content" class="account">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="account_icon" class="icon fl"></div>
					<h1>My Groups</h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="tab_menu">
				<ul>
					<li<?= $tab == 'joined' ? ' class="on"' : ''; ?>><a href="?tab=joined">Groups I've Joined</a></li>
					<li<?= $tab == 'owned' ? ' class="on"' : ''; ?>><a href="?tab=owned">Groups I've Created</a></li>
				</ul>
				<div class="clear"></div>
			</div>
			<?php
				switch ($tab) {
					case 'owned':
						include(BLUPATH_TEMPLATES.'/account/groups/owned.php');
						break;
					case 'joined':
						include(BLUPATH_TEMPLATES.'/account/groups/joined.php');
						break;
				}
			?>
		</div>
		<div class="panel-right">
			<?php $this->sidebar(array('newsletter', 'account', array('ad_mini', 'account')), false); ?>
		</div>

		<div class="clear"></div>
	</div>