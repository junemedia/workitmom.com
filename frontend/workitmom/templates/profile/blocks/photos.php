
	<?php if (Utility::iterable($photos)) { ?>
	<div class="rounded-630-outline block" id="photos-block">
		<div class="top"></div>
		<div class="content grid_list member_list">
			<h2><?= $isSelf ? 'My' : $person->name . '\'s'; ?> Photos</h2>
			<?php if ($total > 12){ ?>
			<a class="button_dark fr" href="<?= SITEURL; ?>/profile/photos/<?= $person->username; ?>"><span>see all <?php Template::pluralise($total, 'photo'); ?></span></a>
			<?php } ?>
			<ul>
				<?php foreach ($photos as $photo) { ?>
				<li>
					<a class="img" href="<?= $photo['link']; ?>"><img src="<?php Template::image($photo, 75); ?>"/></a>
					<a href="<?= $photo['link']; ?>"><?= Text::trim($photo['title'], 40); ?></a>
				</li>
				<?php } ?>
			</ul>
			<div class="clear"></div>
		</div>
		<div class="bot"></div>
	</div>
	<?php } ?>
