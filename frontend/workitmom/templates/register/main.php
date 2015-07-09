
	<div id="main-content" class="signup">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>
				<div class="content">
					<div id="account_icon" class="icon fl"></div>
					<h1>Sign Up</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="rounded-630-outline">
				<div class="top"></div>

				<div class="content">

					<h2>Sign Up to <?= BluApplication::getSetting('storeName'); ?></h2>
					<p class="text-content">
						Your privacy is extremely important to us! All of your information is stored securely, and we'll never share any of it with anyone without your permission - we hate spam just as much as you do! <a href="<?= SITEURL; ?>/privacy/" target="_blank">...read more</a>
					</p>

					<div id="register-stages" class="wizard">
					<?php
						$stageNum = 1;
						foreach ($registerStages as $registerStage) {
							if ($stageNum < $currentStageNum) {
								$status = 'complete';
							} elseif ($stageNum == $currentStageNum) {
								$status = 'current';
							} else {
								$status = 'incomplete';
							}
					?>
						<div class="stage <?= $status ?>" id="<?= $registerStage['id'] ?>">
							<div class="title">
								<h3><?= $registerStage['title'] ?></h3>
								<span class="actions">
									<a href="<?= SITEURL ?>/marketplace/create/<?= $stageNum ?>" class="stage-edit"<?= ($registerStage['edit'] ? '' : ' style="display: none;"') ?>>Edit</a>
								</span>
							</div>
							<div class="stagecontent">
								<?php
									if ($stageNum == $currentStageNum) {
										echo Messages::getMessages();
										$this->view_stage();
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

						/* Wizard handler */
						var wizard = new Wizard($('register-stages'), false, {
							stageDetails: <?= json_encode($registerStages)?>,
							currentStageNum: '<?= $currentStageNum ?>',
							baseUrl: '<?= SITEURL ?>/register'
						});

					<?php Template::endScript() ?>

					<div class="clear"></div>&nbsp;
				</div>

				<div class="bot"></div>
			</div>

			<div class="clear"></div>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array(
				'newsletter',
				'why_sign_up',
				array('ad_mini', $this->_doc->getAdPage())
			), false); ?>
		</div>

		<div class="clear"></div>
	</div>
