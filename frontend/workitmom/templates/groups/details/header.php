
	<div class="rounded-630-outline block" id="group_header">
		<div class="top"></div>

		<div class="content">
			<div class="img">
				<img src="<?= ASSETURL.'/groupimages/125/125/1/'.$group['photo'] ?>">
				<div class="clear"></div>
			</div>
			<div class="body">
				<h2><?= $group['name'] ?></h2>
				<h3><?= $group['blurb'] ?></h3>
				<p class="text-content underline">
					Group founder: <a href="<?= SITEURL.$owner->profileURL ?>"><?= $owner->name ?></a> &nbsp;|&nbsp;
					<a href="<?= SITEURL.'/groups/members/'.$groupId ?>"><?= $numMembers ?> member<?= Text::pluralise($numMembers) ?></a> &nbsp;|&nbsp;
					<a href="<?= SITEURL.'/groups/discussions/'.$groupId ?>"><?= $numTopics ?> discussion<?= Text::pluralise($numTopics) ?></a>
					<?php if (!empty($group['tags'])) { ?>
						<div class="tags text-content">Tags:
						<?php foreach ($group['tags'] as $tag) { ?>
						<a href="<?= SITEURL.'/groups/tags/'.$tag ?>"><?= strtolower($tag) ?></a>
						<?php } ?>
						</div>
					<?php } ?>
				</p>
			</div>
			<div class="clear"></div>
			<div class="buttons">
				<?php if ($group['isMember']) { ?>
				<a href="<?= SITEURL.'/tellafriend/group/'.$group['id'] ?>" class="button_dark fl"><span>Invite Friends</span></a>
				<?php } else { ?>
				<a href="<?= SITEURL.'/groups/join/'.$group['id'] ?>" class="button_dark fl"><span>+ Join now</span></a>
				<?php } ?>
				<?php if ($group['isSubscribed']) { ?>
				<a href="<?= SITEURL.'/groups/unsubscribe/'.$group['id'] ?>" class="button_dark fl"><span>Unsubscribe from discussions</span></a>
				<?php } else { ?>
				<a href="<?= SITEURL.'/groups/subscribe/'.$group['id'] ?>" class="button_dark fl"><span>Subscribe to discussions</span></a>
				<?php } ?>
				<?php if ($group['isMember']) { ?>
				<a href="<?= SITEURL.'/groups/photos/'.$group['id'].'/?tab=upload'; ?>" class="button_dark fl"><span>Upload a photo</span></a>
				<a href="<?= SITEURL.'/groups/leave/'.$group['id'] ?>" class="button_dark fl"><span>Leave group</span></a>
				<?php } ?>
				<div class="clear"></div>
			</div>
		</div>

		<div class="bot"></div>
	</div>