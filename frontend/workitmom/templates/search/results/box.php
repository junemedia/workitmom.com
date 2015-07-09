
	<?php if (!empty($searchTerms)) { ?>
		<div class="tab_menu">
		<ul>
			<li<?= !$type ? ' class="on"' : ''; ?>><a href="<?= SITEURL ?>/search">View All</a></li>
			<li<?= $type == 'article' ? ' class="on"' : ''; ?>><a href="<?= SITEURL ?>/search?type=article">Articles</a></li>
			<li<?= $type == 'blog' ? ' class="on"' : ''; ?>><a href="<?= SITEURL ?>/search?type=blog">Featured Blogs</a></li>
			<li<?= $type == 'note' ? ' class="on"' : ''; ?>><a href="<?= SITEURL ?>/search?type=note">Member Blogs</a></li>
			<li<?= $type == 'forum' ? ' class="on"' : ''; ?>><a href="<?= SITEURL ?>/search?type=forum">Groups</a></li>
			<li<?= $type == 'question' ? ' class="on"' : ''; ?>><a href="<?= SITEURL ?>/search?type=question">Questions</a></li>
		</ul>
		<div class="clear"></div>
	</div>
	<div id="sort_bar">
		<p class="text-content fl">Your search for <strong class="highlight">"<?= implode('", "', $searchTerms); ?>"</strong> returned <strong><?= $total ?></strong> result<?= Text::pluralise($total) ?></p>
		<div class="clear"></div>
	</div>

	<?php
		if (!empty($items)) {
	?>
	<div class="item_list" id="search_results">
	<ul>
	<?php
		$alt = true;
		foreach($items as $item) {

			// Determine image type
			switch ($item['thingType']) {
				case 'group':
					$imageType = 'groupimages';
					break;
				default:
					$imageType = 'userimages';
					break;
			}

	?>
			<li<?= $alt ? ' class="odd"' : ''; ?>>
				<a href="<?= SITEURL.'/'.$item['thingLink'] ?>" class="img"><img src="<?= ASSETURL.'/'.$imageType.'/60/60/1/'.$item['thingImage'] ?>" alt="" /></a>
				<div class="body">
					<p class="relevance"><strong><?= round($item['score'], 2) ?>% relevance</strong></p>
					<a href="<?= SITEURL.'/'.$item['thingLink'] ?>"><?= $item['thingTitle'] ?></a>
					<?php if ($item['thingCreatorID']) { ?>
					<p class="text-content underline">Posted by <?= $item['thingCreator'] ?> in <?= $typeNames[$item['thingType']] ?></p>
					<?php } ?>
				</div>
				<div class="clear"></div>
			</li>
	<?php
			$alt = !$alt;
		}
	?>
		</ul>

	</div>
	<?php
		}
	?>

	<?= $pagination->get('buttons'); ?>

	<?php } ?>