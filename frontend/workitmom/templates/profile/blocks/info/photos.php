<?php
	if ($photos === false) {
		echo $person->name.' has chosen to keep this information private.';
	} elseif (!empty($photos)) {
?>
	<div class="content grid_list">
		<ul>
		<?php foreach($photos as $photo) { ?>
			<li>
				<a href="<?= ASSETURL.'/userimages/800/500/1/'.$photo->image ?>" title="<?= $photo->title ?>" class="img" rel="milkbox:userphotos"><img src="<?= ASSETURL.'/userimages/60/60/1/'.$photo->image ?>" /></a>
			</li>
		<?php } ?>
		</ul>
		<div class="clear"></div>
	</div>
<?php
	}
?>