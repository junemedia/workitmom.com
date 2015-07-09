<?

// Uses $featuredDeals, which should be an array of DailydealObject objects.

?>
<div id="daily_deal" class="block spotlight">
	<div class="header">
		<div class="title">
			<h2><a href="<?= SITEURL; ?>/dailydeals/">Daily Deal</a></h2>
		</div>
		<a href="<?= SITEURL ?>/explore/balancing_act/" class="button_dark fl"><span>See all</span></a>
	</div>
	
	<div class="content">
		<ul>
		<li class="featured">
			<a href="#" class="img"><img src="<?php Template::image($featuredDeals[0]); ?>" /></a>
			<div class="body">
				<a href="#" class="post"><?= $featuredDeals[0]->title; ?></a>
				<p class="text-content"><?= $featuredDeals[0]->abridgedBody; ?></p>
			</div>
			<div class="clear"></div>
			
		</li>
		
		<? if (isset($featuredDeals[1])){ ?>
		<li class="other">
			<a href="#"><?= $featuredDeals[1]->title; ?></a>
		</li>
		<? } ?>
		
		<? if (isset($featuredDeals[2])){ ?>
		<li class="other">
			<a href="#"><?= $featuredDeals[2]->title; ?></a>
		</li>
		<? } ?>
		
	</ul>
	</div>
	<div class="clear"></div>
</div>