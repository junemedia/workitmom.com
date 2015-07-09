
	<?php if (!empty($links)) { ?>
	<div class="rounded-630-orange" id="group_links">
		<div class="top"></div>

		<div class="content">
			<h2>Group Links</h2>
			<?php if ($numLinks > 3) { ?>
			<a href="<?= SITEURL.'/groups/links/'.$groupId ?>" class="button_dark fr"><span>see all <?= $numLinks ?> links</span></a>
			<?php } ?>
			<ul>
				<?php foreach($links as $link) { ?>
				<li>
					<a href="<?= $link['resourceLink'] ?>"><?= $link['resourceName'] ?></a>
					<p class="text-content"><?= $link['resourceDescription'] ?></p>
					<div class="sub">by <a href="<?= SITEURL.$link['user']->profileURL ?>"><?= $link['user']->name ?></a> on <?= Template::time($link['resourceTime']) ?></div>
				</li>
				<?php } ?>
			</ul>
		</div>

		<div class="bot"></div>
	</div>
	<?php } ?>