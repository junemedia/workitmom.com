
	<?php if (!empty($photos)) { ?>
	<div id="account-photos" class="item_list grid_list">
		<ul>
			<?php $i = 0; foreach ($photos as $photo) { $i++; ?>
			<li>
				<a class="img" href="<?php Template::image($photo, 800, 500); ?>" title="<?= $photo['title'] ?>" rel="milkbox:userphotos">
					<img src="<?php Template::image($photo, 100); ?>" alt="<?= $photo['title'] ?>" />
					<small class="caption"><?= Text::trim($photo['title'], 30) ?></small>
				</a>
				<a class="delete" href="<?= SITEURL ?>/account/photos?tab=manage&amp;task=photos_delete&amp;id=<?= $photo['id'] ?>">Delete</a>
			</li>
			<?php if ($i % 4 == 0) { ?><div class="clear"></div><?php } ?>
			<?php } ?>
		</ul>

		<div class="clear"></div>
	</div>
	<?php } else { ?>
	<div class="message message-info">You do not currently have any photos. Why not <a href="?tab=upload">upload some</a> now?</div>
	<?php } ?>