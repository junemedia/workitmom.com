				<li class="friend<?= $alt ? ' odd' : ''; ?>">
					<div class="actions">
						<a href="<?= SITEURL . $person->profileURL; ?>" class="img"><img src="<?= ASSETURL; ?>/userimages/110/110/1/<?= $person->image; ?>" /></a>
						<div class="clear"></div>
						<ul class="links">
							<li><a href="<?= SITEURL . $person->profileURL; ?>" class="button_dark fl"><span>View Profile</span></a><div class="clear"></div></li>
							<li><a href="<?= SITEURL; ?>/account/write_message/?user=<?= $person->username; ?>" class="button_dark fl"><span>Send Message</span></a><div class="clear"></div></li>
							<li><a href="<?= SITEURL; ?>/account/friends/?task=remove_friend&amp;id=<?= $person->userid; ?>" class="delete">Remove Friend</a></li>
						</ul>

						<div class="clear"></div>

					</div>
					<div class="info">

						<h3><a href="<?= SITEURL . $person->profileURL; ?>"><?= $person->name; ?></a></h3>

						<?php if ($job){ ?>
						<h4>Job:</h4>
						<p><?= $job ?></p>
						<?php } ?>

						<?php if ($about){ ?>
						<h4>About:</h4>
						<p><?= $about ?></p>
						<?php } ?>

					</div>

					<div class="clear"></div>
				</li>