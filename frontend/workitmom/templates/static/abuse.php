
	<div id="main-content" class="static">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div class="icon fl"></div>
					<h1>Report <?= ucwords($oType) ?></h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div id="submit_content">

				<div class="rounded-630-blue">
					<div class="top"></div>

					<div class="content" id="contact">

						<h2>Let Us Know</h2>
						<p class="text-content">Please use the form below to report any inappropriate behavior or comments on Workitmom.com. Our community moderators will review your report within 24 business hours and take appropriate action. We appreciate your being an active member of our community.</p>

						<div class="clear" style="height:15px;"></div>

						<div id="contact-form" class="standardform"><div class="formholder">
							<form id="form_abuse" name="form_abuse" action="submit" method="post"><div>

								<div class="fieldwrap inline">
									<label for="form_email">Your Email</label>
									<input type="text" class="textinput required validate-email" maxlength="100" name="form_email" id="form_email" value="<?= $email ?>" />
									<div class="clear"></div>
								</div>

								<div class="fieldwrap inline">
									<label for="form_name">Your Name</label>
									<input type="text" class="textinput required" maxlength="30" name="form_name" id="form_name" value="<?= $name ?>" />
									<div class="clear"></div>
								</div>

								<div class="fieldwrap inline">
									<label for="form_message">Message</label>
									<textarea id="form_message" class="textinput required" name="form_message" cols="10" rows="20">
<?php
	if($message) {
		echo $message;
	} elseif ($offendingItem) {
?>
Dear Work It, Mom! Team,

I am writing to report the <?= $oType ?> "<?= $oTitle ?>" by <?= $oAuthorName ?> as I have reason to believe it contravenes the Work It, Mom! Terms &amp; Conditions for the following reason(s):

<?php
	}
?>
</textarea>
									<div class="clear"></div>
								</div>

								<button name="submit" class="submit" type="submit"><span>Send your message</span></button>

								<input type="hidden" name="task" value="abuse_submit" />

							</div></form>
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
