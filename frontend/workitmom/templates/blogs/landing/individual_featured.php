<?

// Uses $blog, which should be a FeaturedblogObject.
// Uses $post, which is the latest post in $blog.
// Uses $blogUrl, which should be a string.
// Uses $postUrl, which should be a string.
// Uses $alt, which should be a boolean.

?>						<li<?= $alt ? ' class="odd"' : ''; ?>>

							<a href="<?= $postUrl; ?>" class="img"><img src="<?= ASSETURL . '/blogheaderimages/60/60/1/' . $blog->blogImage ?>" /></a>
							
							<div class="body">
								<a href="<?= $postUrl; ?>" class="post"><?= $post->title; ?></a>
								<p class="text-content">
									<span class="underline">
										<a href="<?= $blogUrl; ?>"><?= $blog->title; ?></a>
									</span>
									<? if($post->getCommentCount() > 0) { ?>
										&nbsp;|&nbsp; 
										<a href="<?= $postUrl; ?>#comments" class="comments"><?= $post->getCommentCount(); ?> comment<?= Text::pluralise($post->getCommentCount()); ?></a>
									<? } ?>
							</div>
							
							<div class="clear"></div>
							
						</li>
