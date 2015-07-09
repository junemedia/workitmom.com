
	<?php if (Utility::iterable($photos)) { ?>
	<div id="group-photos" class="item_list grid_list">
		<ul>
			<?php $i = 0; foreach ($photos as $photo) { $i++; ?>
			<li>
				<a class="img" href="<?= ASSETURL.'/groupphotoimages/800/500/1/'.$photo['groupPhotoName'] ?>" title="<?= $photo['photoCaption'] ?>" rel="milkbox:groupphotos">
					<img src="<?= ASSETURL.'/groupphotoimages/100/100/1/'.$photo['groupPhotoName'] ?>" alt="<?= $photo['photoCaption'] ?>" />
					<small class="caption"><?= Text::trim($photo['photoCaption'], 30) ?></small>
				</a>
				<?php if ($photo['isOwner']){ ?>
				<a class="delete" href="<?= SITEURL ?>/groups/photos?tab=manage&amp;task=photos_delete&amp;id=<?= $photo['groupPhotoID'] ?>">Delete</a>
				<?php } ?>
			</li>
			<?php if ($i % 4 == 0) { ?><div class="clear"></div><?php } ?>
			<?php } ?>
		</ul>

		<div class="clear"></div>
	</div>
	<?php } else { ?>
	<div class="message message-info">This group does not have any photos. Why not <a href="?tab=upload">upload some</a> now?</div>
	<?php } ?>