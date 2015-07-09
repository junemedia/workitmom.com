
	<div id="main-content" class="account">
		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="account_icon" class="icon fl"></div>
					<h1>My Profile Details</h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="tab_menu">
				<ul>
					<li<?= $tab == 'basic' ? ' class="on"' : ''?>><a href="/account/details?tab=basic">Basic Information</a></li>
					<li<?= $tab == 'work' ? ' class="on"' : ''?>><a href="/account/details?tab=work">My Work</a></li>
					<li<?= $tab == 'family' ? ' class="on"' : ''?>><a href="/account/details?tab=family">My Family</a></li>
					<li<?= $tab == 'life' ? ' class="on"' : ''?>><a href="/account/details?tab=life">My Life</a></li>
					<li<?= $tab == 'privacy' ? ' class="on"' : ''?>><a href="/account/details?tab=privacy">Privacy</a></li>
					<li<?= $tab == 'alerts' ? ' class="on"' : ''?>><a href="/account/details?tab=alerts">Alerts</a></li>
				</ul>
				<div class="clear"></div>
			</div>

			<div class="rounded-630-blue" id="account-details">
				<div class="top"></div>
				<div class="content">
					<div class="standardform" id="account-details-form">
						<div class="formholder">
							<?php
								switch($tab) {
									case 'basic': $this->details_basic(); break;
									case 'work': $this->details_work(); break;
									case 'family': $this->details_family(); break;
									case 'life': $this->details_life(); break;
									case 'privacy': $this->details_privacy(); break;
									case 'alerts': $this->details_alerts(); break;
									case 'delete': $this->details_delete(); break;
									default: $this->details_basic(); break;
								}
							?>
						</div>
					</div>
				</div>
				<div class="bot"></div>
			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array('newsletter', 'account', array('ad_mini', 'account')), false); ?>
		</div>

		<div class="clear"></div>
	</div>
