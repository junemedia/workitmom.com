<?php

/* Get data */
$essentialGuides = $moduleArgs;

/* Template function */
function listItem(LandingpageObject $item){ 
	?><li><a href="<?= SITEURL; ?>/essentials/<?= $item->id; ?>/"><?= $item->title; ?></a></li><? 
}

?>

<div id="get_the_essentials" class="block block-border">
	
	<div class="header">
		<div class="title">
			<h2><a href="<?= SITEURL; ?>/essentials/">Get the Essentials</a></h2>
		</div>
		
		<a href="<?= SITEURL; ?>/essentials/" class="button_dark"><span>See All</span></a>
		
		<div class="clear"></div>
	</div>
		
	<div class="text-content underline">
		<? /* Split into two columns */ ?>
		<ul>
			<? for ($i = 0; $i < 3; $i++){ if (isset($essentialGuides[$i])){ listItem($essentialGuides[$i]); } } ?>
		</ul>
		<ul>
			<? for ($i = 3; $i < 7; $i++){ if (isset($essentialGuides[$i])){ listItem($essentialGuides[$i]); } } ?>
		</ul>
		<div class="clear"></div>
	</div>

</div>
