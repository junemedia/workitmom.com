
	<?php if (!empty($userBlogPosts)) { ?>
	<div class="item_list">
		<ul>
			<?php
				$alt = true;
				foreach ($userBlogPosts as $post) {
					$link = SITEURL . '/blogs/members/' . $post->author->username . '/' . $post->id;
			?>
			<li<?= $alt ?' class="odd"' : '' ?>>
				<div class="actions">
					<a href="<?= $link ?>"><img src="<?= ASSETURL.'/userimages/100/100/1/'.$post->author->image ?>"></a>
					<ul class="links">
						<li><a href="<?= SITEURL.'/account/edit_blog_post/'.$post->id ?>" class="button_dark fl"><span>Edit Post</span></a><div class="clear"></div></li>
						<li><a href="<?= SITEURL.'/account/delete_blog_post/'.$post->id ?>" class="delete"><span>Delete Post</span></a><div class="clear"></div></li>
					</ul>
				</div>
				<div class="info">
					<h3><a href="<?= $link ?>" class="title"><?= $post->title ?></a></h3>
					<?= Text::trim($post->body, 300) ?>
					<p><a href="<?= $link ?>" class="arrow">Read the rest of this entry</a></p>
				</div>
				<div class="clear"></div>
			</li>
			<?php
					$alt = !$alt;
				}
			?>
		</ul>
	</div>
	<?php } else { ?>
	<div class="message message-info">You have not <a href="<?= SITEURL ?>/contribute/blog">written any blog posts</a>.</div>
	<?php } ?>