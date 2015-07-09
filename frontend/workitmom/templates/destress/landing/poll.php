	
	<?php if (!empty($poll->answers)) { ?>
	<?php
		Template::includeScript(SITEASSETURL . '/js/PollVoter.js');
		Template::startScript(); 
	?>	
		var poll = new PollVoter(<?= $poll->id; ?>, 'form#form_poll').addTrigger('button[name=see]', 'view');
	<?php Template::endScript(); ?>
	<div id="poll" class="rounded-300-blue block">
		<div class="top"></div>
	
		<div class="content">
	
			<div class="header">
				<h2><a href="<?= SITEURL; ?>/destress/polls/">Poll: Sound off!</a></h2>
				<h3><?= $poll->question; ?></h3>
			</div>
		
			<form id="form_poll" action="<?= SITEURL; ?>/destress/polls/">
				<ul>
				<?php foreach($poll->answers as $answer) { ?>
					 <li>
						 <input type="radio" id="poll_option_<?= $answer->id; ?>" value="<?= $answer->id; ?>" name="answer" class="radio" />
						 <label for="poll_option_<?= $answer->id; ?>"><?= $answer->text; ?></label>
						 <div class="clear"></div>
					 </li>
				<?php } ?>
				</ul>
				<button name="submit"><span>Submit my vote</span></button>&nbsp;&nbsp;<button name="see"><span>See results</span></button>
				<div class="clear"></div>
			</form>
			
			<p class="links text-content underline"><a href="<?= SITEURL.'/groups/discussion/'.$poll->pollForum; ?>">Discuss this poll</a> &nbsp;|&nbsp; <a href="<?= SITEURL; ?>/destress/polls/">Other recent polls</a></p>
			
		</div>
		<div class="bot"></div>
	</div>
	<?php } ?>