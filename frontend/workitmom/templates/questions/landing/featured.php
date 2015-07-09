<?

// Uses $featureditem, which should be a QuestionObject object.

?><div id="featured_quote" class="block">

	<div class="header">
		<div class="title">
			<h2><a href="<?= $this->_getItemURL($featureditem); ?>">Featured Question</a></h2>
		</div>
		<div class="clear"></div>
	</div>

	<div class="content">
		<div class="quote_mark"></div>
		<div class="question fl">
			<a href="<?= $this->_getItemURL($featureditem); ?>"><?= Text::trim($featureditem->title, 180); ?> &rdquo;</a>
			<a href="<?= $this->_getItemURL($featureditem); ?>" class="button_dark fl"><span>Reply to this</span></a>
			<p class="links text-content underline fl">
				<a href="<?= $this->_getItemURL($featureditem); ?>#comments" class="scroll"><?= $featureditem->getCommentCount(); ?> answer<?= Text::pluralise($featureditem->getCommentCount()); ?></a>
			</p>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>


	</div>

</div>
