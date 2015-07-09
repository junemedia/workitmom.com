
	<div id="featured_article" class="block rounded-630-orange">
		<div class="top"></div>

		<div class="content">
			<div class="image">
				<img src="<?php Template::image($featuredRecipe, 320, 200); ?>" alt="<?= $featuredRecipe->title; ?>" />
			</div>
			<div class="body_short">
				<div class="title">
					<h2><a href="<?= Uri::build($featuredRecipe); ?>"><?= $featuredRecipe->title ?></a></h2>
				</div>
				<div class="clear" style="height:10px"></div>
				<p>
					<?= $featuredRecipe->abridgedBody ?><br/>
					<a href="<?= Uri::build($featuredRecipe); ?>" class="arrow">Keep reading...</a>
				</p>
				<? /*<a href="<?= SITEURL ?>/quickrecipes/rss?format=xml" class="rss">Get Quick Recipes as an RSS feed</a> */?>
				<div class="clear"></div>
			</div>

			<div class="clear"></div>

		</div>

		<div class="bot"></div>
	</div>
