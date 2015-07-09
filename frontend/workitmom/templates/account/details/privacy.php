
	<form method="post" action="/account/details" id="account-privacy-form"><div>

		<p class="text-content">You can control access to each portion of your profile by modifying the options below.</p>

		<dl>
			<dt><label>Work section</label></dt>
			<dd>
				<label class="radio"><input type="radio" name="form_profilework" value="0"<?php if (!$userPrivacy->userProfileWork) { echo ' checked="checked"'; } ?> />Private</label>
				<label class="radio"><input type="radio" name="form_profilework" value="1"<?php if ($userPrivacy->userProfileWork) { echo ' checked="checked"'; } ?> />Public</label>
			</dd>

			<dt><label>Family section</label></dt>
			<dd>
				<label class="radio"><input type="radio" name="form_profilefamily" value="0"<?php if (!$userPrivacy->userProfileFamily) { echo ' checked="checked"'; } ?> />Private</label>
				<label class="radio"><input type="radio" name="form_profilefamily" value="1"<?php if ($userPrivacy->userProfileFamily) { echo ' checked="checked"'; } ?> />Public</label>
			</dd>

			<dt><label>Life section</label></dt>
			<dd>
				<label class="radio"><input type="radio" name="form_profilelife" value="0"<?php if (!$userPrivacy->userProfileLife) { echo ' checked="checked"'; } ?> />Private</label>
				<label class="radio"><input type="radio" name="form_profilelife" value="1"<?php if ($userPrivacy->userProfileLife) { echo ' checked="checked"'; } ?> />Public</label>
			</dd>

			<dt><label>Groups module</label></dt>
			<dd>
				<label class="radio"><input type="radio" name="form_profilegroups" value="0"<?php if (!$userPrivacy->userProfileGroups) { echo ' checked="checked"'; } ?> />Private</label>
				<label class="radio"><input type="radio" name="form_profilegroups" value="1"<?php if ($userPrivacy->userProfileGroups) { echo ' checked="checked"'; } ?> />Public</label>
			</dd>

			<dt><label>Member network module</label></dt>
			<dd>
				<label class="radio"><input type="radio" name="form_profilemembernetwork" value="0"<?php if (!$userPrivacy->userProfileMemberNetwork) { echo ' checked="checked"'; } ?> />Private</label>
				<label class="radio"><input type="radio" name="form_profilemembernetwork" value="1"<?php if ($userPrivacy->userProfileMemberNetwork) { echo ' checked="checked"'; } ?> />Public</label>
			</dd>

			<dt><label>Articles module</label></dt>
			<dd>
				<label class="radio"><input type="radio" name="form_profilearticles" value="0"<?php if (!$userPrivacy->userProfileArticles) { echo ' checked="checked"'; } ?> />Private</label>
				<label class="radio"><input type="radio" name="form_profilearticles" value="1"<?php if ($userPrivacy->userProfileArticles) { echo ' checked="checked"'; } ?> />Public</label>
			</dd>

			<dt><label>Questions module</label></dt>
			<dd>
				<label class="radio"><input type="radio" name="form_profilequestions" value="0"<?php if (!$userPrivacy->userProfileQuestions) { echo ' checked="checked"'; } ?> />Private</label>
				<label class="radio"><input type="radio" name="form_profilequestions" value="1"<?php if ($userPrivacy->userProfileQuestions) { echo ' checked="checked"'; } ?> />Public</label>
			</dd>

			<dt><label>Comments module</label></dt>
			<dd>
				<label class="radio"><input type="radio" name="form_profilecomments" value="0"<?php if (!$userPrivacy->userProfileComments) { echo ' checked="checked"'; } ?> />Private</label>
				<label class="radio"><input type="radio" name="form_profilecomments" value="1"<?php if ($userPrivacy->userProfileComments) { echo ' checked="checked"'; } ?> />Public</label>
			</dd>

			<dt><label>Photos section</label></dt>
			<dd>
				<label class="radio"><input type="radio" name="form_profilephotos" value="0"<?php if (!$userPrivacy->userProfilePhotos) { echo ' checked="checked"'; } ?> />Private</label>
				<label class="radio"><input type="radio" name="form_profilephotos" value="1"<?php if ($userPrivacy->userProfilePhotos) { echo ' checked="checked"'; } ?> />Public</label>
			</dd>
		</dl>
		<div class="clear"></div>

		<div class="divider"></div>

		<button type="submit"><span>Update Details</span></button>

		<input type="hidden" name="task" value="details_privacy_save" />
	</div></form>
