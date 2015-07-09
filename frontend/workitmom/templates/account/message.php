
	<div id="main-content" class="account">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="balancing_act_icon" class="icon fl"></div>
					<h1>My Messages</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<?php include(BLUPATH_TEMPLATES.'/account/messages/nav.php'); ?>

			<div class="items-right">
				<div class="item_list small_list" id="message-detail">
					<ul id="message-current">
						<li class="odd">
							<div class="header">
								<h3><?= $message['subject'] ?></h3>
								<small class="date"><?= Template::time($message['sent'], true) ?></small>
								<div class="clear"></div>
								<p><em>
									<?php if ($message['type'] == 'sent') { ?>
									To: <a href="<?= SITEURL.$message['recipient']->profileURL ?>"><?= $message['recipient']->name ?></a>
									<?php } else { ?>
									From: <a href="<?= SITEURL.$message['sender']->profileURL ?>"><?= $message['sender']->name ?></a>
									<?php } ?>
								</em></p>
							</div>
							<div class="body text-content">
								<?= $message['body'] ?>
							</div>
							<div class="actions">
								<a href="<?= SITEURL ?>/account/write_message?reply=<?= $message['messageID'] ?>" class="button_dark fl"><span>Reply</span></a>
								<small><a href="<?= SITEURL ?>/account/messages?folder=<?= $folder ?>&amp;task=delete_message&amp;id=<?= $message['messageID'] ?>">Delete this message</a><?php /*&nbsp;|&nbsp; <a href="#">Report this</a>*/ ?></small>
							</div>
							<div class="clear"></div>
						</li>
					</ul>

					<?php if (!empty($messageHistory)) { ?>
					<h2>Recent message history with <?= ($message['type'] == 'sent') ? $message['recipient']->name : $message['sender']->name ?></h2>
					<ul>
						<?php
							$alt = true;
							foreach ($messageHistory as $message) {
						?>
						<li<?php if ($alt) { ?> class="odd"<?php } ?>>
							<div class="header">
								<h3><?= $message['subject'] ?></h3>
								<small class="date"><?= Template::time($message['sent'], true) ?></small>
								<div class="clear"></div>
								<p><em>From: <a href="<?= SITEURL.$message['sender']->profileURL ?>"><?= $message['sender']->name ?></a></em></p>
							</div>
							<div class="body text-content">
								<?= $message['body'] ?>
							</div>
							<div class="clear"></div>
						</li>
						<?php
								$alt = !$alt;
							}
						?>
					</ul>
					<?php } ?>

				</div>

			</div>
			<div class="clear"></div>
		</div>

		<div class="panel-right">
			<?php $this->sidebar(array('newsletter', 'account', array('ad_mini', 'account')), false); ?>
		</div>

		<div class="clear"></div>
	</div>