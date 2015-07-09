				<? if ($thing->author == null) return;?>	
					<li<?= $alt ? ' class="odd"' : ''; ?>>
						<a href="<?= Uri::build($thing->author); ?>" class="img"><img src="<?php Template::image($thing->author, 60); ?>" /></a>
						<div class="body">
							<p class="text-content"><?= $thing->body; ?></p>
							<div class="sub">by <a href="<?= Uri::build($thing->author); ?>"><?= $thing->author->name; ?></a> on <?= $thing->date; ?></div>
						</div>
						<div class="vote">
							<?php /* if($thing->rating['user']) { ?>
							
								<div class="vote_num voted"><?= $thing->votes; ?></div>
								<span><?= $thing->votes != 1 ? 'Votes' : 'Vote'; ?></span>
								
							<? } else { */ ?>
							
								<div class="vote_num"><?= $thing->votes; ?></div>
								<a href="<?= $link; ?>?task=vote&rating=5">Vote!</a>
								
							<?php /* } */ ?>
						</div>
						<div class="clear"></div>
					</li>
