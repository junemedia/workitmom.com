
	<?php
		// We need to do Polls animation all over this page.
		Template::includeScript(SITEASSETURL . '/js/PollVoter.js', 'Poll voting');
	?>

	<div id="main-content" class="polls">
		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="polls_icon" class="icon fl"></div>
					<h1>Polls</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<?php $this->polls_featured(); ?>

			<?php $this->polls_latest(); ?>

			<div class="clear"></div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array(
				'slideshow_featured',
				'marketplace',
				'catch_your_breath'
			)); ?>
		</div>

		<div class="clear"></div>
	</div>
