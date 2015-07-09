
	<?php if (!empty($ownedGroups)) { ?>
	<div class="item_list">
		<ul>
			<?php
				$alt = true;
				foreach ($ownedGroups as $group) {
					$link = SITEURL.'/groups/detail/'.$group['id'];
			?>
			<li<?= $alt ?' class="odd"' : '' ?>>
				<div class="actions">
					<a href="<?= $link ?>"><img src="<?= ASSETURL.'/groupimages/100/100/1/'.$group['photo'] ?>"></a>
					<?php /*<ul class="links">
						<li><a href="<?= SITEURL.'/groups/edit/'.$group['id'] ?>" class="button_dark fl"><span>Edit group</span></a></li>
					</ul>*/ ?>
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
	<div class="message message-info">You have not <a href="<?= SITEURL ?>/groups/create">created any groups</a>.</div>
	<?php } ?>