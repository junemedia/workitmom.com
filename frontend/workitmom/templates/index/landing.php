<?= Messages::getMessages(); ?>

<div id="main-content" class="index">

  <div class="panel-left">
    <?php include(BLUPATH_TEMPLATES.'/index/landing/featured_article.php'); ?>
    <div id="pubexchange_below_content"></div>
  </div>

  <div class="panel-right">
    <?php $this->sidebar(array(
      array('ad_zedo','index'),
      'newsletter',
      'from_our_bloggers',
      'slideshow_featured',
      array('ad_mini','index'),
      'indulge_yourself',
      'catch_your_breath'
    ), false,'home'); ?>
  </div>

  <div class="clear"></div>

  <!-- pubexchange_below_content -->
  <script>
    (function(d, s, id) {
      var js, pjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id; js.async = true;
      js.src = "//cdn.pubexchange.com/modules/partner/work_it_mom";
      pjs.parentNode.insertBefore(js, pjs);
    }(document, "script", "pubexchange-jssdk"));
  </script>

</div>
