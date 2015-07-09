
	<?php if (!empty($featuredListings)) { ?>
	<div id="marketplace_block" class="block">
		<div class="header">
			<div class="title">
				<h2><a href="<?= SITEURL ?>/marketplace/">From our Marketplace</a></h2>
			</div>
			<a href="<? SITEURL ?>/marketplace" class="button_dark fr"><span>See all</span></a>
			<a class="button_bright fr"  href="<?= SITEURL ?>/marketplace/create"><span>Promote your business!</span></a>
			<div class="clear"></div>
		</div>

		<ul>
			<?php
				foreach ($featuredListings as $listing) {
					$link = SITEURL.'/marketplace/detail/'.$listing['marketID'];
			?>
			<li>
				<a href="<?= $link ?>" class="img">
					<img src="<?= ASSETURL ?>/marketimages/130/130/1/<?= $listing['headImage'] ?>" alt="<?= $listing['mTitle'] ?>" />
				</a>
				<div class="body">
					<a href="<?= $link ?>"><?= $listing['mTitle'] ?></a>
					<?php if (isset($listing['categoryShortName'])) { ?>
					<?php } ?>
				</div>
				<div class="clear"></div>
			</li>
			<?php } ?>
		</ul>

		<div class="clear"></div>

	</div>
	<?php } ?>