
	<div id="main-content" class="static">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div class="icon fl"></div>
					<h1>Community Guidelines</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div id="static_content" class="content">

				<small>Last updated April 2008</small>

				<h2>Workitmom.com is an online community for working moms.</h2>
				<p>It&rsquo;s not easy to juggle family and career while maintaining your own sanity. We created Work It, Mom! because we believe that having a community where working moms can share their experiences, advice, and support can make that daily juggle a bit more manageable.</p>
				<p>To make sure that our community remains a welcoming and supportive place as we grow and add new members, we&rsquo;ve created these simple community guidelines. If you have any questions or suggestions, please let us know by filling out a simple <a href="/contact">contact form</a>.</p>
				<div class="divider"></div>

				<h2>Work It, Mom! Responsibilities</h2>
				<p>If our community moderators at any time determine that your behavior on Workitmom.com is not inline with our Community Guidelines, we reserve the right to de-activate your account without prior notice.</p>
				<p>As our community grows we are not able to monitor every post and discussion, but if something violates our community guidelines and we learn about it, we will take the appropriate action.</p>
				<p>One of the best ways to keep our community helpful, supportive, and safe is for you, our members, to make it such. If you read something that you find offensive or inappropriate, please report it to us by clicking the Report This link that appears with most types of posts on the site. Our community moderators will review all reported items and will evaluate whether or not they violate our community policy.</p>
				<p>We reserve the right to remove any posting, warn members, de-activate or suspend accounts to enforce our community guidelines and Work It, Mom! Terms of Service. The decision of Work It, Mom! is final and we won&rsquo;t always explain our actions to the offender.</p>
				<p>Our most important goal is to make sure that Workitmom.com is a helpful, safe, engaging, and supportive community &ndash; and all of our actions will be take with this goal in mind.</p>
				<div class="divider"></div>

				<h2>Be respectful to other moms in the community.</h2>
				<p>We&rsquo;re all entitled to our differences but we need to be respectful when discussing them in the community. Don&rsquo;t personally attack another member or post rude comments &ndash; treat other members as you&rsquo;d want to be treated yourself.</p>
				<div class="divider"></div>

				<h2>Slurs or hate speech of any kind will not be tolerated.</h2>
				<p>Slurs, hate speech and attacks aimed at any race, color, religion, national origin, disability or sexual orientation are not tolerated at all on Workitmom.com and will be removed.</p>
				<div class="divider"></div>

				<h2>Don&rsquo;t post the same thing repeatedly or use all CAPS when you post.</h2>
				<p>This is annoying and disrespectful to other members.</p>
				<div class="divider"></div>

				<h2>Don&rsquo;t share personal information about anyone else and be vigilant about sharing your own.</h2>
				<p>Posting personal information about another member or individual violates their privacy. When you choose to share your own personal information on the site, please make sure that you are comfortable with everyone reading it.</p>
				<div class="divider"></div>

				<h2>Don&rsquo;t post something just to upset or inflame other members.</h2>
				<p>Discussion is welcome, posting for the sake of annoying or upsetting someone is not.</p>
				<div class="divider"></div>

				<h2>As a general rule, we do not allow any advertisements, solicitation or promotions in our community, except in the Work It, Mom! Marketplace.</h2>
				<p>The following are examples of advertisements:</p>
				<ul>
					<li> Work at home businesses or opportunities</li>
					<li>Job postings</li>
					<li>Contests you or another company is holding</li>
					<li>Promotion of your own website, blog, or service or a website which you represent</li>
					<li>Products or books you are selling</li>
					<li>Asking for other members to vote for you in a certain contest or competition online</li>
					<li>Events or fundraisers</li>
				</ul>
				<p>We will delete posts containing these advertisements and if you post repeatedly, will suspend your account. You may include such advertisements on your profile page, in our Marketplace, in groups that allow advertising and promotion (which will be specifically stated in the group introduction).</p>
				<p><strong>If you suspect that someone on the site is not legitimate or is engaged in illegal activities, use our <a href="/contact">contact form</a> and let us know so that we can take appropriate actions.</strong></p>
				<div class="divider"></div>
			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
