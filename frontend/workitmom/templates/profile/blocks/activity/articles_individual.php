						<li<?= $alt ? ' class="odd"' : ''; ?>>
							<a href="<?= $link; ?>"><?= $article->title; ?></a>
							<p class="text-content">Posted on <?= $article->date; ?> | <a href="<?= $link; ?>#comments" class="scroll"><?= $article->getCommentCount(); ?> comment<?= Text::pluralise($article->getCommentCount()); ?></a></p>
						</li>