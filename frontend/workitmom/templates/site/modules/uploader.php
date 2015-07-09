
<?php /* Use "display: none", then redisplay using js, because non-js users can't upload images anyway (they won't have TinyMCE). */ ?>
<iframe id="uploader" src="<?= SITEURL; ?>/assets/upload/" style="display: none;"></iframe>

<?php Template::startScript(); ?>
$(document.body).getElement('iframe#uploader').setStyles({
	display: 'block',
	width: '100%',
	height: '300px'
});
<?php Template::endScript(); ?>