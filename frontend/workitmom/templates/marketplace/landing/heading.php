
	<?php if (!empty($featuredListings)) { ?>
	<div class="rounded-630-blue block" id="featured_marketplace">
		<div class="top"></div>
		<div class="content">

		<ul>
			<?php
				foreach($featuredListings as $listing) {
					$link = SITEURL.'/marketplace/detail/'.$listing['marketID'];
			?>
			<li>
				<div class="img">
					<a href="<?= $link ?>"><img alt="<?= $listing['mTitle'] ?>" src="<?= ASSETURL.'/marketimages/130/130/1/'.$listing['headImage'] ?>" /></a>
					<div class="deal"></div>
				</div>
					<h4><a href="<?= $link ?>"><?= $listing['mTitle'] ?></a></h4>					
			</li>
			<?php
				}
			?>
			<div class="clear"></div>
		</ul>

		</div>
		<div class="bot"></div>
	</div>
	<div class="divider"></div>
	<?php } ?>
