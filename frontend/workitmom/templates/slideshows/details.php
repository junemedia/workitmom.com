
	<div id="main-content" class="<?= $cssClass; ?>">
	
		<div class="panel-left">
		
			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				
				<?php $this->page_heading(); ?>
				
				<div class="bot"></div>
			</div>
			
			<?= Messages::getMessages(); ?>
			
			<?php $this->detail_body($page); ?>
			
			<?php $this->detail_share(); ?>
			
			<?php $this->comments_add(); ?>
			
			<?php $this->comments_view(); ?>
			
			<?php BluApplication::getModules('site')->bottom_blocks(); ?>
			
		</div>
		<? // END >> LEFT COLUMN  ?>
		
		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>
		<? // END >> RIGHT COLUMN  ?>

		<div class="clear"></div>
	</div>
