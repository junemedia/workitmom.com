
			<?php if (!empty($articles)){ ?>

				<div class="item_list">
					<ul>
						<? foreach($articles as $article){ $this->activity_articles_individual($article); } ?>
					</ul>
				</div>
				
				<p class="text-content" style="padding-top:10px;"><a href="<?= SITEURL . '/profile/articles/' . $person->username; ?>" class="arrow">See all articles by <?= $person->name; ?>...</a></p>

			<?php } else { ?>
			<div class="message message-info"><?= $person->name ?> has not written any articles recently.</div>
			<?php } ?>