<?php
// Parse module arguments.
$type = array_shift($moduleArgs);
$latestitems = $moduleArgs;
?>
					
					<!-- Most Popular Posts -->
					<div class="rounded-300-outline block">
						<div class="top"></div>
						<div class="content most_popular">
							<h4>Most Recent <?= $type; ?></h4>
							<ol class="text-content">
								
								<?php foreach($latestitems as $item){ ?>
								<li>
									<a href="<?= Uri::build($item); ?>"><?= $item->title; ?></a><br />
									<small>
										<?= $item->views; ?> view<?= Text::pluralise($item->views); ?> 
										&nbsp;|&nbsp; 
										<a href="<?= Uri::build($item); ?>#comments"><?php Template::comment_count($item->getCommentCount()); ?></a>
									</small>
								</li>
								<?php } ?>
								
							</ol>
						</div>
						<div class="bot"></div>
					</div>
					<!-- END Most Popular Posts -->