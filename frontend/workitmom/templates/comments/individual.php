<?

// Uses $comment, which should be a CommentObject object.
// Uses $alt, which should be a boolean.

?>
					<li<?= $alt ? ' class="odd"' : ''; ?>>
						<a href="<?php Template::link('person', $comment->author); ?>" class="img">
							<img src="<?php Template::image($comment->author); ?>" />
						</a>
						<div class="body">
							<div class="text-content">
								<?= nl2br(Text::enableLinks($comment->body)); ?>
							</div>
							<p class="sub">
								
								<span class="fr">
									<?php if ($comment->reported) { ?>
										<span class="flag">Reported</span>
									<?php } else { ?>
										<a href="?task=report_comment&comment=<?= $comment->id; ?>" class="flag">Flag as inappropriate</a>
									<?php } ?>
									
									<?php if($canDelete) { ?>
										&nbsp;&nbsp;<a href="?task=delete_comment&comment=<?= $comment->id ?>" title="Delete" class="delete">Delete</a>
									<?php } ?>
								</span>
								
								<span class="comment-by">Posted by <a href="<?php Template::link('person', $comment->author); ?>"><?= $comment->author->name; ?></a> 
								on <?= $comment->date; ?></span>
								
							</p>
						</div>
						<div class="clear"></div>
					</li>
