
	<div id="main-content" class="news">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="news_icon" class="icon fl"></div>
					<h1>News</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

		<div id="submit_content">


			<div class="rounded-630-blue">
				<div class="top"></div>

				<div class="content">

					<h2>Share a News Story</h2>
					<h3>Share a news story with the Work It, Mom! community!</h3>

					<div class="clear" style="height:15px;"></div>

					<div id="contact-form" class="standardform"><div class="formholder">
						<form id="form_news_submit" name="form_news_submit" action="submit" method="post">
						
						<?php Template::startScript(); ?>
						new TinyMCE('form_news_submit', {theme: 'standard', plugins: ['spellchecker']});
						<?php Template::endScript(); ?>

						<div class="fieldwrap title">
							<label for="form_title">Title of News Story</label>
							<div class="count_characters"><span id="title_count">0</span> / 100 characters max.</div>
							<div class="clear"></div>
							<input type="text" class="textinput required" maxlength="100" name="form_title" onkeyup="countchars('form_title');" id="form_title" />
							<small>This can be the original story title or you can paraphrase it.</small>
						</div>

						<div class="fieldwrap url">
							<label for="form_url">URL of News Story</label>
							<div class="count_characters"><span id="url_count">0</span> / 100 characters max.</div>
							<div class="clear"></div>
							<input type="text" class="textinput required" maxlength="100" name="form_url" onkeyup="countchars('form_url');" id="form_url" />
							<small>e.g. www.nytimes.com</small>
						</div>

						<div class="fieldwrap description">
							<label for="form_description">Short Description</label>
							<div class="clear"></div>
							<small><p>Please write a 1-3 sentence description of the news story you're submitting.</p></small>
							<textarea id="form_description" class="textinput required" name="form_description" cols="10" rows="10"></textarea>
						</div>

						<div class="fieldwrap tag">
							<label for="form_tags">Tag Your Story</label>
							<div class="clear"></div>
							<input type="text" class="textinput" maxlength="600" name="form_tags" id="form_tags" />
							<small>Enter in a number of keywords that will help people find your news story. Please ensure tags are separated by commas, e.g. full-time work, working mom issues, kids' activities, etc.</small>
						</div>

						<div class="fieldwrap categorize">
							<label for="form_category">Categorize Your Story</label>
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

						<div class="fieldwrap">
						<button name="submit" class="submit" type="submit"><span>Submit your Story</span></button>
						</div>

						<h3>Terms &amp; Conditions</h3>
						<p class="text-content">Our editorial team will review all news submissions prior to publication and it should be live on the site within 24 hours.</p>

						<p class="text-content">Please keep in mind that we're a community for and by working moms and are not able to publish stories not relevant to our audience.</p>

						</form>
					</div></div>

				</div>

				<div class="bot"></div>
			</div>


		</div>
	</div>

		<div class="panel-right">
			<?php $this->sidebar(array(
'slideshow_featured', 'marketplace', 'catch_your_breath'
			)); ?>
		</div>
		<? // END >> RIGHT COLUMN  ?>

		<div class="clear"></div>
	</div>
