
	<?= Messages::getMessages(); ?>

	<div id="main-content" class="index">

		<div class="panel-left">

			<?php include(BLUPATH_TEMPLATES.'/index/landing/featured_article.php'); ?>
			
			<!--video js-->
			<?php /*?><div class="panel-video">
				<div class="header">
					<div class="title">
						<img src="<?php echo '/frontend/workitmom/images/site/videoicon.png';?>"/><h2><a href="#">featured videos</a></h2>
					</div>

					<a href="/articles/" class="button_dark"><span>See All</span></a>
	
					<div class="clear"></div>
				</div>
				<div class="video_content">
					<script type="text/javascript" src="http://pshared.5min.com/Scripts/PlayerSeed.js?sid=179&amp;width=570&amp;height=351&amp;videoGroupID=158978&amp;sequential=1&amp;shuffle=1"></script>
				</div>
			</div>
			<div class="divider"></div>
				<!-- Start VSW Ad Block -->
					<style type="text/css">
								body .era_ad_block.thumbnail  {margin:-5px !important;}
								body .era_ad_block.thumbnail .vsw-ad-item:hover .vsw-ad-title {font-family: Arial,Helvetica,sans-serif !important; color: #FFA708 !important;}
								body .era_ad_block.thumbnail .vsw-ad-item:hover .vsw-ad-title {font-family: Arial,Helvetica,sans-serif !important;}
								body .era_ad_block.thumbnail .vsw-ad-header {font-weight: bold !important; color: #2798bd !important; font-family: Arial,Helvetica,sans-serif !important;}
								body .era_ad_block.thumbnail .vsw-ad-item:hover .vsw-ad-domain {color: #FFA708 !important;}
								</style>
					<script type="text/javascript" language="JavaScript">
					var era_rc = {
					   ERADomain: 'as.vs4family.com',
					   PubID: 'workitmom',
					   Layout: 'thumbnail',
					   MaxRelatedItems: '8',
					   BlockID: 'thumbnail',
					   SearchWidgetPosition: '0',
					   SearchBoxCaption: 'Find More ...',
					   HeaderText: 'You Might Like'
					};
					(function(){var v='ERA_AD_BLOCK';var i=1;while(document.getElementById(v)){if(i==25)break;v='ERA_AD_BLOCK'+i++;}document.write("<"+"div id='"+v+"'><"+"/div>");
					var sch=(location.protocol=='https:'?'https':'http');var host=sch=='http'?'as.ntent.com':'secure.ntent.com';var s=document.createElement('script');var src=sch+"://"+host+"/ERALinks/era_rl.aspx?elid="+v;for(var p in era_rc)
					{if(era_rc.hasOwnProperty(p)){src+=decodeURIComponent('%26')+p.toLowerCase()+"="+encodeURIComponent(era_rc[p]);}};s.src=src;document.getElementsByTagName("head")[0].appendChild(s);})();
					</script>
					<!-- END NTENT ADS -->			
			<div class="divider"></div>
			
			<div class="col-l">

				


				<?php //include(BLUPATH_TEMPLATES.'/index/landing/get_the_essentials.php'); ?>

			</div>

			<div class="col-r"></div>
			<div class="clear"></div>
			<?php */?>
			<?php //BluApplication::getModules('site')->bottom_blocks(); ?>
			<div id="pubexchange_below_content"></div>
		</div>

		<div class="panel-right">
			<?php $this->sidebar(array(
				array('ad_zedo','index'),
				'newsletter',
				'from_our_bloggers',
				'slideshow_featured',
				array('ad_mini','index'),
				'indulge_yourself',
				'catch_your_breath'
			), false,'home'); ?>
			
		</div>

		<div class="clear"></div>
		<!-- pubexchange_below_content -->
		<script>(function(d, s, id) {
  var js, pjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id; js.async = true;
  js.src = "//cdn.pubexchange.com/modules/partner/work_it_mom";
  pjs.parentNode.insertBefore(js, pjs);
}(document, "script", "pubexchange-jssdk"));</script>

	</div>
