
	<div id="main-content" class="marketplace">
		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="marketplace_icon" class="icon fl"></div>
					<h1>Marketplace</h1>
				</div>
				<div class="bot"></div>
			</div>

			<div class="rounded-630-outline">
				<div class="top"></div>
					<div class="content">

						<h2>Create a Marketplace Listing</h2>

						<div id="create-stages" class="wizard">
						<?php
							$stageNum = 1;
							foreach ($createStages as $createStage) {
								if ($stageNum < $currentStageNum) {
									$status = 'complete';
								} elseif ($stageNum == $currentStageNum) {
									$status = 'current';
								} else {
									$status = 'incomplete';
								}
						?>
							<div class="stage <?= $status ?>" id="<?= $createStage['id'] ?>">
								<div class="title">
									<h3><?= $createStage['title'] ?></h3>
									<span class="actions">
										<a href="<?= SITEURL ?>/marketplace/create/<?= $stageNum ?>" class="stage-edit"<?= ($createStage['edit'] ? '' : ' style="display: none;"') ?>>Edit</a>
									</span>
								</div>
								<div class="stagecontent">
									<?php
										if ($stageNum == $currentStageNum) {
											echo Messages::getMessages();
											$this->create_stage();
										}
									?>
								</div>
								<div class="clear"></div>
							</div>
						<?php
								$stageNum++;
							}
						?>
						</div>

						<?php Template::startScript() ?>

							/* Checkout handler */
							var wizard = new Wizard($('create-stages'), false, {
								stageDetails: <?= json_encode($createStages)?>,
								currentStageNum: '<?= $currentStageNum ?>',
								baseUrl: '<?= SITEURL ?>/marketplace/create',
								stageViewTask: 'create_stage'
							});

						<?php Template::endScript() ?>

						<div class="clear"></div>&nbsp;
					</div>
				<div class="bot"></div>
			</div>
		</div>

		<div class="panel-right" id="create-sidebar">
			<?php $this->create_sidebar(); ?>
		</div>

		<div class="clear"></div>
	</div>
