
			<div class="rounded-630-outline block" id="recent_activity">
				<div class="top"></div>
				<div class="content">
				
					<div class="header">
						<div class="title"><h2>Recent Activity</h2></div>
					</div>
					<div class="tab_menu" class="block">
						<ul>
							
							<?php foreach(array(
								'articles' => 'Articles',
								'questions' => 'Questions',
								'groups' => 'Groups',
								'discussions' => 'Discussions'
							) as $key => $name){
								?>
							<li<?= $activity == $key ? ' class="on"' : ''; ?>><a href="?activity=<?= $key; ?>#recent_activity" class="scroll"><?= $name; ?></a></li>
							<?php } ?>
							
						</ul>
						<div class="clear"></div>
					</div>
					
					<?php $this->{'activity_' . $activity}(); ?>
					
					<div class="clear"></div>
				</div>
				<div class="bot"></div>
			</div>


