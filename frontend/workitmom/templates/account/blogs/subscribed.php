
	<?php if (!empty($subscribedPosts)) { ?>
	<div class="item_list">
		<ul>
			<?php
				$alt = true;
				foreach ($subscribedPosts as $post) {
					$link = SITEURL.'/blogs/member_blog_post/'.$post['articleID'];
			?>
			<li<?= $alt ?' class="odd"' : '' ?>>
				<div class="actions">
					<a href="<?= $link ?>"><img src="<?= ASSETURL.'/userimages/100/100/1/'.$post['author']['image'] ?>"></a>
					<ul class="links">
						<li><a href="<?= SITEURL.'/blogs/unsubscribe/'.$post['author']['username'] ?>" class="button_dark fl"><span>Unsubscribe</span></a><div class="clear"></div></li>
					</ul>
				</div>
				<div class="info">
					<h3><a href="<?= $link ?>" class="title"><?= $post['articleTitle'] ?></a></h3>
					<p>by <a href="<?= SITEURL ?>/profile/<?= $post['author']['username'] ?>"><?= $post['author']['fullName'] ?></a></p>
					<?php /* ?><p><?= Text::trim($post['articleBody'], 300) ?></p>
					<p><a href="<?= $link ?>" class="arrow">Read the rest of this entry</a></p> */ ?>
				</div>
				<div class="clear"></div>
			</li>
			<?php
					$alt = !$alt;
				}
			?>
		</ul>
	</div>
	<?php } else { ?>
	<div class="message message-info">You have not subscribed to any <a href="<?= SITEURL ?>/blogs/members">member blogs</a> yet!</div>
	<?php } ?>