<?

// Uses $featuredInterview, which should be an InterviewObject object.
$itemlink = SITEURL . '/interview/' . $featuredInterview->id . '/';

?><div id="meet_a_member" class="block">
	
	<div class="header">
		<div class="title">
			<h2><a href="<?=SITEURL;?>/interviews/">Meet a Member</a></h2>
		</div>
		
		<a href="<?=SITEURL;?>/interviews/" class="button_dark"><span>See All</span></a>
		
		<div class="clear"></div>
	</div>
	
	<div class="content">
		<a href="<?=SITEURL . $featuredInterview->author->profileURL;?>" class="img"><img src="<?= ASSETURL . '/userimages/60/60/1/' . $featuredInterview->author->image;?>" /></a>
		<a href="<?=$itemlink;?>" class="user"><?=$featuredInterview->author->name;?></a>
		<p class="text-content"><?=$featuredInterview->abridgedBody;?><br/><a href="<?=$itemlink;?>" class="arrow">Keep reading...</a></p>
		<div class="clear"></div>
	</div>

</div>
