
	<div id="main-content" class="landing">
		
		<div class="panel-left">
		
			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				
				<div class="content">
					<div id="shop_icon" class="icon fl"></div>
					<h1>Giveaways</h1>
				</div>
				
				<div class="bot"></div>
			</div>
			
			<?= Messages::getMessages(); ?>
			<div class="giveaways">
			<?php $this->detail_body(); ?>
			</div>
			<?php $this->comments_add(); ?>
			
			<?php $this->comments_view(); ?>
			
			<div class="divider"></div>
			
		</div>
		
		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>
		<? // END >> RIGHT COLUMN  ?>
		
		<div class="clear"></div>
		
	</div>
