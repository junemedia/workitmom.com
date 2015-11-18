<?php


class WorkitmomConnectController extends WorkitmomCategoriesController {
	
	/**
	 * Display home page
	 */
	public function view()
	{
		/* Add breadcrumb */
		$this->_addBreadcrumb();
		
		/* Get Models */
		$itemsModel = $this->getModel('items');
		$featuredArticle = $itemsModel->getFeatured('article');
		$latestNews = $itemsModel->getLatest('news', 0, 4);
		
		/* Get site modules */
		$siteModules = BluApplication::getModules('site');
		
		/* Set page title */
		$this->_doc->setTitle('Connect');
		$this->_doc->setAdPage(OpenX::PAGE_MEMBERS);
		
		/* Load page template */
		include (BLUPATH_TEMPLATES . '/connect/landing.php');
	}
	
	/**
	 *	'Meet members' page.
	 */
	public function members(){
		
		/* Add breadcrumbs */
		$pageTitle = 'Meet members';
		$this->_addBreadcrumb();
		BluApplication::getBreadcrumbs()->add($pageTitle, '/' . $this->_controllerName . '/' . __FUNCTION__ . '/');
		
		/* Add page title */
		$this->_doc->setTitle($pageTitle . BluApplication::getSetting('titleSeparator') . 'Connect');
		$this->_doc->setAdPage(OpenX::PAGE_MEMBERS);
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/connect/members.php');
		
	}
	
	/**
	 *	Members pages: members tab.
	 *
	 *	Outputs tab content.
	 */
	public function tab_members(){
		
		/* Display settings */
		$sort = Request::getCmd('sort_members', 'date');
		if (!in_array($sort, array('date', 'active'))){
			$sort = 'date';	// Default tab.
		}
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/connect/tab_members/container.php');
		
	}
	
	/**
	 *	Members pages: member photos tab.
	 */
	public function tab_photos(){
		
		/* Display settings */
		$sort = Request::getCmd('sort_photos', 'date');
		if (!in_array($sort, array('date', 'comments'))){
			$sort = 'date';	// Default tab.
		}
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/connect/tab_photos/container.php');
		
	}
	
	/**
	 *	Members pages: members listing box.
	 */
	public function listing_members($sort = null){
		
		/* Get arguments */
		$limit = 18;
		$page = Request::getInt('page_members', 1);
		$offset = ($page - 1) * $limit;
		
		/* Get parameters */
		$options = array();
		$total = null;
		$sort = $sort ? $sort : Request::getCmd('sort_members', BluApplication::getSetting('listingSort', 'date'));
		switch($sort){
			case 'title':
				$options['order'] = 'name';
				break;
				
			case 'industry':
				$options['order'] = 'industry';
				break;
				
			case 'active':
				$options['order'] = 'active';
				$options['days'] = 30;	// Only display most active members from *past month*.
				break;
			
			case 'date':
				/* Same as default */
				
			default:
				$sort = 'date';
				$options['order'] = 'date';
				break;
		}
		
		/* Get data */
		$personModel = $this->getModel('newperson');
		$people = $personModel->getPeople($offset, $limit, $total, $options);
		
		/* Set up pagination */
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '?sort_members='.urlencode($sort).'&amp;page_members='
		));
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/connect/listing_members/box.php');
	}
	
	/**
	 *	Members pages: photos listing box.
	 */
	public function listing_photos($sort = null){
		
		/* Get arguments */
		$limit = 18;
		$page = Request::getInt('page_photos', 1);
		$offset = ($page - 1) * $limit;
		
		/* Get parameters */
		$total = null;
		$sort = $sort ? $sort : Request::getCmd('sort_photos', BluApplication::getSetting('listingSort', 'date'));
		$options = array();
		switch($sort){
			case 'comments':
				$options['order'] = 'comments';
				break;
			
			case 'date':
			default:
				$options['order'] = 'date';
				break;
		}
		
		/*Filter live photos*/
		$options['status'] = 1;
		
		/* Get data */
		$photosModel = $this->getModel('newphotos');
		$photos = $photosModel->getPhotos($offset, $limit, $total, $options);
		$photosModel->addDetails($photos);
		
		/* Set up pagination */
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '?sort_photos='.urlencode($sort).'&amp;page_photos='
		));
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/connect/listing_photos/box.php');
	}
	
	/**
	 *	Members pages: search members listing box.
	 */
	public function listing_search(){
		
		/* Get arguments */
		$limit = 18;
		$page = Request::getInt('page', 1);
		$offset = ($page - 1) * $limit;
		
		/* Get models */
		$personModel = $this->getModel('newperson');
		$userModel = $this->getModel('user');
		
		/* Prepare pagination URL */
		$queryString = array();
		
		/* Get search criteria */
		$options = array();
		if ($name = Request::getString('name')){
			$options['name'] = $name;
			$queryString['name'] = $name;
		}
		if ($location = Request::getString('location')){
			$options['location'] = $location;
			$queryString['location'] = $location;
		}
		if ($industry = Request::getString('industry')){
			$options['industry'] = $industry;
			$queryString['industry'] = $industry;
		}
		if ($interests = Request::getString('interests')){
			$options['interests'] = $interests;
			$queryString['interests'] = $interests;
		}
		if ($currentTag = Request::getString('tag')){
			$options['tags'] = $currentTag;
			$queryString['tag'] = $currentTag;
		}
		
		/* Get URLs from options. */
		$clearTagsUrl = Router::http_build_str(array_diff_key($queryString, array_flip(array('tag'))), '?');
		$paginationUrl = Router::http_build_str(array_merge($queryString, array('page' => '')), '?');
		
		/* Get data */
		$total = 0;
		$people = $personModel->getPeople($offset, $limit, $total, $options);
		
		/* Set up pagination */
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => $paginationUrl
		));
	
		/* Get display data */
		$industries = $userModel->getIndustries();
		$tagCloud = array(
			'blogger' => 0,
			'business owner' => 0,
			'christian' => 0,
			'creative' => 0,
			'entrepreneur' => 1,
			'family' => 0,
			'friend' => 0,
			'fun' => 0,
			'health' => 0,
			'home' => 0,
			'loving' => 0,
			'marketing' => 0,
			'married' => 0,
			'mom' => 1,
			'mother' => 0,
			'photographer' => 0,
			'reader' => 0,
			'reading' => 0,
			'single mom' => 0,
			'student' => 0,
			'teacher' => 0,
			'travel' => 0,
			'wahm' => 0,
			'wife' => 1,
			'work' => 0,
			'work at home' => 0,
			'work from home' => 0,
			'working mom' => 0,
			'writer' => 1,
			'yoga' => 0
		);	// BODGE - should be dynamic data.
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/connect/member_search/box.php');
	}
	
	/**
	 *	Member search page.
	 */
	public function member_search(){
	
		/* Set page title */
		$this->_doc->setTitle('Search for Members');
		$this->_doc->setAdPage(OpenX::PAGE_ARTICLE);
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/connect/member_search.php');
		
	}
	
	public function member_question(){
		$featuredQuestion = BluApplication::getModel('items')->getIndexFeatured('question');
		$featuredComments = $featuredQuestion->getComments();
		$featuredAnswer = array_shift($featuredComments);
		if (!DEBUG && !$featuredQuestion){ return false; }
		$link = Uri::build($featuredQuestion);
		include(BLUPATH_TEMPLATES.'/connect/landing/member_question.php');
	}
	
}
	
?>
