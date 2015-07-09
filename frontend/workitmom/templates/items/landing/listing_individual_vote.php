<?

// Uses $thing, which should be an ItemObject.
// Uses $link, which shuold be a string.
// Uses $alt, which should be a boolean.

?>
					
					<li<?= $alt ? ' class="odd"' : ''; ?>>
						
						<a href="<?= $link; ?>" class="img"><img src="<?php Template::image($thing); ?>" /></a>
						
						<div class="body">
							<a href="<?= $link; ?>"><?= $thing->title; ?></a>
							<br />
							<p class="snippet"><?= $thing->abridgedBody; ?></p>
							<p class="text-content links">Submitted by <a href="<?= SITEURL . $thing->author->profileURL ?>"><?= $thing->author->name; ?></a> | <?= $thing->date; ?></p>
						</div>
						
						<div class="vote">
							<? if($thing->rating['user']) { ?>
							
								<div class="vote_num voted"><?= $thing->votes; ?></div>
								<span><?= $thing->votes != 1 ? 'Votes' : 'Vote'; ?></span>
								
							<? } else { ?>
							
								<div class="vote_num"><?= $thing->votes; ?></div>
								<a href="<?= $link; ?>?task=vote&rating=5">Vote!</a>
								
							<? } ?>
						</div>
						
						<div class="clear"></div>
					</li>