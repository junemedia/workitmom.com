
	<div id="main-content" class="static">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div class="icon fl"></div>
					<h1>Our Friends</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div id="static_content">

				<p>We're always interested in talking with companies and organizations that support working moms. To speak with us about a possible partnership, please contact us at <a href="mailto:info@workitmom.com" target="_blank">info@workitmom.com</a>.</p>

				<div id="partner_list" class="item_list grid_list">
				<ul>
					<li><a href="http://www.babblesoft.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/babble_soft.gif" /></a></li>
					<li><a href="http://www.bookieboo.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/bookie_boo.jpg" /></a></li>
					<li><a href="http://www.bostonmamas.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/boston_mamas.jpg" /></a></li>
					<li><a href="http://www.careerandkids.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/careerandkids.jpg" /></a></li>
					<li><a href="http://www.chefmama.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/chefmama.gif" /></a></li>
					<li><a href="http://www.downtownwomensclub.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/downtown_womens_club.jpg" /></a></li>
					<li><a href="http://www.freebirthdaystuff.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/free_birthday_stuff.jpg" /></a></li>
					<li><a href="http://www.honestbaby.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/honestbaby.jpg" /></a></li>
					<li><a href="http://www.hybridmom.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/hybrid_mom.jpg" /></a></li>
					<li><a href="http://www.momcorps.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/mom_corps.jpg" /></a></li>
					<li><a href="http://www.mompreneursonline.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/mompreneurs_online.jpg" /></a></li>
					<li><a href="http://www.needlestack.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/needlestack.jpg" /></a></li>
					<li><a href="http://www.ourmilkmoney.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/our_milk_money.gif" /></a></li>
					<li><a href="http://www.pageonce.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/pageonce.jpg" /></a></li>
					<li><a href="http://www.parenthacks.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/parenthacks.jpg" /></a></li>
					<li><a href="http://www.prevention.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/prevention.jpg" /></a></li>
					<li><a href="http://www.recipe4living.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/recipe4living.jpg" /></a></li>
					<li><a href="http://www.sanemoms.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/sane_moms.jpg" /></a></li>
					<li><a href="http://www.swellbeing.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/swellbeing.jpg" /></a></li>
					<li><a href="http://www.thecradle.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/the_cradle.gif" /></a></li>
					<li><a href="http://www.thefamilygroove.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/the_family_groove.jpg" /></a></li>
					<li><a href="http://www.lilliannannyagency.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/the_lillian_nanny_agency.jpg" /></a></li>
					<li><a href="http://www.savvysource.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/the_savvy_source.gif" /></a></li>
					<li><a href="http://www.vickyandjen.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/vickyandjen.jpg" /></a></li>
					<li><a href="http://www.workingmomsagainstguilt.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/wmag.jpg" /></a></li>
					<li><a href="http://www.womenco.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/womenco.jpg" /></a></li>
					<li><a href="http://www.womensjoblist.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/womens_job_list.jpg" /></a></li>
					<li><a href="http://www.hjonesproductions.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/work_at_home_mom_expo.jpg" /></a></li>
					<li><a href="http://www.zwaggle.com/" target="_blank"><img src="<?=ASSETURL?>/partnersimages/160/100/1/zwaggle.jpg" /></a></li>
					<li></li>
				</ul>
				</div>

				<a href="<?= SITEURL ?>/links">See more links we like here &raquo;</a>

				<div class="divider"></div>

			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
