
	<div id="main-content" class="static">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div class="icon fl"></div>
					<h1>About Work it, Mom!</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div id="about_content">

				<p>It's not easy to juggle family and career while maintaining your own sanity. We created Work It, Mom! because we believe that having a community where working moms can share their experiences, advice, and support can make that daily juggle a bit more manageable.</p>

				<p>But the only way that Work It, Mom! can become a vibrant community is if you get involved and contribute your energy, thoughts, and ideas. Please join us, write a personal essay or an informative article, start a great discussion, and invite your working mom friends to join the site. We can't wait to see all of your contributions! (<a href="<?= SITEURL ?>/gettingstarted">Click here </a> for ideas of ways to get involved in the community.)</p>

				<p>We are eager to hear your suggestions for what we can do to make Work It, Mom! a more useful and relevant resource for you. Please share your ideas with us by <a href="<?= SITEURL ?>/contact">clicking here </a>.</p>

				<h2>Contributing Content to Work It, Mom!</h2>

				<p>We're trying something different at Work It, Mom! Instead of hiring lots of editors and writers we would like to build up the greatest library of useful, interesting, and intriguing articles, interviews, and essays for professional moms by relying on contributions from real moms. We want Work It, Mom! to speak through your honest, revealing, and inspiring voices and to be the place where other busy professional moms come for advice, support, ideas, and a bit of laughter and relief from the daily juggle of work and family.</p>

				<p>We are all experts in some aspect of our career or family life. Why not share it with other professional moms and help, empower, and inspire each other? Submitting an article or a personal essay to Work It, Mom! is easy, requires no knowledge of HTML or any other technology, and is a great way to share your wisdom or opinions (and promote your book, business, or blog if you have one.)</p>

				<p><a href="<?= SITEURL ?>/contribute/article">Click here</a> to start writing right now! To read more about our submission guidelines, please <a href="<?= SITEURL ?>/submission">click here</a>.</p>

			</div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array(
				'newsletter',
				'static',
				'ad_mini',
				'slideshow_featured',
				'marketplace'
			), false); ?>
		</div>

		<div class="clear"></div>
	</div>
