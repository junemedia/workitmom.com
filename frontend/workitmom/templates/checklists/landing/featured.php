				<div class="most_popular content">
					<h4>Featured Checklists</h4>
					<ul>
						<? if (Utility::is_loopable($featureditems)){ foreach($featureditems as $item){ ?>
						<li><a href="<?= $this->_getItemURL($item); ?>"><?= $item->title; ?></a></li>
						<? } } ?>
					</ul>
				</div>