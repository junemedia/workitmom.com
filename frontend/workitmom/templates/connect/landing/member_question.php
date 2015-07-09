<?

// Uses $featuredQuestion, which should be a QuestionObject object.

?><div id="featured_quote" class="block">

	<div class="header">
		<div class="title">
			<h2><a href="<?= $link; ?>">Member Question</a></h2>
		</div>

		<a href="<?= SITEURL; ?>/questions/" class="button_dark"><span>See All</span></a>

		<div class="clear"></div>
	</div>

	<div class="content">
		<div class="q fl"></div>
		<div class="question fl">
			<strong><a href="<?= $link; ?>">&ldquo;<?= Text::trim($featuredQuestion->title, 180, false); ?>&rdquo;</a></strong>
			<p class="text-content">Asked by <cite><a href="<?= SITEURL . $featuredQuestion->author->profileURL; ?>" class="user"><?= $featuredQuestion->author->name; ?></a></cite></p>
		</div>
		<div class="clear"></div>

	<? if(isset($featuredAnswer->body)) { ?>
		<div class="a fl"></div>
		<div class="answer text-content fl">
			&ldquo;<?= $featuredAnswer->body; ?>&rdquo;&nbsp;&nbsp;<span style="white-space:nowrap;">-- <?= $featuredAnswer->author->name; ?></span>
		</div>
		<div class="clear"></div>
	<? } ?>

		<p class="links text-content underline">
			<a href="<?= $link; ?>#comment" class="scroll">Reply to this</a> &nbsp;|&nbsp; <a href="<?= $link; ?>#comments" class="scroll">See all <?= $featuredQuestion->getCommentCount(); ?> replies</a> &nbsp;|&nbsp;
			<a href="<?= SITEURL; ?>/questions/#ask_question" class="scroll">Ask a question</a>
		</p>
	</div>

</div>