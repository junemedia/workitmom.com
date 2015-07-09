
	<?php if (!empty($friends)) { ?>
	<div class="rounded-630-outline block" id="friends-block">
		<div class="top"></div>
		<div class="content grid_list member_list">
			<h2><?= $isSelf ? 'My' : $person->name . '\'s'; ?> Friends</h2>

			<?php if ($totalfriends > 12) { ?>
			<a class="button_dark fr" href="<?= SITEURL ?>/profile/friends/<?= $person->username ?>"><span>see all <?= $totalfriends; ?> friend<?= Text::pluralise($totalfriends); ?></span></a>
			<?php } ?>
			<ul>
				<?php
				foreach ($friends as $friend) {
					$link = SITEURL . '/profile/' . $friend->username;
				?>
				<li>
					<a class="img" href="<?= $link; ?>"><img src="<?= ASSETURL; ?>/userimages/80/80/1/<?= $friend->image; ?>"/></a>
					<a href="<?= $link; ?>"><?= $friend->name; ?></a>
				</li>
				<?
					}
				?>
			</ul>
			<div class="clear"></div>
		</div>
		<div class="bot"></div>
	</div>
	<?php } ?>
