<?php

/**
 * RSS Object
 *
 * @package BluCommerce
 * @subpackage SharedLib
 */
class Rss {

	/**
	 * Feed title
	 *
	 * @var string
	 */
	private $_title;

	/**
	 * Feed link
	 *
	 * @var string
	 */
	private $_link;

	/**
	 * Feed description
	 *
	 * @var string
	 */
	private $_desc;

	/**
	 * Feed image
	 *
	 * @var array Feed image details
	 */
	private $_image;

	/**
	 * Feed description limit
	 *
	 * @var int
	 */
	private $descLimit = 400;

	/**
	 * Feed items
	 *
	 * @var array
	 */
	private $_items = array();

	/**
	 * RSS feed constructor
	 *
	 * @param string Feed title
	 * @param string Feed link
	 * @param string Feed description
	 * @param array Feed image
	 * @param int Items description limit
	 */
	public function __construct($title, $link, $desc, $image = null, $descLimit = 400)
	{
		// Store feed properties
		$this->_title = $title;
		$this->_link = $link;
		$this->_desc = $desc;
		$this->_image = $image;
		$this->_descLimit = $descLimit;
	}

	/**
	 * Add item to feed
	 *
	 * @param string Item title
	 * @param string Item link
	 * @param string Item description
	 * @param int Item date
	 * @param string Item author
	 * @param array Item image details
	 * @param string Item price
	 */
	public function addItem($title, $link, $desc, $date = null, $author = null, $image = null, $price = null)
	{
		// Add item to stack
		$this->_items[] = array(
			'title' => $title,
			'link' => $link,
			'desc' => $desc,
			'date' => $date,
			'author' => $author,
			'image' => $image,
			'price' => $price
		);
	}

	/**
	 * Output feed
	 */
	public function output()
	{
		// Output feed header
		echo '<?xml version="1.0" encoding="UTF-8" ?>
			<rss version="2.0">
			<channel>
				<title><![CDATA['.$this->_title.']]></title>
				<link>'.$this->_link.'</link>
				<description><![CDATA['.$this->_desc.']]></description>
				<lastBuildDate>'.date('r').'</lastBuildDate>';

		// Output feed image element if we have one
		if (is_array($this->_image)){
			$image = '<image>
				<title>'.$this->_image['title'].'</title>
				<url>'.$this->_image['url'].'</url>
				<link>'.$this->_image['link'].'</link>
			</image>';
		}

		// Output feed items
		foreach ($this->_items as $item) {

			echo '<item>
				<title><![CDATA['.$item['title'].']]></title>
				<link>'.$item['link'].'</link>
				<guid>'.$item['link'].'</guid>
				<comments>'.$item['link'].'</comments>';

			// Publication date?
			if ($item['date']) {
				echo '<pubDate>'.date('r', strtotime($item['date'])).'</pubDate>';
			}

			// Author
			if ($item['author']) {
				echo '<author><![CDATA['.$item['author'].']]></author>';
			}

			// Build description
			$item['desc'] = Text::trim($item['desc'], $this->_descLimit);
			if ($item['price']) {
				$item['desc'] .= '<br /><br />Price: '.Template::price($item['price'], Template::PRICE_NOCONTAINER).'<br /><br /><a href="'.$item['link'].'">Buy '.$item['title'].' Online</a>';
			}
			if ($item['image']) {
				$item['desc'] = '<img src="'.$item['image']['src'].'" width="'.$item['image']['width'].'" height="'.$item['image']['height'].'" /><br />'.$item['desc'];
			}
			echo '<description><![CDATA['.$item['desc'].']]></description>';

			echo '</item>';
		}

		// Close feed
		echo '</channel></rss>';
	}
}

?>
