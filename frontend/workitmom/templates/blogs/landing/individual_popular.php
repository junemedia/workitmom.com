
							<li>
								<a href="<?= $postUrl; ?>" class="post-title"><?= $post->title; ?></a>
								
								<? if($post->getCommentCount() > 0) { ?>
									<p>
										<a href="<?= $postUrl; ?>#comments" class="scroll comments">
											<?= $post->getCommentCount(); ?> comment<?= Text::pluralise($post->getCommentCount()); ?>
										</a>
									</p>
								<? } ?>
								
							</li>