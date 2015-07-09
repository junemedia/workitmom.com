
	<div class="block rounded-300-outline" id="account_nav">
		<div class="top"/></div>

		<div class="content">
			<h2>My Account</h2>

			<div class="img"><img src="<?php Template::image($user, 40); ?>" /></div>
			<p class="info text-content">Hi <?= $user->name; ?>, and welcome back to Work It, Mom! <a href="<?= SITEURL; ?>/profile/">Click here</a> to see your public profile.</p>

			<div class="clear"></div>

			<ul class="text-content">
				<li><a href="<?= SITEURL ?>/account">My Account Home</a></li>
				<li><a href="<?= SITEURL ?>/account/messages">My Messages</a></li>
				<li><a href="<?= SITEURL ?>/account/details">My Profile Details</a></li>
				<li><a href="<?= SITEURL ?>/account/alerts">My Alerts</a></li>
				<li><a href="<?= SITEURL ?>/account/details?tab=privacy">My Privacy</a></li>
				<li><a href="<?= SITEURL ?>/account/photos">My Photos</a><span class="new">NEW!</span></li>
				<li><a href="<?= SITEURL ?>/account/friends">My Friends</a></li>
				<li><a href="<?= SITEURL ?>/account/blogs">My Blogs</a></li>
				<li><a href="<?= SITEURL ?>/account/myday">My Day Archive</a></li>
				<li><a href="<?= SITEURL ?>/account/groups">My Groups</a></li>
				<li><a href="<?= SITEURL ?>/account/articles">My Articles</a></li>
				<li><a href="<?= SITEURL ?>/account/marketplace">My Marketplace Listings</a></li>
			</ul>
			
			<div class="clear"></div>
		</div>

		<div class="bot"/></div>
	</div>
