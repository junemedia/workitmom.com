
	<div id="main-content" class="">

		<?= Messages::getMessages(); ?>

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="friends_icon" class="icon fl"></div>
					<h1>Add a friend</h1>
				</div>
				<div class="bot"></div>
			</div>

			<div id="article_title" class="rounded-630-grey main block">
				<div class="top"></div>
				<div class="content">
					<div class="img">
						<img src="<?= ASSETURL.'/userimages/60/60/1/'.$friend->image ?>" />
					</div>
					<div class="body">
						<h2><?= $friend->name ?></h2>
						<h3><?= $friend->location ? 'From '.$friend->location : '' ?></h3>
						<p class="text-content">Joined <?= $friend->joined ?></p>
					</div>
					<div class="clear"></div>
				</div>
				<div class="bot"></div>
			</div>
			<div class="clear"></div><br />

			<div class="rounded-630-blue">
				<div class="top"></div>
				<div class="content">
					<div class="standardform"><div class="formholder">
						<form action="<?= SITEURL ?>/profile"><div>
							<label for="form_message">Send a personal message</label>
							<textarea id="form_message" class="textinput required" name="message" cols="10" rows="5"><?= $message ?></textarea>

							<button type="submit"><span>Add as a friend</span></button>

							<input type="hidden" name="friendid" value="<?= $friend->userid ?>" />
							<input type="hidden" name="task" value="add_friend_save" />
						</div></form>
					</div></div>
				</div>
				<div class="bot"></div>
			</div>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
