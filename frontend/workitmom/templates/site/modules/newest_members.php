<?php if (Utility::iterable($newestMembers)){ ?>
<div id="newest_members" class="block">
	<div class="header">
		<div class="title">
			<h2><a href="<?= SITEURL; ?>/connect/members/">Newest Members</a></h2>
		</div>		
		<a href="<?= SITEURL; ?>/connect/members/" class="button_dark"><span>See All</span></a>		
		<div class="clear"></div>
	</div>
	
	<ul>
		<?php foreach($newestMembers as $person) { ?>
		<li>
			<a href="<?php Template::link('person', $person); ?>" class="img">
				<img src="<?php Template::image($person, 35); ?>" />
			</a>
			<div class="body">
				<a href="<?php Template::link('person', $person); ?>" class="user"><?= $person->name; ?></a>
				<p class="text-content underline">from <?= $person->location; ?></p>
			</div>
			<div class="clear"></div>
		</li>
		<?php } ?>
	</ul>
	
	<div class="clear"></div>
</div>
<?php } ?>