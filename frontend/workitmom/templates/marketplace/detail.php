
	<div id="main-content" class="marketplace">
		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="marketplace_icon" class="icon fl"></div>
					<h1>Marketplace</h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<a class="button_bright fr" style="margin: -60px 10px 0 0" href="<?= SITEURL ?>/marketplace/create"><span>Promote your business!</span></a>
			<div class="rounded-630-blue block" id="marketplace_item">
				<div class="top"></div>
				<div class="content">

					<div class="header">
						<div class="img"><a href="<?= ASSETURL.'/marketimages/800/500/1/'.$listing['headImage'] ?>" class="img" rel="milkbox:listingheadimage"><img src="<?= ASSETURL.'/marketimages/85/85/1/'.$listing['headImage'] ?>" /></a></div>
						<div class="body">
							<h2><?= $listing['mTitle'] ?></h2>
							<p style="margin-bottom: 2px;"><a href="<?= SITEURL ?>/marketplace?category=<?= $listing['categoryShortName'] ?>&sort=date"><?= $listing['categoryName'] ?></a></p>
							<p class="text-content underline">Listed by <?= $listing['mContactName'] ?></p>
						</div>
						<div class="clear"></div>
					</div>

					<ul class="item_list">

						<?php if ($listing['link']) { ?>
						<li>
							<h3>Website</h3>
							<a href="<?= $listing['link'] ?>"><?= $listing['link'] ?></a>
						</li>
						<?php } ?>

						<li>
							<h3>Description</h3>
							<p><?= nl2br($listing['mDescription']) ?></p>
						</li>

						<?php if ($listing['mDiscounts']) { ?>
						<li class="discount">
							<h3>Discounts</h3>
							<?= nl2br($listing['mDiscounts']) ?>
						</li>
						<?php } ?>

						<?php if (!empty($listing['images'])) { ?>
						<li>
							<h3>Images</h3>
							<p>Please click to enlarge</p>
							<?php foreach ($listing['images'] as $image) { ?>
							<a href="<?= ASSETURL.'/marketimages/800/500/1/'.$image['mpiFile'] ?>" class="img" rel="milkbox:listingimages"><img src="<?= ASSETURL.'/marketimages/60/60/1/'.$image['mpiFile'] ?>" /></a>
							<?php } ?>
							<div class="clear"></div>
						</li>
						<?php } ?>

						<?php if (($listing['mContactShowLocation'] && $listing['mContactLocation']) ||
							($listing['mContactShowEmail'] && $listing['mContactEmail'])||
							($listing['mContactShowPhone'] && $listing['mContactPhone'])) { ?>
						<li class="contact">
							<h3>Contact Details</h3>
							<dl>
								<?php if ($listing['mContactShowLocation'] && $listing['mContactLocation']) { ?>
								<dt>Location:</dt>
								<dd><?= $listing['mContactLocation'] ?></dd>
								<div class="clear"></div>
								<?php } ?>

								<?php if ($listing['mContactShowEmail'] && $listing['mContactEmail']) { ?>
								<dt>Email:</dt>
								<dd><a href="mailto:<?= $listing['mContactEmail'] ?>"><?= $listing['mContactEmail'] ?></a></dd>
								<?php } ?>

								<?php if ($listing['mContactShowPhone'] && $listing['mContactPhone']) { ?>
								<dt>Phone:</dt>
								<dd><?= $listing['mContactPhone'] ?></dd>
								<?php } ?>
							</dl>
							<div class="clear"></div>
						</li>
						<?php } ?>

						<?php if (!empty($listing['links'])) { ?>
						<li>
							<h3>Links</h3>
							<?php foreach ($listing['links'] as $link) { ?>
							<a href="<?= $link ?>"><?= $link ?></a><br />
							<?php } ?>
						</li>
						<?php } ?>

					</ul>

				</div>
				<div class="bot"></div>
			</div>

			<?php include(BLUPATH_TEMPLATES.'/marketplace/detail/related.php'); ?>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
