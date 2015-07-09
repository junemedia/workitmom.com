
	<div class="col-l">

		<div id="latest_discussions" class="block">

			<?php if (!empty($latestTopics)) { ?>

				<div class="header">
					<div class="title">
						<h2 class="fl">Latest Discussions</h2>
					</div>
					<a href="<?= SITEURL.'/groups/discussions/'.$groupId ?>" class="button_dark fr"><span>see all</span></a>
					<div class="clear"></div>
				</div>

				<ul class="item_list">
					<?php
						$alt = true;
						foreach($latestTopics as $topic) {
							$link = SITEURL.'/groups/discussion/'.$topic['id'];
					?>
					<li<?= $alt ? ' class="odd"' : '' ?>>
						<a href="<?= $link ?>"><?= $topic['title'] ?></a>
						<p class="text-content underline">
							<a href="<?= $link ?>#comments"><?php Template::pluralise($topic['reply_count'], 'reply', 'replies'); ?></a> &nbsp;|&nbsp;
							<a href="<?= $link; ?>">Join in</a>
						</p>
					</li>
					<?php
							$alt = !$alt;
						}
					?>
				</ul>

			<?php } else { ?>

				<p class="text-content">This group hasn't had any discussions yet.</p>

			<?php } ?>

		</div>
	</div>

	<div class="col-r">
		<div id="start_discussion" class="standardform">
			<div class="formholder">
				<h2>Start a Discussion</h2>
				<form id="form_start_discussion" action="<?= SITEURL.'/groups/start_discussion' ?>" method="post"><div>

					<label for="disc_title">Title of your post:</label>
					<input name="disc_title" id="disc_title" type="text" class="textinput required" value="<?= Request::getString('disc_title') ?>" />

					<label for="disc_post">Type your post:</label>
					<textarea name="disc_post" id="disc_post" class="textinput required" rows="8"><?= strip_tags(Request::getString('disc_post')) ?></textarea>

					<label class="check rss">
						<input type="checkbox" name="disc_subscribe" id="disc_subscribe"<?php if (Request::getBool('disc_subscribe')) { ?> checked="checked"<?php } ?> value="y" class="check" />
						Subscribe to discussion replies
					</label>

					<div class="clear"></div>

					<button name="submit" type="submit" class="submit"><span>Post new discussion</span></button>

					<input type="hidden" name="groupid" value="<?= $groupId ?>" />
				</div></form>
			</div>
		</div>
	</div>

	<div class="clear"></div>
