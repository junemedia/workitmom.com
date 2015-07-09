
		
	<?php if (!empty($featuredBlogs)) { ?>
		<ul class="item_list featured_blogs">
			<?php
				$alt = false;
				foreach ($featuredBlogs as $featuredBlog) {
					$post = $featuredBlog->getLatestPost();
					$blogURL = $featuredBlog->url;
					$postURL = $post->url;
			?>
			<li<?= $alt ? ' class="odd"' : ''; ?>>
				<a href="<?= $postURL ?>" class="img">
					<img src="<?= ASSETURL . '/blogheaderimages/60/60/1/' . $featuredBlog->blogImage ?>" />
				</a>
				<div class="body">
					<a href="<?= $postURL ?>" class="post"><?= $post->title ?></a>
					<p class="text-content">
						<span class="underline">
							<a href="<?= $blogURL ?>"><?= $featuredBlog->title ?></a>
						</span>
						<? if($post->getCommentCount()) { ?>
							&nbsp;|&nbsp;
							<a href="<?= $postURL ?>#comments" class="comments">
								<?= $post->getCommentCount() ?> comment<?= Text::pluralise($post->getCommentCount()) ?>
							</a>
						<? } ?>
				</div>
				<div class="clear"></div>
			</li>
			<?php
					$alt = !$alt;
				}
			?>
		</ul>
		
		<?= $pagination->get('buttons'); ?>
		
	<?php } ?>
