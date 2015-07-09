
	<div class="rounded-300-blue block" id="my_life_todolist">
		<div class="top"></div>
		<div class="content">
			<div class="header">
				<div class="title">
					<h2>My Life To-Do List</h2>
				</div>
			</div>

			<div class="standardform">
				<div class="formholder">
					<form action="<?= SITEURL.'/account' ?>" method="post"><div>

						<?php foreach ($todoList as $item) { ?>

						<label for="myday_<?= $item['myId'] ?>"><?= $item['myText'] ?></label>
						<textarea class="textinput" name="myday[<?= $item['myId'] ?>]" id="myday_<?= $item['myId'] ?>"><?= $item['myAnswer'] ?></textarea>

						<?php } ?>

						<label class="check"><input type="checkbox" class="check" name="public"<?php if ($item['myPublic'] !== '0') { ?> checked="checked"<?php } ?> value="1" />
							Make my life to-do list public?</label>
						<div class="clear"></div>

						<button name="submit" class="submit" type="submit"><span>Save</span></button>

						<input type="hidden" name="task" value="myday_save" />
					</div></form>
				</div>
			</div>
		</div>
		<div class="bot"></div>
	</div>
