<div class="wrapper">
  <div class="content">
    <form action="<?= SITEURL ?>/register" method="post" enctype="multipart/form-data">
      <div>

        <p class="text-content" style="margin-bottom: 10px;">The following information is not required but will help you have a richer experience at Work It, Mom!. If you'd like to fill it out later, just click the <strong>'complete sign up'</strong> at the bottom of this page.</p>

        <h3>How do you work?</h3>
        <?php Template::startScript(); ?>
          // Extra field handlers
          var currentFields;
          $$('input[name=form_howwork]').each(function(input) {

            // Store current
            if (input.get('checked')) {
              currentFields = input.get('value');
            }

            // Add change event
            input.addEvent('click', function(event){
              var fields = input.get('value');

              // Hide all but current fields
              $$('fieldset.howwork-extra').each(function(fieldset) {
                if (!fieldset.hasClass(fields+'-fields')) {
                  fieldset.slide('out');
                }
              });

              // Show current fields
              var fieldset = $$('fieldset.'+fields+'-fields');
              if (fieldset) {
                fieldset.slide('in');
              }
            });
          });

          // Hide all but current fields
          $$('fieldset.howwork-extra').each(function(fieldset) {
            if (!fieldset.hasClass(currentFields+'-fields')) {
              fieldset.slide('hide');
            }
          });
        <?php Template::endScript(); ?>

        <label class="radio">
          <input name="form_howwork" class="radio" type="radio" value="employed"<?= $userInfo->employmentType == 'employed' ? ' checked="checked"' : ''; ?> />
          I currently work full-time
        </label>
        <div class="clear"></div>

        <label class="radio">
          <input name="form_howwork" class="radio" type="radio" value="parttime"<?= $userInfo->employmentType == 'parttime' ? ' checked="checked"' : ''; ?> />
          I currently work part-time
        </label>
        <div class="clear"></div>

        <!-- Employer options -->
        <fieldset class="sub howwork-extra employed-fields parttime-fields">
          <dl>
            <dt><label for="form_employer">Name of Employer</label></dt>
            <dd>
              <input name="form_employer" class="textinput" type="text" id="form_employer" size="30" maxlength="100" value="<? Template::out($userInfo->employerName); ?>" />
            </dd>
            <dt><label for="form_jobtitle">Job Title</label></dt>
            <dd>
              <input name="form_jobtitle" class="textinput" type="text" id="form_jobtitle" size="30" maxlength="100" value="<? Template::out($userInfo->jobTitle); ?>" />
            </dd>
            <dt><label>Industry</label></dt>
            <dd>
              <select name="form_industry" id="form_industry" size="1">
                <option value="">Please select below</option>
                <?php foreach($industries as $industryID => $industryName) { ?>
                <option<?= $userInfo->industry == $industryID ? ' selected="true"' : ''; ?> value="<?= $industryID; ?>"><?= $industryName; ?></option>
                <?php } ?>
              </select>
            </dd>
          </dl>
          <div class="clear"></div>
        </fieldset>

        <label class="radio"><input name="form_howwork" class="radio" type="radio" value="entrepreneur"<?= $userInfo->employmentType == 'self' ? ' checked="checked"' : ''; ?> />
          I run my own business</label>
        <div class="clear"></div>

        <!-- Entrepreneur fields -->
        <fieldset class="sub howwork-extra entrepreneur-fields">
          <dl>
            <dt><label for="form_business_name">Name of Business</label></dt>
            <dd>
              <input name="form_business_name" class="textinput" type="text" id="form_business_name" size="30" maxlength="100" value="<? Template::out($userInfo->employerName); ?>" />
            </dd>
            <dt><label for="form_industry_entreprenuer">Industry</label></dt>
            <dd>
              <select name="form_industry" id="form_industry_entreprenuer" size="1">
                <option value="">Please select below</option>
                <?php foreach($industries as $industryID => $industryName) { ?>
                <option<?= $userInfo->industry == $industryID ? ' selected="true"' : ''; ?> value="<?= $industryID; ?>"><?= $industryName; ?></option>
                <?php } ?>
              </select>
            </dd>
          </dl>
          <div class="clear"></div>
        </fieldset>

        <label class="radio"><input name="form_howwork" class="radio" type="radio" value="freelancer"<?= $userInfo->employmentType == 'consultant' ? ' checked="checked"' : ''; ?> />
          I work as a consultant / freelancer</label>
        <div class="clear"></div>

        <!-- Freelance fields -->
        <fieldset class="sub howwork-extra freelancer-fields">
          <dl>
            <dt><label for="form_industry_freelance">Industry</label></dt>
            <dd>
              <select name="form_industry" id="form_industry_freelance" size="1">
                <option value="">Please select below</option>
                <?php foreach($industries as $industryID => $industryName) { ?>
                <option<?= $userInfo->industry == $industryID ? ' selected="true"' : ''; ?> value="<?= $industryID; ?>"><?= $industryName; ?></option>
                <?php } ?>
              </select>
            </dd>
          </dl>
          <div class="clear"></div>
        </fieldset>

        <label class="radio"><input name="form_howwork" class="radio" type="radio" value="unemployed"<?= $userInfo->employmentType == 'unemployed' ? ' checked="checked"' : ''; ?> />
          I'm not currently working</label>
        <div class="clear"></div>

        <div class="divider"></div>

        <h3>A bit about you</h3>
        <dl>
          <dt><label for="form_children">Number of Children</label></dt>
          <dd>
            <select name="form_children" id="form_children" size="1">
              <option value="none">Please select below</option>
              <option<?= $userInfo->numChildren == 0 ? ' selected="true"' : ''; ?> value="0">None</option>
              <? for($i = 1; $i < 5; $i++){ ?>
              <option<?= $userInfo->numChildren == $i ? ' selected="true"' : ''; ?> value="<?= $i; ?>"><?= $i; ?></option>
              <? } ?>
              <option<?= $userInfo->numChildren == 5 ? ' selected="true"' : ''; ?> value="5">5 or more</option>
            </select>
          </dd>
          <dt><label for="form_ages">Ages of Children</label></dt>
          <dd>
            <input name="form_ages" class="textinput" type="text" id="form_ages" size="30" maxlength="30" value="<? Template::out($userInfo->childrenAge); ?>" />
          </dd>
          <dt><label for="form_tags">Your Tags</label></dt>
          <dd>
            <textarea name="form_tags" class="textinput overtext" id="form_tags" title="Examples: entrepreneur, writer, yoga, environment, single mom" rows="2" cols="30"><?= $tags ?></textarea>
          </dd>

          <dt><label for="form_aboutyou">A little bit about you</label></dt>
          <dd>
            <textarea name="form_aboutyou" class="textinput" id="form_aboutyou" rows="5" cols="30"><? Template::out($userInfo->statement); ?></textarea>
          </dd>
        </dl>
        <div class="clear"></div>

        <div class="divider"></div>

        <h3>Would you like to add a photo to your profile?</h3>
        <div class="fieldwrap photo">
          <ul>
            <li>
              <label for="form_imgfile">Upload a photo from your computer</label>
              <input type="file" name="photoupload" id="photoupload" class="file text-content" size="50" />
            </li>
            <li>
              <label>OR, choose an avatar below:</label>
              <div class="imageradios">
                <label><img src="<?= ASSETURL ?>/userimages/60/60/1/avatar1.png" />
                  <input type="radio" name="avatar" value="1" checked="checked" /></label>
                <label><img src="<?= ASSETURL ?>/userimages/60/60/1/avatar2.png" />
                  <input type="radio" name="avatar" value="2" /></label>
                <label><img src="<?= ASSETURL ?>/userimages/60/60/1/avatar3.png" />
                  <input type="radio" name="avatar" value="3" /></label>
              </div>
              <div class="clear"></div>
            </li>
          </ul>
        </div>

        <div class="divider"></div>

        <div class="notify">
          The information below WILL NOT be made public in your profile and is for our information only. We will NEVER share your private information with any third party. To read our Privacy Policy, <a href="<?= SITEURL; ?>/privacy/" target="_blank">click here</a>.
        </div>

        <dl>
          <dt><label for="form_household">Household Income</label></dt>
          <dd>
            <select name="form_household" id="form_household" size="1">
              <option<?= !$userInfo->household ? ' selected="true"' : ''; ?> value="-1">Please select below</option>
              <?php if (Utility::is_loopable($enumsHousehold)){ foreach($enumsHousehold as $key => $enum){ ?>
              <option<?= $userInfo->household == $enum ? ' selected="true"' : ''; ?> value="<?= $key; ?>"><?= $enum; ?></option>
              <?php } } ?>
            </select>
          </dd>

          <dt><label for="form_age">Your Age</label></dt>
          <dd>
            <input name="form_age" class="textinput" type="text" id="form_age" size="30" maxlength="30" value="<? Template::out($userInfo->own_age); ?>" />
          </dd>

          <dt><label for="form_education">Education</label></dt>
          <dd>
            <select name="form_education" id="form_education" size="1">
              <option<?= !$userInfo->education ? ' selected="true"' : ''; ?> value="-1">Please select below</option>
              <?php if (Utility::is_loopable($enumsEducation)){ foreach($enumsEducation as $key => $enum) { ?>
              <option<?= $userInfo->education == $enum ? ' selected="true"' : ''; ?> value="<?= $key; ?>"><?= $enum; ?></option>
              <?php } } ?>
            </select>
          </dd>
        </dl>
        <div class="clear"></div>

        <div class="divider"></div>


        <div align="center"><button type="submit"><span>Complete sign up and go to my account</span></button></div>

        <input type="hidden" name="task" value="s2_optional_save" />
        <input type="hidden" id="queueid" name="queueid" value="<?= $queueId ?>" />
      </div>
    </form>
  </div>
</div>
