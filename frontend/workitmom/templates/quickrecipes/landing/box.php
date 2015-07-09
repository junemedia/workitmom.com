
	<?php if (!empty($items)) { ?>
	<div class="item_list">
		<ul>
		<?php
			$alt = true;
			foreach($items as $item) {
				$link = Uri::build($item);
		?>
			<li<?= $alt ? ' class="odd"' : ''; ?>>
				<div class="img">
					<img src="<?php Template::image($item); ?>" />
				</div>
				<div class="body">
					<div class="header">
						<h3><a href="<?= $link ?>"><?= $item->title ?></a></h3>
					</div>
					<p class="text-content"><?= $item->abridgedBody ?></p>
				</div>
				<div class="clear"></div>
			</li>
		<?php
				$alt = !$alt;
			}
		?>
		</ul>
	</div>

	<?= $pagination->get('buttons'); ?>

	<?php } ?>
