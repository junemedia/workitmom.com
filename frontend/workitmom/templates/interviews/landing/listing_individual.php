<?

// Uses $thing, which should be an InterviewObject object.
// Uses $link, which should be a string.
// Uses $alt, which should be a boolean.

?>					

					<li<?= $alt ? ' class="odd"' : ''; ?>>
					
						<a href="<?= $link; ?>" class="img"><img src="<? Template::image($thing); ?>"></a>
						
						<div class="body">
							<a href="<?= $link; ?>"><?= $thing->title; ?></a>
							<p class="text-content"><strong><?= $thing->subtitle; ?></strong></p>
							<p class="text-content"><?= $thing->teaser; ?></p>
						</div>
						
						<div class="clear"></div>
						
					</li>