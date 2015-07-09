<?php
//Reverting back to old comments system
// Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments">This post is password protected. Enter the password to view comments.<p>

			<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'odd';
?>

<?php //comments_rss_link('Subscribe to Comments RSS Feed'); ?>				


<!-- You can start editing here. -->


<?php if ('open' == $post->comment_status) : ?>

<!-- START of DISQUS -->
<!--<div id="disqus_thread"></div>
<script type="text/javascript">
	/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
	var disqus_shortname = 'workitmom'; // required: replace example with your forum shortname
	var disqus_url = 'http://www.workitmom.com<?php echo substr($_SERVER["REQUEST_URI"],0,strlen($_SERVER["REQUEST_URI"])-1); ?>';
	
	/* * * DON'T EDIT BELOW THIS LINE * * */
	(function() {
		var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
		dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
		(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
	})();
</script>-->
<!-- END of DISQUS -->




<?php if ($comments) : ?>
<div id="comments">
	<h2><?php comments_number('No comments yet', 'One comment so far...', '% comments so far...' );?></h2>

	
	<div class="item_list small_list">
	<ul class="commentlist">

	<?php foreach ($comments as $comment) : ?>

		<li class="<?php echo $oddcomment; ?>" id="comment-<?php comment_ID() ?>">
			
			<div class="body">
				<div class="reply">
					<?php if ($comment->comment_approved == '0') : ?>
					<small>Your comment is awaiting moderation.</small>
					<?php endif; ?>
					<?php comment_text() ?>
				</div>
				<p class="text-content underline">
					<cite><?php comment_author_link() ?></cite> &nbsp;|&nbsp;
					<?php comment_date('F jS, Y') ?> at <?php comment_time() ?>
				</p>
			</div>
			<div class="clear"></div>

		</li>

	<?php /* Changes every other comment to a different class */
		if ('odd' == $oddcomment) $oddcomment = "";
		else $oddcomment = 'odd';
	?>	

	<?php endforeach; /* end for each comment */ ?>

	</ul>
	</div>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>
