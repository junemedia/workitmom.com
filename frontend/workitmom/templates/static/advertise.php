
	<div id="main-content" class="static">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div class="icon fl"></div>
					<h1>Advertise</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div id="advertise_content">

				<h2>Work It, Mom! is the leading online community for working moms.</h2>

				<div class="content text-content">
					<p>Our members are extremely engaged within our online community, contributing advice, articles, and support to each other in different ways. They come to Work It, Mom! to connect with other moms in similar situations, get advice about their life, family, work or business, or take a few minutes just for themselves. To augment content contributed by members, Work It, Mom! editorial staff and regular bloggers cover topics of importance to moms juggling work and family.</p>

					<p>Our members and site visitors are working moms who work full or part-time, including those who work outside the home, from home, and run their own businesses. They:</p>

					<ul>
						<li>Are between 25 and 55 years old</li>
						<li>Have incomes of $75K+</li>
						<li>Most have children under the age of 12</li>
						<li>Spend more hours a day on Internet then mothers who don&rsquo;t work</li>
						<li>Do a large portion of their shopping online</li>
						<li>Have influence over home and office budgets</li>
						<li>Located primarily in large metropolitan areas</li>
					</ul>

					<h4>How we can work together:</h4>

					<p>We look forward to working with you to find the best way to feature your product or service to our audience. In addition to traditional banner advertising, we offer content sponsorship and giveaways, email sponsorship, and featured listings in our Marketplace. </p>
					
					<h2>Please send an email to info@workitmom.com to request our media kit and discuss your marketing needs.</h2>

					<table width="100%" cellspacing="0" cellpadding="0" style="margin-top:20px;">
						<thead>
							<tr>
								<th>Unit</th>
								<th>Dimensions</th>
								<th>Max File Size</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Leaderboard</td>
								<td>728 x 90</td>
								<td>25K for images<br/>39K for rich media</td>
							</tr>
							<tr>
								<td>Skyscraper</td>
								<td>120 x 600 or 160 x 600</td>
								<td>25K for images<br/>39K for rich media</td>
							</tr>
							<tr>
								<td>Medium Rectangle</td>
								<td>300 x 250</td>
								<td>25K for images<br/>
								39K for rich media</td>
							</tr>
							<tr>
								<td>Featured Marketplace Listing </td>
								<td>125 x 125</td>
								<td>25K for images</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
