
	<?php if (!empty($savedArticles)) { ?>
	<div class="item_list">
		<ul>
			<?php
				$alt = true;
				foreach ($savedArticles as $article) {
					$link = SITEURL.'/articles/detail/'.$article['articleID'];
			?>
			<li<?= $alt ?' class="odd"' : '' ?>>
				<div class="actions">
					<a href="<?= $link ?>"><img src="<?= ASSETURL.'/'.$article['imageDirectory'].'images/100/100/1/'.$article['image'] ?>"></a>
				</div>
				<div class="info">
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
	<div class="message message-info">You have not saved any <a href="<?= SITEURL ?>/articles">articles</a> yet!</div>
	<?php } ?>