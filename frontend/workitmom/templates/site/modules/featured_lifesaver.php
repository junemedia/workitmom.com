<?

// Uses $featuredLifesaver, which should be a LifesaverObject object.

?><div id="featured_lifesaver" class="block">
	<div class="header">
		<div class="title">
			<h2><a href="<?= SITEURL; ?>/lifesavers/">Member Lifesaver</a></h2>
		</div>
		
		<?php if ($useButton){ ?>
		<a href="<?= SITEURL; ?>/lifesavers/" class="button_dark"><span>See All</span></a>
		<?php } ?>
		
		<div class="clear"></div>
	</div>
	
	<div class="text-content">
		<a href="<?= SITEURL . $featuredLifesaver->author->profileURL; ?>" class="img">
			<img src="<? Template::image($featuredLifesaver, 100, 120); ?>" />
		</a>
		
		<div class="quote_mark"></div>
		
		<p><?= $featuredLifesaver->body; ?>&rdquo;</p>
		<div class="sub">
			Posted by <cite><a href="<?= SITEURL . $featuredLifesaver->author->profileURL; ?>"><?= $featuredLifesaver->author->name; ?></a></cite> on <?= $featuredLifesaver->date; ?>
		</div>

	</div>

</div>