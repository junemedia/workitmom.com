
	<div id="main-content" class="account">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="articles_icon" class="icon fl"></div>
					<h1><?= $person->name ?></h1>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="rounded-630-outline members" id="group-members">
				<div class="top"></div>
				<div class="content grid_list member_list">
					<h2>Friends (<?= $total ?>)</h2>

					<?php if (!empty($friends)) { ?>
					<ul>
					<?php
						foreach($friends as $friend) {
							$link = SITEURL.$friend->profileURL;
					?>
						<li>
							<a href="<?= $link ?>" class="img"><img src="<?= ASSETURL.'/userimages/60/60/1/'.$friend->image ?>" alt="" /></a>
							<a href="<?= $link ?>"><?= $friend->name ?></a>
						</li>
					<?php
						}
					?>
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
