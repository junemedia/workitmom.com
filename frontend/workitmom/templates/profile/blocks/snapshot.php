
				<div class="rounded-300-orange block" id="my_day_snapshot">
					<div class="top"></div>
					<div id="submit_content" class="content">
						<div class="header">
							<div class="title">
								<h2>My Day Snapshot</h2>
							</div>
							<div class="clear"></div>
						</div>
						
						<?php if($snapshotdate == 0) { ?>
						
							<p class="text-content"><?= $isSelf ? 'You haven\'t' : $person->name . ' hasn\'t' ;?> written a snapshot yet.</p>
							
						<?php } else { ?>
							
							<div class="text-content" style="margin-bottom: 7px"><?= Text::trim(Template::time($snapshotdate, false, true)) ?></div>
							<ul class="invisible">
								<?php
									$noanswers = true;
									foreach($mostrecentsnapshot as $mostrecentdetail){
										if ($mostrecentdetail['myAnswer']) {
											$noanswers = false;
								?>
								<li>
									<h3><?= $mostrecentdetail['myText'] ?>:</h3>
									<?php
										// Stressmeter (hack until we re-model)
										if ($mostrecentdetail['myId'] == 11) {
											$v = (int)$mostrecentdetail['myAnswer'];
											$p = ($v-1)/100;
											$rgb = Color::hsvToRgb(array(((1-$p)*90), 1, 200));
											$hex = Color::rgbToHex($rgb);
									?>
									<div class="stressslider" style="background: <?= $hex ?>;">
										&nbsp;<div class="knob" style="left: <?= (270 / 100) * $v ?>px;"></div>
									</div>
									<?php } else { ?>
									<p class="text-content"><?= nl2br($mostrecentdetail['myAnswer']) ?></p>
									<?php } ?>
								</li>
								<?php
										}
									}
								?>
							</ul>
							
						<?php if($noanswers) { ?>
							<p class="text-content"><?= $isSelf ? 'You deleted your' : $person->name . ' deleted their' ;?> most recent snapshot.</p>
						<?php } ?>
						
					<?php } ?>
					
					</div>
					<div class="bot"></div>
				</div>
