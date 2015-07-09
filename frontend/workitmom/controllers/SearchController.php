<?php

/**
 * Search Controller
 *
 * @package BluApplication
 * @subpackage FrontendControllers
 */
class WorkitmomSearchController extends ClientFrontendController
{
	/**
	 * 	Default controller entry point
	 */
	public function view()
	{
		// Add breadcrumb
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Search', '/search');

		// Get search terms and type
		$searchTerms = $this->_getSearchTerms();
		$type = Request::getCmd('type');
		
		/* Set header ad */
		$this->_doc->setAdPage(OpenX::PAGE_SEARCH);

		// Load template
		include(BLUPATH_TEMPLATES . '/search/results.php');
	}

	/**
	 * Results listing
	 */
	public function listing()
	{
		// Get data from request
		$page = Request::getInt('page', 1);
		$limit = 10;
		$offset = ($page - 1) * $limit;

		// Get search terms and type
		$searchTerms = $this->_getSearchTerms();
		$type = Request::getCmd('type');

		// Type mappings
		$typeNames = array(
			'article' => 'Articles',
			'blog' => 'Featured Blogs',
			'comment' => 'Comments',
			'forum' => 'Discussions',
			'interview' => 'Interviews',
			'news' => 'News',
			'question' => 'Questions',
			'note' => 'Member Blogs',
			'slideshow' => 'Slideshows',
			'group' => 'Groups',
			'quicktip' => 'Quick Tips',
			'landingpage' => 'Essential Guides'
		);
		if (!in_array($type, array_keys($typeNames))){ $type = null; }

		// Perform search
		$searchModel = $this->getModel('search');
		$total = null;
		$items = $searchModel->findItems($searchTerms, $type, $offset, $limit, $total);

		// Build pagination
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => SITEURL.'/search?type='.$type.'&amp;page='
		));

		// Load template
		include(BLUPATH_TEMPLATES . '/search/results/box.php');
	}

	/**
	 * Get the current search terms from request/session
	 *
	 * @return array Search terms
	 */
	private function _getSearchTerms()
	{
		// Get terms from request
		$requestTerms = Request::getString('search');

		// Parse request terms
		if ($requestTerms) {
			$requestTerms = preg_replace('/\s\s+/', ' ', $requestTerms);
			$terms = explode(' ', $requestTerms);
			Session::set('search_terms', $terms);

		// Try to get search criteria from session
		} else {
			$terms = Session::get('search_terms');
		}

		return $terms;
	}
}

?>
