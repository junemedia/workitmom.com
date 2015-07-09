
	<div id="main-content" class="account">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="article_icon" class="icon fl"></div>
					<h1>Member Articles</h1>
					<h2>By <?= $person->name ?></h2>
				</div>
				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<?php if (!empty($articles)) { ?>
			<div class="item_list">
				<ul>
					<?php
						$alt = true;
						foreach ($articles as $article) {
							$link = SITEURL.'/articles/detail/'.$article['articleID'];
					?>
					<li<?= $alt ?' class="odd"' : '' ?>>
						<a href="<?= $link ?>" class="img">
							<img src="<?php Template::image($article, 60); ?>">
						</a>
						<div class="body">
							<h3><a href="<?= $link ?>" class="title"><?= $article['articleTitle'] ?></a></h3>
							<small><?= $article['articleTeaser'] ?></small>
						</div>
						<div class="clear"></div>
					</li>
					<?php
							$alt = !$alt;
						}
					?>
				</ul>
			</div>
			<?php } else { ?>
			<div class="message message-info"><?= $person->name ?> has not written any articles.</div>
			<?php } ?>

		</div>
		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
