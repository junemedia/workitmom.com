<?php echo('<?xml version="1.0" encoding="iso-8859-1"?'.'>');
		?>
		<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
		   <channel>
		      <title>Recent <?= $this->itemtype_plural; ?></title>
		      <link><?=SITEURL."/explore/"?></link>
		      <description>
				The <?= count($latestitems); ?> latest <?= strtolower($this->itemtype_plural); ?> on Work It, Mom!
			  </description>
		      <language>en-us</language>
		      <lastBuildDate><?=date("r")?></lastBuildDate>
		      <docs>http://blogs.law.harvard.edu/tech/rss</docs>
			  <atom:link href="<?=SITEURL."/explore/articles.xml"?>" rel="self" type="application/rss+xml" />
		<?