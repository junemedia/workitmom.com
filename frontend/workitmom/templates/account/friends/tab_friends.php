
			<div id="sort_bar">
				<a class="button_bright fr" href="<?= SITEURL; ?>/tellafriend"><span>Invite your friends to Work It, Mom!</span></a>
				<p class="text-content">Showing <strong><?= $pagination->get('start'); ?>-<?= $pagination->get('end'); ?></strong> of <strong><?= $pagination->get('total'); ?></strong> friend<?= Text::pluralise($pagination->get('total')); ?>.</p>
				<div class="clear"></div>
			</div>
			
			<ul class="item_list" id="account-list">
			
				<?php foreach($friends as $person){
					$this->friends_individual($person);
				} ?>
			
			</ul>
			
			<?= $pagination->get('buttons'); ?>