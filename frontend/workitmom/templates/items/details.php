  <div id="main-content" class="<?= $cssClass; ?>">

    <div class="panel-left">

      <?php include BLUPATH_TEMPLATES.'/site/ads/connatix_infeed.php'; ?>

      <div id="landing_title" class="block rounded-630-landing">
        <div class="top"></div>

        <?php $this->page_heading(); ?>

        <div class="bot"></div>
      </div>
      <?php if($this->_itemtype == "article") { ?>
        <a class="button_bright fr" style="margin: -60px 10px 0 0" href="<?= SITEURL ?>/contribute/article/"><span>Write an article!</span></a>
      <?php } ?>

      <?= Messages::getMessages(); ?>

      <?php $this->detail_title(); ?>
      <?php if($this->_itemtype == "article") {?>
      <div style="margin-top: 10px; float: left; clear: both;"><?php echo $item->video_js;?></div>
      <?php }?>

      <?php $this->detail_body($page); ?>

      <?php $this->detail_author(); ?>

      <?php $this->detail_share(); ?>

      <?php $this->comments_add(); ?>

      <?php $this->comments_view(); ?>

      <?php BluApplication::getModules('site')->bottom_blocks(); ?>

    </div>

    <div class="panel-right">
      <?php
      // Use ItemsController (or its derived class's) detail page sidebar.
      $this->detail_sidebar();
      ?>
    </div>

    <div class="clear"></div>
  </div>
