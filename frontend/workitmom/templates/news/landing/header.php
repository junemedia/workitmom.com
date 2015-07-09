<div class="col-l">

	<div class="rounded-300-orange" id="news_story">
		<div class="top"></div>
		
		<div class="content">
			<h2>Submit a news story!</h2>
			<h3>
				Did you read something that you think other members in the Work It, Mom! community would be interested in? Share it here! 
				<br /><br />
				It can come from an online news outlet, a website, or a blog, but please make sure that it is relevant and interesting to our community of working moms. 
			</h3>
			<a href="<?= SITEURL; ?>/contribute/news/" class="button_dark fl"><span>Submit your news story</span></a>
			<div class="clear"></div>
		</div>
		
		<div class="bot"></div>
	</div>
	
</div>

<div class="col-r">

<div id="search_block" class="block">

	
	
	<div class="most_popular">
	
		<? /* if(Utility::is_loopable($mostvoteditemsthisweek)) { ?>
			<h4>this week's most popular</h4>
			<ul>
				<? foreach($mostvoteditemsthisweek as $item) { ?>
					<li><a href="<?= Uri::build($item); ?>"><?= $item->title; ?></a></li>
				<? } ?>
			</ul>
			<div class="divider"></div>
		<? } */ ?>
		
		<? if(Utility::is_loopable($mostvoteditemsthismonth)) { ?>
			<h4>this month's most popular</h4>
			<ul>
				<? foreach($mostvoteditemsthismonth as $item) { ?>
					<li><a href="<?= Uri::build($item); ?>"><?= $item->title; ?></a></li>
				<? } ?>
			</ul>
		<? } ?>
	
	</div>
	<div class="divider"></div>
	<div class="header">
		<div class="title">
			<h2>Search News Stories</h2>
		</div>
	</div>

	<div class="content">
		<form id="groups-form-search" action="/search?type=news" method="post" class="search"><div>
			<div class="input-wrapper"><input class="textinput overtext" type="text" alt="enter search keywords..." autocomplete="off" name="search" /></div>
			<button class="but-find" type="submit" title="Find"></button></div>
		</form>
		<div class="clear"></div>
	</div>
	
	

</div>

	

</div>

<div class="clear"></div>
			
<div class="divider"></div>

