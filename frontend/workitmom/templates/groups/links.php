
	<div id="main-content" class="groups">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="groups_icon" class="icon fl"></div>
					<h1><?= $group['name'] ?></h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="rounded-630-outline members" id="group-members">
				<div class="top"></div>
				<div class="content">
					<h2>Group Links</h2>

					<?php if (!empty($links)) { ?>
					<ul>
						<?php foreach($links as $link) { ?>
						<li>
							<a href="<?= $link['resourceLink'] ?>"><?= $link['resourceName'] ?></a>
							<p class="text-content"><?= $link['resourceDescription'] ?></p>
							<div class="sub">by <a href="<?= SITEURL.$link['user']->profileURL ?>"><?= $link['user']->name ?></a> on <?= Template::time($link['resourceTime']) ?></div>
						</li>
						<?php } ?>
					</ul>
					<?php } ?>

					</div>
				<div class="bot"></div>
			</div>
			
			<?= $pagination->get('buttons'); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?>
		</div>

		<div class="clear"></div>
	</div>
