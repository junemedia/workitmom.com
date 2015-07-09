
	<div class="rounded-630-blue">
		<div class="top"></div>
		<div class="content">

			<div class="message message-info" style="margin-top:0;">
				Please note that inappropriate photos will be removed and can be reported by members.
			</div>

			<p class="text-content">To upload a photo, choose the file from your computer using the fields below. Optionally, you can add a title to any photo you upload.</p>

			<div class="standardform">
				<div class="formholder">
					<form method="post" action="<?= SITEURL ?>/account/photos?tab=upload" enctype="multipart/form-data"><div>

						<div id="account-photo-upload">
							<dl>
								<?php for ($i = 1; $i <= 3; $i++) { ?>
								<dt><label for="photoupload<?= $i ?>">Photo file</label></dt>
								<dd><input type="file" id="photoupload<?= $i ?>" name="photoupload<?= $i ?>" class="file" size="47" /></dd>

								<dt class="caption"><label for="photocaption<?= $i ?>">Caption</label></dt>
								<dd class="caption"><input type="text" id="photocaption<?= $i ?>" name="photocaption<?= $i ?>" class="textinput" /></dd>
								<?php } ?>
							</dl>
							<div class="clear"></div>
						</div>

						<div class="clear" style="height: 25px;"></div>

						<button class="submit" type="submit"><span>Upload now</span></button>

						<input type="hidden" name="task" value="photos_upload" />
						<input type="hidden" id="queueid" name="queueid" value="<?= $queueId ?>" />
					</div></form>
				</div>
			</div>
		</div>
		<div class="bot"></div>
	</div>