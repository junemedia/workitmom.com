<div class="rounded-630-blue block" id="marketplace_item">
	<div class="top"></div>
	<div class="content">
		
		<div class="header">
			<div class="img"><a href="[LIGHTBOX?]"><? $modules->image(); ?></a></div>
			<div class="body">
				<h2><?= $modules->get('title'); ?></h2>
				<p style="margin-bottom:2px;"><? $modules->category(); ?></p>
				<p class="text-content underline">Listed by <? $modules->name(); ?></p>
			</div>
			<div class="clear"></div>
		</div>
		
		<ul class="item_list">
			
			<? $modules->website(); ?>
			
			<? $modules->description(); ?>
			
			<? $modules->images(); ?>

			<li class="contact">
				
				<h3>Contact Details</h3>
				
				<? $modules->location(); ?>
				
				<? $modules->email(); ?>
				
				<? $modules->phone(); ?>
				
				<? $modules->links(); ?>
				
			</li>

		</ul>

	</div>
	<div class="bot"></div>
</div>