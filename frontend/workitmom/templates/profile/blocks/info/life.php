
<dl>

	<?php
	
	if ($life === false){
	
		// Data is private.
		echo ($isSelf ? 'You have' : $person->name . ' has') . ' chosen to keep this information private.';
		
	} else if (!$life->statement && !$life->interests && !$life->destress && !$life->book && !$life->movie && !$life->website && !$life->advice && !$life->adjective) { 
	
		// No data available.
		echo ($isSelf ? 'You have' : $person->name . ' has') . ' chosen to keep this information private.';
		
	} else {
	
	?>
	
	<?php if ($tags){ ?>
	<dt>My tags:</dt>
	<dd><?php Template::tags($tags, 'profile', false); ?></dd>
	<?php } ?>
	
	<?php if ($life->statement) { ?>
	<dt>About me:</dt>
	<dd><?= $life->statement; ?></dd>
	<?php } ?>
	
	<?php if ($life->interests) { ?>
	<dt>My interests:</dt>
	<dd><?= $life->interests; ?></dd>
	<?php } ?>
	
	<?php if ($life->website) { ?>
	<dt>My website/blog:</dt>
	<dd><a href="<?= $life->website; ?>"><?= $life->website; ?></a></dd>
	<?php } ?>
	
	<?php if ($life->destress) { ?>
	<dt>How I de-stress:</dt>
	<dd><?= $life->destress; ?></dd>
	<?php } ?>
	
	<?php if ($life->advice) { ?>
	<dt>Best advice I've gotten:</dt>
	<dd><?= $life->advice; ?></dd>
	<?php } ?>
	
	<?php if ($life->adjective) { ?>
	<dt>Adjective to describe me:</dt>
	<dd><?= $life->adjective; ?></dd>
	<?php } ?>
	
	<?php if ($life->book) { ?>
	<dt>My favorite book:</dt>
	<dd><?= $life->book; ?></dd>
	<?php } ?>
	
	<?php if ($life->movie) { ?>
	<dt>My favorite movie:</dt>
	<dd><?= $life->movie; ?></dd>
	<?php } ?>
	
	<?php } ?>
	
</dl>