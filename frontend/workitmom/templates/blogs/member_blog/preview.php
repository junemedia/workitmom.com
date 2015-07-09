
	<div id="post">
		<div class="entry">
			<h2><a href="<?= $post['url']; ?>"><?= $post['title']; ?></a></h2>
			<div class="header">
				<p class="fl">
					Posted <?php Template::date($post['date']); ?> 
					by <a href="<?= $post['author']['url']; ?>"><?= $post['author']['name']; ?></a><?php Template::tags($post['tags'], 'note'); ?>
				</p>
				<p class="fr">

				<?php if ($post['comment_count']) { ?>
					<a href="<?= $post['url']; ?>#comments" class="scroll"><?php Template::comment_count($post['comment_count']); ?></a>
					&nbsp;|&nbsp;
				<?php } ?>

					<a href="<?= $post['url']; ?>#comment" class="scroll">Leave a comment</a>
				</p>
				<div class="clear"></div>
			</div>
			<?= Text::trim($post['body'], 300); ?>
			<p><a href="<?= $post['url']; ?>" class="arrow">Read the rest of this entry</a></p>
		</div>
		<div class="clear"></div>
	</div>

	<div class="divider"></div>