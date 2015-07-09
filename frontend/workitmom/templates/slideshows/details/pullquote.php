							<div class="most_popular">
								<h4>You May Also Like</h4>
								<ul>
									<?php foreach($links as $link){ ?>
									<li><a href="<?= $link['url']; ?>"><?= $link['title']; ?></a></li>
									<?php } ?>
								</ul>
							</div>