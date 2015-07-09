
	<div id="main-content">
	
		<div class="panel-left" id="slideshow">
		
			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				
				<div class="content">
					<div id="photos_icon" class="icon fl"></div>
					<h1>Member Photos</h1>
				</div>
				
				<div class="bot"></div>
			</div>
			
			<?= Messages::getMessages(); ?>

			<div class="rounded-630-blue block" id="photo_detail">
				<div class="top"></div>
				
				<div class="content">
					
					<?php /* TITLE */ ?>
					<div class="header">
						<?php if ($photo['author']['image']) { ?>
						<div class="img">
							<a href="<?= $photo['author']['url']; ?>"><img src="<?php Template::image($photo['author'], 55); ?>" /></a>
						</div>
						<?php } ?>
						<div class="body">
							<h2><?= $photo['author']['name']; ?>'s Photos</h2>
							<p class="text-content underline">
								<?php if ($photo['author']) { ?>
								<a href="<?= $photo['author']['url']; ?>">View profile</a>
								&nbsp;|&nbsp;
								<?php } ?>
								<a href="#comments" class="scroll"><?php Template::comment_count($photo['comment_count']); ?></a>
							</p>
						</div>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
					<?php /* END TITLE */ ?>
					
					<div id="slide_num">
						<p>Photo <?= $pagination->get('current'); ?> of <?= $pagination->get('total'); ?></p>
						<?php
						if ($pagination->get('hasPrevious')) { ?><a href="<?= $adjacentPhotos['previous']['link']; ?>" class="prev"></a><? }
						if ($pagination->get('hasNext')) { ?><a href="<?= $adjacentPhotos['next']['link']; ?>" class="next"></a><? }
						?>
					</div>
					
					<div class="photo_body">
						<h3 class="title"><?= $photo['title']; ?></h3>
						<?php /* Lightbox? */ ?>
						<a href="<?php Template::image($photo, 500); ?>" rel="milkbox" >
							<img src="<?php Template::image($photo, 300); ?>" alt="<?= $photo['title']; ?>" />
						</a>

						<?php if ($pagination->get('hasNext')){ ?>
						<p class="text-content">
							Next photo: 
							<a href="<?= $adjacentPhotos['next']['link']; ?>" class="arrow"><?= $adjacentPhotos['next']['title']; ?></a>
						</p>
						<?php } ?>
						
						<div class="clear"></div>
					</div>

				</div>
				<div class="clear"></div>
				
				<div class="bot"></div>
				
				
			</div>
			<div class="clear" style="height:25px;"></div>

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
