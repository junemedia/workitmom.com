
	<div class="wrapper">
		<div class="content">
			<form action="<?= SITEURL ?>/marketplace/create" method="post"><div>

				<dl>
					<dt><label for="category">Category <span class="red-ast">*</span></label></dt>
					<dd>
						<select id="category" name="category" class="required">
							<option value="">Please select from below</option>
							<?php foreach ($categories as $category) { ?>
							<option value="<?= $category['mpcID'] ?>"<?php if ($categoryId == $category['mpcID']) { ?> selected="selected"<?php } ?>><?= $category['categoryName'] ?></option>
							<?php } ?>
						</select>
					</dd>

					<dt><label for="title">Title <span class="red-ast">*</span></label></dt>
					<dd>
						<input type="text" id="title" name="title" value="<?= $title ?>" class="textinput required" />
						<small class="fieldhint">Check out our tips for writing effective titles by <a href="<?= SITEURL ?>/info/marketplace_tips" class="info-popup">clicking here</a>.</small>
					</dd>

					<dt>
						<label for="shortttitle">Short Title <span class="red-ast">*</span></label>
						<span class="fieldcount"><span id="shorttitlecount">0</span>/32</span>
					</dt>
					<dd>
						<input type="text" id="shorttitle" name="shorttitle" maxlength="32" value="<?= $shorttitle ?>" class="textinput" />
						<small class="fieldhint">This is the title that will be shown with your featured listing in our featured marketplace module.</small>
						<?php Template::startScript(); ?>

							var shorttitleCounter = new LengthCounter('shorttitle', 'shorttitlecount');

						<?php Template::endScript(); ?>
					</dd>

					<dt>
						<label for="description">Description <span class="red-ast">*</span></label>
						<span class="fieldcount"><span id="descriptioncount">0</span>/1500</span>
					</dt>
					<dd>
						<textarea id="description" name="description" class="textinput required" rows="6"><?= $description ?></textarea>
						<?php Template::startScript(); ?>

							var descriptionCounter = new LengthCounter('description', 'descriptioncount', {
								maxLength: 1500
							});

						<?php Template::endScript(); ?>
					</dd>

					<dt><label for="website">Website</label></dt>
					<dd>
						<input type="text" id="website" name="website" value="<?= $website ?>" class="textinput" />
						<small class="fieldhint">If you have a website, please enter the URL above to be shown in your listing.</small>
					</dd>

					<dt><label for="discounts">Discounts</label></dt>
					<dd>
						<textarea id="discounts" name="discounts" class="textinput overtext" rows="6" title="Offering a discount or a promotion for Work It, Mom! members is a great way to increase clicks on your listing and to encourage moms to purchase your product or service. Please make sure that you only list CURRENT discounts and deals and indicate any restrictions (expiration date, limited quantity, etc.)"><?= $discounts ?></textarea>
					</dd>
				</dl>
				<div class="clear"></div>

				<div class="divider"></div>

				<h3>Your listing contact details</h3>
				<dl>
					<dt><label for="name">Name <span class="red-ast">*</span></label></dt>
					<dd>
						<input type="text" id="contactname" name="contactname" value="<?= $contact['name'] ?>" class="textinput required" />
	                </dd>

					<dt><label for="email">Email <span class="red-ast">*</span></label></dt>
					<dd>
	                	<input type="text" id="contactemail" name="contactemail" value="<?= $contact['email'] ?>" class="textinput required"/>

						<label class="check show-check"><input type="checkbox" id="contactshowemail" name="contactshowemail" value="1"<?php if ($contact['showEmail']) { ?> checked="checked"<?php } ?> />
							Show this on my listing</label>
					</dd>

					<dt><label for="phone">Phone</label></dt>
					<dd>
						<input type="text" id="contactphone" name="contactphone" value="<?= $contact['phone'] ?>" class="textinput" />

						<label class="check show-check"><input type="checkbox" id="contactshowphone" name="contactshowphone" value="1"<?php if ($contact['showPhone']) { ?> checked="checked"<?php } ?> />
							Show this on my listing</label>
					</dd>

					<dt><label for="location">Location <span class="red-ast">*</span></label></dt>
					<dd>
						<input type="text" id="contactlocation" name="contactlocation" class="textinput" size="30" maxlength="100" value="<?= $contact['location'] ?>" />

						<label class="check show-check"><input type="checkbox" id="contactshowlocation" name="contactshowlocation" value="1"<?php if ($contact['showLocation']) { ?> checked="checked"<?php } ?> />
							Show this on my listing</label>

						<?php Template::startScript(); ?>

							// Location input
							var locationInput = $('contactlocation');

							// Location autocompleter
							new Autocompleter.Request.JSON(locationInput, SITEURL + '/locations/search/', {postVar: 'criteria'});

							// Custom validator
							locationInput.addClass('validate-custom');
							locationInput.set('validatorProps', JSON.encode({custom_error: 'Please enter a valid location from the autocompleter.', custom_url: SITEURL + '/locations/'}));

						<?php Template::endScript(); ?>
					</dd>
				</dl>
				<div class="clear"></div>

				<div class="divider"></div>

				<p>
					<label class="check show-check"><input type="checkbox" id="linktoprofile" name="linktoprofile" value="1"<?php if ($linkToProfile) { ?> checked="checked"<?php } ?> />
						Link my listing to my Work It, Mom! profile?</label>
					<small class="fieldhint">A link to your profile will appear with your listing and a link to your listing will be shown on your profile page.</small>
				</p>

				<p>
                    <label class="check show-check"><input type="checkbox" id="receivealert" name="receivealert" value="1"<?php if ($receiveAlert) { ?> checked="checked"<?php } ?> />
                    	Remind me by email a week before my listing expires?</label>
				</p>
				<div class="clear"></div>

				<button type="submit" class="submit"><span><?= $listing['mLive'] ? 'Save' : 'Continue'; ?></span></button>

				<div class="required"><strong>NOTE:</strong> All fields marked <span class="red-ast">*</span> are required.</div>

				<input type="hidden" name="task" value="s1_details_save" />
			</div></form>
		</div>
	</div>
