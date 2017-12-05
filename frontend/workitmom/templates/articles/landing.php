  <div id="main-content" class="article">

    <div class="panel-left">

      <div id="landing_title" class="block rounded-630-landing">
        <div class="top"></div>

        <?php $this->page_heading(); ?>

        <div class="bot"></div>
      </div>
      <a class="button_bright fr" style="margin: -60px 10px 0 0" href="<?= SITEURL ?>/contribute/article/"><span>Write an article!</span></a>

      <?= Messages::getMessages(); ?>

      <? include(BLUPATH_TEMPLATES.'/articles/landing/header.php'); ?>

      <h2>Member Articles</h2>

      <div id="articles_listing">

        <?php Template::startScript(); ?>
          var articlesListing = new BrowseArea('articles_listing');
        <?php Template::endScript(); ?>

        <?php $this->listing(); ?>

      </div>

      <?php BluApplication::getModules('site')->bottom_blocks(); ?>

    </div>

    <div class="panel-right">
    <?php $this->sidebar(array('article_write','slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

    <div class="clear"></div>
  </div>
