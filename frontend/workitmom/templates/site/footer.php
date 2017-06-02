  </div><!-- /#content-wrapper -->

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
        <li><a href="<?= SITEURL ?>/press/">Press</a></li>
        <li><a href="<?= SITEURL ?>/advertise/">Advertise</a></li>
        <li><a href="<?= SITEURL ?>/partners/">Our Friends</a></li>
        <li><a href="<?= SITEURL ?>/submission/">Submission Guidelines</a></li>
        <li><a href="<?= SITEURL ?>/community/">Community Guidelines</a></li>
        <li><a href="<?= SITEURL ?>/privacy/">Privacy</a></li>
        <li><a href="<?= SITEURL ?>/terms/">Terms of Use</a></li>
        <li><a href="<?= SITEURL ?>/abuse/">Report Abuse</a></li>

        <?php
        if (strpos($_SERVER["HTTP_USER_AGENT"], "Chrome")) {
          $rsslink = "http://feeds.feedburner.com/workitmom/faZt";
        }
        else {
          $rsslink = SITEURL."/articles/rss?format=rss";
        }?>
        <li><a href="<?php echo $rsslink;?>">Rss</a></li>
      </ul>

      <p class="fr text-content">&copy; <?php echo date('Y'); ?> <a href="http://www.junemedia.com/" target="_blank">June Media Inc</a> All rights reserved</p>
    </div>
    <div class="clear"></div>

  </div><!-- /#footer -->
  <?php if (DEBUG_INFO && isset($debugInfo)) { echo $debugInfo; } ?>
</div><!-- /#site-wrapper -->

<div id="ad-header">
  <script type="text/javascript">
    var LB_BTF_Params = { ybot_slot:"LB_BTF", ybot_size:"", ybot_cpm:"" };
    try {
      LB_BTF_Params = yieldbot.getSlotCriteria('LB_BTF');
    }
    catch(e) {/*ignore*/}
  </script>

  <!--/* OpenX Asynchronous JavaScript tag */-->
  <div id="537228341_728x90BTF" style="width:728px;height:90px;margin:0;padding:0">
    <noscript> <iframe id="78f4885284" name="78f4885284" src="//junemedia-d.openx.net/w/1.0/afr?auid=537228341&cb=INSERT_RANDOM_NUMBER_HERE" frameborder="0" scrolling="no" width="728" height="90"> <a href="//junemedia-d.openx.net/w/1.0/rc?cs=78f4885284&cb=INSERT_RANDOM_NUMBER_HERE" > <img src="//junemedia-d.openx.net/w/1.0/ai?auid=537228341&cs=78f4885284&cb=INSERT_RANDOM_NUMBER_HERE" border="0" alt=""> </a> </iframe> </noscript>
  </div>
  <script type="text/javascript">
    var OX_ads = OX_ads || [];
    OX_ads.push({
      slot_id: "537228341_728x90BTF",
      auid: "537228341",
      vars: { "ybot_slot":LB_BTF_Params.ybot_slot, "ybot_size": LB_BTF_Params.ybot_size, "ybot_cpm": LB_BTF_Params.ybot_cpm }
    });
  </script>

  <script type="text/javascript" src="//junemedia-d.openx.net/w/1.0/jstag"></script>
  <!-- end openx -->
</div><!-- /#ad-header -->

<script src="http://31870.hittail.com/mlt.js" type="text/javascript"></script>
<script type="text/javascript" src="http://static.fmpub.net/site/workitmom"></script>

<?php
  if (($task == 'view') && (($option == 'index') ||
                            ($option == 'blogs') ||
                            ($option == 'connect') ||
                            ($option == 'questions') )) { ?>
    <script type="text/javascript" src="http://cetrk.com/pages/scripts/0008/5621.js"></script>
<?php } ?>

<?php include BLUPATH_TEMPLATES.'/site/ads/tynt.php'; ?>
<?php include BLUPATH_TEMPLATES.'/site/ads/swoop.php'; ?>
<?php include BLUPATH_TEMPLATES.'/site/ads/underdog.php'; ?>
<?php include BLUPATH_TEMPLATES.'/site/ads/liveramp.php'; ?>

</body>
</html>
