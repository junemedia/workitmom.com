	<div id="meet_a_member" class="rounded-300-outline block">
		
		<div class="top"></div>
		
		<div class="content">
			<div class="header">
				
				<h2><a href="<?= Uri::build($item); ?>"><?= Text::trim($item->title, 40); ?></a></h2>
				<h3><?= $item->subtitle; ?></h3>
			</div>
			
			<p class="text-content">
				<a href="<?= Uri::build($item); ?>" class="img" style="width: 60px; height: 60px;"><img src="<?php Template::image($item, 60); ?>" /></a>
				<?= Text::trim($item->teaser, 500); ?> 
				<br/>
				<a href="<?= Uri::build($item); ?>" class="arrow">Keep reading...</a>
			</p>
			<div class="clear"></div>			
			
		</div>
		
		<div class="bot"></div>
	</div>
