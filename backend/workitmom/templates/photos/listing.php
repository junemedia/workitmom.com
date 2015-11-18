

	<table id="comments_data" class="centered horizontal">

		<tr class="metadata">
			<td colspan="5" style="border: 0px;">
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
			?>">Photo Number</a></th>
			
			<th><a href="?sort=name<?php
			if ($sort == 'name') { echo '&amp;direction=' . ($direction == 'asc' ? 'desc' : 'asc'); }
			?>">Title</a></th>

			<th><a href="?sort=date<?php
			if ($sort == 'date') { echo '&amp;direction=' . ($direction == 'asc' ? 'desc' : 'asc'); }
			?>">Time Uploaded</a></th>

			<th><a href="?sort=comments<?php
			if ($sort == 'comments') { echo '&amp;direction=' . ($direction == 'asc' ? 'desc' : 'asc'); }
			?>">Comments</a></th>
			<th>Delete</th>
			<th>Edit</th>
			<th>Status</th>

		</tr>

		<?php if (Utility::iterable($photos)){
			foreach($photos as $photo){
				$this->listing_individual($photo);
			}
		} ?>

		<tr class="metadata">
			<td colspan="6" style="border: 0px;">
				<div class="fr"><?= $pagination->get('buttons'); ?></div>
			</td>
		</tr>

		<?php Template::startScript(); ?>
		new Table('comments_data');
		<?php Template::endScript(); ?>

	</table>