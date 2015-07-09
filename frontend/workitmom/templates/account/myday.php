
	<div id="main-content" class="account">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="account_icon" class="icon fl"></div>
					<h1>My Day Archive</h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<?php
				// Feature snapshot if one exists for today
				if (!empty($myDays)) {
					reset($myDays);
					$myDay = (date("Y-m-d", key($myDays)) == date("Y-m-d") ? array_shift($myDays) : null);
			?>
			<div class="rounded-630-orange block" id="my_day_snapshot">
				<div class="top"></div>
				<div class="content">
					<a href="/account" class="button_dark fr"><span>Edit</span></a>

					<div class="header">
						<div class="title">
							<h2 style="width:80%;">My Day Snapshot - Today, <?= date("jS F Y"); ?></h2>
						</div>
					</div>

					<div class="text-content">
						<dl>
						<?php if(isset($myDay)) { foreach ($myDay as $item) { ?>
							<?php
								// Stressmeter
								if ($item['myId'] == 11) {
									$v = (int)$item['myAnswer'];
									$p = ($v-1)/100;
									$rgb = Color::hsvToRgb(array(((1-$p)*90), 1, 200));
									$hex = Color::rgbToHex($rgb);
									?><p style="margin-top:10px;"> Stress-o-meter: 
										<div class="stressslider" style="background: <?= $hex ?>;">
											<div class="knob" style="left: <?= (270 / 100) * $v ?>px;"></div>
										</div>
									</p>
							<?php } else { ?>
								<p><?= $item['myText'] ?>: <b><?= nl2br($item['myAnswer']) ?></b></p>
							<?php } ?>
						<?php }} else { ?>
							<p><?= $isSelf ? 'You haven\'t' : $user->name.' hasn\'t' ?> written a snapshot for today yet. 
								<a href="<?= SITEURL; ?>/account/#my_day_snapshot" class="scroll">Write one now?</a>
							</p>
						<?php } ?>
						<div class="clear"></div>
					</div>

				</div>
				<div class="bot"></div>
			</div>
			<?php } ?>

			<?php if (!empty($myDays)) { ?>
			<div class="block">
				<h2 style="width: 80%;">My Day Snapshot Archives</h2>
				<div class="clear"></div>
				<ul class="item_list" id="day_archive_list">
					<?php
						$alt = false;
						foreach ($myDays as $myDay) {
					?>
					<li<?= ($alt ? ' class="odd"' : '') ?>>
						<h4><?= Template::time($myDay[6]['myDate'], false, true); ?></h4>
						<div class="clear"></div>
						<div class="text-content">
							<?php foreach ($myDay as $item) { ?>
								<?php
									// Stressmeter
									if ($item['myId'] == 11) {
										$v = (int)$item['myAnswer'];
										$p = ($v-1)/100;
										$rgb = Color::hsvToRgb(array(((1-$p)*90), 1, 200));
										$hex = Color::rgbToHex($rgb);
										?><p style="margin-top:10px;"> Stress-o-meter: 
											<div class="stressslider" style="background: <?= $hex ?>;">
												<div class="knob" style="left: <?= (270 / 100) * $v ?>px;"></div>
											</div>
										</p>
								<?php } else { ?>
									<p><?= $item['myText'] ?>: <b><?= nl2br($item['myAnswer']) ?></b></p>
								<?php } ?>
							<?php } ?></p>
							<div class="clear"></div>
						</div>
					</li>
					<?php
							$alt = !$alt;
						}
					?>
				</ul>
			</div>
			<?php } ?>

			<div class="clear"></div>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array('newsletter', 'account', array('ad_mini', 'account')), false); ?>
		</div>

		<div class="clear"></div>
	</div>
