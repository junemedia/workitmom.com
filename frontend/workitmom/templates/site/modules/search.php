
	<div id="search_block" class="block screenonly">
	
		<?php if ($header){ ?>
		<div class="header">
			<div class="title">
				<h2>Search <?= $header; ?></h2>
			</div>
		</div>
		<? } ?>

		<div class="content">
			<form id="form-search" action="/search/<?= $type; ?>/" method="post" class="search"><div>
				<?php if ($label) { ?><label for="nav-top-search-query">search:</label><? } ?>
				<div class="input-wrapper"><input class="textinput overtext" type="text" title="enter search keywords..." autocomplete="off" name="searchterm" /></div>
				<button class="but-find" type="submit" title="Find"></button>
			</div></form>
			<div class="clear"></div>
		</div>
	</div>