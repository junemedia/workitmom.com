
	<div class="block rounded-300-outline">
		<div class="top"/></div>

		<div class="content">
		
			<h2>More about Work It, Mom!</h2>

			<ul class="text-content">
				<?php
				$staticPages = array(
					'/index/team' => 'Team',
					'/index/advisors/' => 'Advisors',
					'/index/press' => 'Press'					
				);
				foreach($staticPages as $link => $name){ ?>
				<li><a href="<?= SITEURL; ?><?= $link; ?>"><?= $name; ?></a></li>
				<?php } ?>
			</ul>

		</div>

		<div class="bot"></div>
	</div>