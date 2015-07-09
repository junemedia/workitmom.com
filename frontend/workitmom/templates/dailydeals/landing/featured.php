<?

// Uses $latestitems, which should be an array of ItemObject objects.

// Grab the first one (which should be today's).
$featuredDeal = array_shift($latestitems);

?>
<div id="featured_article" class="block rounded-630-orange">
	<div class="top"></div>

	<div class="content">
		<div class="image">
		<img src="<?= ASSETURL . '/marketimages/183/183/1/' . $featuredDeal->image; ?>" alt="<?= $featuredDeal->title; ?>" />

		</div>

		<div class="body">

			<div class="title">
				<h2><a href="<?= SITEURL; ?>/dailydeals/<?= $featuredDeal->id; ?>/"><?= $featuredDeal->title; ?></a></h2>
				<span class="text-content underline">
					<?= $featuredDeal->date; ?>
					&nbsp;|&nbsp;
					<a href="<?= SITEURL; ?>/dailydeals/<?= $featuredDeal->id; ?>/#comments" class="scroll">
						<?= $featuredDeal->getCommentCount(); ?> comment<?= Text::pluralise($featuredDeal->getCommentCount()); ?>
					</a>
				</span>
			</div>

			<div class="clear"></div>

			<p><?= $featuredDeal->abridgedBody; ?> <a href="<?= SITEURL; ?>/dailydeals/<?= $featuredDeal->id; ?>/" class="arrow">...keep reading</a></p>

			<a href="<?= SITEURL . "/dailydeals/rss?format=xml" ?>" class="rss">Get the Daily Deal Alert as an RSS feed</a>
			<div class="clear"></div>

		</div>

		<div class="clear"></div>

	</div>

	<div class="bot"></div>
</div>