<?

// Uses $uploadQueue, which should be an array.
// Uses $queueId, which should be an md5 hash.

?>		

<?php /* Remove 'fancyoff' class to enable FancyUpload */ ?>
<div class="fancyoff standardform"><div class="formholder">	
	<form action="/assets/upload/" method="post" enctype="multipart/form-data" id="submit_content">		
		<div id="upload-holder">
			
			
			<? /* Upload status */ ?>
			<div id="upload-status">
				<?= Messages::getMessages('uploader'); ?>
			</div>
			
			
			<? if (Utility::is_loopable($uploads)) { /* Upload queue */ ?>
			<div class="fieldwrap images">
				<label>Your Images</label>
				<div class="clear"></div>
				<small>To insert an image into your post, simply drag it to the notepad.</small>
				<ul id="upload-list">
					<? foreach($uploads as $image) { ?>
					<li><a href="#"><img src="<?= $image['dir'] . '/75/75/1/' . $image['file']; ?>" /></a></li>
					<input name="uploads[]" type="hidden" value="<?= $image['dir'] . '/75/75/1/' . $image['file']; ?>" />
					<? } ?>
				</ul>
			</div>
			<? } ?>
			
			
			<div class="fieldwrap image">
				<label for="form_image">Include an Image</label>
				<div class="clear"></div>
				
				<? /* FancyForm Fallback */ ?>
				<div id="upload-fallback">
					<input name="fileupload" type="file" class="file" id="form_image" size="40" />
					<button type="submit" value="upload"><span>upload</span></button>
				</div>
				
				<? /* Upload actions */ ?>
				<div id="upload-actions" style="display: none;">
					<a id="upload-browse">upload image</a>
				</div>
				
			</div>
			
			
			
			<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
			<input type="hidden" id="reloadtask" name="reloadtask" value="newticket" />
			<input type="hidden" id="uploadtask" name="uploadtask" value="ticket_addfile" />
			<input type="hidden" id="queueid" name="queueid" value="<?= $queueId; ?>" />
			
			
		</div>	
	</form>	
</div></div>