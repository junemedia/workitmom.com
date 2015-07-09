
	<? /*
	<form action="<?= $this->_url ?>" class="reloads">
		<label>Sort By</label>
		<select name="sort" class="reloads">
			<? foreach ($sorts as $sortKey => $sortHTML){ ?>
			<option value="<?= $sortKey; ?>"<?= $sort == $sortKey || (!$on && $sort == $defaultSort) ? ' selected' : ''; ?>><?= $sortHTML; ?></option>
			<? } ?>
		</select>
		<div class="clear"></div>

		<noscript>
			<button id="refresh" class="fr"><span>Sort</span></button>
		</noscript>

		<?php if (isset($category)){ ?><input type="hidden" name="category" value="<?= $category; ?>" /><?php } ?>

	</form>

	*/?>

	<?php /* "View all" does the same as 'latest'. */ ?>
	<?php /* <li<?= $viewAll ?  ' class="on"' : ''; ?>><a href="?category=">View All</a></li> */ ?>

	<?php foreach($sorts as $sortKey => $sortHTML){
		// Build href.
		$href = '?';
		if (isset($category) && $category){
			$href .= 'category='.urlencode($category);
		}
		$href .= '&amp;sort='.$sortKey;
	?>
	<li<?= $sort == $sortKey ? ' class="on"' : ''; ?>><a href="<?= $href; ?>" class="star"><?= $sortHTML; ?></a></li>
	<?php } ?>
