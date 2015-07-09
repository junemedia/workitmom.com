
	<div id="pullquote" class="screenonly">
		<div class="most_popular rounded-300-outline" style="position: relative;">
			<div class="top"></div>
	
			<div class="content">
				<h4>You may also like</h4>
				<ul>
					<?php foreach($relatedItems as $item){ ?>
					<li><a href="<?= Uri::build($item); ?>"><?= $item->title; ?></a></li>
					<?php } ?>
				</ul>
			</div>
			
			<div class="bot"></div>
		</div>
	</div>
