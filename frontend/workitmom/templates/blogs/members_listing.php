
			<?php /* <div class="item_list full_width block" id="member_blogs"> */ ?>
			
	<div id="browse_bar" class="block">
		<h3>Sort&hellip;</h3>
		<ul class="categories">
			<?php $this->members_listing_sorter($sort); ?>
		</ul>
		
		<?php $this->members_listing_ad(); ?>
		<div class="clear"></div>
	</div>

	<div class="items-right item_list">
	
		<div id="sort_bar">
			<?php $this->members_listing_countstring($pagination); ?>
			<div class="clear2"></div>
		</div>
			
		<?php if (Utility::iterable($posts)) { ?>
		<ul>
			<?php foreach($posts as $post) {
				$this->members_listing_individual($post);
			} ?>
		</ul>
		<?php } ?>

		<?= $pagination->get('buttons'); ?>
		
	</div>