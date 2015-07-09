
	<div id="main-content" class="question">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="questions_icon" class="icon fl"></div>
					<h1>Member Questions</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

		<div id="submit_content">


			<div class="rounded-630-blue">
				<div class="top"></div>

				<div class="content">

					<h2>Something on your mind?</h2>
					<h3>Ask questions and get answers from moms just like you.</h3>

					<div class="required"><strong>NOTE:</strong> All fields marked <span class="red-ast">*</span> are required.</div>

					<div class="clear" style="height:15px;"></div>

					<div id="contact-form" class="standardform"><div class="formholder">
						<form id="form_question_submit" name="form_question_submit" action="submit" method="post">

						<div class="fieldwrap question">
							<label for="form_question">Your Question <span class="red-ast">*</span></label>
							<div class="clear"></div>
							<textarea id="form_question" class="textinput required" name="form_question" cols="10" rows="10"><?= $Lq; ?></textarea>
						</div>

						<div class="fieldwrap tag">
							<label for="form_tags">Tag Your Question</label>
							<div class="clear"></div>
							<input type="text" class="textinput" maxlength="600" name="form_tags" id="form_tags" />
							<small>Enter in a number of keywords that will help people search for your question on Work It, Mom! Please ensure tags are separated by commas, e.g. full-time work, working mom issues, kids' activities, etc.</small>
						</div>

						<div class="fieldwrap categorize">
							<label for="form_category">Categorize Your Question</label>
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
									<li>You will automatically receive an alert whenever someone replies to your question.  To control these alerts you can edit your alerts settings from your account at any time.</li>
								</ul>
							</div>
						</div>

						<div class="fieldwrap">
						<button name="submit" class="submit" type="submit"><span>Ask your Question</span></button>
						</div>

						</form>
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
		<? // END >> RIGHT COLUMN  ?>

		<div class="clear"></div>
	</div>
