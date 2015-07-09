
	<?php if (!empty($theEssentials)) { ?>
	<div id="get_the_essentials" class="block">

		<div class="header">
			<div class="title">
				<h2><a href="<?= SITEURL ?>/essentials/">Get the Essentials</a></h2>
			</div>

			<a href="<?= SITEURL ?>/essentials/" class="button_dark"><span>See All</span></a>

			<div class="clear"></div>
		</div>

		<div class="text-content">
			<ul>
			<?php foreach ($theEssentials as $essentialItem) { ?>
				<li>
					<a href="<?= Uri::build($essentialItem); ?>"><?= $essentialItem->title ?></a>
				</li>
			<?php } ?>
			</ul>
			<div class="clear"></div>
		</div>

	</div>
	<?php } ?>