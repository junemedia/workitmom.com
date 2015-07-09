			
			
			
				<div class="rounded-630-blue" id="search-members">
					<div class="top"></div>
					<div class="content">

						<form class="reloads" id="form_members" action="<?= SITEURL; ?>/connect/member_search/" method="post">

							<dl>
								<dt><label for="name">Search by Name</label></dt>
								<dd><input name="name" class="textinput" type="text" size="30" maxlength="30" value="<?= $name; ?>" /></dd>
								
								<dt><label for="location">Search by Location</label></dt>
								<dd>
									<input name="location" id="form_location" class="textinput" type="text" size="30" maxlength="30" value="<?= $location; ?>" />
									<?php Template::startScript(); ?>

										// Location input
										var locationInput = $('form_location');

										// Location autocompleter
										new Autocompleter.Request.JSON(locationInput, SITEURL + '/locations/search/', {postVar: 'criteria'});

										// Custom validator
										locationInput.addClass('validate-custom');
										locationInput.set('validatorProps', JSON.encode({custom_error: 'Please enter a valid location from the autocompleter.', custom_url: SITEURL+'/locations/'}));

									<?php Template::endScript(); ?>
								</dd>
								
								<dt><label for="industry">Search by Industry</label></dt>
								<dd class="fieldwrap inline">
									<select name="industry" id="form_industry" size="1">
										<option value="">Please select below</option>
										<?php foreach($industries as $industryID => $industryName) {
											$selected = isset($industry) && $industry == $industryID ? ' selected="true"' : ''; ?>
										<option<?= $selected; ?> value="<?= $industryID; ?>"><?= $industryName; ?></option>
										<?php } ?>
									</select>
								</dd>

								<dt><label for="interests">Search by Interests</label></dt>
								<dd><input name="interests" class="textinput" type="text" size="30" maxlength="30" value="<?= $interests; ?>" /></dd>
								
							</dl>
							
							<div class="clear"></div>

							<div class="fieldwrap submit">
								<button type="submit"><span>Search Work It, Mom! Members</span></button>
							</div>
							
							<div class="divider"></div>

						</form>
					
						<div class="tag-cloud">
							<h3>Popular member tags</h3>
							<?php foreach($tagCloud as $tag => $value){ 
								$css = array('tag');
								if ($value == 1){ $css[] = 'large'; }
								$link = SITEURL.'/connect/member_search/'.Router::http_build_str(array_merge($queryString, array('tag' => urlencode($tag))), '?');
								?>
							<a class="<?= implode(' ', $css); ?>" href="<?= $link; ?>"><?= $tag; ?></a>
							<?php } ?>
						</div>
					
					</div>
					<div class="bot"></div>
				</div>
				
				
				<? /*
				<div id="sort_bar">				
					<div class="text-content fl">
						Showing <?= $pagination->get('start'); ?>-<?= $pagination->get('end'); ?> of <strong><?= $pagination->get('total'); ?></strong> member<?= Text::pluralise($pagination->get('total')); ?>.
						
					</div>					
					<div class="clear"></div>
				</div>
				*/?>
				
				<div id="peeps" class="grid_list member_list">
				
					<?php if (isset($currentTag) && $currentTag){ ?>
					<p class="text-content">Showing people that match the tag <em><?= $currentTag; ?></em>&nbsp;<a class="tag" href="<?= $clearTagsUrl; ?>">[Clear tag]</a>:</p>
					<?php } ?>
					
					<ul>					
						<?php if (Utility::iterable($people)){ foreach($people as $person) { ?>
						<li>
							<a href="<?= $person['url']; ?>" class="img"><img src="<?php Template::image($person, 85); ?>" /></a>
							<a href="<?= $person['url']; ?>"><?= $person['name']; ?></a>
						</li>
						<?php } } else { ?>
						<p class="text-content"><strong>Sorry!</strong> We couldn't find any members that matched your criteria.</p>
						<?php } ?>
					</ul>
					<div class="clear"></div>
					
				</div>

				<?= $pagination->get('buttons'); ?>
				
			