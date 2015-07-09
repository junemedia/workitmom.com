
<div id="featured_article" class="block rounded-630-orange">
	<div class="top"></div>

	<div class="content">
		<div class="img"><img src="<?php Template::image($featuredArticle, 183, 251, 3); ?>"/></div>

		<div class="body">
			<a href="<?= Uri::build($featuredArticle->author); ?>" class="fl">
				<img src="<?php Template::image($featuredArticle->author); ?>" />
			</a>
			<div class="title fl">
				<h2><a href="<?= $link; ?>"><?= $featuredArticle->title; ?></a></h2>
				<cite><a href="<?= Uri::build($featuredArticle->author); ?>" class="user"><?= $featuredArticle->author->name; ?></a></cite>
				<span class="text-content underline">
					<?= $featuredArticle->views; ?> view<?= Text::pluralise($featuredArticle->views); ?>
					&nbsp;|&nbsp;
					<a href="<?= $link; ?>#comments" class="scroll">
						<?= $featuredArticle->getCommentCount(); ?> comment<?= Text::pluralise($featuredArticle->getCommentCount()); ?>
					</a>
					&nbsp;|&nbsp;
					<? for($i = 1, $j = 0; $i < 6; $i++, $j += 20) { ?><img src="<?= $featuredArticle->rating['average'] > $j ? SITEASSETURL . '/images/site/icon-star-sm.png' : '' ?>" />&nbsp;<? } ?>
				</span>
			</div>

			<div class="clear"></div>

			<p><?= Text::trim($featuredArticle->body, 250); ?><br/><a href="<?= $link; ?>" class="arrow">Keep reading...</a></p>

			<a href="<?= SITEURL; ?>/articles?category=<?= urlencode($this->_category) ?>" class="button_dark fl">
				<span>View all <?= $this->_category ?> articles</span>
			</a>

			<div class="clear"></div>

		</div>

		<div class="clear"></div>

	</div>

	<div class="bot"></div>
</div>
