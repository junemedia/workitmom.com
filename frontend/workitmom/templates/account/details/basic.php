
	<form method="post" action="/account/details" enctype="multipart/form-data"><div>

		<dl>
			<dt><label for="form_display_name">Public Display Name <span class="red-ast">*</span></label></dt>
			<dd>
				<input name="form_display_name" class="textinput required" type="text" id="form_display_name" value="<?= $displayName ?>" />
			</dd>

			<dt><label for="form_email">Email Address <span class="red-ast">*</span></label></dt>
			<dd>
				<input name="form_email" class="textinput required validate-email" type="text" id="form_email" value="<?= $email ?>" />
				<a href="#change-password-panel" id="change-password">Change password?</a>
			</dd>
		</dl>
		<div class="clear"></div>

		<dl id="change-password-panel">
			<dt><label for="form_password">New Password <span class="red-ast">*</span></label></dt>
			<dd>
				<input name="form_password" class="textinput minLength maxLength" validatorProps="{minLength: 6, maxLength: 30}" type="password" id="password" value="" size="30" maxlength="30" />
			</dd>

			<dt><label for="form_password_confirm">Confirm Password <span class="red-ast">*</span></label></dt>
			<dd>
				<input name="form_password_confirm" class="textinput validate-passwordconfirm" type="password" id="form_password_confirm" size="30" maxlength="30" />
			</dd>
		</dl>
		<div class="clear"></div>

		<?php Template::startScript(); ?>

			/* Show/hide change password fields */
			var passwordPanel = new PanelSlider('change-password', 'change-password-panel', {hideLink: false});

		<?php Template::endScript(); ?>

		<dl>
			<dt><label>Location <span class="red-ast">*</span></label></dt>
			<dd>
				<input name="form_location" class="textinput" type="text" id="form_location" size="30" maxlength="100" value="<?= $location ?>" />
				<?php Template::startScript(); ?>

					// Location input
					var locationInput = $('form_location');

					// Location autocompleter
					new Autocompleter.Request.JSON(locationInput, SITEURL + '/locations/search/', {postVar: 'criteria'});

					// Custom validator
					locationInput.addClass('validate-custom');
					locationInput.set('validatorProps', JSON.encode({custom_error: 'Please enter a valid location from the autocompleter.', custom_url: SITEURL + '/locations/'}));

				<?php Template::endScript(); ?>
			</dd>

			<dt><label for="form_timezone">Time Zone <span class="red-ast">*</span></label></dt>
			<dd>
				<select name="form_timezone" id="form_timezone">
					<option value="-12"<?php if ($timezone == -12) { echo ' selected="selected"'; } ?>>(GMT -12:00) Eniwetok, Kwajalein</option>
					<option value="-11"<?php if ($timezone == -11) { echo ' selected="selected"'; } ?>>(GMT -11:00) Midway Island, Samoa</option>
					<option value="-10"<?php if ($timezone == -10) { echo ' selected="selected"'; } ?>>(GMT -10:00) Hawaii</option>
					<option value="-9.5"<?php if ($timezone == -9.5) { echo ' selected="selected"'; } ?>>(GMT -9:30) French Polynesia</option>
					<option value="-9"<?php if ($timezone == -9) { echo ' selected="selected"'; } ?>>(GMT -9:00) Alaska</option>
					<option value="-8"<?php if ($timezone == -8) { echo ' selected="selected"'; } ?>>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
					<option value="-7"<?php if ($timezone == -7) { echo ' selected="selected"'; } ?>>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
					<option value="-6"<?php if ($timezone == -6) { echo ' selected="selected"'; } ?>>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
					<option value="-5"<?php if ($timezone == -5) { echo ' selected="selected"'; } ?>>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
					<option value="-4"<?php if ($timezone == -4) { echo ' selected="selected"'; } ?>>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
					<option value="-3.5"<?php if ($timezone == -3.5) { echo ' selected="selected"'; } ?>>(GMT -3:30) Newfoundland</option>
					<option value="-3"<?php if ($timezone == -3) { echo ' selected="selected"'; } ?>>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
					<option value="-2"<?php if ($timezone == -2) { echo ' selected="selected"'; } ?>>(GMT -2:00) Mid-Atlantic</option>
					<option value="-1"<?php if ($timezone == -1) { echo ' selected="selected"'; } ?>>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
					<option value="0"<?php if ($timezone == 0) { echo ' selected="selected"'; } ?>>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
					<option value="1"<?php if ($timezone == 1) { echo ' selected="selected"'; } ?>>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
					<option value="2"<?php if ($timezone == 2) { echo ' selected="selected"'; } ?>>(GMT +2:00) Kaliningrad, South Africa</option>
					<option value="3"<?php if ($timezone == 3) { echo ' selected="selected"'; } ?>>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
					<option value="3.5"<?php if ($timezone == 3.5) { echo ' selected="selected"'; } ?>>(GMT +3:30) Tehran</option>
					<option value="4"<?php if ($timezone == 4) { echo ' selected="selected"'; } ?>>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
					<option value="4.5"<?php if ($timezone == 4.5) { echo ' selected="selected"'; } ?>>(GMT +4:30) Kabul</option>
					<option value="5"<?php if ($timezone == 5) { echo ' selected="selected"'; } ?>>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
					<option value="5.5"<?php if ($timezone == 5.5) { echo ' selected="selected"'; } ?>>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
					<option value="5.75"<?php if ($timezone == 5.75) { echo ' selected="selected"'; } ?>>(GMT +5:45) Nepal</option>
					<option value="6"<?php if ($timezone == 6) { echo ' selected="selected"'; } ?>>(GMT +6:00) Almaty, Dhaka, Colombo</option>
					<option value="6.5"<?php if ($timezone == 6.5) { echo ' selected="selected"'; } ?>>(GMT +6:30) Burma</option>
					<option value="7"<?php if ($timezone == 7) { echo ' selected="selected"'; } ?>>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
					<option value="8"<?php if ($timezone == 8) { echo ' selected="selected"'; } ?>>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
					<option value="9"<?php if ($timezone == 9) { echo ' selected="selected"'; } ?>>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
					<option value="9.5"<?php if ($timezone == 9.5) { echo ' selected="selected"'; } ?>>(GMT +9:30) Adelaide, Darwin</option>
					<option value="10"<?php if ($timezone == 10) { echo ' selected="selected"'; } ?>>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
					<option value="10.5"<?php if ($timezone == 10.5) { echo ' selected="selected"'; } ?>>(GMT +10:30) Lord Howe Island</option>
					<option value="11"<?php if ($timezone == 11) { echo ' selected="selected"'; } ?>>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
					<option value="11.5"<?php if ($timezone == 11.5) { echo ' selected="selected"'; } ?>>(GMT +11:30) Norfolk Island</option>
					<option value="12"<?php if ($timezone == 12) { echo ' selected="selected"'; } ?>>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
					<option value="12.75"<?php if ($timezone == 12.75) { echo ' selected="selected"'; } ?>>(GMT +12:45) Chatham Islands</option>
					<option value="13"<?php if ($timezone == 13) { echo ' selected="selected"'; } ?>>(GMT +13:00) Kiribati, Tonga</option>
					<option value="14"<?php if ($timezone == 14) { echo ' selected="selected"'; } ?>>(GMT +14:00) Line Islands, Kiribati</option>
				</select>
			</dd>

			<dt><label for="form_tags">Your Tags</label></dt>
			<dd>
				<textarea name="form_tags" class="textinput overtext" id="form_tags" title="Examples: entrepreneur, writer, yoga, environment, single mom" rows="2" cols="30"><?= $tags ?></textarea>
			</dd>

			<dt><label for="form_aboutyou">A little bit about you</label></dt>
			<dd>
				<textarea name="form_aboutyou" class="textinput" id="form_aboutyou" rows="5" cols="30"><?= $aboutYou; ?></textarea>
			</dd>
		</dl>
		<div class="clear"></div>

		<div class="divider"></div>

		<h3>Your Profile Photo</h3>
		<div class="fieldwrap photo">
			<img src="<?= ASSETURL ?>/userimages/150/150/1/<?= $user->image ?>" class="fl" style="margin-right: 20px;" />
			<ul class="fl">
				<li>
					<label for="form_imgfile">Replace with a photo from your computer</label>
					<input type="file" name="photoupload" id="photoupload" class="file text-content" size="50" />
				</li>
				<li>
					<label>OR, choose an avatar below:</label>
					<div class="imageradios">
						<label><img src="<?= ASSETURL ?>/userimages/60/60/1/avatar1.png" />
							<input type="radio" name="avatar" value="1"<?php if ($user->image == 'avatar1.png') { echo 'checked="checked"'; } ?> /></label>
						<label><img src="<?= ASSETURL ?>/userimages/60/60/1/avatar2.png" />
							<input type="radio" name="avatar" value="2"<?php if ($user->image == 'avatar2.png') { echo 'checked="checked"'; } ?> /></label>
						<label><img src="<?= ASSETURL ?>/userimages/60/60/1/avatar3.png" />
							<input type="radio" name="avatar" value="3"<?php if ($user->image == 'avatar3.png') { echo 'checked="checked"'; } ?> /></label>
					</div>
					<div class="clear"></div>
				</li>
			</ul>
			<div class="clear"></div>
		</div>

		<div class="divider"></div>

		<div class="notify">
			The information below WILL NOT be made public in your profile and is for our information only. We will NEVER share your private information with any third party. To read our Privacy Policy, <a href="<?= SITEURL; ?>/privacy/" target="_blank">click here</a>.
		</div>

		<dl>
			<dt><label for="form_household">Household Income</label></dt>
			<dd>
				<select name="form_household" id="form_household" size="1">
					<option value="-1">Please select below</option>
					<?php foreach($enumsHousehold as $key => $enum) { ?>
					<option<?= $household == $key ? ' selected="true"' : ''; ?> value="<?= $key ?>"><?= $enum ?></option>
					<?php } ?>
				</select>
			</dd>

			<dt><label for="form_age">Your Age</label></dt>
			<dd>
				<input name="form_age" class="textinput" type="text" id="form_age" size="30" maxlength="30" value="<?= $age ?>" />
			</dd>

			<dt><label for="form_education">Education</label></dt>
			<dd>
				<select name="form_education" id="form_education" size="1">
					<option value="-1">Please select below</option>
					<?php foreach($enumsEducation as $key => $enum) { ?>
					<option<?= $education == $key ? ' selected="true"' : ''; ?> value="<?= $key ?>"><?= $enum ?></option>
					<?php } ?>
				</select>
			</dd>
		</dl>
		<div class="clear"></div>

		<div class="divider" style="margin-bottom:5px"></div>
		<dl>
		<dt style="padding-top:0;"><label for="form_age">Delete Account</label></dt>
			<dd  style="padding-top:0;"  id="delete-account">
				<a href="/account/details?tab=delete"><span>Click here to delete your account</span></a> <br />
(You'll be able to confirm this before your account is fully deleted)
			</dd>
		</dl>
					
		<div class="divider"></div>

		<button type="submit"><span>Update Details</span></button>

		<input type="hidden" name="task" value="details_basic_save" />
		<input type="hidden" id="queueid" name="queueid" value="<?= $queueId ?>" />
	</div></form>
