
	<form method="post" action="/account/details"><div>

		<dl>
			<dt><label for="form_children">Number of Children</label></dt>
			<dd>
				<select name="form_children" id="form_children" size="1">
					<option value="none">Please select below</option>
					<option<?= $userInfo->numChildren == 0 ? ' selected="true"' : ''; ?> value="0">None</option>
					<? for($i = 1; $i < 5; $i++){ ?>
					<option<?= $userInfo->numChildren == $i ? ' selected="true"' : ''; ?> value="<?= $i; ?>"><?= $i; ?></option>
					<? } ?>
					<option<?= $userInfo->numChildren == 5 ? ' selected="true"' : ''; ?> value="5">5 or more</option>
				</select>
			</dd>

			<dt><label for="form_ages">Ages of Children</label></dt>
			<dd>
				<input name="form_ages" class="textinput" type="text" id="form_ages" size="30" maxlength="30" value="<?= $userInfo->childrenAge ?>" />
			</dd>

			<dt><label for="form_relationship">Relationship status</label></dt>
			<dd>
				<select id="form_relationship" name="form_relationship">
					<option <?= $userInfo->relationship == 'none' ? 'selected="selected"' : ''?> value="none">Please select below</option>
					<option <?= $userInfo->relationship == 'single' ? 'selected="selected"' : ''?> value="single">Single</option>
					<option <?= $userInfo->relationship == 'married' ? 'selected="selected"' : ''?> value="married">Married</option>
					<option <?= $userInfo->relationship == 'partner' ? 'selected="selected"' : ''?> value="partner">Partnered/In a civil union</option>
					<option <?= $userInfo->relationship == 'dating' ? 'selected="selected"' : ''?> value="dating">In a Relationship</option>
					<option <?= $userInfo->relationship == 'notsay' ? 'selected="selected"' : ''?> value="notsay">Would rather not say</option>
				</select>
			</dd>

			<dt><label for="form_parentadvice">One piece of parenting advice you always follow</label></dt>
			<dd>
				<textarea id="form_parentadvice" name="form_parentadvice" class="textinput" rows="2" cols="30"><?= $userInfo->parentadvice ?></textarea>
			</dd>

			<dt><label for="form_parentignore">One piece of parenting advice you always ignore</label></dt>
			<dd>
				<textarea id="form_parentignore" name="form_parentignore" class="textinput" rows="2" cols="30"><?= $userInfo->parentignore ?></textarea>
			</dd>

			<dt><label for="form_kidactivity">Favorite activity to do with your kids</label></dt>
			<dd>
				<textarea id="form_kidactivity" name="form_kidactivity" class="textinput" rows="2" cols="30"><?= $userInfo->kidactivity ?></textarea>
			</dd>
		</dl>
		<div class="clear"></div>
		<div class="divider"></div>

		<button type="submit"><span>Update Details</span></button>

		<input type="hidden" name="task" value="details_family_save" />
	</div></form>