	<?php /*?><img src="<?= ASSETURL . "/blogheaderimages/0/0/1/";?><?=$blogInfo['blogImage']?>" alt="<?=$blogInfo['blogTitle']?>" style="border: 0px; margin: 0px; padding: 0px" usemap="#blogheadmap" />                        
	<map id="blocheadmap" name="blogheadmap">
		<?=$blogInfo['blogImageMap']?>
	</map><?php */?>
				<div id="article_title" class="rounded-630-orange">
					<div class="header_icon"><img src="<?= ASSETURL; ?>/blogheaderimages/45/45/1/sm_<?= $imageUrl; ?>" /></div>
					<div class="top"></div>
					<div class="content">
						<!--INFOLINKS_OFF-->
						<?php if ($imagemapUrl) { ?>
						<img class='blog-banner' src="<?= ASSETURL; ?>/blogheaderimages/0/0/1/<?= $imagemapUrl; ?>" />
						<?php } else { ?>
						<div class="img"><img src="<?= ASSETURL; ?>/<?= $headImage['imageType']; ?>images/100/100/3/<?= $headImage['image']; ?>" /></div>
						<div class="body">
							<div class="header">
								<h2><a href="<?= $link; ?>"><?= $title; ?></a></h2>
								<p>with <?= $author; ?></p>
							</div>
							<p class="text-content"><?= $description; ?></p>
						</div>
						<?php } ?>
						<div class="clear"></div>
						<!--INFOLINKS_ON-->
					</div>
					<div class="bot"></div>
				</div>
