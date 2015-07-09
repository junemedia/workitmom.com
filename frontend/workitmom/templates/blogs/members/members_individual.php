					<li <?= $alt ? 'class="odd"' : ''?>>
						<a href="<?= $post['author']['url']; ?>" class="img">
							<img src="<?php Template::image($post['author'], 60); ?>" />
						</a>
						<div class="body">
							<a href="<?= $post['url']; ?>" class="post"><?= $post['title']; ?></a>
							<p class="text-content fl">
							by <cite><a href="<?= $post['author']['url']; ?>"><?= $post['author']['name']; ?></a></cite> 
							on <?php Template::date($post['date']); ?>
							<?php if ($post['comment_count'] > 0) { ?>&nbsp;|&nbsp; <a href="<?= $post['url'].'#comments'; ?>"><?php Template::comment_count($post['comment_count']); ?></a><?php } ?>
							</p>
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
					</li>
