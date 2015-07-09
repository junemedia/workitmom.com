
				<div<?= $css ? ' class="' . $css. '"' : ''; ?>>
					<ul>
					<? foreach($things as $thing){ $this->listing_individual($thing); } ?>
					</ul>
				</div>
