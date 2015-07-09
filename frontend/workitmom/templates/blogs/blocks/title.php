
	<div class="rounded-630-orange" id="blog_title">
		<div class="top"></div>

				<div class="content">
					<div class="img">
						<a href="<?= SITEURL . $blog->author->profileURL; ?>"><img src="<?= ASSETURL . '/userimages/75//1/' . $blog->author->image; ?>" /></a>
					</div>

					<div class="body">

						<h2><a href="<?= SITEURL . '/blogs/members/' . $blog->author->username; ?>/"><?= $blog->title; ?></a></h2>

						<div class="clear"></div>

						<cite><a href="<?= SITEURL . $blog->author->profileURL; ?>">See <?= $blog->author->name; ?>'s Profile</a></cite>

						<p class="text-content">
							<?php if ($user && $blog->author->userid != $user->userid) { ?>
							<?php if ($isSubscribed) { ?>
							<a href="<?= SITEURL . '/blogs/unsubscribe/' . $blog->author->username; ?>">Unsubscribe</a>&nbsp;|&nbsp;
							<?php } else { ?>
							<a href="<?= SITEURL . '/blogs/subscribe/' . $blog->author->username; ?>">Subscribe</a>&nbsp;|&nbsp;
							<?php } ?>
							<?php } ?>
							<a href="<?= SITEURL . '/blogs/members/' . $blog->author->username; ?>">View All Posts</a>
						</p>

					</div>

					<div class="clear"></div>

				</div>

				<div class="bot"></div>
			</div>
