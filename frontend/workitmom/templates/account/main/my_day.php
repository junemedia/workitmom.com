
	<div class="rounded-300-orange block" id="my_day_snapshot">
		<div class="top"></div>
		<div class="content">
			<div class="header">
				<div class="title">
					<h2>My Day Snapshot</h2>
				</div>
			</div>

			<div class="standardform">
				<div class="formholder">
					<form action="<?= SITEURL.'/account' ?>" method="post"><div>

						<?php foreach ($myDay as $item) { ?>

							<?php if ($item['myId'] == 11) { ?>

							<label for="myday_stress">Stress-o-meter</label>
							<div id="myday_stressslider" class="stressslider">
								<div class="knob"></div>
							</div>
							<input type="hidden" name="myday[<?= $item['myId'] ?>]" id="myday_stress" value="<?= $item['myAnswer'] ?>" />
							<?php Template::startScript(); ?>

								var stressSliderEl = $('myday_stressslider');
								var stressInput = $('myday_stress');
								var stressSlider = new Slider(stressSliderEl, stressSliderEl.getElement('.knob'), {
									steps: 100,
									range: [0, 100],
									onChange: function(v){
										stressInput.set('value', v);
										var p = (v-1)/100;
										var rgb = [((1-p)*90),1,200].hsvToRgb();
										var hex = rgb.rgbToHex();
										stressSliderEl.setStyle('background', hex);
									}
								}).set(<?= (int)$item['myAnswer'] ?>);

							<?php Template::endScript(); ?>

							<?php } else { ?>

							<label for="myday_<?= $item['myId'] ?>"><?= $item['myText'] ?></label>
							<input type="text" class="textinput" maxlength="100" name="myday[<?= $item['myId'] ?>]" id="myday_<?= $item['myId'] ?>" value="<?= $item['myAnswer'] ?>" />

							<?php } ?>

						<?php } ?>

						<label class="check"><input type="checkbox" class="check" name="tellfriends"<?php if ($item['myTellFriend'] !== '0') { ?> checked="checked"<?php } ?> value="1" />
							Tell my friends when I am stressed out?</label>
						<div class="clear"></div>
						<label class="check"><input type="checkbox" class="check" name="public"<?php if ($item['myPublic'] !== '0') { ?> checked="checked"<?php } ?> value="1" />
							Make my day public?</label>
						<div class="clear"></div>

						<button name="submit" class="submit fl" type="submit"><span>Save</span></button><a href="<?= SITEURL ?>/account/myday" class="button_bright fr"><span>View Archive</span></a>
						<div class="clear"></div>
						<input type="hidden" name="task" value="myday_save" />
					</div></form>
				</div>
			</div>
		</div>
		<div class="bot"></div>
	</div>
