
	<div id="main-content" class="groups">

		<div class="panel-left">

			<div id="landing_title" class="block rounded-630-landing">
				<div class="top"></div>

				<div class="content">
					<div id="groups_icon" class="icon fl"></div>
					<h1>Group Discussions</h1>
				</div>

				<div class="bot"></div>
			</div>

			<?= Messages::getMessages(); ?>

			<div class="rounded-630-outline">
				<div class="top"></div>
					<div class="content">

						<h2>Create a Group</h2>

						<div class="wizard">
							<div class="stage current">
								<div class="title">
									<h3>Fill in group details</h3>
								</div>
								<div class="stagecontent">
									<div class="wrapper standardform">
										<div class="content formholder">
											<form method="post" action="<?= SITEURL ?>/groups/create" enctype="multipart/form-data"><div>

												<dl>
													<dt><label for="group_title">Title <span class="red-ast">*</span></label></dt>
													<dd>
														<input name="group_title" id="group_title" type="text" class="textinput required" value="<?= Request::getString('group_title') ?>" />
													</dd>

													<dt><label for="group_category">Category <span class="red-ast">*</span></label></dt>
													<dd>
														<select name="group_category" id="group_category">
															<option value="">Please select from below</option>
															<?php foreach ($categories as $category) { ?>
															<option value="<?= $category['id'] ?>"<?php if ($categoryId == $category['id']) { ?> selected="selected"<?php } ?>><?= $category['name'] ?></option>
															<?php } ?>
														</select>
													</dd>

													<dt><label>Group type <span class="red-ast">*</span></label></dt>
													<dd>
														<label class="radio"><input type="radio" name="group_type" value="public" class="validate-one-required" />
															<strong>Open group</strong>
															<small class="fieldhint">Any user in the community can join, post comments and contribute to the forums.</small>
														</label>

														<label class="radio"><input type="radio" name="group_type" value="public" />
															<strong>Private group</strong>
															<small class="fieldhint">Your group will not be visible by anyone in the community. New members join by invite only.</small>
														</label>
													</dd>

													<dt><label>Group image <span class="red-ast">*</span></label></dt>
													<dd>
														<input type="file" name="photoupload" id="photoupload" class="file text-content" size="50" />
													</dd>

													<dt><label for="group_blurb">Group blurb <span class="red-ast">*</span></label></dt>
													<dd>
														<textarea name="group_blurb" id="group_blurb"></textarea>
														<small class="fieldhint">A short description of you group for search results and listings</small>
													</dd>

													<dt><label for="group_desc">Group description <span class="red-ast">*</span></label></dt>
													<dd>
														<textarea name="group_desc" id="group_desc"></textarea>
														<small class="fieldhint">A full length description of your group</small>
													</dd>

													<dt><label for="group_tags">Group tags <span class="red-ast">*</span></label></dt>
													<dd>
														<textarea name="group_tags" id="group_tags"></textarea>
														<small class="fieldhint">Please ensure tags are seperated by commas, e.g. employment in boston, interview techniques, etc</small>
													</dd>
												</dl>
												<div class="clear"></div>

												<button type="submit" class="submit"><span>Create group</span></button>

												<input type="hidden" name="task" value="create_save" />
												<input type="hidden" id="queueid" name="queueid" value="<?= $queueId ?>" />
											</div></form>
										</div>
									</div>
								</div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>&nbsp;
						</div>
					</div>
					<div class="bot"></div>
				</div>
			</div>
		</div>

		<div class="panel-right">
		<?php $this->sidebar(Array('slideshow_featured', 'marketplace', 'catch_your_breath'
)); ?>
		</div>

		<div class="clear"></div>
	</div>
