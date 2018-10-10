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


<?php include BLUPATH_TEMPLATES.'/site/ads/pubexchange_js.php'; ?>

</body>
</html>
