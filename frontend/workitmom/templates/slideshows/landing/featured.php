<? 

// Uses $featureditem, which is a SlideshowObject object.

$slideshow = $featureditem;
?>

	<div id="featured_slideshow" class="block rounded-300-grey">
		<div class="top"></div>
		
		<div class="content">
			
			<div class="fr">
				<a href="<?= $this->_getItemURL($slideshow); ?>">
					<img src="<?= ASSETURL . '/slideshowimages/100/100/1/' . $slideshow->image; ?>">
				</a>
			</div>
			
			<div class="text-content body">
				<h2><a href="<?= $this->_getItemURL($slideshow); ?>"><?= $slideshow->title; ?></a></h2>
				<p><?= count($slideshow->slides); ?> slide<?= Text::pluralise(count($slideshow->slides)); ?></p>
			</div>
			
			<div class="clear"></div>
		</div>
		
		<div class="bot"></div>
	</div>

