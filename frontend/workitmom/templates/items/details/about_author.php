<?

// Uses $author, which should be a PersonObject object.

?>

<?php if (($this->_itemtype != 'quicktip') && ($this->_itemtype != 'list') && ($this->_itemtype != 'interview') && ($this->_itemtype != 'news')) { ?>

	<div id="about_author">
		<?php if ($author->byline) { ?>
			<h2>About the Author</h2>
			<p><?= Text::enableLinks($author->byline, array('match' => Utility::LAZY_URL, 'replace' => 'http://\\2')); ?></p>
		<?php } ?>
		<a href="<?= SITEURL ?>/profile/articles/<?= $author->username; ?>" class="button_dark fl screenonly"><span>Read more by <?= $author->name; ?></span></a>
		<div class="clear"></div>
	</div>

<?php } ?>
