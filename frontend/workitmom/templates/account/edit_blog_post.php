
	<div id="main-content" class="account">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="account_icon" class="icon fl"></div>
					<h1>My Blog</h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="rounded-630-blue">
				<div class="top"></div>
				<div class="content">
					<h2>Edit Blog Post</h2>

					<div class="standardform"><div class="formholder">
						<form id="form_blog_submit" name="form_blog_submit" action="<?= SITEURL ?>/account/blogs?tab=owned" method="post"><div>

							<dl>
								<dt>
									<label for="form_title">Title <span class="red-ast">*</span></label>
									<span class="fieldcount"><span id="titlecount">0</span>/100</span>
								</dt>
								<dd>
									<input type="text" class="textinput required" maxlength="100" name="title" id="form_title" value="<?= $title ?>" />
									<?php Template::startScript(); ?>

										var titleCounter = new LengthCounter('form_title', 'titlecount');

									<?php Template::endScript(); ?>
								</dd>

								<dt><label for="form_body">Post <span class="red-ast">*</span></label></dt>
								<dd>
									<textarea id="form_body" class="textinput required" name="body" cols="10" rows="20"><?= $body ?></textarea>
								</dd>

								<dt><label for="form_tags">Tags</label></dt>
								<dd>
									<input type="text" class="textinput" maxlength="600" name="tags" id="form_tags" value="<?= $tags ?>" />
									<small class="fieldhint">Enter in a number of keywords that will help people find your article.  Please ensure tags are separated by commas, e.g. employment in boston, interview techniques, etc.</small>
								</dd>

								<dt><label for="form_category">Category</label></dt>
								<dd>
									<select style="width: 300px;" id="form_category" name="category" class="required">
										<option value="none">Please select below</option>
										<?php foreach ($categories as $category) { ?>
										<option value="<?= $category['articleCategoryID'] ?>"<?php if ($category['articleCategoryID'] == $categoryId) { ?> selected="selected"<?php } ?>><?= $category['articleCategoryName'] ?></option>
										<?php } ?>
									</select>
								</dd>

								<dt><label>Privacy</label></dt>
								<dd>
									<label class="radio"><input name="privacy" type="radio" class="radio" value="public" checked="checked" />
										Anyone on Work it, Mom! can read this</label>
									<div class="clear"></div>

									<label class="radio"><input name="privacy" type="radio" class="radio" value="private" />
										Only my friends &amp; I can read this</label>
									<div class="clear"></div>
								</dd>
							</dl>
							<div class="clear"></div>
							<div class="divider"></div>

							<button name="submit" class="submit"><span>Edit blog post</span></button>

							<input type="hidden" name="task" value="edit_blog_post_save" />
							<input type="hidden" name="postid" value="<?= $post['articleID'] ?>" />
						</div></form>
					</div></div>
				</div>
				<div class="bot"></div>
			</div>
		</div>
		<div class="panel-right">
			<?php $this->sidebar(array('newsletter', 'account', array('ad_mini', 'account')), false); ?>
		</div>

		<div class="clear"></div>
	</div>