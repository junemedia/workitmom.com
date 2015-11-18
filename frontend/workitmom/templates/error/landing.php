
	<div id="main-content" class="404">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="balancing_act_icon" class="icon fl"></div>
					<h1>Page not found...</h1>
					<div class="text-content">
					
						<p>We're sorry, but the page that you requested is no longer available.
						We will take you to our home page in a few seconds or you can click <a href="http://www.workitmom.com">here</a>.</p>
					
					</div>
					<div>
				<script language="javascript">
				var i = 5;
				window.onload=redirect;
				function redirect()
				{
				    var time = document.getElementById('time');
				    i--;
				    setTimeout("redirect()",1100);
				    if(i<0)
				    {
				    	i = 5;
				    }
				    if(i==0)
				    {
				    	i = 5;
				        location.replace("<?php echo SITEURL.'/search'?>");
				    }
				}
				</script>
				<span id="time">
				</span>
				</div>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="col-l">

			</div>

			<div class="col-r">

			</div>

			<div class="clear"></div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
