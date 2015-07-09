
						<?php if ($isSelf) {?>
						<a class="button_bright fr" style="margin: 0px 10px 0 0" href="<?= SITEURL ?>/account/details"><span>Edit My Details</span></a>
						<?php } ?>
						
						<div class="tab_menu">
							<ul>
							
								<?php foreach(array(
									'blog' => 'Blog',
									'life' => 'Life',
									'family' => 'Family',
									'work' => 'Work'
								) as $key => $name){
									?>
								<li<?= $module == $key ? ' class="on"' : ''; ?>><a<?= $module == $key ? '' : ' href="?info=' . $key . '#profile_module"'; ?> class="scroll"><?= $name; ?></a></li>
									<?php
								}
								?>
							</ul>
							<div class="clear"></div>
						</div>

												
						<div class="holder text-content">
							<?php $this->{'info_' . $module}(); ?>
							<div class="clear"></div>
						</div>