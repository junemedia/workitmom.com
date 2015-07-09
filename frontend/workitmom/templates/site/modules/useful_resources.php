
	<div id="useful_resources" class="block">
		<div class="header">
			<div class="title">
				<h2>Useful Resources</h2>
			</div>
			<div class="clear"></div>
		</div>
		<div class="content">
			<ul>
				<?php
					if (isset($resources['dailydeal'])) {
						$link = SITEURL.'/dailydeal/detail/'.$resource->id;
				?>
				<li>
					<a href="<?= $link ?>"><?= $resource->title ?></a>
					<div class="text-content underline">
						<a href="<?= $link ?>#comments" class="scroll"><?= $resource->getCommentCount() ?> comment<?= Text::pluralise($resource->getCommentCount()) ?></a>
					</div>
				</li>
				<?php
					}
				?>

				<?php
					if (isset($resources['quickrecipe'])) {
						$link = SITEURL.'/quickrecipes/detail/'.$resource->id;
				?>
				<li>
					<a href="<?= $link ?>"><?= $resource->title ?></a>
					<div class="text-content underline">
						<a href="<?= $link ?>#comments" class="scroll"><?= $resource->getCommentCount() ?> comment<?= Text::pluralise($resource->getCommentCount()) ?></a>
					</div>
				</li>
				<?php
					}
				?>

				<?php
					if (isset($resources['dailydeal'])) {
						$link = SITEURL.'/quicktip/detail/'.$resource->id;
				?>
				<li>
					<a href="<?= $link ?>"><?= $resource->title ?></a>
					<div class="text-content underline">
						<a href="<?= $link ?>#comments" class="scroll"><?= $resource->getCommentCount() ?> comment<?= Text::pluralise($resource->getCommentCount()) ?></a>
					</div>
				</li>
				<?php
					}
				?>
			</ul>
		</div>
	</div>
