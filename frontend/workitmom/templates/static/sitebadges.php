
	<div id="main-content" class="static">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div class="icon fl"></div>
					<h1>Site Badges</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div id="site-badges">

				<div class="rounded-630-blue">
					<div class="top"></div>

					<div class="content" id="tell_friends">

						<h2>Add a Work It, Mom! button to your site!!</h2>
						<p class="text-content">
							If you run a website or blog and would like to help promote Work It, Mom! please feel free to add one of our site badges!
						</p>

						<div class="clear" style="height:15px;"></div>

						<div class="white">

							<!-- BADGE -->
							<div class="badge-holder">

								<div class="image">
								<img src="<?= SITEASSETURL; ?>/images/ads/button5.gif" />
								</div>

								<div class="information">
								<textarea readonly onClick="this.focus();this.select();"><a href="http://www.workitmom.com"><img src="http://www.workitmom.com/frontend/workitmom/images/ads/button5.gif" border="0" /></a>></textarea>
								<p>Click inside the box and copy the code</p>
								</div>

							<div class="clear"></div>
							</div>
							<!-- END BADGE -->

							<!-- BADGE -->
							<div class="badge-holder">

								<div class="image">
								<img src="<?= SITEASSETURL; ?>/images/ads/button4.gif" />
								</div>

								<div class="information">
								<textarea readonly onClick="this.focus();this.select();" name="textarea4" class="wimbubox"><a href="http://www.workitmom.com"><img src="http://www.workitmom.com/frontend/workitmom/images/ads/button4.gif" border="0" /></a></textarea>
								<p>Click inside the box and copy the code</p>
								</div>

							<div class="clear"></div>
							</div>
							<!-- END BADGE -->

							<!-- BADGE -->
							<div class="badge-holder">

								<div class="image">
								<img src="<?= SITEASSETURL; ?>/images/ads/button6.gif" />
								</div>

								<div class="information">
								<textarea readonly onClick="this.focus();this.select();" name="textarea4" class="wimbubox"><a href="http://www.workitmom.com"><img src="http://www.workitmom.com/frontend/workitmom/images/ads/button6.gif" border="0" /></a></textarea>
								<p>Click inside the box and copy the code</p>
								</div>

							<div class="clear"></div>
							</div>
							<!-- END BADGE -->

						</div>
					</div>
					<div class="bot"></div>
				</div>
			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
