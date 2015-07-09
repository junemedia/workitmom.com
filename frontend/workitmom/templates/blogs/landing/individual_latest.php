
						<li<?= $alt ? ' class="odd"' : ''; ?>>
							<a href="<?= SITEURL . $blog->author->profileURL; ?>" class="img"><img src="<?= ASSETURL . '/userimages/60/60/1/' . $blog->author->image;?>" /></a>
							<div class="body">
								<a href="<?= $postUrl; ?>" class="post"><?= $post->title; ?></a>
								
								<? if($post->getCommentCount() > 0) { ?>
									<p class="text-content">
										<a href="<?= $postUrl; ?>#comments" class="scroll comments">
											<?= $post->getCommentCount(); ?> comment<?= Text::pluralise($post->getCommentCount()); ?>
										</a>
									</p>
								<? } ?>
								
							</div>
							<div class="clear"></div>
						</li>