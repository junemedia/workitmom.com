
	<div id="main-content" class="static">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div class="icon fl"></div>
					<h1>Unsubscribe</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div id="advertise_content">
				<div class="content text-content">
					<?php
						$iframe_url = "http://wim.popularliving.com/subctr/unsub/wim/entry.php?listid=".$_REQUEST['lid']."&jobid=".$_REQUEST['jid']."&email=".$_REQUEST['e'];
						
						if (isset($_REQUEST['e'])) {
							setcookie("EMAIL_ID", $_REQUEST['e'], time()+642816000, "/", ".workitmom.com");
							echo "<img src='http://jmtkg.com/plant.php?email=".$_REQUEST['e']."' width='0' height='0'></img>";
						}
					?>
					<iframe frameborder="0" scrolling="No" width="575" height="700" id="new_unsubscribe" src="<?php echo $iframe_url; ?>"></iframe>
				</div>
			</div>

			<?php //BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
