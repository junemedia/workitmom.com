
	<?php if (!empty($latestAlerts)) { ?>
	<div class="block" id="account_alerts">
		<div class="header">
			<div class="title">
				<h2>My Alerts</h2>
				<a href="<?= SITEURL ?>/account/alerts" class="button_dark fr"><span>See All</span></a>
				<a href="<?= SITEURL ?>/account/details?tab=alerts" class="button fr"><span>Edit</span></a>
			</div>

		</div>
		<div class="content">
			<ul>
				<?php
					$alt = true;
					foreach ($latestAlerts as $alert) { ?>
				<li>
					<?= $alert['format_list'] ?>
					<a class="delete" href="<?= SITEURL ?>/account/remove_alert?alert=<?= $alert['alertID'] ?>">Delete</a>
				</li>
				<?php
						$alt = !$alt;
					}
				?>
			</ul>

			<div class="clear"></div>
		</div>
	</div>
	<div class="divider"></div>
	<?php } ?>
