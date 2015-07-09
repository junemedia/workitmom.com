
	<div class="tab_menu">
		<ul>
			<li<?= $sort == 'date' ? ' class="on"' : ''; ?>><a class="tab" href="<?= SITEURL; ?>/connect/members/?sort_members=latest">Latest Members</a></li>
			<li<?= $sort == 'active' ? ' class="on"' : ''; ?>><a class="tab" href="<?= SITEURL; ?>/connect/members/?sort_members=active">Most Active Members</a></li>
		</ul>
		<div class="clear"></div>
	</div>
	
	<?= Messages::getMessages(); ?>
				
<?php /*?>			<div id="sort_bar">
				<div class="text-content fl">
					Showing <?= $pagination->get('start'); ?>-<?= $pagination->get('end'); ?> of <strong><?= $pagination->get('total'); ?></strong> member<?= Text::pluralise($pagination->get('total')); ?>.
					<a href="<?= SITEURL; ?>/tellafriend/" class="arrow">Invite your friends</a>
				</div>
				
				<div class="clear"></div>
			</div>
<?php */?>	
	
	<div class="grid_list member_list">
		<ul>
		
			<?php if (Utility::iterable($people)){ foreach($people as $person) { ?>
			<li>
				<a href="<?= $person['url']; ?>" class="img"><img src="<?php Template::image($person, 80); ?>" /></a>
				<a href="<?= $person['url']; ?>"><?= $person['name']; ?></a>
			</li>
			<?php } } ?>
			
		</ul>
		<div class="clear"></div>
	</div>

	<?= $pagination->get('buttons'); ?>