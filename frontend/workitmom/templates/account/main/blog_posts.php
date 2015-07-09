
	<div class="block" id="my_blog_posts">
		<div class="header">
			<div class="title">
				<h2 style="float: left; width: 100px;">My Blog</h2>
				<a href="<?= SITEURL ?>/contribute/blog" class="button_bright fr"><span>Write a post</span></a>
			</div>
		</div>
		<div class="content most_popular">
			<?php if (!empty($blogPosts)) { ?>
			<ul>
				<?php
					foreach($blogPosts as $post) {
						$link = SITEURL.'/blogs/members/'.$post->author->username.'/'.$post->id;
				?>
				<li>
					<a href="<?= $link ?>"><?= $post->title ?></a>
					<p class="text-content underline">
						<?= $post->views ?> view<?= Text::pluralise($post->views) ?> &nbsp;|&nbsp;
						<a href="<?= $link ?>#comments" class="scroll"><?= $post->getCommentCount() ?> comment<?= Text::pluralise($post->getCommentCount()) ?></a>
					</p>
				</li>
				<?php
					}
				?>
			</ul>
			<a href="<?= SITEURL ?>/account/blogs" class="arrow">See all posts</a>
			<div class="clear"></div>
			<?php } else { ?>
			<p>You haven't written any posts yet, why not <a href="<?= SITEURL ?>/contribute/blog">start one now</a>?</p>
			<?php } ?>
		</div>
	</div>
	
	<div class="divider"></div>
	
	<?php BluApplication::getModules('site')->this_weeks_topic(); ?>
	
	<div class="divider"></div>