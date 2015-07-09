
	<div id="main-content" class="account">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="account_icon" class="icon fl"></div>
					<h1>My Account</h1>
					<h3>You are signed in as <?= $user->username; ?>.  <a href="/account/logout">Log out?</a></h3>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="col-l">
				<?php include(BLUPATH_TEMPLATES.'/account/main/my_day.php'); ?>
				<?php include(BLUPATH_TEMPLATES.'/account/main/todolist.php'); ?>
			</div>

			<div class="col-r">
				<?php include(BLUPATH_TEMPLATES.'/account/main/alerts.php'); ?>
				<?php include(BLUPATH_TEMPLATES.'/account/main/blog_posts.php'); ?>
				<?php include(BLUPATH_TEMPLATES.'/account/main/featured_question.php'); ?>
				<div class="divider"></div>
				<?php BluApplication::getModules('site')->daily_inspiration(); ?>
			</div>

			<div class="clear"></div>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array('newsletter', 'account', array('ad_mini', 'account')), false); ?>
		</div>

		<div class="clear"></div>
	</div>