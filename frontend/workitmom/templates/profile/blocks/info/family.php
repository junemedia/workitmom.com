
<dl>

	<?php
	
	if ($family === false){
		
		// Data is private
		echo ($isSelf ? 'You have' : $person->name . ' has') . ' chosen to keep this information private.';
		
	} else if (!$family->childrenCount && !$family->childrenAge && !$family->advice && !$family->ignore && !$family->activity && $family->relationship == 'Would rather not say') {
		
		// No data
		echo ($isSelf ? 'You have' : $person->name . ' has') . ' chosen to keep this information private.';
		
	} else {
	
	?>
	
	<?php if ($family->childrenCount) { ?>
	<dt>Number of children:</dt>
	<dd><?= $family->childrenCount; ?></dd>
	<?php } ?>
	
	<?php if ($family->childrenAge) { ?>
	<dt>Age of children:</dt>
	<dd><?= $family->childrenAge; ?></dd>
	<?php } ?>
	
	<?php if ($family->relationship) { ?>
	<dt>Relationship status:</dt>
	<dd><?= $family->relationship; ?></dd>
	<?php } ?>
	
	<?php if ($family->advice) { ?>
	<dt>Parenting advice I follow:</dt>
	<dd><?= $family->advice; ?></dd>
	<?php } ?>
	
	<?php if ($family->ignore) { ?>
	<dt>Parenting advice I ignore:</dt>
	<dd><?= $family->ignore; ?></dd>
	<?php } ?>
	
	<?php if ($family->activity) { ?>
	<dt>Favorite activity with my kids:</dt>
	<dd><?= $family->activity; ?></dd>
	<?php } ?>
	
	<?php } ?>
	
</dl> 	