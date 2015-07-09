
	<div id="main-content" class="article">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="lifesavers_icon" class="icon fl"></div>
					<h1>Lifesavers</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

		<div id="submit_content">

			<div class="rounded-630-blue">
				<div class="top"></div>

				<div class="content">

					<h2>Share a lifesaver</h2>
					<p class="text-content">What helps you juggle work and family? What's your favorite quick stress reducer? Have you found a way to squeeze in some 'me' time into your busy schedule? Share your tips!</p>

					<div class="clear" style="height:15px;"></div>

					<div id="contact-form" class="standardform"><div class="formholder">
						<form id="form_lifesaver_submit" name="form_lifesaver_submit" action="submit" method="post">

						<div class="fieldwrap article">
							<label for="form_byline">Enter your lifesaver below: <span class="red-ast">*</span></label>
							<div class="clear"></div>
							<textarea id="form_lifesaver" class="textinput required" name="form_lifesaver" cols="10" rows="20"></textarea>
							<div class="required"><strong>NOTE:</strong> All fields marked <span class="red-ast">*</span> are required.</div>

						</div>

						<button name="submit" class="submit" type="submit"><span>Submit your lifesaver</span></button>

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
