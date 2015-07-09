
	<div id="main-content" class="account">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="photos_icon" class="icon fl"></div>
					<h1><?= $person->name ?></h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="rounded-630-outline members" id="group-members">
				<div class="top"></div>
				<div class="content grid_list member_list">
					<h2>Photos (<?= $total; ?>)</h2>
					<?php if (Utility::iterable($photos)) { ?>
					<ul>
						<?php foreach($photos as $photo) { ?>
						<li>
							<a class="img" href="<?= $photo['link']; ?>"><img src="<?php Template::image($photo, 75); ?>" /></a>
							<a href="<?= $photo['link']; ?>"><?= $photo['title']; ?></a>
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
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
