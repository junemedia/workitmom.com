
	<div class="wrapper">
		<div class="content">
			<form action="<?= SITEURL ?>/marketplace/create" method="post" class="no-submit-handling disable-on-submit"><div>

				<div style="background: #fff; border: 1px solid #ddd; padding: 15px 15px 5px 15px;" class="text-content">
					<p>You are purchasing a <strong><?= $paymentTypeTitle ?></strong> for <strong><?= $paymentDuration ?></strong> month(s) as shown below.</p>
					<p>Total Cost: 
						<? if(isset($paymentDiscount['code'])) { ?>
							<strike><?= Template::price($paymentPrice) ?></strike> 
							<strong><?= Template::price(($paymentPrice*((100-$paymentDiscount['percentage'])/100))) ?></strong>
						<? } else { ?>
							<strong><?= Template::price($paymentPrice) ?></strong>
						<? } ?>
					</p>
				</div>

				<div class="divider"></div>

				<div id="marketplace_item">
					<div class="header">
						<div class="img"><a href="<?= ASSETURL.'/marketimages/800/500/1/'.$listing['headImage'] ?>" class="img" rel="milkbox:listingheadimage"><img src="<?= ASSETURL.'/marketimages/85/85/1/'.$listing['headImage'] ?>" /></a></div>
						<div class="body">
							<h2><?= $listing['mTitle'] ?></h2>
							<p style="margin-bottom: 2px;"><?= $listing['categoryName'] ?></p>
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
							<?php foreach ($listing['images'] as $image) { ?>
							<a href="<?= ASSETURL.'/marketimages/800/500/1/'.$image['mpiFile'] ?>.jpg" class="img" rel="milkbox:listingimages"><img src="<?= ASSETURL.'/marketimages/60/60/1/'.$image['mpiFile'] ?>" /></a>
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
						<?php
							}
						?>

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

				<button type="submit" name="submit" class="submit"><span>Continue</span></button>

				<input type="hidden" name="task" value="s4_confirm_save" />
			</div></form>
		</div>
	</div>