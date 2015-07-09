
	<?php if (!empty($members)) { ?>
	<div class="rounded-630-outline members" id="group-members">
		<div class="top"></div>

		<div class="content grid_list member_list">
			<h2>Group Members</h2>
			<?php if ($numMembers > 12) { ?>
			<a href="<?= SITEURL.'/groups/members/'.$groupId ?>" class="button_dark fr"><span>see all <?= $numMembers ?> members</span></a>
			<?php } ?>
			<ul>
			<?php
				foreach($members as $member) {
					$link = SITEURL.$member->profileURL;
			?>
				<li>
					<a href="<?= $link ?>" class="img"><img src="<?= ASSETURL.'/userimages/80/80/1/'.$member->image ?>" /></a>
					<a href="<?= $link ?>"><?= $member->name ?></a>
				</li>
			<?php
				}
			?>
			</ul>
			<div class="clear"></div>
		</div>

		<div class="bot"></div>
	</div>
	<?php } ?>
