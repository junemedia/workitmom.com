
	<?php if (!empty($photos) || $group['isMember']) { ?>
	<div class="rounded-630-outline members block" id="group-photos">
		<div class="top"></div>

		<div class="content grid_list member_list">
			<h2>Group Photos</h2>
			<?php if ($numPhotos > 12 || $group['isMember']) { ?>
			<a href="<?= SITEURL.'/groups/photos/'.$groupId ?>" class="button_dark fr"><span>See all <?= $numPhotos; ?> photos</span></a>
			<?php } ?>
			<?php if ($group['isMember']) { ?>
			<a href="<?= SITEURL.'/groups/photos/'.$groupId.'?tab=upload'; ?>" class="button_dark fr" ><span>Upload a photo</span></a>
			<?php } ?>
			<?php if (Utility::iterable($photos)){ ?>
			<ul>
			<?php foreach($photos as $photo) { ?>
				<li>
					<a href="<?= ASSETURL.'/groupphotoimages/800/500/1/'.$photo['groupPhotoName'] ?>" title="<?= $photo['photoCaption'] ?>" class="img" rel="milkbox:groupphotos">
						<img src="<?= ASSETURL.'/groupphotoimages/60/60/1/'.$photo['groupPhotoName'] ?>" />
					</a>
					Added by <a href="<?= SITEURL.$photo['user']->profileURL ?>"><?= $photo['user']->name ?></a>
				</li>
			<?php } ?>
			</ul>
			<?php } else { ?>
			<p class="text-content">No photos yet.</p>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="bot"></div>
	</div>
	<?php } ?>