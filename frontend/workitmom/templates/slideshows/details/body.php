

			<div id="slideshow" class="rounded-630-blue block">
				<div class="top"></div>
				
				<div class="content">
					
					<?php $this->detail_title(); ?>
					
					<div class="clear"></div>
					
					<div id="slide_num">
						<p>Slide <?= $slidenumber; ?> of <?= $slidecount; ?></p>
						<?php
						if ($hasPrevious) { ?><a href="<?= Uri::build($item); ?>/<?= $slidenumber - 1; ?>" class="prev"></a><? }
						if ($hasNext) { ?><a href="<?= Uri::build($item); ?>/<?= $slidenumber + 1; ?>" class="next"></a><? }
						?>
					</div>
					
					<div class="body">
						
						<div class="img">
							<a href="<?php Template::image($slide, 600); ?>" rel="milkbox:userimage"><img src="<?php Template::image($slide, 300); ?>" alt="<?= $slide->title; ?>" /></a>
						</div>
						
						<div class="blurb">
										
							<h3 class="title"><?= $slide->title; ?></h3>
							
							<div class="text-content"><?= $slide->description; ?></div>

							<?php if ($hasNext){ ?>
							<p class="text-content slide_nav">
								Next slide: 
								<a href="<?= Uri::build($item) . '/' . ($slidenumber + 1); ?>" class="arrow"><?= $content[$slidenumber]->title; ?></a>
							</p>
							<?php } ?>
							
							<div class="divider"></div>
							
							<?php if (isset($guestAuthor)){ ?>
							<!-- Slideshow Author -->
							<div id="slideshow-author">
									<a href="<?= Uri::build($guestAuthor); ?>" style="float: right;"><img src="<?php Template::image($guestAuthor, 40); ?>" /></a>
									<h3>By <?= $guestAuthor->name; ?></h3>							
									
									<br />

									<a href="https://twitter.com/Work_It_Mom" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @Work_It_Mom</a>
									<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
									
									<?php if ($guestAuthor->byline){ ?><p><?= $guestAuthor->byline; ?></p><?php } ?>
									<div class="clear"></div>
							</div>
							<div class="divider"></div>
							<!-- End -->
							<?php } ?>
							
							<?php $this->detail_pullquote(3); ?>
							
						</div>
							
						<div class="clear"></div>
					
						<div class="tags text-content underline">
							<strong>Tags:</strong> <?php Template::tags($item->tags, 'slideshow', false); ?>
						</div>
					
					</div>

				</div>
				
				<div class="bot"></div>
			</div>