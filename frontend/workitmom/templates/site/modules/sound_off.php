
	<div id="sound_off" class="block">
		<div class="header">
			<div class="title">
				<h2><a href="<?= SITEURL; ?>/groups/">Group Discussions</a></h2>
			</div>

			<a href="<?= SITEURL ?>/groups/" class="button_dark"><span>See All</span></a>

			<div class="clear"></div>
		</div>

		<div class="content">
			<ul>
				<?php if (Utility::iterable($featuredTopics)) { foreach($featuredTopics as $topic) { ?>
				<li>
					<a href="<?= SITEURL; ?>/groups/discussion/<?= $topic['id'] ?>/"><?= $topic['title'] ?></a>
					<div class="text-content underline">
						<a href="<?= SITEURL; ?>/groups/discussion/<?= $topic['id'] ?>/"><?php Template::pluralise($topic['reply_count'], 'reply', 'replies'); ?></a>
					</div>
				</li>
				<?php } } ?>
			</ul>
			<p class="text-content underline"><a href="<?= SITEURL; ?>/groups/">Join in on group discussions!</a></p>
		</div>
	</div>