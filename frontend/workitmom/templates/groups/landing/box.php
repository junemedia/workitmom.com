
	<div id="browse_bar">
		<h3>Sort&hellip;</h3>
		<ul class="categories">
			<?php $this->listing_sorter($sort, $page); ?>
			<?php if ($user) { ?><li<?= $categorySlug == 'joined' ? ' class="on"' : '' ?>><a href="<?= SITEURL ?>/groups?category=joined&amp;sort=<?= $sort ?>" class="star">My Groups</a></li><?php } ?>
		</ul>
		<h3>Browse&hellip;</h3>
		<ul class="categories">
			<li<?= $categorySlug ? '' : ' class="on"'; ?>><a href="<?= SITEURL ?>/groups?sort=<?= $sort ?>">View All</a></li>
			<?php foreach ($categories as $category) { ?>
			<li<?php if ($category && ($category['slug'] == $categorySlug)) { echo ' class="on"'; } ?>>
				<a href="<?= SITEURL.'/groups?category='.$category['slug'].'&amp;sort='.$sort ?>"><?= $category['name'] ?></a>
			</li>
			<?php } ?>
		</ul>
		
		<div class="clear"></div>
	</div>

	<div class="items-right">
		<div id="sort_bar">
			<p id="countstring" class="text-content fl">
				<?= Template::itemCount($total, 'group', 'groups') ?>
			</p>
			<div class="clear"></div>
		</div>
		<div class="item_list">
			<?php if (!empty($groups)) { ?>
			<ul>
				<?php
					$alt = false;
					foreach ($groups as $group) {
						$link = SITEURL.'/groups/detail/'.$group['id'];
				?>
				<li<?= $alt ?' class="odd"' : '' ?>>
					<div class="img">
						<a href="<?= $link ?>"><img src="<?= ASSETURL.'/groupimages/125/125/1/'.$group['photo'] ?>"></a>
					</div>
					<div class="body">
						<div class="header">
							<h3><a href="<?= $link ?>" class="title"><?= $group['name'] ?></a></h3>
							<p class="text-content">
								<a href="<?= SITEURL.'/groups/members/'.$group['id'] ?>" class="comments"><?php Template::pluralise($group['numMembers'], 'member'); ?></a>
								&nbsp;|&nbsp;
								<a href="<?= SITEURL.'/groups/discussions/'.$group['id'] ?>" class="comments"><?php Template::pluralise($group['numTopics'], 'discussion'); ?></a></p>
							<?php /* <small><?= $group['blurb'] ?></small> */ ?>
							<div class="actions">
								<a href="<?= $link; ?>" class="button_bright"><span>+ Join in</span></a>
							</div>
						</div>
						<div class="content text-content">
							<ul>
							<?php
								if (!empty($group['latestTopics'])) {
									foreach($group['latestTopics'] as $topic) {
							?>
								<li>
									<a href="<?= SITEURL.'/groups/discussion/'.$topic['id'] ?>"><?= $topic['title'] ?></a>
									<?php if($topic['post_count'] > 0) { ?>
										<span style="white-space:nowrap;">(<?php Template::pluralise($topic['reply_count'], 'reply', 'replies'); ?>)</span>
									<?php } ?>
								</li>
							<?php
									}
								}
							?>
							</ul>
						</div>
					</div>
					<div class="clear"></div>
				</li>
				<?php
						$alt = !$alt;
					}
				?>
			</ul>
			<?php } ?>
		</div>

		<?= $pagination->get('buttons'); ?>

	</div>

	<div class="divider"></div>

