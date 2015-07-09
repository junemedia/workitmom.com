
	<?

	// Uses $homepageArticles, which should be an object with variables:
	// 	major: a single stdClass object, with your data.
	//	minors: an array of two (or less) stdClass objects, each of which contain your other data.

	?><div id="featured_article" class="block rounded-630-blue">
		<div class="top"></div>

		<div class="content">
			<div class="img"><a href="<?=$homepageArticles->major->url;?>"><img src="<?=ASSETURL;?>/landingimages/184/252/3/<?=$homepageArticles->major->image;?>"></a></div>
			<div class="body">
				<h2 style="height:45px;"><a href="<?=$homepageArticles->major->url;?>"><?=$homepageArticles->major->title;?></a></h2>
				<p><?=strip_tags($homepageArticles->major->body, '<strong><em><u>');?><br /><a href="<?=$homepageArticles->major->url;?>" class="arrow">Keep reading...</a></p>

				<ul>
					<?
					if (Utility::is_loopable($homepageArticles->minors)){
						foreach($homepageArticles->minors as $hp){
						?>
					<li><a href="<?=$hp->url;/* This is an absolute URL */?>"><?=$hp->title;?></a></li>
						<?
						}
					}
					?>
				</ul>

			</div>
			<div class="clear"></div>

		</div>

		<div class="bot"></div>
	</div>
