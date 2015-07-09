
			<div class="marketplace_block rounded-300-grey">
				<div class="top"></div>
				<div class="content">
				
					<h2>Support small businesses!</h2>
				
					<ul>
						
					<?php foreach($listings as $listing){ ?>
						<li>
							<a href="<?= SITEURL; ?>/marketplace/detail/<?= $listing['marketID']; ?>"><img src="<?= ASSETURL . '/marketimages/120/120/1/' . $listing['headImage']; ?>" /></a>
							<a href="<?= SITEURL; ?>/marketplace/detail/<?= $listing['marketID']; ?>"><?= $listing['mShortTitle']; ?></a>
						</li>
						<?php
						static $alt = false;
						$alt = !$alt;
						if (!$alt){ 
							?><div class="clear"></div><?php
						}
							
					} ?>
					
					</ul>
					<div class="clear"></div>
					<div align="center"><h3><a href="<?= SITEURL ?>/marketplace/create">Click here to promote your business!</a></h3></div>
					
				</div>
				<div class="bot"></div>		
			</div>