
	<?php if (!empty($requests)) { ?>
	<div class="item_list alert-list">
		<ul>
			<?php
				$alt = true;
				foreach ($requests as $request) {
					$friend = $request['friend'];
					$job = $friend['userInfo']['jobTitle'];
					$about = $friend['userInfo']['describeyourself'];
			?>
			<li class="friend<?= $alt ? ' odd' : ''; ?>">
				<div class="actions">
					<a href="<?= SITEURL ?>/profile/<?= $friend['username'] ?>" class="img"><img src="<?= ASSETURL ?>/userimages/110/110/1/<?= $friend['image']; ?>" /></a>
					<div class="clear"></div>
					<ul class="links">
						<li><a href="<?= SITEURL ?>/profile/<?= $friend['username'] ?>" class="button_dark fl"><span>View Profile</span></a><div class="clear"></div></li>
						<li><a href="<?= SITEURL; ?>/account/friends/?task=accept_friend&amp;id=<?= $friend['UserID'] ?>" class="button_bright fl"><span>Accept Friend</span></a></li>
						<li><a href="<?= SITEURL; ?>/account/friends/?task=reject_friend&amp;id=<?= $friend['UserID'] ?>" class="delete">Remove Friend</a></li>
					</ul>
					<div class="clear"></div>
				</div>
				<div class="info">
					<h3><a href="<?= SITEURL ?>/profile/<?= $friend['username'] ?>"><?= $friend['name'] ?></a></h3>
					<?php if ($job){ ?>
					<h4>Job:</h4>
					<p><?= $job ?></p>
					<?php } ?>
					<?php if ($about){ ?>
					<h4>About:</h4>
					<p><?= $about ?></p>
					<?php } ?>
					<?php if ($request['message']) { ?>
					<h4>Personal request message:</h4>
					<p><?= $request['message'] ?></p>
					<?php } ?>
				</div>
				<div class="clear"></div>
			</li>
			<?php
					$lat = !$alt;
				}
			?>
		</ul>
	</div>
	<div class="clear"></div>
	<?php } else { ?>
	<div class="message message-info">You do not have any network requests.</div>
	<?php } ?>