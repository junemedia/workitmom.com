<style>
.photodetail td{
border:none !important;
}
</style>
<div style="margin-left: 50px;">
	<div class="body_wrapper">
	
		<div class="header_wrapper">
			<h1>Photo:</h1>
		</div>
		<form method="post" action="<?= SITEURL; ?>/photos/edit/?photo=<?= $photo['id']; ?>" enctype="multipart/form-data">
		<!--<div class="chunk">
			<div class="header_wrapper">
				<h3>Actions:</h3>
			</div>
			<div>
				<?php if ($photo['deleted']){ ?>
				<span class="resolved action">Deleted</span>
				<?php } else { ?>
				<a class="action" href="<?= SITEURL; ?>/photos/delete/?photo=<?= $photo['id']; ?>">Delete</a>
				<?php } ?>
			</div>
		</div>-->
	<table class="photodetail">
	<tr class="chunk">
		<td>
			<div class="header_wrapper">
				<h3>Title:</h3>
			</div>
		</td>
		<td>
			<input type="text" name="photoTitle" id="photoTitle" value="<?= $photo['title']?>"/>
		</td>
	</tr>
	<tr class="chunk">
		<td>
			<div class="header_wrapper">
				<h3>Author:</h3>
			</div>
		</td>
		<td>
			<input type="text" name="photoAuthor" id="photoAuthor" DISABLED value="<?= $photo['author']['name']?>" readonly/>
		</td>
	</tr>
	<tr class="chunk">
		<td>
			<div class="header_wrapper">
				<h3>Description:</h3>
			</div>
		</td>
		<td>
			<textarea style="height: 120px;width:413px" name="photoDesc" id="photoDesc"><?= $photo['description']; ?></textarea>
			<div class="clear"></div>
		</td>
	</tr>
	<tr class="chunk">
		<td>
			<div class="header_wrapper">
				<h3>Photo View:</h3>
			</div>
		</td>
		<td>
			<img src="<?php Template::image($photo, 150); ?>" />
		</td>
	</tr>
	<tr class="chunk">
		<td>
			<div class="header_wrapper">
				<h3>Set Live:</h3>
			</div>
		</td>
		<td>
			<input type="checkbox" value="1" name="photoLive" <?=$photo['status']?'checked':'';?>>
		</td>

	</tr>
		</table>
		<input type="submit" value="Save">
		</form>
	
	</div>
</div>