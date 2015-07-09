
	<?php $this->_doc->setAdPage('marketlisting'); ?>

	<div id="main-content" class="marketplace">
		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="slideshow_icon" class="icon fl"></div>
					<h1>Marketplace</h1>
					<h3 class="subtitle">Products &amp; services to help make your daily juggle easier &amp; more fun!</h3>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<? $this->product_box($product); ?>

			<? $this->product_related(); ?>

			<div class="divider"></div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array('promote_your_business','slideshow_featured', 'marketplace', 'catch_your_breath'

				)); ?>
		</div>

		<div class="clear"></div>
	</div>
