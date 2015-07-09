
<?php if (Utility::is_loopable($posts)){ ?>


<ul class="blog_posts">
	<?php while (Utility::is_loopable($posts) && $post = array_shift($posts)){ $this->info_blog_individual($post); }?>	
</ul>
<p style="padding-top:8px;">
	<a href="<?= SITEURL . '/blogs/member_blog/' . $post->author->username; ?>" class="arrow fr">
		See all posts from <?= $post->author->name; ?>'s blog...
	</a>
</p>

<?php } else { include(BLUPATH_TEMPLATES . '/profile/blocks/info/blog_empty.php'); } ?>