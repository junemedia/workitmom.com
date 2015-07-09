
	<?php if (!empty($featuredBlogs)) { ?>
	<div id="from_our_bloggers" class="block">
		<div class="header">
			<div class="title">
				<h2><a href="<?= SITEURL ?>/blogs/">From Our Bloggers</a></h2>
			</div>

			<a href="<?= SITEURL ?>/blogs/" class="button_dark"><span>See All</span></a>

			<div class="clear"></div>
		</div>
		<ul>
			<?php foreach ($featuredBlogs as $featuredBlog) {
if(in_array($featuredBlog->title,array('The Same But Different','Mom On A Budget','Power Mom')))
				{
				$post = $featuredBlog->getLatestPost();?>
			<li>
				<a href="<?=$post->url;?>" class="img">
					<img src="<?= ASSETURL . '/blogheaderimages/60/60/1/' .$featuredBlog->blogImage;?>" />
				</a>
				<div class="body">
					<a href="<?= $post->url ?>" class="post"><?= $post->title ?></a>
					<p class="text-content">
						<span class="underline">
							<a href="<?= $featuredBlog->url ?>"><?= $featuredBlog->title ?></a>
						</span>
						<? if($featuredBlog->getLatestPost()->getCommentCount()) { ?>
							&nbsp;|&nbsp;
							<a href="<?= $post->url ?>#comments" class="comments">
								<?= $post->getCommentCount() ?> comment<?= Text::pluralise($post->getCommentCount()) ?>
							</a>
						<? } ?>
				</div>
				<div class="clear"></div>
			</li>
			<?php } 
			} ?>
		</ul>
		<div class="clear"></div>
	</div>
	<?php } ?>
