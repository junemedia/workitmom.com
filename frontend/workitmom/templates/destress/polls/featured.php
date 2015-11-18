<? Template::startScript();?>
var poll = new PollVoter(<?= $poll->id; ?>, 'form#featured_poll').addTrigger('button[name=see]', 'view');
<? Template::endScript();?>

<div id="poll" class="rounded-630-blue block">
	<div class="top"></div>

	<div class="content">

		<div class="header">
			<h2>Featured Poll: Sound off!</h2>
			<h3><?= $poll->question; ?></h3>
		</div>

		<form id="featured_poll">
			<ul>
				<? if (Utility::is_loopable($poll->answers)){ foreach($poll->answers as $answer) { ?>

				 <li>
					 <input type="radio" id="poll_option_<?= $answer->id; ?>" value="<?= $answer->id; ?>" name="answer" class="radio" />
					 <label for="poll_option_<?= $answer->id; ?>"><?= $answer->text; ?></label>
					 <div class="clear"></div>
				 </li>

				<? } } ?>
			</ul>
			<button name="submit"><span>Submit my vote</span></button>&nbsp;&nbsp;<button name="see"><span>See results</span></button>
		</form>

		<div class="divider"></div>

		<div id="discuss_poll">
			<h3>Discuss this poll</h3>
			<p class="text-content">Share your opinions in our discussion group!
			<a href="<?= SITEURL.'/groups/discussion/'.$poll->pollForum; ?>" class="arrow">Discuss now</a></p>
			<div class="clear"></div>
		</div>

	</div>

	<div class="bot"></div>
</div>