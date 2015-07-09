
	<div id="browse_bar" class="block">
		<h3>Sort&hellip;</h3>
		<ul class="categories">
			<?php $this->listing_sorter($category, $sort); ?>
		</ul>

		<?php $this->listing_categories($category, $sort); ?>
		
		<?php $this->listing_ad(); ?>
		<div class="clear"></div>
	</div>

	<div class="items-right">
		<div id="sort_bar">
			<?php $this->listing_countstring($pagination); ?>

			<?php /* Sorter now goes with categories bar. Viva la revolucionne. */ ?>
			<?php //$this->listing_sorter($category, $sort); ?>

			<div class="clear2"></div>
		</div>

		<?php $this->listing_list($items); ?>

		<?= $pagination->get('buttons'); ?>

	</div>
