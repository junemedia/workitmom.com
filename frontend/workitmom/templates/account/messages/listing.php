
	<form action="" name="" id=""><div>
		<div id="sort_bar">

			<!--<div id="message-actions" class="fr">
				<label for="select-messages">select</label>
				<select name="actions" id="select-messages">
					<option value="">none</option>
					<option value="">read</option>
					<option value="">unread</option>
					<option value="">all</option>
				</select>
				<a href="#">Mark as Read</a>
				<a href="#">Mark as Unread</a>
				<a href="#">Delete</a>
			</div>-->

			<div class="text-content fl"><?= $offset + 1 ?> - <?= min($offset + $limit, $total) ?> of <strong><?= $total ?></strong> message<?= Text::pluralise($total) ?></div>

			<div class="clear"></div>
		</div>

		<table id="inbox" class="messaging-system">
			<thead>
				<tr>
					<th>Subject</th>
					<th><?= $folder == 'inbox' ? 'From' : 'To' ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
					if (!empty($messages)) {
						foreach($messages as $message) {
							$link = SITEURL.'/account/message/'.$message['messageID'];
				?>
				<tr<?= $message['read'] ? '' : ' class="new-message"'; ?>>
					<td class="message-subject"><a href="<?= $link ?>"><?= $message['subject'] ?></a></td>
					<td>
						<?php if ($folder == 'inbox') { ?>
						<a href="<?= SITEURL.$message['sender']->profileURL ?>"><?= $message['sender']->name ?></a>
						<?php } else { ?>
						<a href="<?= SITEURL.$message['recipient']->profileURL ?>"><?= $message['recipient']->name ?></a>
						<?php } ?>
						<small><?= Template::time($message['sent'], true) ?></small>
					</td>
					<td class="message-actions"><a href="/account/messages?folder=<?= $folder ?>&amp;task=delete_message&amp;id=<?= $message['messageID'] ?>" title="Delete"><img src="<?= SITEASSETURL ?>/images/site/icon-delete.png" alt="Delete" /></a></td>
				</tr>
				<?php
						}
					}
				?>
			</tbody>
		</table>

		<?= $pagination->get('buttons'); ?>

	</div></form>