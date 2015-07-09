
	<div id="main-content" class="static">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div class="icon fl"></div>
					<h1>Submission Guidelines</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div id="about_content">

				<p>Thanks for your interest in contributing to Work It, Mom! Our submission guidelines are simple and we've outlined them below. If you have a specific question, please feel free to get in touch with us.</p>

				<h2>Submitting an article to be published on Workitmom.com</h2>
				<p>To submit an article or a personal essay to be published on Workitmom.com please use our simple submission form. Please do not email your articles to us as we will not be able to review them that way.</p>
				<div class="divider"></div>

				<h2>Types of essays and articles</h2>
				<p>Work It, Mom! is about real working moms and their real voices.</p>
				<p>Writing an article or a personal essay is a great way to share your expertise, advice, personal experiences or opinions. Do you have some advice about finding a flexible job situation, making a living as a freelancer, or changing careers? Share it! Or write a personal essay about an experience you've had as a working mom or talk about what you do to juggle work and family.</p>
				<p>While we love original content, if you have an article or a blog post that you&rsquo;d like to turn into an article that&rsquo;s relevant and interesting to our audience of working moms, we&rsquo;d love to consider it for publication.</p>
				<div class="divider"></div>

				<h2>Rights, warranties, and exclusivity</h2>
				<p>Your work is your work and you retain the copyright to all articles you submit for publication on Workitmom.com. You&rsquo;re free to publish your article on your personal blog, website or other third-party sites if you&rsquo;d like.</p>
				<p>By submitting an article to Workitmom.com you warrant that you are the rightful copyright owner of your article.</p>
				<p>By submitting your article for publication, you grant to us the perpetual, non-exclusive, royalty-free, worldwide license to publish, display, excerpt (including for advertising or promotional purposes), and feature your articles on workitmom.com and any of our partner sites. If your article is published and displayed on a partner site of Workitmom.com, you will always be clearly identified as the article&rsquo;s author.</p>
				<p>By submitting your article for publication on Workitmom.com you warrant that your article does not contain abusive, vulgar, libelous, or defamatory material or violate any intellectual property or other laws.</p>
				<p>By submitting your article to Workitmom.com you agree to indemnify us and hold us harmless from any loss, damage or expense that we may suffer due to a breach by you of any of the above warranties.</p>
				<div class="divider"></div>

				<h2>Editorial policy</h2>
				<p>For the most part, we will make only minor edits to your writing, including correcting some grammar or spelling mistakes. However, occasionally, our editors will rework certain parts of your article to ensure that it is easy to read and relevant for our audience. If large portions of your article need to be edited you will be notified by email by one of our editors prior to your article&rsquo;s publications.</p>
				<p>On very rare occasions, we will find an article or essay that, while having merits in its own right, will not meet the mission or focus of Work It, Mom! In those rare cases, we will not publish the submission. If this happens, we will notify you by email. We reserve the right to reject any article submitted for publication to Workitmom.com.</p>
				<p>If someone knowingly or unknowingly submits copyrighted material we will remove it from the site as soon as we become aware of it.</p>
				<div class="divider"></div>

				<h2>Timing of Publication</h2>
				<p>We will aim to publish your article on Workitmom.com within 2 business days of submission. In a rare case that it takes longer, we hope that you will be patient with us.</p>
				<div class="divider"></div>

				<h2>Self-Promotion</h2>
				<p>If you're an expert in a certain area, have a related business, website, blog, or book, please include it in your byline that will appear with each article you submit. Please don't use articles as a way to promote your business or your work -- shameless self-promotion does not make for good content and we will not publish articles that don&rsquo;t offer much beyond that.</p>
				<div class="divider"></div>

				<h2>Payment</h2>
				<p>We currently do not pay members for submitting articles. We hope that sharing your knowledge and experiences with other professional moms will be worth your time. If you have a website, a blog, have published a book or run a business, you're welcome to include it in your byline and raise awareness for it by authoring articles. By submitting your article to Workitmom.com you warrant that we have no obligation to compensate you for your submission.</p>
				<p>At this time, we are not looking for new bloggers. However, if you are interested in becoming a regular columnist for Work It, Mom!, please <a href="<?= SITEURL ?>/contact">get in touch with us</a>.</p>
				<div class="divider"></div>

			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
