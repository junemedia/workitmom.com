
<div id="featured_quote" class="block">
		
	<div class="header">
	
		<div class="title2">
			<h2>Featured Article</h2>
		</div>
		
		<div class="clear"></div>
	</div>
	
	<div class="content">
		<div class="quote_mark"></div>
		<div class="question fl">
			<a href="<?= Uri::build($featuredArticle); ?>"><?= $featuredArticle->title; ?></a>
			<br />
			<?= $featuredArticle->abridgedBody; ?>
			<p class="links text-content underline">
				by <a href="<?= SITEURL . $featuredArticle->author->profileURL; ?>"><?= $featuredArticle->author->name; ?></a>
				
			</p>
		</div>
		<div class="clear"></div>
		
		
	</div>

</div>