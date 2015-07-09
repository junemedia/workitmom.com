
	<div id="main-content" class="groups">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="groups_icon" class="icon fl"></div>
					<h1><?= $topic['title'] ?></h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div id="add_reply" class="standardform">
				<div class="formholder">
					<h2>Edit Reply</h2>
					<form id="form_edit_reply" action="<?= SITEURL.'/groups/discussion/'.$topic['id'] ?>" method="post"><div>

						<label for="reply_post">Message:</label>
						<textarea name="reply_post" id="reply_post" class="textinput required" rows="8"><?= strip_tags(Request::getString('reply_post', $post['text'])) ?></textarea>

						<div class="clear"></div>

						<button name="submit" type="submit" class="submit"><span>Edit reply</span></button>

						<input type="hidden" name="topicid" value="<?= $topic['id'] ?>" />
						<input type="hidden" name="postid" value="<?= $post['id'] ?>" />
						<input type="hidden" name="task" value="save_post" />
					</div></form>
				</div>
			</div>

		</div>

		<div class="panel-right">
<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?>
		</div>

		<div class="clear"></div>
	</div>
