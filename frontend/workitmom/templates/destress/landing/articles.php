
	<ul>
		
		<?php
		/* Featured article for this category */
		$article = array_shift($articles);
		?>
		
		<li class="featured">
			<a href="<?= Uri::build($article->author); ?>" class="img"><img src="<?php Template::image($article); ?>" /></a>
			<div class="body">
				<a href="<?= Uri::build($article); ?>" class="post"><?= $article->title; ?></a>
				<p class="text-content underline">
					by <cite><a href="<?= Uri::build($article->author); ?>"><?= $article->author->name; ?></a></cite>
				</p>
				<p class="text-content">
					<?= $article->abridgedBody; ?><br/>
					<a href="<?= Uri::build($article); ?>" class="arrow">Keep reading...</a>
				</p>
			</div>
			<div class="clear"></div>
		</li>
		
		<?php /* Other articles for this category. */
		if (Utility::is_loopable($articles)){ foreach($articles as $article){ ?>
		<li class="other"><a href="<?= Uri::build($article); ?>" class="post"><?= $article->title; ?></a></li>
		<?php } } ?>
		
	</ul>