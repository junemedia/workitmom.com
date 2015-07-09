
	<?php if (empty($joinedGroups) && empty($joinedGroups)) { ?>
	<div class="message message-info"><?= $this->_person->name ?> has not joined or created any groups recently.</div>
	<?php } ?>

	<?php if (!empty($joinedGroups)) { ?>
	<h3>Groups joined</h3>
	<div class="item_list">
		<ul>
			<?php
				$alt = true;
				foreach ($joinedGroups as $group) {
					$link = SITEURL.'/groups/detail/'.$group['id'];
			?>
			<li<?= $alt ?' class="odd"' : '' ?>>
				<a href="<?= $link ?>" class="title"><?= $group['name'] ?></a>
			</li>
			<?php
					$alt = !$alt;
				}
			?>
		</ul>
	</div>
	<?php } ?>

	<?php if (!empty($ownedGroups)) { ?>
	<h3>Groups created</h3>
	<div class="item_list">
		<ul>
			<?php
				$alt = true;
				foreach ($joinedGroups as $group) {
					$link = SITEURL.'/groups/detail/'.$group['id'];
			?>
			<li<?= $alt ?' class="odd"' : '' ?>>
				<a href="<?= $link ?>" class="title"><?= $group['name'] ?></a>
			</li>
			<?php
					$alt = !$alt;
				}
			?>
		</ul>
	</div>
	<?php } ?>