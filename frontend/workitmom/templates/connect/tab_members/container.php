
	<!-- Start border -->	
	<div class="rounded-630-outline members" id="members">
		<div class="top"></div>

		<div class="content">		
	<!-- end border -->
		
			<h2>Work It, Mom! Members</h2>
			
			<div id="members_listing">
				<?php $this->listing_members($sort); ?>
		
				<?php Template::startScript(); ?>
				new BrowseArea('members_listing', {updateTask: 'listing_members', scrollTo: 'members'});
				<?php Template::endScript(); ?>
			</div>
			
	<!-- start border -->			
		</div>	
		<div class="clear"></div>
		<div class="bot"></div>
	</div>	
	<!-- end border -->