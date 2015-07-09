<?

// Uses $featuredInterview, which should be an InterviewObject object.
// Uses $link, which should be a string.

?><div id="mom_interview" class="block">
	
	<div class="header">
		<div class="title">
			<h2><a href="<?= SITEURL; ?>/interviews/">Mom Interviews</a></h2>
		</div>
		<a href="<?= SITEURL; ?>/interviews/" class="button_dark fl"><span>See All</span></a>
		
		<div class="clear"></div>
	</div>
	
	<div class="content">
		<a href="<?= isset($featuredInterview->author->username) ? SITEURL . $featuredInterview->author->profileURL : SITEURL . $link; ?>" class="img">
			<img src="<?php Template::image($featuredInterview, 60); ?>" />
		</a>
		<a href="<?= $link; ?>" class="user"><?= $featuredInterview->author->name; ?></a>
		<p class="text-content interview">
			<?= $featuredInterview->teaser; ?><br/>
			<a href="<?= $link; ?>" class="arrow">Keep reading...</a>
		</p>
		<div class="clear"></div>
	</div>

</div>
