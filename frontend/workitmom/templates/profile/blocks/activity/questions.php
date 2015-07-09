
	<?php if (empty($questions) && empty($replies)) { ?>
	<div class="message message-info"><?= $person->name ?> has not asked or answered any questions recently.</div>
	<?php } ?>

	<?php if (!empty($questions)){ ?>
	<h3>Questions asked</h3>
	<div class="item_list">
		<ul>
			<?php $alt = false; foreach($questions as $question){ $this->activity_questions_question_individual($question); } ?>
		</ul>
	</div>
	<?php } ?>

	<?php if (!empty($replies)){ ?>
	<h3>Questions answered</h3>
	<div class="item_list">
		<ul>
			<?php $alt = false; foreach($replies as $reply){ $this->activity_questions_reply_individual($reply); } ?>
		</ul>
	</div>
	<?php } ?>