
	<div id="main-content" class="account">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="account_icon" class="icon fl"></div>
					<h1>My Friends</h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="tab_menu">
				<ul>
					<li<?= $tab == 'friends' ? ' class="on"' : ''; ?>><a href="?tab=friends">My Network</a></li>
					<li<?= $tab == 'requests' ? ' class="on"' : ''; ?>><a href="?tab=requests">My Network Requests</a></li>
				</ul>
				<div class="clear"></div>
			</div>

			<?php $this->friends_tab($tab); ?>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array('newsletter', 'account', array('ad_mini', 'account')), false); ?>
		</div>

		<div class="clear"></div>
	</div>
