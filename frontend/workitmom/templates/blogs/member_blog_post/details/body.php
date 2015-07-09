
<div id="post">
	<div class="entry">

	<h2><?= $post->title; ?></h2>
	<div class="header">
		<p>Posted <?= $post->date; ?> by <a href="<?= SITEURL . $post->author->profileURL; ?>"><?= $post->author->name; ?></a><? Template::tags($post->tags, 'note'); ?></p>
		<p>
			<?php if($post->getCommentCount() > 0) { ?>
				<a href="#comments" class="scroll"><?= $post->getCommentCount(); ?> comment<?= Text::pluralise($post->getCommentCount()); ?></a> &nbsp;|&nbsp;
			<?php } ?>
			<a href="#comment" class="scroll">Leave a comment</a> &nbsp;|&nbsp;
			<a href="<?= SITEURL . '/abuse/' . $post->id; ?>">Report</a>
		</p>
		<div class="clear"></div>
	</div>

	<?= $pagination->get('content'); ?>

	</div>

	<div class="clear"></div>

	<?= $pagination->get('buttons'); ?>

	<div class="clear"></div>

</div>
