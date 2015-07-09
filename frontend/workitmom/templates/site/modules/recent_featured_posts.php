
	<?php if (Utility::iterable($recentPosts)){ ?>
	<div id="recent_list" class="most_popular rounded-630-outline">
		<div class="top"></div>
		<div class="content">
			<h4>Most Recent Posts...</h4>
			<ul>
				<?php foreach ($recentPosts as $post) { ?>
				<li>
					<a href="<?= $post['guid']; ?>" class="fl"><?= $post['post_title']; ?></a>
					<?php /* Floating right: 
					<p class="text-content underline fr"><a href="[link]">[text]</a></p>
					*/ ?>
					<div class="clear"></div>
				</li>
				<?php } ?>
			</ul>
		</div>
		<div class="bot"></div>
	</div>
	<div class="divider"></div>
	<?php } ?>