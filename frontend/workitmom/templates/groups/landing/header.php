
	<div class="col-l">
		<div class="rounded-300-orange" id="sub_header">
			<div class="top"></div>

			<div class="content">
				<h2>Find Support &amp; Advice</h2>
				<h3>Talk with working moms who share your interests, career, family situation, or live near you!</h3>
						<img src="<?= ASSETURL; ?>/landingimages/0/0/1/landing_group.jpg"/>

			</div>

			<div class="bot"></div>
		</div>
	</div>
	<div class="col-r">
		<?php include(BLUPATH_TEMPLATES.'/groups/blocks/search.php'); ?>

		<div class="divider"></div>

		<?php if (!empty($popularGroups)) { ?>
		<div class="content most_popular">
			<h4>This Week's Most Popular</h4>
			<ul>
				<?php foreach($popularGroups as $group) { ?>
				<li><a href="<?= SITEURL ?>/groups/detail/<?= $group['id'] ?>/"><?= $group['name'] ?></a></li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
		<div class="divider"></div>

		<h4>New here? <a href="<?= SITEURL ?>/groups/detail/344/">Join</a> our New to Work It, Mom! Group!</h4>		
	</div>
	<div class="clear"></div>
	<div class="divider"></div>