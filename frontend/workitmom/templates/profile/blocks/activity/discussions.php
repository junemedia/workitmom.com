
	<?php if (!empty($createdDiscussions)) { ?>
	<h3>Discussions started</h3>
	<div class="item_list">
		<ul>
			<?php
				$alt = true;
				foreach ($createdDiscussions as $discussion) {
					$link = SITEURL.'/groups/discussion/'.$discussion['id'];
			?>
			<li<?= $alt ?' class="odd"' : '' ?>>
				<a href="<?= $link ?>" class="title"><?= $discussion['title'] ?></a>
			</li>
			<?php
					$alt = !$alt;
				}
			?>
		</ul>
	</div>
	<?php } else { ?>
	<div class="message message-info"><?= $this->_person->name ?> has not started any discussions recently.</div>
	<?php } ?>