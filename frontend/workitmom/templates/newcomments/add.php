<?php /* ?>
<? if(!BluApplication::getUser() && isset($this->_itemtype)) { ?><div class="message message-info">Please <a href="<?= SITEURL ?>/account/">sign in</a> or <a href="<?= SITEURL ?>/register/">register</a> to <?= isset($type) && $type == 'question' ? 'reply to ' : 'comment on '; ?>this <?= $this->_itemtype; ?>.</div><? } ?>
<?php */ ?>

<div id="comment" class="rounded-630-grey block screenonly">

	<div class="top"></div>

	<div class="content">

		<h2>Leave <?= isset($type) && $type == 'question' ? 'answer' : 'a comment'; ?></h2>
		<form id="form_question_reply" method="post" action=""><div>

			<textarea name="body" id="q" cols="14" rows="4" class="overtext" title="Write your <?= isset($type) && $type == 'question' ? 'reply' : 'comment'; ?> here"></textarea>

			<button type='submit'><span>Submit your <?= isset($type) && $type == 'question' ? 'answer' : 'comment'; ?></span></button>

			<input type="hidden" name="task" value="add_comment" />

			<div class="clear"></div>

		</div></form>

	</div>

	<div class="bot"></div>

</div>
