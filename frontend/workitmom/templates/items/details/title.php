
	<div class="rounded-630-blue" id="article_title">
		<div class="top"></div>
		<div class="content">
		
			<div class="img screenonly">
				<img src="<?= $image; ?>" />
			</div>
			
			<div class="body">
				<h2><?= $item->title; ?></h2>
				<h3><?= $item->subtitle; ?></h3>
				<p class="text-content">
					<?php if ($item->author) {
						if (isset($item->author->username)) { ?>by <a href="<?= SITEURL.$item->author->profileURL; ?>"><?= $item->author->name; ?></a><?php }
						else { ?> by <?= $item->author->name; ?><?php } ?>
						&nbsp;|&nbsp;
					<?php } ?>
					
					<?php Template::pluralise($item->views, 'view'); ?>
					&nbsp;|&nbsp;
					<a href="#comments" class="scroll"><?php Template::comment_count($commentCount); ?></a>
					&nbsp;|&nbsp; 
					<?php for($i = 1, $j = 0; $i < 6; $i++, $j += 20) { 
						if ($item->rating['average'] > $j){ ?><img src="<?= SITEASSETURL; ?>/images/site/icon-star-sm.png" />&nbsp;<?php }
					} ?>
					<?php if (!$item->rating['user']){ ?><a class="scroll" href="#ratings">Rate this now!</a>&nbsp;<?php } ?>
				</p>
				
				<a href="https://twitter.com/Work_It_Mom" class="twitter-follow-button" data-show-count="false">Follow @Work_It_Mom</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				
			</div>
			<div class="clear"></div>
			
		</div>
		<div class="bot"></div>
	</div>