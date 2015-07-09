<?

// Uses $rating, which should be an array.
$star_src_on = SITEASSETURL . '/images/site/icon-star.png';
$star_src_off = SITEASSETURL . '/images/site/icon-star_off.png';
// Share-this icons.
foreach (array('delicious', 'digg', 'facebook', 'kirtsy', 'reddit') as $ext) $$ext = SITEASSETURL . '/images/site/share_'.$ext.'.gif';

?><div id="share_post" class="screenonly">
	<ul>
	
		<li>
			<a href="?task=bookmark<?=($isBookmarked ? '&remove=1' : '')?>">
				<?=($isBookmarked ? 'Remove from saved list' : 'Save')?>
			</a> &nbsp;|&nbsp;&nbsp;
		</li>
		
		<li><a href="javascript:window.print();">Print</a> &nbsp;|&nbsp;&nbsp;</li>
		
		<li><a href="<?= SITEURL . "/tellafriend/share/" . $share_id ?>">Email</a> &nbsp;|&nbsp;&nbsp;</li>
		
		<li><a id="share">Share this</a> &nbsp;|&nbsp;&nbsp;</li>
		
		<?php Template::startScript(); ?>
		new PanelSlider(
			$('share').setStyle('cursor', 'pointer'), 
			'sharethis', 
			{hideLink: false, linkDisplay: 'inline'}
		);
		<?php Template::endScript(); ?>
		
		<li>
			<span class="fl"><?= !$rating['user'] ? 'Rate this: ' : 'You voted: ' ?></span>
			<div class="rate" id="ratings">
				<? 
				if(!$rating['user']) { // User hasn't voted yet
					
					// Non-JS version (fallback)
					for($i = 1, $j = 0; $i < 6; $i++, $j += 20) { ?>
					<a class="star" href="?task=vote&rating=<?= $i; ?>"><img src="<?= $rating['average'] > $j ? $star_src_on : $star_src_off; ?>" /></a>
					<? }
					
					// JS version
					Template::startScript();
					?>new Ratings('ratings', {current: <?= $rating['average']; ?>});<?php
					Template::endScript();
					
				} else { // User has voted
					
					for($i = 1; $i < 6; $i++){ ?>
					<img src="<?= $rating['user'] >= $i ? $star_src_on : $star_src_off; ?>" />
					<? }
					
				}
				?>
			</div>
		</li>
	</ul>
	
	<div class="clear2"></div>
	
	<div id="sharethis">
		<strong>Share this on:</strong>
		<ul>
			<li id="digg"><a target="_blank" href="http://digg.com/submit?phase=2&amp;url=http://www.workitmom.com<?=$share_link?>&amp;title=<?=trim(strip_tags($share_title));?>">Digg</a></li>
			<li id="facebook"><a target="_blank" href="http://www.facebook.com/share.php?u=http://www.workitmom.com<?=$share_link?>">Facebook</a></li>    
			<li id="reddit"><a target="_blank" href="http://reddit.com/submit?url=http://www.workitmom.com<?=$share_link?>&amp;title=<?=trim(strip_tags($share_title));?>">Reddit</a></li>
			<li id="delicious"><a target="_blank" href="http://del.icio.us/post?url=http://www.workitmom.com<?=$share_link?>&amp;title=<?=trim(strip_tags($share_title));?>">del.icio.us</a></li>
			<li id="kirtsy" class="last"><a target="_blank" href="http://www.kirtsy.com/submit.php?url=http://www.workitmom.com<?=$share_link?>">kirtsy</a></li>
		</ul>
	</div>
	
	<div class="clear2"></div>
</div>

<div class="divider"></div>
