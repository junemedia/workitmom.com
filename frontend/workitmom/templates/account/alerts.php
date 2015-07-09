
	<div id="main-content" class="account">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content with-buttons">
					<div id="account_icon" class="icon fl"></div>
					<h1>My Alerts</h1>
				</div>
				<div class="buttons">
					<a href="/account/details?tab=alerts" class="button_bright fr"><span>Edit alerts</span></a>
					<a href="/account/alerts?task=remove_all_alerts" class="button_dark fr"><span>Delete all</span></a>
				</div>
				<div class="clear"></div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<?php if (!empty($alerts)) { ?>
			<div class="item_list alert-list">
				<ul>
					<?php
						$alt = true;
						foreach ($alerts as $alert) { ?>
					<li<?php if ($alt) { ?> class="odd"<?php } ?>>
						<span class="alert-detail fl"><?= $alert['format_list'] ?></span>
						<a href="<?= SITEURL ?>/account/remove_alert?alert=<?= $alert['alertID'] ?>" title="Delete" class="delete fr">Delete</a>
						<div class="clear"></div>
					</li>
					<?php
							$alt = !$alt;
						}
					?>
				</ul>
			</div>
			<div class="clear"></div>
			<?php } else { ?>
			<div class="message message-info">You do not have any alerts.</div>
			<?php } ?>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array('newsletter', 'account', array('ad_mini', 'account')), false); ?>
		</div>

		<div class="clear"></div>
	</div>
