<!--INFOLINKS_OFF-->
<div class="wrapper">
  <div class="content">

    <form action="<?= SITEURL ?>/register" method="post">
      <div>

        <dl>
          <dt><label for="form_username">Username <span class="red-ast">*</span></label></dt>
          <dd>
            <input name="form_username" class="textinput minLength validate-alphanum" validatorProps="{minLength: 6}" type="text" id="form_username" size="30" value="<?= $username ?>" />
            <small class="fieldhint">This name is unique to you and can't be changed later, so please pick something you will remember. Your username is not visible to others on Work It, Mom!, although it does show up in the URL window when someone views your profile.</small>
          </dd>

          <dt><label for="form_email">Email Address <span class="red-ast">*</span></label></dt>
          <dd>
            <input name="form_email" class="textinput required validate-email" type="text" id="form_email" value="<?= $email ?>" />
          </dd>

          <dt><label for="form_password">Password <span class="red-ast">*</span></label></dt>
          <dd>
            <input name="form_password" class="textinput minLength maxLength" validatorProps="{minLength: 6, maxLength: 30}" type="password" id="password" value="" size="30" maxlength="30" />
          </dd>

          <dt><label for="form_password_confirm">Confirm Password <span class="red-ast">*</span></label></dt>
          <dd>
            <input name="form_password_confirm" class="textinput validate-passwordconfirm" type="password" id="form_password_confirm" />
          </dd>

          <dt><label for="form_display_name">Public Display Name <span class="red-ast">*</span></label></dt>
          <dd>
            <input name="form_display_name" class="textinput required" type="text" id="form_display_name" value="<?= $displayName ?>" />
            <small class="fieldhint">This is your public profile name, which will be shown on your profile page and with all of your articles, comments, member notes, questions, and discussions. You can change your profile name as often as you like.</small>
          </dd>
          <input type="hidden" value="United States" name="form_location">
        </dl>
        <div class="clear"></div>

        <div class="required"><strong>NOTE:</strong> All fields marked <span class="red-ast">*</span> are required.</div>

        <div class="divider"></div>

        <p>
          <label for="form_referral">Where did you hear about Work it, Mom!?</label>
          <input name="form_referral" class="textinput" type="text" id="form_referral" size="30" maxlength="100" value="<?= $referral ?>" />
          <small class="fieldhint">If you were referred by a friend, please type in their email or name in the box above.</small>
        </p>

        <div class="divider"></div>

        <p>
          <label class="check"><input name="form_newsletter" class="check" type="checkbox" id="form_newsletter" value="y" />
            Yes, send me the Making It Work newsletter with the latest community blogs and tips! As a bonus, I will also receive special offers from third party partners.</label>
        </p>
        <div class="clear"></div>

        <p>
          <label class="check"><input name="form_terms" class="check validate-terms-required" type="checkbox" id="form_terms" value="y" />
            Yes, I agree to the Work It, Mom! <a href="<?= SITEURL ?>/info/terms" class="info-popup">terms and conditions</a></label>
        </p>
        <div class="clear"></div>

        <div class="divider"></div>

        <div class="captcha">
          <div class="img">
            <img src="<?= SITEURL; ?>/captcha?format=asset&uniq=<?= uniqid(); ?>" class="captcha-img" />
            <div class="captcha-reload"><small><a href="#">Get a new image</a></small></div>
          </div>
          <div class="body">
            <label>Please enter the code to the left:</label>
            <input name="wimcaptcha" class="textinput validate-captcha" type="text" id="wimcaptcha" size="30" maxlength="100" />
            <small><a href="<?= SITEURL ?>/info/whatsthis" class="info-popup">What's this?</a></small>
          </div>

          <div class="clear"></div>
        </div>

        <div class="divider"></div>

        <button type="submit"><span>Continue</span></button>

        <input type="hidden" name="task" value="s1_basic_save" />
      </div>
    </form>
  </div>
</div>
<!--INFOLINKS_ON-->
