<?

// Uses $featuredQuestion, which should be a QuestionObject object.
$permalink = SITEURL . '/questions/detail/' . $featuredQuestion->id . '/';

?><div id="featured_quote" class="block">

	<div class="header">
		<div class="title">
			<h2><a href="<?= $permalink; ?>">Member Question</a></h2>
		</div>

		<a href="<?= SITEURL; ?>/questions/" class="button_dark"><span>See All</span></a>

		<div class="clear"></div>
	</div>

	<div class="content">
		<div class="q fl"></div>
		<div class="question fl">
			<a href="<?= $permalink; ?>">&ldquo;<?= $featuredQuestion->title; ?>&rdquo;</a>
		</div>
		<div class="clear"></div>

		<?
		// Don't put the "answer" icon here?
		//<div class="a fl"></div>
		?>
		<div class="answer text-content fl">
			<p>Asked by <cite><a href="<?= SITEURL . $featuredQuestion->author->profileURL; ?>" class="user"><?= $featuredQuestion->author->name; ?></a></cite></p>
		</div>
		<div class="clear"></div>

		<p class="links text-content underline">
			<a href="<?= $permalink; ?>#comment" class="scroll">Reply to this</a> &nbsp;|&nbsp;
			<a href="<?= $permalink; ?>#comments" class="scroll">See all <?= $featuredQuestion->getCommentCount(); ?> replies</a> &nbsp;|&nbsp;
			<a href="<?= SITEURL; ?>/questions/#ask_question">Ask a question</a>
		</p>
	</div>

</div>