<?

// Uses $popularitems, which should be an array of ItemObject objects.
// Uses $featureditem, which should be an ItemObject object.

?><div class="col-l">

	<div class="rounded-300-orange" id="ask_question">
		<div class="top"></div>
		
		<div class="content">
			<h2>Looking for Real-World Advice?</h2>
			<h3>Check out helpful tips for making your daily juggle more manageable and get insights into the lives of fellow working moms.</h3>
			<img src="<?= SITEASSETURL;?>/images/site/dummy-image.png" alt="Photo caption here" />

			<a href="<?= SITEURL . "/articles/rss?format=xml" ?>" class="rss">Subscribe to articles via RSS</a>
		</div>
		
		<div class="bot"></div>
	</div>	
	
	
</div>

<div class="col-r">
	
	<?php $this->landing_featured(); ?>

	<div class="divider"></div>
	
	<div class="content most_popular">	
		<h4>This Week's Most Popular</h4>
		<ul>
			<? if (Utility::is_loopable($mostvieweditems)){ foreach($mostvieweditems as $item){ ?>
			<li><a href="<?= Uri::build($item); ?>"><?= $item->title; ?></a></li>
			<? } } ?>
		</ul>
	</div>


</div>

<div class="clear"></div>
			
<div class="divider"></div>