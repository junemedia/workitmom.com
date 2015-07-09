	
	<div id="redirect-content">
		<div class="content">
		
			<h1><?= $reason ?>...</h1>
			
			<div class="text-content">
				<form method="post" action="<?= $res['ACSURL'] ?>" id="3dsecure-form"><div>
					
					<p><?= Template::text('payment_3dsecure_text1') ?></p>
					<p><?= Template::text('payment_3dsecure_text2') ?></p>
					
					<p class="if-no-redirect"><?= Template::text('global_redirect_fallback', array('seconds' => 15, 'link' => '<button type="submit" class="link"><span>', '/link' => '</span></button>')) ?></p>
				
					<input type="hidden" name="PaReq" value="<?= $res['PAReq'] ?>" />
					<input type="hidden" name="TermUrl" value="<?= $url ?>" />
					<input type="hidden" name="MD" value="<?= $res['MD'] ?>" />
				</div></form>
			</div>
			
			<?php Template::startScript() ?>
				
				(function() {
					$('3dsecure-form').submit();
				}).delay(10000);
				
			<?php Template::endScript() ?>
		
		</div>
	</div>
