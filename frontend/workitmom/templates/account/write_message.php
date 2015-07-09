
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

			<div id="browse_bar" class="block">
				<h3>Browsing&hellip;</h3>
				<ul>
					<li><a href="/account/messages?folder=inbox">Inbox</a></li>
					<li><a href="/account/messages?folder=sent">Sent Messages</a></li>
					<li class="on"><a href="/account/write_message">Write a Message</a></li>
				</ul>
			</div>

			<div class="items-right">
				<div class="standardform" id="write-message">
					<h2>Write a Message</h2>
					<div class="formholder">
						<form method="post" action="<?= SITEURL ?>/account/messages" class="nofancy"><div>

							<dl>
								<dt><label>To</label></dt>
								<dd>
									<?php if ($replyMessage) { ?>
									<strong class="text-content"><?= $replyMessage['sender']->name ?></strong>
									<input type="hidden" name="recipients[]" value="<?= $replyMessage['sender']->userid ?>" />
									<?php } elseif (isset($recipientUser)) { ?>
									<strong class="text-content"><?= $recipientUser->name ?></strong>
									<input type="hidden" name="recipients[]" value="<?= $recipientUser->userid ?>" />
									<?php } else { ?>
									<div class="friends-checklist">
										<ul><?php foreach ($friends as $friend) {
											?><li><label><input type="checkbox" name="recipients[]"<?php if (in_array($friend->userid, $checkedFriends)) { ?> checked="checked"<?php } ?> value="<?= $friend->userid ?>" />
												<?= $friend->name ?></label></li><?php
											} ?></ul>
									</div>
									<?php } ?>
								</dd>

								<dt><label for="subject">Subject</label></dt>
								<dd>
									<input type="text" name="subject" id="subject" class="textinput" value="<?= $subject ?>" />
								</dd>

								<dt><label for="message">Message</label></dt>
								<dd>
									<textarea name="message" id="message" class="textinput" cols="12" rows="12"><?= $message ?></textarea>
								</dd>

								<dt></dt>
								<dd>
									<button type="submit"><span>Send Message</span></button>
								</dd>
							</dl>
							<div class="clear"></div>

							<input type="hidden" name="task" value="write_message_send" />
						</div></form>
					</div>
				</div>

				<div class="item_list small_list" id="message-detail">
					<?php if (!empty($messageHistory)) { ?>
					<h2>Recent message history with <?= $replyMessage['sender']->name ?></h2>
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
