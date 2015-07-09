
	<div class="wrapper">
		<div class="content">
			<form action="<?= SITEURL ?>/marketplace/create" method="post"><div>
				
				<?php if ($expires > 0) { ?>
				<small class="fieldhint">
					The listing you are editing expires in <?= round($expires / (60 * 60 * 24)); ?> days. If you would like to extend the listing, please choose an option below. You will then be asked to confirm your payment securely.
				</small>
				<small class="fieldhint">
					If you don't wish to extend your listing, you do not need to proceed any further.
				</small>
				<div style="height: 1em;"></div>
				<?php } ?>

				<table class="text-content">
					<thead>
						<tr>
							<th width="31%">Listing Type</th>
					    <?php if ($renew) { ?>
							<th width="17%">Listing Price</th>
							<th width="20%">Upgrade Price</th>
						<?php } else { ?>
							<th width="7%">Price</th>
							<th width="12%">Savings</th>
						<?php } ?>
					    	<th width="13%">Select</th>
						</tr>
					</thead>
					<tbody>
				<?php
					foreach ($listingPrices as $type => $typeDetails) {

						// Skip featured type if not available
						if (($type == 'featured') && !$featAvail) {
							continue;
						}

						// Get base price ro base savings off
						$basePrice = reset($typeDetails['prices']) / key($typeDetails['prices']);
				?>
						<tr>
							<th colspan="4"><?= htmlspecialchars($typeDetails['title']) ?></th>
						</tr>
					<?php
						foreach ($typeDetails['prices'] as $months => $price) {

							// Calculate saving
							$saving = ($basePrice * $months) - $price;
					?>
						<tr>
							<td><?= $months ?> <?= ($months == 1) ? 'Month' : 'Months' ?></td>
							<td width="17%"><?= Template::price($price) ?></td>
							<td width="20%"><?= $saving ? Template::price(($basePrice * $months) - $price) : '-' ?></td>
							<td width="12%">
								<?php if ($price > 0) { ?>
								<label><input type="radio" name="paymentoption" value="<?= $type.'|'.$months ?>" /> &nbsp;</label>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				<?php
					}
				?>
					
					</tbody>
				</table>
				
				<dt><label for="coupon">Discount Code</label></dt>
				<dd><div class="clear"></div>
					<input type="text" id="coupon" name="coupon" maxlength="20" class="textinput" size="20" />
					<small class="fieldhint">If you have a promotional discount code, enter it here.</small>
				</dd>
				<div style="height:1em;"></div>

				<button type="submit" class="submit"><span>Continue</span></button>

				<input type="hidden" name="task" value="s3_type_save" />
			</div></form>
		</div>
	</div>
