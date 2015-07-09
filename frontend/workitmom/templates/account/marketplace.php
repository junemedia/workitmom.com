
	<div id="main-content" class="account">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="account_icon" class="icon fl"></div>
					<h1>My Marketplace Listings</h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<?php if (!empty($ownedListings)) { ?>
			<div class="item_list">
				<ul>
					<?php
						$alt = true;
						foreach ($ownedListings as $listing) {
							$link = SITEURL.'/marketplace/create/'.$listing['marketID'];
					?>
					<li<?= $alt ?' class="odd"' : '' ?>>
						<div class="actions">
							<a href="<?= $link ?>"><img src="<?= ASSETURL.'/marketimages/60/60/1/'.$listing['headImage'] ?>"></a>
						</div>
						<div class="info">
							<h3><a href="<?= $link ?>" class="title"><?= $listing['mTitle'] ?></a></h3>
							<small><?= $listing['mDescription'] ?></small>
						</div>
						<div class="clear"></div>
					</li>
					<?php
							$alt = !$alt;
						}
					?>
				</ul>
			</div>
			<?php } else { ?>
			<div class="message message-info">You do not have any active marketplace listings. Why not <a href="<?= SITEURL ?>/marketplace/article">create one</a> now?</div>
			<?php } ?>
		</div>
		<div class="panel-right">
			<?php $this->sidebar(array('newsletter', 'account', array('ad_mini', 'account')), false); ?>
		</div>

		<div class="clear"></div>
	</div>
