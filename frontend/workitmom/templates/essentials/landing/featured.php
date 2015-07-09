				<div class="most_popular content">
					<h4>Featured Essential Guides</h4>
					<ul>
						<? if (Utility::is_loopable($popularitems)){ foreach($popularitems as $item) { ?>
						<li><a href="<?= $this->_getItemURL($item); ?>"><?= $item->title; ?></a></li>
						<? } } ?>
					</ul>
				</div>