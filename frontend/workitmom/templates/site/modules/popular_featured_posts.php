
	<?php if (Utility::iterable($popularPosts)){ ?>
	<!-- Most Popular Posts -->
	<div class="rounded-300-outline block">
		<div class="top"></div>
		<div class="content most_popular">
			<h4>Most Popular Posts</h4>
			<ol class="text-content">
				
				<?php foreach($popularPosts as $post){ ?>
				<li>
					<a href="<?= $post['guid']; ?>"><?= $post['post_title']; ?></a>
					<br />
					<small><?php Template::comment_count($post['comment_count']); ?></small>
				</li>
				<?php } ?>
				
			</ol>
		</div>
		<div class="bot"></div>
	</div>
	<!-- END Most Popular Posts -->
	<?php } ?>
