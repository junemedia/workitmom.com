						<li<?= $alt ? ' class="odd"' : ''; ?>>
							<a href="<?= $link; ?>"><?= $question->title; ?></a>
							<p class="text-content">
								Answered on <?= $reply->date; ?>:
								<blockquote><?= $reply->body; ?></blockquote>
							</p>
							<p class="text-content">
								<a href="<?= $link; ?>#comments"><?php Template::pluralise($question->getCommentCount(), 'reply', 'replies'); ?></a>
							</p>
						</li>