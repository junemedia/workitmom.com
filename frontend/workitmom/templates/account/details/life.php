
	<form method="post" action="/account/details"><div>

		<dl>
			<dt><label for="form_statement">Brief Profile</label></dt>
			<dd>
				<textarea id="form_statement" name="form_statement" class="textinput" rows="2" cols="30"><?= $userInfo->statement ?></textarea>
				<small class="fieldhint">Share a bit about yourself with the rest of the community</small>
			</dd>

			<dt><label for="form_interests">What are your interests and hobbies outside of work?</label></dt>
			<dd>
				<textarea id="form_interests" name="form_interests" class="textinput" rows="2" cols="30"><?= $userInfo->interests ?></textarea>
			</dd>

			<dt><label for="form_url">Website/Blog</label></dt>
			<dd>
				<textarea id="form_url" name="form_url" class="textinput" rows="2" cols="30"><?= $userInfo->url ?></textarea>
				<small class="fieldhint">Separate multiple websites with a new line</small>
			</dd>

			<dt><label for="form_destress">Best ways for you to de-stress and relax</label></dt>
			<dd>
				<textarea id="form_destress" name="form_destress" class="textinput" rows="2" cols="30"><?= $userInfo->destress ?></textarea>
			</dd>

			<dt><label for="form_bestadvice">Best piece of advice you've ever gotten as a professional mom</label></dt>
			<dd>
				<textarea id="form_bestadvice" name="form_bestadvice" class="textinput" rows="2" cols="30"><?= $userInfo->bestadvice ?></textarea>
			</dd>

			<dt><label for="form_describeyourself">One adjective that best describes you</label></dt>
			<dd>
				<input id="form_describeyourself" name="form_describeyourself" class="textinput" type="text" size="30" maxlength="30" value="<?= $userInfo->describeyourself ?>" />
			</dd>

			<dt><label for="form_book">Your favorite book of all time</label></dt>
			<dd>
				<input id="form_book" name="form_book" class="textinput" type="text" size="30" maxlength="30" value="<?= $userInfo->book ?>" />
			</dd>

			<dt><label for="form_movie">Your favorite movie</label></dt>
			<dd>
				<input id="form_movie" name="form_movie" class="textinput" type="text" size="30" maxlength="30" value="<?= $userInfo->movie ?>" />
			</dd>
		</dl>
		<div class="clear"></div>
		<div class="divider"></div>

		<button type="submit"><span>Update Details</span></button>

		<input type="hidden" name="task" value="details_life_save" />
	</div></form>
