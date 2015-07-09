
	<div id="main-content" class="account">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="account_icon" class="icon fl"></div>
					<h1>My Messages</h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<?php include(BLUPATH_TEMPLATES.'/account/messages/nav.php'); ?>

			<div class="items-right">
				<?php $this->messages_listing(); ?>
			</div>

			<div class="clear"></div>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array('newsletter', 'account', array('ad_mini', 'account')), false); ?>
		</div>

		<div class="clear"></div>
	</div>