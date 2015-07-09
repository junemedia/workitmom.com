
	<?php $this->_doc->setAdPage('media'); ?>

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

			<div id="market_content" class="content">

<p>Promote your business to thousands of Work It, Mom! members who love to shop online by purchasing a listing in our Marketplace!</p>
		<img src="<?= SITEASSETURL; ?>/images/site/large_marketplace.png" class="cart"/>
				
		<div class="info">		
		<h2>Listing Benefits</h2>
		<ul>
			<li>Promote your product to working moms who love to shop online</li>
			<li>Find new customers and increase sales</li>
			<li>Create a custom listing page optimized for search engines</li>
			<li><strong>If you select a Featured Listing, it will be featured throughout the site and will receive 1000s of page impressions a month.</strong></li>
		</ul>

		<h2>Listing Prices</h2>
		

		<ul>
			<li><strong>REGULAR</strong>1 Month: $20 / 3 Months: $50</li>
			<li><strong>FEATURED</strong>1 Month: $60 / 3 Months: $150</li>
			<li><strong>FEATURED &amp; NEWSLETTER</strong> 1 Month: $110 / 3 Months: $280</li>
		</ul>
		<p><a href="<?= SITEURL ?>/info/marketplace_pricing" class="info-popup"><strong>Click here to find out more</strong></a></p>
		
		</div>
		<div class="clear"></div>
 
		 <p align="center" class="register"><img src="<?= SITEASSETURL; ?>/images/site/button_register_marketplace.png"/><br />

Existing members, please <a href="#">click here</a> to log in and submit your listing.</p>
				
				
				
			</div>

			<? include(BLUPATH_TEMPLATES.'/site/modules/bottom_blocks.php'); ?>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array(
				'newsletter',
				'static',
				'ad_mini',
				'slideshow_featured',
				'marketplace',
				'ad_skyscraper'
			), false); ?>
		</div>

		<div class="clear"></div>
	</div>