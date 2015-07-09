				<div class="most_popular content">
					<h4>Featured Tips</h4>
					<ul>
						<? if (Utility::is_loopable($featureditems)){ foreach($featureditems as $item) { ?>
						<li><a href="<?= Uri::build($item); ?>"><?= $item->title; ?></a></li>
						<? } } ?>
					</ul>
				</div>