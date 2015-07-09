
	<div id="main-content" class="article">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="article_icon" class="icon fl"></div>
					<h1>Member Articles</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

		<div id="submit_content">

			<div class="rounded-630-blue">
				<div class="top"></div>

				<div class="content">

					<h2>Write an article</h2>
					<h3>Share your expertise, personal observations or stories with the Work It, Mom! community by writing an article!</h3>
					<div class="required"><strong>NOTE:</strong> All fields marked <span class="red-ast">*</span> are required.</div>

					<div class="clear" style="height:15px;"></div>

					<div id="contact-form" class="standardform"><div class="formholder">
						<form id="form_article_submit" name="form_article_submit" action="submit" method="post">

						<?php Template::startScript(); ?>
						new TinyMCE('form_article_submit', {theme: 'standard', plugins: ['spellchecker']});
						<?php Template::endScript(); ?>
						
						<div class="fieldwrap title">
							<label for="form_title">Title <span class="red-ast">*</span></label>
							<div class="count_characters"><span id="title_count">0</span> / 100 characters max.</div>
							<div class="clear"></div>
							<input type="text" class="textinput required" maxlength="100" name="form_title" id="form_title" />
							<small>e.g. Finding a Flexible Work Arrangement</small>
							
							<?php Template::startScript(); ?>
							new LengthCounter('form_title', 'title_count', {maxLength: 100});
							<?php Template::endScript(); ?>
						</div>

						<div class="fieldwrap subtitle">
							<label for="form_subtitle">Sub-Title <span class="red-ast">*</span></label>
							<div class="count_characters"><span id="subtitle_count">0</span> / 100 characters max.</div>
							<div class="clear"></div>
							<input type="text" class="textinput required" maxlength="100" name="form_subtitle" id="form_subtitle" />
							<small>e.g. Six tips for negotiating with your current employer</small>
							
							<?php Template::startScript(); ?>
							new LengthCounter('form_subtitle', 'subtitle_count', {maxLength: 100});
							<?php Template::endScript(); ?>
						</div>

						<? if (!isset($user->contentcreatorid)){ ?>
						<div class="fieldwrap author">
							<label for="form_author">Author Name <span class="red-ast">*</span></label>
							<div class="count_characters"><span id="author_count">0</span> / 30 characters max.</div>
							<div class="clear"></div>
							<input type="text" class="textinput required" maxlength="30" name="form_author" id="form_author" />
							<small>Your name as you'd like it to appear with the article</small>
							
							<?php Template::startScript(); ?>
							new LengthCounter('form_author', 'author_count', {maxLength: 30});
							<?php Template::endScript(); ?>
						</div>

						<div class="fieldwrap byline">
							<label for="form_byline">Author By-line <span class="red-ast">*</span></label>
							<div class="count_characters"><span id="authorby_count">0</span> / 200 characters max.</div>
							<div class="clear"></div>
							<input type="text" class="textinput required" maxlength="200" name="form_byline" id="form_byline" />
							<small>A short statement about yourself that you'd like to appear with your article</small>
							
							<?php Template::startScript(); ?>
							new LengthCounter('form_byline', 'authorby_count', {maxLength: 200});
							<?php Template::endScript(); ?>
						</div>
						<? } ?>

						<div class="fieldwrap article">
							<label for="form_article">Article <span class="red-ast">*</span></label>
							<div class="clear"></div>
							<small><p>If you are sharing advice in your article, keep in mind that more specific your suggestions, the more helpful your article will be. Please remember that professional moms are busy and juggling lots of tasks during their day - they might get more out of your article if you keep it relatively short.</p><p>If you are copying your article from a word processing program and pasting it here, please copy into Notepad first and then paste. Otherwise, formatting might be distorted.</p></small>
							<textarea id="form_article" class="textinput required" name="form_article" cols="10" rows="20"></textarea>
						</div>

						<div class="fieldwrap tag">
							<label for="form_tags">Tag your article</label>
							<div class="clear"></div>
							<input type="text" class="textinput" maxlength="600" name="form_tags" id="form_tags" />
							<small>Enter in a number of keywords that will help people find your article.  Please ensure tags are separated by commas, e.g. employment in boston, interview techniques, etc.</small>
						</div>

						<div class="fieldwrap categorize">
							<label for="form_category">Categorize your article</label>
							<div class="clear"></div>
							<select style="width: 300px;" id="form_category" name="form_category">
								<option value="none">Please select below</option>
								<option value="Balancing Act">Balancing Act</option>
								<option value="Career & Money">Career &amp; Money</option>
								<option value="Pregnancy & Parenting">Pregnancy &amp; Parenting</option>
								<option value="Your Business">Your Business</option>
								<option value="Just For You">Just For You</option>
							</select>
						</div>

						<div class="fieldwrap check">
							<label class="title">Notifications</label>
							<div class="clear"></div>
							<div class="text-content">
								<ul>
									<li>You will automatically receive alerts when someone adds a comment to your articles.  To control these alerts you can edit your alerts settings from your account at any time.</li>
								</ul>
							</div>
						</div>

						<div class="fieldwrap">
						<button name="submit" class="submit" type="submit"><span>Submit your article</span></button>
						</div>

						<h3>Terms &amp; Conditions</h3>
						<p class="text-content">Due to the high volume of articles we receive it might take a bit of time for yours to be published, so please be patient with us. Please keep in mind that we're a community for and by working moms and are not able to publish articles that we don't feel are of interest to our audience.</p>

						<p class="text-content">All of the articles that appear on Work It, Mom! can be ranked and discussed by our members. By submitting an article to be published on Work It, Mom! you're indicating that you're in agreement with our submission guidelines.</p>

						<p class="text-content">Please note that Work It, Mom! reserves the right to not publish any article that we don't feel is appropriate or relevant for our audience of working moms. We may edit your article for grammar, spelling and length - if we believe significant edits are required, our editor will contact you directly. If you're submitting several articles at once they may be published over time.</p>

						<p class="text-content">While members are free to include a link to their business in their byline, Work It, Mom! does not publish articles or essays that are business solicitations, advertisements, or marketing for specific products.</p>


						</form>
					</div></div>

				</div>

				<div class="bot"></div>
			</div>


		</div>
	</div>

		<div class="panel-right">
			<?php $this->sidebar(array(
				'slideshow_featured',
				'marketplace',
				'catch_your_breath'
			)); ?>
		</div>
		<? // END >> RIGHT COLUMN  ?>

		<div class="clear"></div>
	</div>
