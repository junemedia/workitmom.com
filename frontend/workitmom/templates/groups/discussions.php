
	<div id="main-content" class="groups">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="groups_icon" class="icon fl"></div>
					<h1>Group discussions</h1>
					<h2><?= $group['name'] ?></h2>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<?php if (!empty($topics)) { ?>
			<ul class="item_list">
				<?php
					$alt = true;
					foreach($topics as $topic) {
						$link = SITEURL.'/groups/discussion/'.$topic['id'];
				?>
				<li<?= $alt ? ' class="odd"' : '' ?>>
					<?php if ($topic['reply_count'] >= 10) { ?>
						<img src="<?= SITEASSETURL . '/images/site/flag_'.($topic['reply_count'] >= 25 ? 'red' : 'yellow').'.png' ?>" title="<?= $topic['reply_count'] >= 25 ? 'Very popular discussion' : 'Popular discussion' ?>">
					<?php } ?>
					<a href="<?= $link ?>"><?= $topic['title'] ?></a>
					<p class="text-content underline">
						<a href="<?= $link ?>#comments"><?php Template::pluralise($topic['reply_count'], 'reply', 'replies'); ?></a> &nbsp;|&nbsp;
						<a href="<?= $link ?>">Join in</a>
					</p>
				</li>
				<?php
						$alt = !$alt;
					}
				?>
			</ul>

			<div id="discussion-legend">
				<ul>
					<li class="popular">Popular posts (10-25 comments)</li>
					<li class="very-popular">Very popular posts (25+ comments)</li>
				</ul>
			</div>
			
			<?= $pagination->get('buttons'); ?>

			<?php } ?>


		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
