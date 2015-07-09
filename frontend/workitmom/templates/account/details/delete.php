
	<form method="post" action="<?= SITEURL; ?>/account/details/"><div>
	
		<h2>Are you sure you want to delete your account?</h2>
		
		<dl>
			<dt><label for="reason">Why are you leaving?</label></dt>
			<dd><input type="text" class="textinput" name="reason" value="" /></dd>
			<dt></dt>
			<dd>
				<button type="submit" class="submit fl"><span>Delete my account</span></button>
				<div class="delete-text fl"><p class="text-content">I've changed my mind! Return to <a href="<?= SITEURL; ?>/account/">my account</a>.</p></a>
			</dd>
		</dl>
		
		<div class="clear"></div>
		
		<input type="hidden" name="task" value="details_delete_save" />
		
	</div></form>

