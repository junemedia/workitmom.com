				<div class="most_popular content">
					<h4>Featured Slideshows</h4>
					<ul>
					<? if (Utility::is_loopable($nextfeatureditems)){ foreach($nextfeatureditems as $item) { ?>
						<li><a href="<?= $this->_getItemURL($item); ?>"><?= $item->title; ?></a></li>
					<? } } ?>
					</ul>
				</div>