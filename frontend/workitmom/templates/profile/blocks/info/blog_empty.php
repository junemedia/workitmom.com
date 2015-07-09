<?
if (BluApplication::getUser() && $person->equals(BluApplication::getUser())){
	// Current user viewing their own profile.
	?>You have no blog posts. <a href="<?= SITEURL; ?>/contribute/blog/">Write one.</a><?
} else {
	?><?= $person->name; ?> has no blog posts.<?
}
?>