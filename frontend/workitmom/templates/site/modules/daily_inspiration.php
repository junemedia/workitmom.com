<div id="daily_inspiration" class="block">
	
	<div class="header">
		<div class="title">
			<h2>Daily Inspiration</h2>
		</div>
		
		<div class="clear"></div>
	</div>
	
	<div class="content">
		<? foreach($dailyInspiration as $d) { ?>
			<h3><a href="<?= SITEINSECUREURL . '/' . $d['inspirationLink'] ?>"><?= $d['inspiration'] ?></a></h3>
			<ul class="text-content underline">
				<li><a href="<?= SITEINSECUREURL . "/" . $d['link'] ?>"><?= $d['title'] ?></a></li>
			</ul>
		<? } ?>
	</div>

</div>