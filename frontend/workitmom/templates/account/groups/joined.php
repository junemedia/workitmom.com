
	<?php if (!empty($joinedGroups)) { ?>
	<div class="item_list">
		<ul>
			<?php
				$alt = true;
				foreach ($joinedGroups as $group) {
					$link = SITEURL.'/groups/detail/'.$group['id'];
			?>
			<li<?= $alt ?' class="odd"' : '' ?>>
				<div class="actions">
					<a href="<?= $link ?>"><img src="<?= ASSETURL.'/groupimages/100/100/1/'.$group['photo'] ?>"></a>
					<ul class="links">
						<li><a href="<?= SITEURL.'/groups/leave/'.$group['id'] ?>" class="button_dark fl"><span>Leave group</span></a></li>
					</ul>
				</div>
				<div class="info">
					<h3><a href="<?= $link ?>" class="title"><?= $group['name'] ?></a></h3>
					<small><?= $group['blurb'] ?></small>
				</div>
				<div class="clear"></div>
			</li>
			<?php
					$alt = !$alt;
				}
			?>
		</ul>
	</div>
	<?php } else { ?>
	<div class="message message-info">You have not joined any <a href="<?= SITEURL ?>/groups">groups</a> yet!</div>
	<?php } ?>