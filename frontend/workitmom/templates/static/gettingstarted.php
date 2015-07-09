
	<div id="main-content" class="static">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div class="icon fl"></div>
					<h1>Getting Started</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div id="about_content">

				<p>There are so many great ways to become involved with the Work It, Mom! community that we thought we might point out a few in case you need a place to start.</p>

				<ul id="getting-started-list">
					<li>
						<h2><a href="<?= SITEURL ?>/account/register">Register to become a member</a></h2>
						<p>You don't have to register to browse the site, read member articles, group discussions or blogs. But if you'd like to get involved in the community, invite other members to your network, comment on articles, join group discussions, write member notes or ask a question in our Q&amp;A, you have to become a member of Work It, Mom!.  It takes just a minute and is absolutely free.<br />
						<a href="<?= SITEURL ?>/account/register">To join our community, click here!</a></p>
					</li>

					<li>
						<h2><a href="<?= SITEURL ?>/account">Write a blog, reflect on your day's highlights, or create your Life To-Do List</a></h2>
						<p>We know you're busy so we've created a few quick ways for you to catch your breath, reflect on your day, and focus on what you want to get out of your life.</p>
						<p>In your account, you will find a module called My Day - feel free to answer as many or as few questions daily as you like and decide whether you want to share your reflections with the Work It, Mom! community or just keep them for your Work It, Mom! friends.</p>
						<p>You can easily keep track of your goals and projects in the My Life To-Do module in your account.</p>
						<p>To write a member blog, you can just start typing it in your My Blog module, located in your account. A note is like a quick journal entry - you can write about something you want to remember or vent about something that's stressing you out.<br />
						<a href="<?= SITEURL ?>/account">Check out these great tools in your account now!</a></p>
					</li>

					<li>
						<h2><a href="<?= SITEURL ?>/blogs">Read and comment on member articles or member notes</a></h2>
						<p>One of the best ways to get involved in the Work It, Mom! community and meet other members is by reading member articles and member notes and leaving a comment for the member who wrote them. It's always nice for members to hear words of support or relevant comments on their writing and we've found that this is one of the best ways to make friends here at Work It, Mom!<br />
						</p>
						<p>Writing an article is a great way to contribute to our community and to share your thoughts, ideas, experiences as a working mom or helpful tips with other members. Our online submission form is easy to use and your article will be published in 24-48 hours.<br />
						<a href="<?= SITEURL ?>/contribute/article">Click here to start writing.</a></p>
					</li>

					<li>
						<h2><a href="<?= SITEURL ?>/groups">Join in on group discussions</a></h2>
						<p>There are groups on Work It, Mom! for everyone from book fanatics to entrepreneurs, freelancers, moms who are committed to a greener lifestyle and moms who want to talk about work fashion. And if you don't find a group that fits your interests you can create one in just a minute and invite your friends to join.<br />
						<a href="<?= SITEURL ?>/groups">Click here to browse through Work It, Mom! groups and find one for you!</a></p>
					</li>

					<li>
						<h2><a href="<?= SITEURL ?>/questions">Ask or answer questions in our Q&amp;A</a></h2>
						<p>Our Q&amp;A area is extremely popular with members and it's a great place to get answers to any questions you might have. If you see a member question and have a comment or an answer, be a good friend and post it as a response.<br />
						<a href="<?= SITEURL ?>/questions">Check out the latest questions in the Q&amp;A!</a></p>
					</li>

					<li>
						<h2><a href="<?= SITEURL ?>/blogs">Check out Work It, Mom! Blogs</a></h2>
						<p>Our bloggers write about topics of interest to working moms, like putting together a great work wardrobe, survive as a freelance mom who works from home, or juggle having a large family and a full-time job outside the home.  They love comments from members so <a href="<?= SITEURL ?>/blogs">head over there and check them out!</a></p>
					</li>
				</ul>

				<p>There are many other things to do on Work It, Mom! but we wanted to put together a quick list to get you started. If you ever have any questions, please email us at info@workitmom.com and we'll try to get back to you as soon as we can.</p>

				<p>Thanks for being part of Work It, Mom!</p>

				<p>Nataly &amp; Victoria<br />Co-Founders of Work It, Mom!</p>

			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
		<?php $this->sidebar(array('slideshow_featured', 'marketplace', 'catch_your_breath')); ?></div>

		<div class="clear"></div>
	</div>
