
	<div id="main-content" class="landing">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="shop_icon" class="icon fl"></div>
					<h1>Shop</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="block rounded-630-orange" id="page-intro">
				<div class="top"></div>

				<div class="content">
					<div class="img"><img src="<?= ASSETURL; ?>/landingimages/0/0/1/landing_shop.jpg"/></div>

					<div class="body">
						<h2>How about a little retail therapy?</h2>
						<p>From affordable luxuries to our regular giveaways, we bring you some treats we know you deserve.</p>
					</div>

					<div class="clear"></div>

				</div>

				<div class="bot"></div>
			</div>

			<div class="col-l">
				<?php BluApplication::getModules('site')->indulge_yourself(); ?>
			</div>

			<div class="col-r">
				<?php $this->landing_giveaways(); ?>
			</div>

			<div class="clear"></div>

			<div class="divider"></div>

			<?php include(BLUPATH_TEMPLATES.'/shop/landing/marketplace.php'); ?>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array('promote_your_business', 'daily_grommet','slideshow_featured', 'catch_your_breath'
)); ?>
		</div>

		<div class="clear"></div>
	</div>
