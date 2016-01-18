
	</div>

	<div id="footer">
<div style="text-align:center;width:100%;margin-top: -10px;" id="footerfamily">
		<img src="/frontend/workitmom/images/site/wimfamilyofsites.png" alt="Work it, Mom! Family Of Sites" class="screenonly">
		<style>
		#footerfamily ul li a{
		text-transform: none;
		}
		</style>
		<ul class="text-content">
			<li><a href="http://goo.gl/JEH0Y">Recipe4Living</a></li>
			<li><a href="http://goo.gl/UUd0z">Fit & Fab Living</a></li>
			<li><a href="http://goo.gl/qtaUh">Running With Mascara</a></li>
			<li><a href="http://goo.gl/2pH48">Chew on That Blog</a></li>		
			<li><a href="http://www.savvyfork.com/">Savvy Fork</a></li>	
		</ul>
		</div>
		<div style="margin-top: 15px;padding-top: 15px;background:url('http://www.workitmom.com/frontend/workitmom/images/site/dotted-large.png') repeat-x scroll left top transparent;">
		<ul class="fl text-content">
			<li><a href="<?= SITEURL ?>/contact/">Contact </a></li>
			<? /*<li><a href="<?= SITEURL ?>/team/">Team</a></li>
			<li><a href="<?= SITEURL ?>/advisors/">Advisors</a></li> */?>
			<li><a href="<?= SITEURL ?>/press/">Press</a></li>
			<li><a href="<?= SITEURL ?>/advertise/">Advertise</a></li>
			<li><a href="<?= SITEURL ?>/partners/">Our Friends</a></li>
			<li><a href="<?= SITEURL ?>/submission/">Submission Guidelines</a></li>
			<li><a href="<?= SITEURL ?>/community/">Community Guidelines</a></li>
			<li><a href="<?= SITEURL ?>/privacy/">Privacy</a></li>
			<li><a href="<?= SITEURL ?>/terms/">Terms of Use</a></li>
			<li><a href="<?= SITEURL ?>/abuse/">Report Abuse</a></li>
			<?php if(strpos($_SERVER["HTTP_USER_AGENT"],"Chrome")) {
				$rsslink = "http://feeds.feedburner.com/workitmom/faZt";
			} else {
				$rsslink = SITEURL."/articles/rss?format=rss";
			}?>
			<li><a href="<?php echo $rsslink;?>">Rss</a></li>
		</ul>

		<p class="fr text-content">&copy; <?php echo date('Y'); ?> <a href="http://www.junemedia.com/" target="_blank">June Media Inc</a> All rights reserved</p>
		</div>
		<div class="clear"></div>

	</div>
	<?php if (DEBUG_INFO && isset($debugInfo)) { echo $debugInfo; } ?>
</div>

	<div id="ad-header">
		<!--/* OpenX Asynchronous JavaScript tag */-->

		<!-- /*
		 * The tag in this template has been generated for use on a
		 * non-SSL page. If this tag is to be placed on an SSL page, change the
		 * 'http://ox-d.junemedia.com/...'
		 * to
		 * 'https://ox-d.junemedia.com/...'
		 */ -->
		 
		 <script type="text/javascript">
        var LB_BTF_Params = {ybot_slot:"LB_BTF", ybot_size:"", ybot_cpm:""};
        try{
            LB_BTF_Params = yieldbot.getSlotCriteria('LB_BTF');
        }catch(e){/*ignore*/}
        </script>

		<div id="537228341_728x90BTF" style="width:728px;height:90px;margin:0;padding:0">
		  <noscript><iframe id="eb1451ebc6" name="eb1451ebc6" src="http://ox-d.junemedia.com/w/1.0/afr?auid=537228341&cb=INSERT_RANDOM_NUMBER_HERE" frameborder="0" scrolling="no" width="728" height="90"><a href="http://ox-d.junemedia.com/w/1.0/rc?cs=eb1451ebc6&cb=INSERT_RANDOM_NUMBER_HERE" ><img src="http://ox-d.junemedia.com/w/1.0/ai?auid=537228341&cs=eb1451ebc6&cb=INSERT_RANDOM_NUMBER_HERE" border="0" alt=""></a></iframe></noscript>
		</div>
		<script type="text/javascript">
		  var OX_ads = OX_ads || [];
		  OX_ads.push({
			 slot_id: "537228341_728x90BTF",
			 auid: "537228341",
			 vars: {"ybot_slot":LB_BTF_Params.ybot_slot, "ybot_size": LB_BTF_Params.ybot_size, "ybot_cpm": LB_BTF_Params.ybot_cpm}
		  });
		</script>

		<script type="text/javascript" src="http://ox-d.junemedia.com/w/1.0/jstag"></script>
		<!-- end openx -->
	</div>

	<script src="http://31870.hittail.com/mlt.js" type="text/javascript"></script>
	<script type="text/javascript" src="http://static.fmpub.net/site/workitmom"></script>

	<?php if (($task == 'view') && (($option == 'index') || ($option == 'blogs') || ($option == 'connect')  || ($option == 'questions') )) { ?>
	<script type="text/javascript" src="http://cetrk.com/pages/scripts/0008/5621.js"></script>
	<?php } ?>
	
	<?php if (!(strstr($_SERVER['REQUEST_URI'],'redbox-keeping-the-family-budget-on-track-one-rental-at-a-time') || 
			strstr($_SERVER['REQUEST_URI'],'craftsy-the-creative-outlet-for-the-busy-moms') || 
			strstr($_SERVER['REQUEST_URI'],'/bloggers/milkandcookies/') || 
			strstr($_SERVER['REQUEST_URI'],'199039') || strstr($_SERVER['REQUEST_URI'],'199051') || 
			$_SERVER['REQUEST_URI'] == '/')) { ?>
		<!-- infolinks tag for WIM -->
		<script type="text/javascript">var infolinks_pid = 1863387;var infolinks_wsid = 2;</script><script type="text/javascript" src="http://resources.infolinks.com/js/infolinks_main.js"></script>
		<!-- infolinks tag for WIM -->
		
	<?php } ?>

<!-- LiveRamp --><iframe name="_rlcdn" width=0 height=0 frameborder=0 src="http://rc.rlcdn.com/381139.html"></iframe><!-- LiveRamp -->
<!-- BEGIN SiteCTRL Script -->
<script type="text/javascript">
if(document.location.protocol=='http:'){
 var Tynt=Tynt||[];Tynt.push('adh8yO_H8r45vlacwqm_6l');
 (function(){var s=document.createElement('script');s.async="async";s.type="text/javascript";s.src='http://tcr.tynt.com/ti.js';var h=document.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);})();
}
</script>
<!-- END SiteCTRL Script -->
<!-- +SWOOP -->
  <script type="text/javascript">
  (function addSwoopOnce(domain) {
    var win = window;
    try {
      while (!(win.parent == win || !win.parent.document)) {
        win = win.parent;
      }
    } catch (e) {
      /* noop */
    }
    var doc = win.document;
    if (!doc.getElementById('swoop_sdk')) {
      var serverbase = doc.location.protocol + '//ardrone.swoop.com/';
      var s = doc.createElement('script');
      s.type = "text/javascript";
      s.src = serverbase + 'js/spxw.js';
      s.id = 'swoop_sdk';
      s.setAttribute('data-domain', domain);
      s.setAttribute('data-serverbase', serverbase);
      doc.head.appendChild(s);
    }
  })('SW-10152718-5');
</script>
<!-- -SWOOP -->
</body>
</html>
