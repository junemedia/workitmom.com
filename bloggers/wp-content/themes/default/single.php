<?php 
$relatedlink = get_permalink();
$addtitle = get_the_title();
get_header();
get_sidebar();
if (have_posts()) : while (have_posts()) : the_post(); 
$pinterestImage =  urlencode(catch_that_image());
 ?>	
<div id="main-content" class="blogs">
		<div class="panel-left">

		<wppost id="<?php the_ID(); ?>" />

		<?php	// NOT THE BEST WAY, BUT IT WORKS FOR TIME BEING.  QUICK N EASY BANDAGE
			if (strstr($_SERVER['REQUEST_URI'], 'workingonmotherhood') || strstr($_SERVER['REQUEST_URI'], 'orderingdisorder') || 
				strstr($_SERVER['REQUEST_URI'], 'problemsolved') || strstr($_SERVER['REQUEST_URI'], 'singlemomatwork') || 
				strstr($_SERVER['REQUEST_URI'], 'workitmom') || strstr($_SERVER['REQUEST_URI'], 'parentingwithoutamanual') || 
				strstr($_SERVER['REQUEST_URI'], 'workingcloset') || strstr($_SERVER['REQUEST_URI'], '36hourday') || 
				strstr($_SERVER['REQUEST_URI'], 'momseyeview') || strstr($_SERVER['REQUEST_URI'], 'quickrecipes')) {
					// do nothing :)
			} else { ?>
				WP_BLOGIMAGE
		<?php } ?>


		<div class="post">

			<div id="post_title">
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<p class="byline">Posted by <?php the_author_link() ?> on <?php the_time('jS F Y') ?></p>
				<p class="text-content fl">Categories: <?php the_category(', ') ?><?php edit_post_link('Edit', ' &nbsp;|&nbsp; ', ''); ?></p>
				<p class="text-content fr"><a href="#comments"><?php comments_number('No comments yet', '1 comment', '% comments' );?></a></p>
				<div class="clear"></div>
			</div>
			
			
  <table style="width: 100px;">
    <tr>
      <td style="border-bottom: none;">
        <span><a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fworkitmom.com%2F&media=<?=$pinterestImage;?>" class="pin-it-button" count-layout="none"><img border="0" src="http://assets.pinterest.com/images/PinExt.png" title="Pin It" /></a></span>
</td>
<td style="border-bottom: none;">
<span  class='st_facebook_button' displayText='Facebook'></span>
      </td>
      <td style="border-bottom: none;">
<span  class='st_twitter_button' displayText='Tweet'></span>
      </td>
<td style="border-bottom: none;">
      <span  class='st_plusone_button' ></span>
      </td>
      <td style="border-bottom: none;">
<span  class='st_email_button' displayText='Email'></span>
      </td></tr></table>

<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "0541fe9f-2a3f-4c01-ac74-8f02c84e7fde"});</script>


					
			<div class="entry">
			<?php $gotauth=the_author('',false);
			 the_content('<br />Read the rest of this entry'); ?>
			 <div class="clear"></div>
			 
			 
			 
			 
			 
			 
			 <div class="clear"></div>
			 <br>
			 <script type="text/javascript">
                netseer_tag_id = "13547";
                netseer_ad_width = "630";
                netseer_ad_height = "80";
                netseer_task = "ad";
                netseer_imp_type = "1"; 
                netseer_imp_src = "2"; 
				</script>
				<script src="http://cl.netseer.com/dsatserving2/scripts/netseerads.js" type="text/javascript"></script>
			 <br>
			 <div class="clear"></div>
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 <a href="<?= bloginfo('url') ?>/feed/" class="rss" style="font-size:1em;">Subscribe to blog via RSS</a>
			<div class="clear"></div>
			</div>
			
		</div>
		
		


<?php include_once('share.php'); ?>
		
		<? //st_the_tags();?>
		<? /* Tags: <?=STP_GetPostTags();?>*/?>
	
	<?php /* THREE MOST RECENT POSTS? */ ?>
	
	
	<a name="comment"></a>
	<?php comments_template(); ?>
	
	<?php endwhile; else: ?>

	</div>


<?php get_sidebar(); ?>

<?php endif; ?>

	</div>
	
	WP_BOTTOMBLOCKS

	<div class="clear"></div>

</div>	

<?php get_footer(); ?>
