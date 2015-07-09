
	<div id="browse_bar" class="block">
		<h3>Sort&hellip;</h3>
		<ul class="categories">
			<?php $this->listing_sorter($sort, $page); ?>
		</ul>
		<h3>Browse&hellip;</h3>
		<ul class="categories">
			<li<?= $categorySlug ? '' : ' class="on"'; ?>><a href="<?= SITEURL ?>/groups?sort=<?= $sort ?>">View All</a></li>
			<?php if ($user) { ?><li<?php if ($categorySlug == 'owner') { echo ' class="on"'; } ?>><a href="<?= SITEURL ?>/marketplace?category=owner&amp;sort=<?= $sort ?>" class="star">My Listings</a></li><?php } ?>
			<?php foreach ($categories as $category) { ?>
			<li<?php if ($category && ($category['categoryShortName'] == $categorySlug)) { echo ' class="on"'; } ?>>
				<a href="<?= SITEURL.'/marketplace?category='.$category['categoryShortName'].'&amp;sort='.$sort ?>"><?= $category['categoryName'] ?></a>
			</li>
			<?php } ?>
		</ul>
		<div class="clear"></div>
	</div>
	<div class="items-right">
		<? /*<div id="sort_bar">
			<p id="countstring" class="text-content fl">
				<?= Template::itemCount($total, 'listing', 'listings') ?>
			</p>
			<div class="clear"></div>
		</div>*/?>
		<div class="item_list grid_list">
			<ul>
				<?php
					foreach($listings as $listing) {
						$link = SITEURL . '/marketplace/detail/'.$listing['marketID'];
				?>
				<li>
					<a href="<?= $link ?>" class="img"><img src="<?= ASSETURL.'/marketimages/117/117/1/'.$listing['headImage'] ?>" /></a>
					<a href="<?= $link ?>"><?= $listing['mTitle'] ?></a>
				</li>
				<?php
					}
				?>
			</ul>
		</div>

		<div class="clear"></div>
	<?php $this->listing_pagination($sort, $page, $total); ?>

	</div>


	<div class="divider"></div>

