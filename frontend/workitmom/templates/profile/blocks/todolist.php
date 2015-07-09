<?

// Uses $todoList, which should be an array.

?>				<div class="rounded-300-blue block" id="my_life_todolist">
					<div class="top"></div>
					<div id="submit_content" class="content">
						<div class="header">
							<div class="title">
								<h2>My Life To-Do List</h2>
							</div>
						</div>
						
					<?php if(Utility::is_loopable($todoList)) {
						$noanswers = true; ?>
					
						<ul class="invisible">
							<?php foreach($todoList as $todo){
								if($todo['myAnswer']) {
									$noanswers = false;
									$this->to_do_list_individual($todo);
								}
							} ?>
						</ul>
						<?php if($noanswers) { ?><p class="text-content"><?= $isSelf ? 'You haven\'t' : $person->name . ' hasn\'t'; ?> written a life to-do list yet.</p><?php } ?>
					
					<?php } else { ?><p class="text-content"><?= $isSelf ? 'You haven\'t' : $person->name . ' hasn\'t'; ?> written a life to-do list yet.</p><?php } ?>
						
					</div>
					<div class="bot"></div>
				</div>
