
	<?php if (!empty($latestBlogs)) { ?>
	<div class="block" id="member_blogs">
		<div class="header">
			<div class="title">
				<h2>From our Members</h2>
			</div>
			<a href="<?=SITEURL;?>/blogs/members/" class="button_dark"><span>See all</span></a>
		</div>
		<ul class="item_list featured_blogs" id="member_blogs">
			<?php
				$alt = false;
				foreach ($latestPosts as $post) {
					$postUrl = SITEURL.'/blogs/member_blog_post/'.$post->id;
			?>
			<li<?= $alt ? ' class="odd"' : ''; ?>>
				<a href="<?= SITEURL.$post->author->profileURL ?>" class="img"><img src="<?php Template::image($post->author, 60); ?>" /></a>
				<div class="body">
					<a href="<?= $postUrl ?>" class="post"><?= $post->title ?></a>
					<p class="text-content">
						<a href="<?= $postUrl ?>#comments" class="scroll comments"><?php Template::comment_count($post->getCommentCount()); ?></a>
					</p>
				</div>
				<div class="clear"></div>
			</li>
			<?php
					$alt = !$alt;
				}
			?>
		</ul>
		<a href="<?= SITEURL ?>/contribute/blog/" class="button_bright fl"><span>Write a blog post</span></a>
	</div>
	<?php } ?>