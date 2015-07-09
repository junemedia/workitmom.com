
	<?php if (!empty($popularPosts)) { ?>
	<div class="rounded-300-yellow block">
		<div class="top"></div>
		<div class="content most_popular">
			<h4>This Week's Most Popular</h4>
			<ul>
				<?php foreach ($popularPosts as $post) { ?>
				<li>
					<strong><a href="<?= $post['guid'] ?>"><?= $post['post_title']; ?></a></strong>
					<p>
						<?/* <a href="<?= $post['guid'] ?>#comments" class="scroll comments">*/?>
							<?php Template::comment_count($post['comment_count']); ?>
						<?/* </a>*/?>
					</p>
				</li>
				<?php } ?>
			</ul>
		</div>
		<div class="bot"></div>
	</div>
	<?php } ?>