
						<div class="standardform">
							<div class="formholder">
								<form method="post" action="<?= SITEURL ?>/account/photos?tab=upload" enctype="multipart/form-data"><div>

									<dl>
										<dt><label for="imageupload">Image</label></dt>
										<dd><input type="file" id="imageupload" name="imageupload" class="file" size="47" /></dd>
									</dl>
									
									<noscript><button class="submit"><span>Upload now</span></button></noscript>
									<?php Template::startScript(); ?>
									$('imageupload').addEvent('change', function(){ 
										this.getParent('form').submit();
									});
									<?php Template::endScript(); ?>

									<input type="hidden" name="task" value="photos_upload" />
									<input type="hidden" id="queueid" name="queueid" value="<?= $queueId ?>" />
								</div></form>
							</div>
						</div>