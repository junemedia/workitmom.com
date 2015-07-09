<?php

/**
 * Pagination Helper
 *
 * Provides helper functions to display pagination links
 *
 * @package BluApplication
 * @subpackage SharedLib
 */
class Pagination
{
	/**
	 *	The total number of buttons/pages.
	 */
	protected $_pages;

	/**
	 *	The base URL for the buttons
	 */
	protected $_baseUrl;

	/**
	 *	Store the current page, for consistency
	 */
	protected $_currentpage;
	
	/**
	 *	Location hash.
	 */
	protected $_locationHash = '';

	/**
	 * Store the total number of pages.
	 */
	protected function __construct($pages){
		$this->_pages = (int) $pages;
	}

	/**
	 * Mutator method
	 */
	public function set($key, $value = null){
		
		/* Recursive */
		if (Utility::is_loopable($key)){
			foreach($key as $k => $v){
				$this->set($k, $v);
			}
			return $this;
		} else if (is_null($value)){
			/* Need a value. */
			return false;
		}
		
		/* Set variables */
		switch($key){
			case 'page':
				$page = (int) $value;
				if ($value < 1 || $value > $this->_pages){ $page = 1; }	// Error checking - defaults to page 1.
				$this->_currentpage = $page;
				break;

			case 'url':
				$this->_baseUrl = $value;
				break;
				
			case 'locationHash':
				$this->_locationHash = $value;
				break;

			default:
				/* do nothing */
				break;
		}
		
		/* Exit */
		return $this;
	}

	/**
	 * Accessor method.
	 */
	public function get($key){
		switch(strtolower($key)){
			case 'buttons':
				return $this->_getButtons($this->_locationHash);
				break;
		}
	}

	/**
	 * Build a page link/placeholder
	 *
	 * @param int Page for which to create a link
	 * @param string location hash.
	 *
	 * @return string HTML formatted link/placeholder
	 */
	private function _buildPageLink($page, $locationHash = null)
	{
		if ($page == $this->_currentpage) {
			$html = "\n" . '<li><span class="item current">'.$page.'</span></li>';
		} else {
			$html = "\n" . '<li><a href="'.$this->_baseUrl.$page.($locationHash ? '#' . $locationHash : '').'">'.$page.'</a></li>';
		}
		return $html;
	}

	/**
	 * Get a HTML formatted pagination list for the given paramaters
	 *
	 * @args (string) location hash
	 * @return string HTML formatted pagination list
	 */
	private function _getButtons($locationHash = null)
	{
		$html = '';

		// Number of pages to show adjacent to the current (i.e on the left and right)
		$adjacent = 2;

		// Number of pages to always show at the start and end of the list
		$trailing = 1;

		// Calculate total and current pages
		$numPages = $this->_pages;
		if ($numPages > 1) {
			$html.= '<ul>';

			// Build prev and next links
			if ($this->_currentpage > 1) {
				$prevLink = '<li><a href="'.$this->_baseUrl.($this->_currentpage - 1).($locationHash ? '#' . $locationHash : '').'" class="prev">Previous</a></li> ';
			} else {
				$prevLink = '';
			}
			if ($this->_currentpage < $numPages) {
				$nextLink = '<li><a href="'.$this->_baseUrl.($this->_currentpage + 1).($locationHash ? '#' . $locationHash : '').'" class="next">Next</a></li> ';
			} else {
				$nextLink = '';
			}

			// Previous
			$html.= $prevLink;

			// Surrounding page boundaries
			$startPage = $this->_currentpage - $adjacent;
			$endPage = $this->_currentpage + $adjacent;
			if ($startPage < 1) {
				$endPage-= ($startPage - 1);
			}
			if ($endPage > $numPages) {
				$startPage-= ($endPage - $numPages);
				$endPage = $numPages - 1;
			}

			// Prevent use of spaces when only one page is missing
			if ($startPage == ($trailing + 2)) {
				$startPage = ($trailing + 1);
			}
			if ($endPage == ($numPages - $trailing - 1)) {
				$endPage = ($numPages - $trailing);
			}

			// Leading pages
			for ($page = 1; $page <= min($trailing, $numPages); $page++) {
				$html.= $this->_buildPageLink($page, $locationHash);
			}
			if ($startPage > $page) {
				$html.= '<li><span class="spacer">&#8230;</span></li> ';
			} else {
				$startPage = $page;
			}

			// Surrounding pages
			for ($page = $startPage; $page <= $endPage; $page++) {
				$html.= $this->_buildPageLink($page, $locationHash);
			}

			// Trailing pages
			if ($endPage < ($numPages - 1)) {
				$html.= '<li><span class="spacer">&#8230;</span></li> ';
			}
			for ($page = max($page, ($numPages - ($trailing - 1))); $page <= $numPages; $page++) {
				$html.= $this->_buildPageLink($page, $locationHash);
			}

			// Next
			$html .= $nextLink;

			// Finish
			$html .= '</ul>';

			// Clear and wrap
			$html = '<div class="pagination">'.$html.'<div class="clear"></div></div>';
		}
		return $html;
	}


	/**
	 *	Text pagination object.
	 */
	public static function text(array $options = array()){
		$pagination = new TextPagination();
		if (!empty($options)){
			$pagination->set($options);
		}
		return $pagination;
	}
	
	/**
	 *	One-liner pagination object.
	 */
	public static function simple(array $options = array()){
		$pagination = new SimplePagination();
		if (!empty($options)){
			$pagination->set($options);
		}
		return $pagination;
	}

	/**
	 *	Just return buttons
	 */
	public static function buttons($page = 1, $pages = null, $baseUrl = null, $locationHash = null){
		$pagination = new Pagination($pages);
		$pagination->set(array(
			'url' => $baseUrl,
			'page' => $page,
			'locationHash' => $locationHash
		));
		return $pagination->get('buttons');
	}

}
?>
