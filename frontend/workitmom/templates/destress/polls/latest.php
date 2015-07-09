<? Template::startScript(); ?>

	$$('.otherpolls').each(function (polltoggler, i){

		// Get the corresponding form
		var form = polltoggler.getParent().getNext('form');

		// Hide it first time round
		form.get('slide').hide();

		// Make it toggle the form, when the toggler is clicked
		polltoggler.addEvent('click', function(form){
			form.get('slide').toggle();
			return false;
		}.pass(form));

	});

<? Template::endScript(); ?>

<div id="recent_list" class="most_popular rounded-630-outline">
	<div class="top"></div>

	<div class="content">
		<h4>Check out other recent polls</h4>
		<ul>
		<? if (Utility::is_loopable($polls)){ foreach($polls as $poll){ ?>

			<? Template::startScript(); ?>
			new PollVoter(<?= $poll->id; ?>, 'form#poll_<?= $poll->id; ?>').addTrigger('button[name=see]', 'view');
			<? Template::endScript(); ?>

			<li>
				<a href="#" class="otherpolls"><?= $poll->question; ?></a>
				<p class="text-content"><?= $poll->date; ?></p>
			</li>

			<form id="poll_<?= $poll->id; ?>">
				<ul>
				<? foreach($poll->answers as $answer) { ?>

					<li>
						<input type="radio" id="poll_option_<?= $answer->id; ?>" value="<?= $answer->id; ?>" name="answer" class="radio" />
						<label for="poll_option_<?= $answer->id; ?>"><?= $answer->text; ?></label>
						<div class="clear"></div>
					</li>

				<? } ?>
				</ul>
				<button name="submit"><span>Submit my vote</span></button>&nbsp;&nbsp;<button name="see"><span>See results</span></button>

				<p><a href="<?= SITEURL.'/groups/discussion/'.$poll->pollForum; ?>" class="arrow">Discuss now</a></p>
			</form>

		<? } } ?>
		</ul>
	</div>

	<div class="bot"></div>
</div>
