
	<div id="main-content">
	
		<div class="panel-left" id="photos">
		
			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				
				<div class="content">
					<div id="search_icon" class="icon fl"></div>
					<h1>Search Members</h1>
				</div>
				
				<div class="bot"></div>
			</div>
			<a class="button_bright fr" style="margin: -60px 10px 0 0" href="<?= SITEURL ?>/tellafriend"><span>Invite Your Friends</span></a>
			
			<?= Messages::getMessages(); ?>

			<div id="member_search">
				<?php $this->listing_search(); ?>
				
				<?php Template::startScript(); ?>
				var membersListing = new BrowseArea('member_search', {updateTask: 'listing_search', scrollTo: 'peeps'});
				<?php Template::endScript(); ?>
			</div>
			
			
			
			<?php BluApplication::getModules('site')->bottom_blocks(); ?>
			
		</div>
		<? // END >> LEFT COLUMN  ?>
		
		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>
		<? // END >> RIGHT COLUMN  ?>

		<div class="clear"></div>
	</div>
