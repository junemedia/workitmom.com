
	<div id="comments" class="screenonly">
	
		<h2><?= isset($type) && $type == 'question' ? $commentCount.' replies' : 'Comments'; ?> so far...</h2>
		
		<div id="comments_listing">
			<?php $this->view_comments($options); ?>
			
			<?php Template::startScript(); ?>
			new BrowseArea('comments_listing', {
				updateTask: 'view_comments', 
				scrollTo: 'comments'
			});
			<?php Template::endScript(); ?>
		</div>
		
	</div>