					<li <?= $alt ? 'class="odd"' : ''?>>

						<a href="<?= $postUrl; ?>" class="img">
							<img src="<?= ASSETURL . "/blogheaderimages/60/60/1/" . $blog->blogImage ?>" />
						</a>
						<div class="body">

							<a href="<?= $postUrl ?>" class="post"><strong><?= $post->title ?></strong></a>
							<p class="text-content underline"><a href="<?= $blogUrl ?>"><?= $blog->title ?></a>
							<? if($post->getCommentCount() > 0) { ?>&nbsp;|&nbsp; <a href="<?= $postUrl . "#comments" ?>">
									<?php Template::comment_count($post->getCommentCount()); ?>
								</a><? } ?></p>
							<p class="text-content underline">
								<? /*by <cite><a href="<?= $blogUrl ?>"><?= $post->author->name ?></a></cite>  */?>




							</p>
						</div>
						<div class="clear"></div>
					</li>
