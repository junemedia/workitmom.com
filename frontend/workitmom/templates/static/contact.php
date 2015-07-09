
	<div id="main-content" class="static">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div class="icon fl"></div>
					<h1>Contact Work it, Mom!</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div id="submit_content">

				<div class="rounded-630-blue">
					<div class="top"></div>

					<div class="content" id="contact">

						<h2>We'd love to hear from you</h2>
						<p class="text-content">Please use the form below to get in touch with us. We will do our best to reply within 48 business hours. If you need help with using Work It, Mom! consider checking out our <a href="<?=SITEURL?>/help/">Help &amp; FAQ area</a> for helpful tips.</p>

						<div class="clear" style="height:15px;"></div>

						<div id="contact-form" class="standardform"><div class="formholder">
							<form id="form_contact" name="form_contact" action="<?= SITEURL ?>/contact" method="post">

								<div class="fieldwrap inline">
									<label for="form_subject">Subject</label>
									<select name="form_subject" id="form_subject" style="width:400px;">
										<option>Please select from the options below...</option>
										<option value="feedback">Ideas to improve Work It, Mom!</option>
										<option value="support">Website Support &amp; Help</option>
										<option value="marketplace">Marketplace</option>
										<option value="press">Press</option>
										<option value="bizdev">Business Development</option>
										<option value="jobs">Employment</option>
									</select>
									<div class="clear"></div>
								</div>

								<div class="fieldwrap inline">
									<label for="form_email">Your Email</label>
									<input type="text" class="textinput required validate-email" maxlength="100" name="form_email" id="form_email" />
									<div class="clear"></div>
								</div>

								<div class="fieldwrap inline">
									<label for="form_name">Your Name</label>
									<input type="text" class="textinput required" maxlength="30" name="form_name" id="form_name" />
									<div class="clear"></div>
								</div>

								<div class="fieldwrap inline">
									<label for="form_message">Message</label>
									<textarea class="textinput required" name="form_message" id="form_message" cols="10" rows="20"></textarea>
									<div class="clear"></div>
								</div>

								<div class="fieldwrap captcha">
									<div class="img">
										<img src="<?= SITEINSECUREURL ?>/captcha?format=asset&amp;uniq=<?= uniqid(); ?>" class="captcha-img" />
										<div class="captcha-reload"><small><a href="#">Get a new image</a></small></div>
									</div>
									<div class="body">
										<label>Please enter the code to the left:</label>
										<input name="form_captcha" class="textinput validate-captcha" type="text" id="form_captcha" size="30" maxlength="100" />
									<small><a href="<?= SITEURL ?>/info/whatsthis" class="info-popup">What's this?</a></small>
									</div>
									<div class="clear"></div>
								</div>

								<button name="submit" class="submit" type="submit"><span>Send your message</span></button>

								<input type="hidden" name="task" value="contact_send" />
							</form>
						</div></div>

					</div>

					<div class="bot"></div>
				</div>


			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
