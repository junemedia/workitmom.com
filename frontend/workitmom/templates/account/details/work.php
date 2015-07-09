
	<form method="post" action="/account/details"><div>

		<h3>How do you work?</h3>
		<?php Template::startScript(); ?>

			// Extra field handlers
			var currentFields;
			$$('input[name=form_howwork]').each(function(input) {

				// Store current
				if (input.get('checked')) {
					currentFields = input.get('value');
				}

				// Add change event
				input.addEvent('click', function(event){
					var fields = input.get('value');

					// Hide all but current fields
					$$('fieldset.howwork-extra').each(function(fieldset) {
						if (!fieldset.hasClass(fields+'-fields')) {
							fieldset.slide('out');
						}
					});

					// Show current fields
					var fieldset = $$('fieldset.'+fields+'-fields');
					if (fieldset) {
						fieldset.slide('in');
					}
				});
			});

			// Hide all but current fields
			$$('fieldset.howwork-extra').each(function(fieldset) {
				if (!fieldset.hasClass(currentFields+'-fields')) {
					fieldset.slide('hide');
				}
			});

		<?php Template::endScript(); ?>

		<label class="radio"><input name="form_howwork" class="radio" type="radio" value="employed"<?= $userInfo->employmentType == 'employed' ? ' checked="checked"' : ''; ?> />
			I currently work full-time</label>
		<div class="clear"></div>
		<label class="radio"><input name="form_howwork" class="radio" type="radio" value="parttime"<?= $userInfo->employmentType == 'parttime' ? ' checked="checked"' : ''; ?> />
			I currently work part-time</label>
		<div class="clear"></div>

		<!-- Employer options -->
		<fieldset class="sub howwork-extra employed-fields parttime-fields">
			<dl>
				<dt><label for="form_employer">Name of Employer</label></dt>
				<dd>
					<input name="form_employer" class="textinput" type="text" id="form_employer" size="30" maxlength="100" value="<? Template::out($userInfo->employerName); ?>" />
				</dd>
				<dt><label for="form_jobtitle">Job Title</label></dt>
				<dd>
					<input name="form_jobtitle" class="textinput" type="text" id="form_jobtitle" size="30" maxlength="100" value="<? Template::out($userInfo->jobTitle); ?>" />
				</dd>
				<dt><label>Industry</label></dt>
				<dd>
					<select name="form_industry" id="form_industry" size="1">
						<option value="none">Please select below</option>
						<?php foreach($industries as $industryID => $industryName) { ?>
						<option<?= $userInfo->industry == $industryID ? ' selected="true"' : ''; ?> value="<?= $industryID; ?>"><?= $industryName; ?></option>
						<?php } ?>
					</select>
				</dd>
			</dl>
			<div class="clear"></div>
		</fieldset>

		<label class="radio"><input name="form_howwork" class="radio" type="radio" value="self"<?= $userInfo->employmentType == 'self' ? ' checked="checked"' : ''; ?> />
			I run my own business</label>
		<div class="clear"></div>

		<!-- Entrepreneur fields -->
		<fieldset class="sub howwork-extra entrepreneur-fields">
			<dl>
				<dt><label for="form_business_name">Name of Business</label></dt>
				<dd>
					<input name="form_business_name" class="textinput" type="text" id="form_business_name" size="30" maxlength="100" value="<? Template::out($userInfo->employerName); ?>" />
				</dd>
				<dt><label for="form_industry_entrepreneur">Industry</label></dt>
				<dd>
					<select name="form_industry_entrepreneur" id="form_industry_entrepreneur" size="1">
						<option value="none">Please select below</option>
						<?php foreach($industries as $industryID => $industryName) { ?>
						<option<?= $userInfo->industry == $industryID ? ' selected="true"' : ''; ?> value="<?= $industryID; ?>"><?= $industryName; ?></option>
						<?php } ?>
					</select>
				</dd>
			</dl>
			<div class="clear"></div>
		</fieldset>

		<label class="radio"><input name="form_howwork" class="radio" type="radio" value="consultant"<?= $userInfo->employmentType == 'consultant' ? ' checked="checked"' : ''; ?> />
			I work as a consultant / freelancer</label>
		<div class="clear"></div>

		<!-- Freelance fields -->
		<fieldset class="sub howwork-extra freelancer-fields">
			<dl>
				<dt><label for="form_industry_freelance">Industry</label></dt>
				<dd>
					<select name="form_industry_freelance" id="form_industry_freelance" size="1">
						<option value="none">Please select below</option>
						<?php foreach($industries as $industryID => $industryName) { ?>
						<option<?= $userInfo->industry == $industryID ? ' selected="true"' : ''; ?> value="<?= $industryID; ?>"><?= $industryName; ?></option>
						<?php } ?>
					</select>
				</dd>
			</dl>
			<div class="clear"></div>
		</fieldset>

		<label class="radio"><input name="form_howwork" class="radio" type="radio" value="unemployed"<?= $userInfo->employmentType == 'unemployed' ? ' checked="checked"' : ''; ?> />
			I'm not currently working</label>
		<div class="clear"></div>

		<div class="divider"></div>

		<h3>What is your job like?</h3>
		<dl>
			<dt><label for="form_jobhours">How many hours a week do you work?</label></dt>
			<dd>
				<select name="form_jobhours" id="form_jobhours">
					<option <?= $userInfo->jobhours == 'none' ? 'selected="selected"' : ''?> value="none">Please select below...</option>
					<option <?= $userInfo->jobhours == '0-20' ? 'selected="selected"' : ''?> value="0-20">0 to 20 hours a week</option>
					<option <?= $userInfo->jobhours == '20-40' ? 'selected="selected"' : ''?> value="20-40">20 to 40 hours a week</option>
					<option <?= $userInfo->jobhours == '40+' ? 'selected="selected"' : ''?> value="40+">40 or more hours a week</option>
				</select>
			</dd>

			<dt><label for="form_joblike">How much do you like your job?</label></dt>
			<dd>
				<select name="form_joblike" id="form_joblike">
					<option <?= $userInfo->joblike == 'none' ? 'selected="selected"' : ''?> value="none">Please select below...</option>
					<option <?= $userInfo->joblike == 'love' ? 'selected="selected"' : ''?> value="love">I love it</option>
					<option <?= $userInfo->joblike == 'like' ? 'selected="selected"' : ''?> value="like">I like it</option>
					<option <?= $userInfo->joblike == 'paysbills' ? 'selected="selected"' : ''?> value="paysbills">It pays the bills</option>
					<option <?= $userInfo->joblike == 'notverymuch' ? 'selected="selected"' : ''?> value="notverymuch">Not very much</option>
				</select>
			</dd>

			<dt><label for="form_jobstress">How stressful is your job?</label></dt>
			<dd>
				<select name="form_jobstress" id="form_jobstress">
					<option <?= $userInfo->jobstress == 'none' ? 'selected="selected"' : '' ?> value="none">Please select below...</option>
					<option <?= $userInfo->jobstress == 'extremely' ? 'selected="selected"' : '' ?> value="extremely">Extremely stressful</option>
					<option <?= $userInfo->jobstress == 'pretty' ? 'selected="selected"' : '' ?> value="pretty">Pretty stressful</option>
					<option <?= $userInfo->jobstress == 'not' ? 'selected="selected"' : '' ?> value="not">Not very stressful</option>
				</select>
			</dd>

			<dt><label for="form_bestjobthing">What is the best thing about your job?</label></dt>
			<dd>
				<textarea name="form_bestjobthing" id="form_bestjobthing" class="textinput"><?= $userInfo->bestjobthing ?></textarea>
			</dd>

			<dt><label for="form_worstjobthing">What is the worst thing about your job?</label></dt>
			<dd>
				<textarea name="form_worstjobthing" id="form_worstjobthing" class="textinput"><?= $userInfo->worstjobthing ?></textarea>
			</dd>

			<dt><label for="form_dreamjob">What is your dream job?</label></dt>
			<dd>
				<textarea name="form_dreamjob" id="form_dreamjob" class="textinput"><?= $userInfo->dreamjob ?></textarea>
			</dd>
		</dl>
		<div class="clear"></div>
		<div class="divider"></div>

		<button type="submit"><span>Update Details</span></button>

		<input type="hidden" name="task" value="details_work_save" />
	</div></form>
