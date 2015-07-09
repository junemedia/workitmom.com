
	<h3>Browse&hellip;</h3>
	<ul class="categories">
		<?php foreach($categoryTabs as $categoryName => $categoryNameHTML){ ?>
		<li<?= $category == $categoryName ? ' class="on"' : ''; ?>><a href="<?= $this->_url ?>?category=<?= urlencode($categoryName); ?>&amp;sort=<?= $sort ?>"><?= $categoryNameHTML; ?></a></li>
		<?php } ?>
	</ul>
