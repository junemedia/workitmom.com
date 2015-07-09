
	<!-- Start border -->	
	<div class="rounded-630-outline members" id="photos">
		<div class="top"></div>

		<div class="content">		
	<!-- end border -->

			<h2>Member Photos</h2>

			<div id="photos_listing">
				<?php $this->listing_photos($sort); ?>	
			
				<?php Template::startScript(); ?>
				new BrowseArea('photos_listing', {updateTask: 'listing_photos', scrollTo: 'photos'});
				<?php Template::endScript(); ?>			
			</div>
	
	<!-- start border -->			
		</div>	
		<div class="clear"></div>
		<div class="bot"></div>
	</div>
	<!-- end border -->