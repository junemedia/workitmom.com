
	<?php if (!empty($relatedListings)) { ?>
	<div id="recent_list" class="most_popular rounded-630-outline">
		<div class="top"></div>
		<div class="content">
			<h4>You May Also Like...</h4>
			<ul>
				<?php foreach ($relatedListings as $listing) { ?>
				<li>
					<a href="<?= SITEURL ?>/marketplace/detail/<?= $listing['marketID'] ?>" class="fl"><?= htmlspecialchars($listing['mTitle']) ?></a>
					<p class="text-content underline fr"><a href="<?= SITEURL ?>/marketplace?category=<?= $listing['categoryShortName'] ?>"><?= $listing['categoryName'] ?></a></p>
					<div class="clear"></div>
				</li>
				<?php } ?>
			</ul>
		</div>
		<div class="bot"></div>
	</div>
	<?php } ?>
