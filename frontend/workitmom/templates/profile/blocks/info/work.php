
<dl>

	<?php
	
	if ($work === false){
	
		// Data is private.
		echo ($isSelf ? 'You have' : $person->name . ' has') . ' chosen to keep this information private.';
		
	} else if (!$work->employment && !$work->hours && !$work->like && !$work->stress && !$work->best && !$work->worst && !$work->dream) {
	
		// No data available.
		echo ($isSelf ? 'You have' : $person->name . ' has') . ' chosen to keep this information private.';
		
	} else {
	
	?>
	
	<?php if ($work->employment) { ?>
		<dt>Employment:</dt>
		<?php switch($work->employment) {
			case 'employed':
				?><dd>Full-time<?= $work->job ? ' as '.Utility::prefixIndefiniteArticle($work->job) : '' ?><?= $work->employer ? ' at '.$work->employer : ''?></dd><?php
				break;
			case 'parttime':
				?><dd>Part-time<?= $work->job ? ' as '.Utility::prefixIndefiniteArticle($work->job) : '' ?><?= $work->employer ? ' at '.$work->employer : ''?></dd><?php
				break;
			case 'self':
				?><dd>I run <?= $work->employer ? $work->employer : ' my own business' ?></dd><?php
				break;
			case 'consultant':
				?><dd>I work as a consultant/freelancer</dd><?php
				break;
			case 'unemployed':
				?><dd>I'm not currently working</dd><?php
				break;
			default:
				?><dd>N/A</dd><?php
				break;
		} ?>
	<?php } ?>
	
	<?php if ($work->industry) { ?>
		<dt>Industry:</dt>
		<dd><?= $work->industry; ?></dd>
	<?php } ?>
	
	<?php if ($work->hours) { ?>
		<dt>How many hours I work:</dt>
		<dd><?= $work->hours; ?></dd>
	<?php } ?>
	
	<?php if ($work->like) { ?>
		<dt>How much I like my job:</dt>
		<dd><?= $work->like; ?></dd>
	<?php } ?>
	
	<?php if ($work->stress) { ?>
		<dt>How stressful I find my job:</dt>
		<dd><?= $work->stress; ?></dd>
	<?php } ?>
	
	<?php if ($work->best) { ?>
		<dt>Best thing about my job:</dt>
		<dd><?= $work->best; ?></dd>
	<?php } ?>
	
	<?php if ($work->worst) { ?>
		<dt>Worst thing about my job:</dt>
		<dd><?= $work->worst; ?></dd>
	<?php } ?>
	
	<?php if ($work->dream) { ?>
		<dt>My dream job:</dt>
		<dd><?= $work->dream; ?></dd>
	<?php } ?>
	
	<?php } ?>
	
</dl>