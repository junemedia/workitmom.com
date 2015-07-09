		
		<div class="item_list<?= isset($extraCss) ? $extraCss : ''; ?>">
			<ul>
				<?php foreach($comments as $comment){
					$this->comments_view_individual($comment);
				} ?>
			</ul>
		</div>
		
		<?= $commentPagination->get('buttons'); ?>