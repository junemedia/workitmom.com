					<div class="content">					
						<ul>
						
							<?php $featured = array_shift($items); ?>
							<li class="featured">
								<img src="<?php Template::image($featured, 120); ?>" class="img" />
								<a class="post" href="<?= Uri::build($featured); ?>"><?= $featured->title; ?></a>
								<div class="clear"></div>
							</li>
							
							<?php foreach($items as $item){ ?>
							<li class="other">
								<a class="post" href="<?= Uri::build($item); ?>"><?= $item->title; ?></a>
							</li>
							<?php } ?>
							
						</ul>					
					</div>