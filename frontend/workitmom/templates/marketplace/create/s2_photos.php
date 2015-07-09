
	<div class="wrapper">
		<div class="content">
			<form action="<?= SITEURL ?>/marketplace/create" method="post" enctype="multipart/form-data"><div>

				<p class="text-content">
					You can upload multiple photos for your listing.
					The first photo will be used as the main listing photo.
				</p>

				<ul class="upload">
				<?php
					$canContinue = false;
					for ($i = 1; $i <= 3; $i++) {

						// Get current image
						if (!empty($listing['images'])) {
							$canContinue = true;
							$image = array_shift($listing['images']);
						} else {
							$image = null;
						}
				?>
					<li>
						<?php if ($image) { ?>
						<img src="<?= ASSETURL.'/marketimages/60/60/1/'.$image['mpiFile'] ?>" alt="" />
						<?php } ?>
						<input type="file" name="fileupload<?= $i ?>" id="fileupload<?= $i ?>" size="50" class="fileinput" />
						<?php if ($image && $i > 1) { ?>
						<label class="check"><input type="checkbox" name="filedelete<?= $i ?>" value="<?= $image['mpiID'] ?>" />
							Remove this photo</label>
						<?php } ?>
						<div class="clear"></div>
					</li>
				<?php
					}
				?>
				</ul>
				<div class="clear"></div>

				<button type="submit" class="submit"><span>Upload</span></button>

				<input type="hidden" name="task" value="s2_photos_save" />
				<input type="hidden" id="queueid" name="queueid" value="<?= $queueId ?>" />
			</div></form>

			<?php if ($canContinue) { ?>
			<div class="divider"></div>
			<form action="<?= SITEURL ?>/marketplace/create/2" method="post"><div>
				<button type="submit" class="submit"><span><?= $listing['mLive'] ? 'Save' : 'Continue'; ?></span></button>
				<input type="hidden" name="task" value="s2_photos_continue" />
			</div></form>
			<?php } ?>

		</div>
	</div>
