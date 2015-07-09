
	
	<table id="comments_data" class="centered horizontal">
		
		<tr class="metadata">
			<td colspan="4" style="border: 0px;">
				<div class="fr"><?= $pagination->get('buttons'); ?></div>
				<div style="height: 10px; margin: 14px 0px;">
					Listing <?= $pagination->get('start'); ?>-<?= $pagination->get('end'); ?> of <?= $pagination->get('total'); ?>
				</div>
			</td>
		</tr>
		
		<tr class="metadata">
			
			<?php /*
			<th><input class="checkall" type="checkbox" /></th>
			*/ ?>
			
			<th><a href="?sort=id<?php 
			if ($sort == 'id') { echo '&amp;direction=' . ($direction == 'asc' ? 'desc' : 'asc'); }
			?>">Comment</a></th>
			
			<th><a href="?sort=text<?php
			if ($sort == 'text') { echo '&amp;direction=' . ($direction == 'asc' ? 'desc' : 'asc'); }
			?>">Text preview</a></th>
			
			<th><a href="?sort=date<?php 
			if ($sort == 'date') { echo '&amp;direction=' . ($direction == 'asc' ? 'desc' : 'asc'); }
			?>">Time Reported</a></th>
			
			<th><a href="?sort=reports<?php
			if ($sort == 'reports') { echo '&amp;direction=' . ($direction == 'asc' ? 'desc' : 'asc'); }
			?>">Reports</a></th>
			
		</tr>
		
		<?php if (!empty($comments)){
			foreach($comments as $comment){
				$this->comments_listing_individual($comment);
			}
		} ?>
		
		<tr class="metadata">
			<td colspan="5" style="border: 0px;">
				<div class="fr"><?= $pagination->get('buttons'); ?></div>
			</td>
		</tr>
		
		<?php Template::startScript(); ?>
		new Table('comments_data');
		<?php Template::endScript(); ?>
		
	</table>