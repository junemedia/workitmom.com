
	<div id="comments" class="screenonly">
	
		<h2><?php
		if (isset($type) && $type == 'question'){
			Template::pluralise($commentCount, 'reply', 'replies');
		} else {
			Template::comment_count($commentCount);
		}
		?> so far...</h2>
		
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