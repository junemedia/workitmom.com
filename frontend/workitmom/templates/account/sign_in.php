
	<div id="main-content" class="login">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="account_icon" class="icon fl"></div>
					<h1>My Account</h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="col-l">

				<div class="rounded-300-blue">
					<div class="top"></div>

					<div class="content">
						<h2>Sign In</h2>

						<div class="standardform"><div class="formholder">
							<form name="form_sign_in" id="form_sign_in" action="<?= SITEURL; ?>/account/login/" method="post">

								<label>Username or Email Address</label>
								<input name="form_identifier" class="textinput required" type="text" id="form_identifier" size="30" />
								
								<?php Template::startScript(); ?>
								$('form_identifier').focus();
								<?php Template::endScript(); ?>

								<label>Password</label>
								<input name="form_password" class="textinput required" type="password" id="form_password" size="30"  />

								<button type="submit" name="submit"><span>Sign in to your account</span></button>

							</form>
							<div class="clear"></div>

							<a id="forgot-password-link" href="forgot-password-form">Send my password to me</a>

							<? Template::startScript(); ?>
								new PanelSlider('forgot-password-link', 'forgot-password-form', {
									hideLink: false
								});
							<? Template::endScript(); ?>

							<form id="forgot-password-form" action="<?= SITEURL; ?>/account/password_reminder/" method="post">

								<label>Enter your email address below and we'll send you a new password straight away.</label>
								<input name="form_identifier" class="textinput required" type="text" id="form_identifier" size="30" />

								<button type="submit" name="submit"><span>Send Password</span></button>

							</form>

						</div></div>
					</div>

					<div class="bot"></div>
				</div>

			</div>

			<div class="col-r">

				<div class="rounded-300-grey">
					<div class="top"></div>

					<div class="content">
						<h2>Why join Work it, Mom!?</h2>
						<ul id="why_join">
							<li>Meet other working moms</li>
							<li>Exchange tips and advice</li>
							<li>Publish articles &amp; write notes</li>
							<li>Join groups based on interests</li>
							<li>Find resources to make life easier</li>
						</ul>
						<a href="<?= SITEURL ?>/register/" class="button_dark fl"><span>sign me up now!</span></a>
						<div class="clear"></div>
					</div>

					<div class="bot"></div>
				</div>

			</div>

			<div class="clear"></div>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array(
				'newsletter',
				'why_sign_up',
				array('ad_mini', $this->_doc->getAdPage())
			), false); ?>
		</div>

		<div class="clear"></div>
	</div>
