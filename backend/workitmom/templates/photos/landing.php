
<div id="photos_listing">
	<?php $this->listing(); ?>

	<?php Template::startScript(); ?>
	new BrowseArea('photos_listing', {updateTask: 'listing', evalScripts: true});
	<?php Template::endScript(); ?>
</div>