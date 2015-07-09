
	<div id="main-content" class="profile">

		<?= Messages::getMessages(); ?>

		<div class="panel-left">

			<div class="rounded-630-grey main block">
				<div class="top"></div>
				<div class="content">
					<div class="img">
						<a href="<?php Template::image($person, 600, 500); ?>" rel="milkbox:userimage" title="<?= $person->name; ?>">
							<img src="<?php Template::image($person, 140); ?>" />
						</a>
						<div class="buttons">
							<?php if($showAddFriend) { $this->button('addfriend'); } ?>
							<?php if($showLeaveComment) { $this->button('comment'); } ?>
							<?php if($showSendPrivateMessage) { $this->button('message'); } ?>
							<div class="clear"></div>
						</div>
					</div>
					<div class="body">

						<div class="header">
							<h2><?= $person->name; ?></h2>
							<h3><?= $person->location ? 'From ' . $person->location : ''; ?></h3>
							<p class="text-content">Joined <?= $person->joined; ?></p>
						</div>

						<div id="profile_module">
							<?php $this->info(); ?>
						</div>

					</div>
					<div class="clear"></div>
				</div>
				<div class="bot"></div>
			</div>

			<div class="col-l">
				<?php $this->snapshot(); ?>
			</div>

			<div class="col-r">
				<?php $this->to_do_list(); ?>
			</div>

			<div class="clear"></div>

			<?php $this->activity(); ?>


			<?php $this->friends_block(); ?>

			<?php $this->photos_block(); ?>



			<?php $this->comments_add(); ?>
			<?php $this->comments_view(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
