

	<div id="post" <?= $type == 'list' ? 'class="checklist"' : ''?>>

		<? if($type != 'news') { ?>
			<div class="spacer"></div>
		<? } ?>
		

		<? if($type == 'news') { ?>
			
			<h3>
				<a href="<?= $item->xlink; ?>" target="_blank" class="arrow">Read the original story here</a>
			</h3>

		<? } ?>

		<div class="entry">

			<? if($type == 'news') { ?>

				<div class="vote">
					<? if($item->rating['user']) { ?>
						<div class="vote_num voted"><?= $item->votes; ?></div>
						<span><?= $item->votes != 1 ? 'Votes' : 'Vote'; ?></span>
					<? } else { ?>
						<div class="vote_num"><?= $item->votes; ?></div>
						<a href="<?= Uri::build($item); ?>?task=vote&rating=5">Vote!</a>
					<? } ?>
				</div>
			<? } ?>
			
			<?= $pagination->get('content'); ?>

		</div>

    <div style="margin:30px auto 0;">
      <?php include BLUPATH_TEMPLATES.'/site/ads/lockerdome.html'; ?>
    </div>

		<? $this->detail_pullquote(); ?>

		<div class="clear"></div>

		<?= $pagination->get('buttons'); ?>

		<div class="clear"></div>

	</div>

	<div class="divider"></div>
