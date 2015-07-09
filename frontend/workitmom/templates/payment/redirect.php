
	<div id="redirect-content">
		<div class="content">

			<h1><?= $reason ?>...</h1>

			<div class="text-content">
				<p class="if-no-redirect">If you are not redirected within 15 seconds, <a href="<?= $url ?>">click here</a></p>
			</div>

			<?php Template::startScript() ?>

				(function() {
					window.location = '<?= $url ?>';
				}).delay(3000);

			<?php Template::endScript() ?>

		</div>
	</div>
