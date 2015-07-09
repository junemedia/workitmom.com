
	<div id="main-content" class="questions">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<?php $this->page_heading(); ?>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="col-l">
				<?php $this->landing_ask_question(); ?>
			</div>

			<div class="col-r">
			
				<?php $this->landing_featured_question(); ?>
				<div class="divider"></div>

				<div id="search_block" class="block">
				
					<div class="header">
						<div class="title">
							<h2>Find Questions &amp; Answers</h2>
						</div>
					</div>
				
						<div class="content">
							<form id="groups-form-search" action="/search?type=question" method="post" class="search"><div>
								<div class="input-wrapper"><input class="textinput overtext" type="text" alt="enter search keywords..." autocomplete="off" name="search" /></div>
								<button class="but-find" type="submit" title="Find"></button></div>
							</form>
							<div class="clear"></div>
						</div>
				
				</div>



			</div>

			<div class="clear"></div>
			<div class="divider"></div>

			<div id="questions_listing">

				<?php Template::startScript(); ?>
					var questionsListing = new BrowseArea('questions_listing');
				<?php Template::endScript(); ?>

				<?php $this->listing(); ?>

			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>
		<? // END >> RIGHT COLUMN  ?>

		<div class="clear"></div>
	</div>
