			
			<h3><?= $type; ?></h3>
			<ul>
			<?php if (Utility::is_loopable($links)){ foreach($links as $link){ ?>
				<li><a href="<?= $link['linkUrl']; ?>"><?= $link['linkTitle']; ?></a></li>	
			<?php } } ?>
			</ul>