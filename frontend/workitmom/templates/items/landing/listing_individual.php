<?

// Uses $thing, which should be an ItemObject
// Uses $link, which should be a string.
// Uses $alt, which should be a boolean

?>
	<li<?= $alt ? ' class="odd"' : ''; ?>>
		<?php //if ($thing->author) { ?>
		<a href="<?= $link; ?>" class="img">
			<img src="<?php Template::image($thing); ?>" />
		</a>
		<?php //} ?>
		<div class="body">
			<div class="header">
				<h3><a href="<?= $link; ?>"><?= $thing->title; ?></a></h3>
				<p class="text-content">
					<?php if ($thing->author) { ?>
					by <a href="<?= SITEURL.$thing->author->profileURL ?>"><?= $thing->author->name ?></a>
					<?php } ?>
					<!-- on <?= $thing->date ?> -->
				</p>
			</div>
			<p class="text-content"><?= $thing->abridgedBody ?></p>
			<div class="sub">
				<p class="fr">
					<?php if($commentCount > 0) { ?>
						<a class="scroll" href="<?= $link; ?>#comments"><?php Template::comment_count($commentCount); ?></a>
						&nbsp;|&nbsp;
					<?php } ?>
					<?php Template::pluralise($thing->views, 'view'); ?>
				</p>
				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>
	</li>
