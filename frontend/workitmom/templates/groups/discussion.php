
	<div id="main-content" class="groups">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="groups_icon" class="icon fl"></div>
					<h1>Group Discussions</h1>
				</div>

				<div class="bot"></div>
			</div>
			<?= Messages::getMessages(); ?>
			<h2 style="margin-bottom: 10px; width: 80%; float: left;"><?= $topic['title'] ?></h2>
			<?php if ($topic['isSubscribed']) { ?>
			<a href="<?= SITEURL ?>/groups/unsubscribe_discussion?topicid=<?= $topicId ?>" class="button_dark fr"><span>Unsubscribe</span></a>
			<?php } else { ?>
			<a href="<?= SITEURL ?>/groups/subscribe_discussion?topicid=<?= $topicId ?>" class="button_dark fr"><span>Subscribe</span></a>
			<?php } ?>
			<div class="clear"></div>

			<?php if (!empty($posts)) { ?>
			<ul id="post_list" class="item_list">
				<?php
					$alt = true;
					foreach($posts as $post) {
				?>
				<li<?= $alt ? ' class="odd"' : '' ?> id="post_<?= $post['id'] ?>">
					<div class="img">
						<a href="<?= SITEURL.$post['user']->profileURL ?>">
						<img src="<?= ASSETURL.'/userimages/75/75/1/'.$post['user']->image ?>" width="75" height="75" alt="" />
						</a>
					</div>
					<div class="body">
						<div class="content">
						<?= Template::bbcode($post['text']) ?>
						</div>
						<div class="sub">
							<span class="fr">
								<?php if($post['canDelete']) { ?>
									<a href="?task=delete_post&postid=<?= $post['id'] ?>" title="Delete" class="delete">Delete</a>&nbsp;&nbsp;
								<?php } ?>
								<?php if ($post['canEdit']) { ?>
									<a href="?task=edit_post&amp;postid=<?= $post['id'] ?>&amp;topicid=<?= $topic['id'] ?>">Edit</a> |
								<?php } ?>
								<?php if ($post['reports'] == 0){ ?>
								<a href="?task=report_post&amp;postid=<?= $post['id'] ?>" class="flag">Flag as inappropriate</a>
								<?php } else { ?>
								<span class="flag">Reported.</span>
								<?php } ?>
							</span>
							<span class="comment-by">Posted by <a href="<?= SITEURL.$post['user']->profileURL ?>"><?= $post['user']->name ?></a> on <?= Template::time($post['created']) ?></span>
						</div>
					</div>

					<div class="clear"></div>
				</li>
				<?php
						$alt = !$alt;
					}
				?>
			</ul>

			<?= $pagination->get('buttons'); ?>

			<?php } ?>

			<div class="clear" style="height:25px;"></div>

			<div class="rounded-630-grey">
				<div class="top"></div>
				<div class="content">
					<div id="add_reply" class="standardform">
						<div class="formholder">
							<h2>Add a Reply</h2>
							<form id="form_add_reply" action="<?= SITEURL.'/groups/discussion/'.$topic['id'] ?>" method="post"><div>

								<label for="reply_post">Your Message:</label>
								<textarea name="reply_post" id="reply_post" class="textinput required" rows="8"><?= strip_tags(Request::getString('reply_post')) ?></textarea>

								<label class="check rss">
									<input type="checkbox" name="reply_subscribe" id="reply_subscribe"<?php if (Request::getBool('reply_subscribe')) { ?> checked="checked"<?php } ?> value="y" class="check" />
									Subscribe to this discussion
								</label>

								<div class="clear"></div>

								<button name="submit" type="submit" class="submit"><span>Add my reply</span></button>

								<input type="hidden" name="topicid" value="<?= $topic['id'] ?>" />
								<input type="hidden" name="task" value="add_post" />
							</div></form>
						</div>
					</div>
				</div>
				<div class="bot"></div>
			</div>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath'
)); ?>
		</div>

		<div class="clear"></div>
	</div>
