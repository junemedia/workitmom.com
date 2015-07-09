
<div class="rounded-630-outline block" id="question">
	<div class="top"></div>
	
	<div class="content">
		<div class="quote_mark"></div>
		<div class="question fl">
			<h3><?= $item->body; ?>&rdquo;</h3>
			<p class="links text-content underline">
				Asked by <a href="<?= SITEURL . $item->author->profileURL; ?>"><?= $item->author->name; ?></a> on <?= $item->date; ?>
				&nbsp;|&nbsp;
				<a href="#comments" class="scroll"><?php Template::pluralise($commentCount, 'reply', ' replies'); ?></a>
			</p>
		</div>
		<div class="clear"></div>
	</div>

	<div class="bot"></div>
</div>
