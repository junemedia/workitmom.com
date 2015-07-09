
	<div id="main-content" class="landing">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="contribute_icon" class="icon fl"></div>
					<h1>Contribute</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="block rounded-630-orange" id="page-intro">
				<div class="top"></div>

				<div class="content">
					<div class="img"><img src="<?= ASSETURL; ?>/landingimages/0/0/1/landing_contribute.jpg"/></div>

					<div class="body">
						<h2>Have something to say?</h2>
						<p>Share your experiences, opinions and favorite tips &amp; advice with our active community.</p>
					</div>

					<div class="clear"></div>

				</div>

				<div class="bot"></div>
			</div>


			<div class="col-l">

				<!-- Share a Lifesaver -->
				<?php $this->landing_lifesaver(); ?>
				<div class="divider"></div>
				
				<!-- Write an Article -->
				<?php $this->landing_article(); ?>
				<div class="divider"></div>

				<!-- Write a Blog -->
				<?php $this->landing_blog(); ?>

			</div>
			<? // END >> LEFT COLUMN  ?>

			<div class="col-r">
			
				<!-- Ask a Question -->
				<?php $this->landing_question(); ?>
				<div class="divider"></div>
			
				<!-- Upload a Photo -->
				<?php $this->landing_photo(); ?>
				<div class="divider"></div>

				<!-- Share a News Story -->
				<?php $this->landing_news(); ?>

			</div>
			<? // END >> MIDDLE COLUMN  ?>

			<div class="clear"></div>

			<?php BluApplication::getModules('site')->bottom_blocks(); ?>

		</div>

		<div class="panel-right">
			<?php $this->sidebar(array(
				'catch_your_breath',
				'marketplace',
				'slideshow_featured'
			)); ?>
		</div>
		<? // END >> RIGHT COLUMN  ?>

		<div class="clear"></div>
	</div>
