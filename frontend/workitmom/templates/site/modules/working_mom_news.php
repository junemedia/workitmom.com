<?

// Uses $latestNews, which should be an array of NewsObject objects.

?><div id="news_block" class="block rounded-300-yellow">
	<div class="top"></div>

	<div class="content">

		<div class="header">
			<div class="title">
				<h2>Latest News</h2>
			</div>
			<a href="<?= SITEURL; ?>/news/" class="button_dark"><span>See all</span></a>
		</div>

		<ul>
			<? 
			foreach((array)$latestNews as $item){ 
				$permalink = SITEURL . '/news/detail/' . $item->id . '/';
				?>
						<li>
							<a href="<?= $permalink; ?>"><?= $item->title; ?></a>
							<? /*<p class="text-content underline"><a href="<?= $permalink; ?>#comments" class="scroll"><?= $item->getCommentCount(); ?> comment<?= Text::pluralise($item->getCommentCount()); ?></a></p>*/ ?>
						</li>
				<?
			} 
			?>
		</ul>

	</div>

	<div class="bot"></div>
</div>