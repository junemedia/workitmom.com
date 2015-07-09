
	<div id="main-content" class="blogs">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="blogs_icon" class="icon fl"></div>
					<h1>Blogs</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div id="submit_content">

				<div class="rounded-630-blue">
					<div class="top"></div>

					<div class="content">

						<h2>Write a Blog Post</h2>

						<div class="required"><strong>NOTE:</strong> All fields marked <span class="red-ast">*</span> are required.</div>

						<div class="clear" style="height:15px;"></div>

						<div id="contact-form" class="standardform"><div class="formholder">
							<form id="form_blog_submit" name="form_blog_submit" action="<?= SITEURL; ?>/contribute/blog/submit/" method="post"><div>
							
								<?php Template::startScript(); ?>
								new TinyMCE('form_blog_submit', {theme: 'standard', plugins: ['spellchecker']});
								<?php Template::endScript(); ?>

								<div class="fieldwrap title">
									<label for="form_title">Title <span class="red-ast">*</span></label>
									<div class="count_characters"><span id="title_count">0</span> / 100 characters max.</div>
									<div class="clear"></div>
									<input type="text" class="textinput required" maxlength="100" name="form_title" id="form_title" value="<?= $prefillTitle; ?>" />
									
									<?php Template::startScript(); ?>
									new LengthCounter('form_title', 'title_count', {maxLength: 100});
									<?php Template::endScript(); ?>
									
								</div>

								<div class="fieldwrap blogpost">
									<label for="form_blogpost">Your Blog Post <span class="red-ast">*</span></label>
									<div class="clear"></div>
									<textarea id="form_blogpost" class="textinput required" name="form_blogpost" cols="10" rows="20"><?= $prefillBody; ?></textarea>
								</div>

								<div class="fieldwrap tag">
									<label for="form_tags">Tag your blog post</label>
									<div class="clear"></div>
									<input type="text" class="textinput" maxlength="600" name="form_tags" id="form_tags" />
									<small>Enter in a number of keywords that will help people find your article.  Please ensure tags are separated by commas, e.g. employment in boston, interview techniques, etc.</small>
								</div>

								<div class="fieldwrap categorize">
									<label for="form_category">Categorize your blog post</label>
									<div class="clear"></div>
									<select style="width: 300px;" id="form_category" name="form_category">
										<option value="none">Please select below</option>
										<option value="Balancing Act">Balancing Act</option>
										<option value="Career & Money">Career &amp; Money</option>
										<option value="Parenting & Pregnancy">Pregnancy &amp; Parenting</option>
										<option value="Your Business">Your Business</option>
										<option value="Just For You">Just For You</option>
									</select>
								</div>

								<div class="fieldwrap radio">
									<label class="title">Privacy</label>
									<div class="clear"></div>

									<label class="radio"><input name="form_privacy" type="radio" class="radio" value="public" checked="checked" />
									Anyone on Work it, Mom! can read this</label>
									<div class="clear"></div>

									<label class="radio"><input name="form_privacy" type="radio" class="radio" value="private" />
									Only my friends &amp; I can read this</label>
									<div class="clear"></div>
								</div>

								<div class="fieldwrap check">
									<label class="title">Notifications</label>
									<div class="clear"></div>
									<div class="text-content">
										<ul>
											<li>You will automatically receive alerts when someone adds a comment to your articles.  To control these alerts you can edit your alerts settings from your account at any time.</li>
											<li>We'll also let your friends know about your blog post for you!</li>
										</ul>
									</div>
									<div class="clear"></div>
								</div>

								<button name="submit" class="submit" type="submit"><span>Submit your blog post</span></button>								
								<div class="clear"></div>

							</div></form>
						</div></div>
					</div>
					<div class="bot"></div>
				</div>
			</div>
		</div>

		<div class="panel-right">
			<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath'

			)); ?>
		</div>

		<div class="clear"></div>
	</div>
