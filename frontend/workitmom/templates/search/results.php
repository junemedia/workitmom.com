
	<div id="main-content" class="search">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="search_icon" class="icon fl"></div>
					<h1>Search</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div id="submit_content">
				<div class="block rounded-630-blue" id="site_search">
					<div class="top"></div>

					<div class="content">
						<form name="form_search_site" id="form_search_site" action="" method="post">
							<label>Search Work It, Mom!</label>

							<input type="text" class="textinput" maxlength="100" size="30" name="search" value="<?= !empty($searchTerms) ? implode(' ', $searchTerms) : ''; ?>" />
							<button class="submit"><span>Search</span></button>
							<div class="clear"></div>
						</form>
					</div>

					<div class="bot"></div>
				</div>
			</div>

			<div id="search_listing">
				<?php $this->listing(); ?>
			</div>
			<?php Template::startScript(); ?>
				var searchListing = new BrowseArea('search_listing');
			<?php Template::endScript(); ?>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>
		</div>

		<div class="panel-right">
			<?php $this->sidebar(array(
				'slideshow_featured',
				'marketplace',
				'catch_your_breath',
				'howto'
			)); ?>
		</div>

		<div class="clear"></div>
	</div>
