
	<?php if (!empty($ownedArticles)) { ?>
	<div class="item_list">
		<ul>
			<?php
				$alt = true;
				foreach ($ownedArticles as $article) {
					$link = SITEURL.'/articles/detail/'.$article['articleID'];
			?>
			<li<?= $alt ?' class="odd"' : '' ?>>
				<h3><a href="<?= $link ?>" class="title"><?= $article['articleTitle'] ?></a></h3>
				<small><?= $article['articleTeaser'] ?></small>
			</li>
			<?php
					$alt = !$alt;
				}
			?>
		</ul>
	</div>
	<?php } else { ?>
	<div class="message message-info">You have not written any articles. Why not <a href="<?= SITEURL ?>/contribute/article">write one</a> now?</div>
	<?php } ?>