						<li<?= $alt ? ' class="odd"' : ''; ?>>
							<a href="<?= $link; ?>"><?= $question->title; ?></a>
							<p class="text-content">
								Asked on <?= $question->date; ?>
								&nbsp;|&nbsp; 
								<a href="<?= $link; ?>#comments" class="scroll"><?php Template::pluralise($question->getCommentCount(), 'reply', 'replies'); ?></a>
							</p>
						</li>