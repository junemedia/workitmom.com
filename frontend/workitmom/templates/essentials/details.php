
	<div id="main-content" class="<?= $cssClass; ?>">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<?php $this->page_heading(true); ?>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<!-- BEGIN CONTENT -->
			<div id="essentials_content" class="rounded-630-outline block">

				<div class="top"></div>

				<div class="content">
					
					<h2 class="title"><?= $item->title; ?></h2>
					<div class="clear"></div>

					<div class="links">

					<?php if (Utility::is_loopable($mainLinks)){ foreach($mainLinks as $type => $typeLinks){
						$this->detail_essentials_links($type, $typeLinks);
					} } ?>

					</div>

				</div>

					<?php if (Utility::is_loopable($sideLinks)){ ?>

						<div class="sidebar">
							<img alt="Recipes and Cooking" src="<?php Template::image($item, 190); ?>" />
							<div class="quicktip_checklist_box content">

								<h3><?= implode(' / ', $sideTypes); ?></h3>

								<ul class="text-content">
									<?php if (Utility::is_loopable($sideLinks)) { foreach($sideLinks as $link){ ?>
									<li><a href="<?= $link['linkUrl']; ?>"><?= $link['linkTitle']; ?></a></li>
									<? } } ?>
								</ul>

							</div>
						</div>
						
					<?php } ?>

				<div class="clear"></div>
				<div class="bot"></div>
			</div>
			<!-- END -->


		</div>

		<div class="panel-right">
			<?php $this->detail_sidebar(); ?>
		</div>

		<div class="clear"></div>
	</div>