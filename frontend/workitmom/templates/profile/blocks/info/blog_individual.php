	<li>
		<a href="<?= $link; ?>"><?= $post->title; ?></a>
		<p class="underline">
			<?= $post->date; ?> &nbsp;|&nbsp; <?= $post->views; ?> view<?= Text::pluralise($post->views); ?>
			&nbsp;|&nbsp;
			<a href="<?= $link; ?>#comments" class="scroll"><?= $post->getCommentCount(); ?> comment<?= Text::pluralise($post->getCommentCount()); ?></a>
		</p>
	</li>