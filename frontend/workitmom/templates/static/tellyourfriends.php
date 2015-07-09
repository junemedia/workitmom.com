
	<div id="main-content" class="">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div class="icon fl"></div>
					<?php switch ($action) { case 'share': ?>
					<h1>Tell Your Friends about this <?= $item->getType('single') ?></h1>
					<?php break; case 'group': ?>
					<h1>Tell Your Friends about your group</h1>
					<?php break; default: ?>
					<h1>Tell Your Friends about Work it, Mom!</h1>
					<?php break; } ?>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="rounded-630-blue">
				<div class="top"></div>
				<div class="content" id="tell_friends">
					<?php switch ($action) { case 'share': ?>
					<h2><?= $item->title ?></h2>
					<p class="text-content">
						If you know some friends who you think would find this <?= $item->getType('single') ?>
						interesting, you can send them a personalised link to it below!
					</p>
					<?php break; case 'group': ?>
					<h2>Send invites to your group</h2>
					<p class="text-content">
						If you know some friends who you think would find this group
						interesting, you can send them an invite to join in and get involved in discussions!
					</p>
					<?php break; default: ?>
					<h2>Help spread the word</h2>
					<p class="text-content">
						If you would like to help spread the word about Work It, Mom! or you have some friends
						who you think would find this site useful, you can send a personalised email to them below!
					</p>
					<?php break; } ?>

					<div class="clear"></div>

					<div id="contact-form" class="standardform">
						<div class="formholder">
							<form id="form_tell_friends" name="form_tell_friends" action="submit" method="post" class="nofancy"><div>

								<?php if (!$sharer_name || !$sharer_email) { ?>
								<dl>
									<dt><label for="form_name">Your Name <span class="red-ast">*</span></label></dt>
									<dd>
										<input type="text" class="textinput required" maxlength="30" name="form_sharer_name" id="form_sharer_name" />
									</dd>

									<dt><label for="form_email">Your Email <span class="red-ast">*</span></label></dt>
									<dd>
										<input type="text" class="textinput required" maxlength="30" name="form_sharer_email" id="form_sharer_email" />
									</dd>
								</dl>
								<div class="clear"></div>

								<?php } else { ?>
								<input type="hidden" name="form_sharer_name" value="<?= $sharer_name ?>" />
								<input type="hidden" name="form_sharer_email" value="<?= $sharer_email ?>" />
								<?php } ?>

								<dl>
									<?php if (!empty($friends) && $action) { ?>
									<dt><label>Work It, Mom! friends</label></dt>
									<dd>
										<div class="friends-checklist">
											<ul>
												<?php foreach ($friends as $friend) { ?>
												<li><label><input type="checkbox" name="recipients"<?php if (in_array($friend->userid, $checkedFriends)) { ?> checked="checked"<?php } ?> value="<?= $friend->userid ?>" />
													<?= $friend->name ?></label></li>
												<?php } ?>
											</ul>
										</div>
									</dd>
									<?php } ?>

									<dt><label>Enter your friends' <br />email addresses</label></dt>
									<dd>
										<table id="contactlist" class="text-content">
											<thead>
												<tr>
													<th>Name</th>
													<th>Email address</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($contacts as $email => $name) { ?>
												<tr>
									    			<td><input type="text" name="contact_name[]" value="<?= $name ?>" class="textinput name" /></td>
									    			<td><input type="text" name="contact_email[]" value="<?= $email ?>" class="textinput email" /></td>
									    		</tr>
												<?php } ?>
												<?php for ($i = 0; $i < 5; $i++) { ?>
												<tr>
													<td><input type="text" name="contact_name[]" value="" class="textinput name" /></td>
													<td><input type="text" name="contact_email[]" value="" class="textinput email" /></td>
									    		</tr>
									    		<?php } ?>
									    	</tbody>
										</table>
										<input type="hidden" id="hiddenContacts" />
										<a href="#" id="plaxoaddbutton" style="display: none;"><img alt="Add from my address book" src="http://www.plaxo.com/images/abc/buttons/add_button.gif"/></a>
										<?php
											Template::includeScript('http://www.plaxo.com/css/m/js/util.js');
				    						Template::includeScript('http://www.plaxo.com/css/m/js/basic.js');
				    						Template::includeScript('http://www.plaxo.com/css/m/js/abc_launcher.js');
				    						Template::includeScript(SITEASSETURL.'/js/plaxo.js');
											Template::startScript();
										?>
											var plaxoButton = $('plaxoaddbutton');
											plaxoButton.addEvent('click', function(event) {
												showPlaxoABChooser('hiddenContacts', '<?= SITEURL ?>/index/plaxo');
												event.stop();
											});
											plaxoButton.setStyle('display', 'block');
										<?php
											Template::endScript();
										?>
									</dd>
								</dl>
								<div class="clear"></div>

								<label for="form_message">Message <span class="red-ast">*</span></label>
								<textarea id="form_article" class="textinput required" name="form_message" cols="12" rows="12">
<?php
switch ($action) {
	case 'share':
?>
Hi [Friend],

I've found this <?= $item->getType('single') ?> ("<?= $item->title ?>") on Work It, Mom! that I thought you might find interesting - check it out!

http://www.workitmom.com<?= Uri::build($item) ?>/

<?= $sharer_name ?>

P.S. By the way, if you haven't heard of Work It, Mom! before, it's an online community for working moms like us and a good place to connect, share advice, read articles and great blogs. Here's the link: www.workitmom.com.
<?php
		break;
	case 'group':
?>
Hi [Friend],

I thought you might like to check out the "<?= $group['name'] ?>" group on Work It, Mom!

Hope to see you there!

<?= $sharer_name ?>
<?php
		break;
	default:
?>
Hi [Friend],

I thought you might like to check out a site called Work It, Mom! It's an online community for working moms like us and a good place to connect, share advice, read articles and great blogs.

Here's the link: www.workitmom.com.

Hope to see you there!

<?= $sharer_name ?>
<?php
		break;
}
?>
								</textarea>

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
								<div class="divider"></div>

								<button name="submit" class="submit" type="submit"><span><?php
									switch ($action) { case 'share': ?>
									Share with friends
									<?php break; case 'group': ?>
									Invite your friends
									<?php break; default: ?>
									Invite your friends
									<?php break; }
								?></span></button>

								<input type="hidden" name="task" value="tellafriend_submit" />
								<input type="hidden" name="action" value="<?= $action ?>" />

								<?php if ($action == 'share') { /* If sharing, pass the item type too */?>
								<input name="item_type" type="hidden" value="<?= $item->getType('single'); ?>"/>
								<input name="redirect" type="hidden" value="<?= Uri::build($item); ?>"/>
								<?php } ?>

							</div></form>
						</div>
					</div>
				</div>
				<div class="bot"></div>
			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
