
	<div class="tab_menu">
		<ul>
			<li<?= $sort == 'date' ? ' class="on"' : ''; ?>><a class="tab" href="<?= SITEURL; ?>/connect/members/?sort_photos=latest">Latest Photos</a></li>
			<li<?= $sort == 'comments' ? ' class="on"' : ''; ?>><a class="tab" href="<?= SITEURL; ?>/connect/members/?sort_photos=comments">Most Comments</a></li>
		</ul>
		<div class="clear"></div>
	</div>

	<?= Messages::getMessages(); ?>
			
<?php /*?>			<div id="sort_bar">
				<div class="text-content fl">
					Showing <?= $pagination->get('start'); ?>-<?= $pagination->get('end'); ?> of <strong><?= $pagination->get('total'); ?></strong> user photo<?= Text::pluralise($pagination->get('total')); ?>.
					<a href="<?= SITEURL; ?>/contribute/photo/" class="arrow">Upload your photos</a>
				</div>
				
				<div class="clear"></div>
			</div>
<?php */?>
				
	<div class="grid_list member_list photos">
		<ul>
			<?php if (Utility::iterable($photos)){ foreach($photos as $photo) { ?>
			<li>
				<a href="<?= $photo['link']; ?>" title="<?= $photo['title']; ?>" class="img" rel="milkbox:memberphotos">
					<img src="<?php Template::image($photo, 85); ?>" />
				</a>
				Added by <a href="<?= $photo['author']['url']; ?>"><?= $photo['author']['name']; ?></a>
			</li>
			<?php } } ?>
		</ul>
		<div class="clear"></div>
	</div>

	<?= $pagination->get('buttons'); ?>