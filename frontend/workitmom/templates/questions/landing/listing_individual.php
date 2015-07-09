<?

// Uses $thing, which should be a QuestionObject object.
// Uses $link, which should be a string.
// Uses $alt, which should be a boolean.

?>
	
	
	<li<?= $alt ? ' class="odd"' : ''; ?>>
		
		<a href="<?= $link; ?>" class="img"><img src="<?php Template::image($thing); ?>" /></a>
		
		<div class="body">
			<a href="<?= $link; ?>"><?= Text::trim($thing->title, 180); ?></a>
			<p class="text-content underline">
				<a href="<?= $link; ?>#comments" class="scroll"><?= $thing->getCommentCount(); ?> answer<?= Text::pluralise($thing->getCommentCount()); ?></a>
				&nbsp;|&nbsp;
				<a href="<?= $link; ?>#comment" class="scroll">Reply </a>
			</p>
		</div>
		
		<div class="clear"></div>
		
	</li>
