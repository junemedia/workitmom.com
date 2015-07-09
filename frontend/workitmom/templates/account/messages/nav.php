
	<div id="browse_bar" class="block">
		<h3>Browse&hellip;</h3>
		<ul>
			<li<?= $folder == 'inbox' ? ' class="on"' : ''; ?>><a href="<?= SITEURL ?>/account/messages?folder=inbox">Inbox</a></li>
			<li<?= $folder == 'sent' ? ' class="on"' : ''; ?>><a href="<?= SITEURL ?>/account/messages?folder=sent">Sent Messages</a></li>
			<li><a href="<?= SITEURL ?>/account/write_message/">Write a Message</a></li>
		</ul>
	</div>