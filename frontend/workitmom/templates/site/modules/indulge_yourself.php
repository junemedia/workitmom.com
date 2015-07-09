<div id="indulge_yourself" class="block rounded-300-outline">
	<div class="top"></div>

	<div class="content">
		
		<div class="header">
			<div class="title">
				<h2><a href="<?= $affordable['path']; ?>"><?= $affordable['title']; ?> Blog</a></h2>
			</div>
			<div class="clear"></div>
		</div>
		
		<ul>
			<?php foreach($posts as $luxury) { ?>
			<li><a href="<?= $luxury['url']; ?>"><?= $luxury['title']; ?></a></li>
			<?php } ?>
		</ul>

	</div>
	
	<div class="bot"></div>
</div>
