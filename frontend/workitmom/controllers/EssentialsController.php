<?php

/**
 *	Articles of type `articleType` = 'landingpage'
 */
class WorkitmomEssentialsController extends WorkitmomItemsController {

	/**
	 *	Construct.
	 */
	public function __construct($args)
	{
		/* Set variables */
		$this->_itemtype = 'landingpage';
		$this->itemtype_singular = 'Essential Guide';
		$this->itemtype_plural = 'Essential Guides';

		/* Add breadcrumb */
		BluApplication::getBreadcrumbs()->add('Save Time', '/savetime/');
		
		/* ItemsController constructor */
		parent::__construct($args);
		
		/* Set header ad */
		$this->_doc->setAdPage(OpenX::PAGE_LANDING);
	}

	/**
	 *	Default BROWSE page.
	 */
	public function view()
	{
		/* Load template */
		include (BLUPATH_TEMPLATES . '/essentials/landing.php');
	}
	
	/**
	 *	Overrides ItemsController.
	 */
	protected function listing_list_customise_css(array &$css){
		$css[] = 'grid_list';
	}

	/**
	 *	Overrides ItemsController
	 */
	protected function listing_individual_template()
	{
		return BLUPATH_TEMPLATES . '/site/landing/listing_individual_grid.php';
	}
	
	/**
	 *	Landing page: intro module.
	 */
	protected function landing_intro(){
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/essentials/landing/intro.php');
		
	}
	
	/**
	 *	Landing page: featured essentials guides.
	 */
	protected function landing_featured(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$popularitems = $itemsModel->getMostCommented($this->_itemtype, 0, 3);
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/essentials/landing/featured.php');
		
	}

	/**
	 *	Overrides ItemsController
	 */
	public function detail(){

		/* Get arguments */
		$args = $this->_args;

		/* Parse arguments */
		if (!Utility::is_loopable($args)){ $this->_errorRedirect(); }
		$id = (int) array_shift($args);
		$page = Utility::is_loopable($args) ? (int) array_shift($args) : 1;

		/* Get (and set) the single item */
		$this->_item = $this->getModel('items')->getItem($id);
		$item = $this->_item;

		/* Add breadcrumb */
		BluApplication::getBreadcrumbs()->add($item->title, $this->_getItemURL($item));

		/* Set page title */
		$this->_doc->setTitle($item->title . BluApplication::getSetting('titleSeparator') . $this->itemtype_plural);
		$this->_doc->setAdPage(OpenX::PAGE_LANDING);

		/* Increment view count */
		$item->increaseViews();
		
		/* Get more data */
		$allLinks = $item->getLinks();
		
		/* Transform data */
		$sideTypes = array_flip(array('Quick Tips', 'Checklists'));
		$mainLinks = array_diff_key($allLinks, $sideTypes);
		$otherLinks = array_intersect_key($allLinks, $sideTypes);
		$sideLinks = array();
		foreach(array_keys($otherLinks) as $type){
			$sideLinks = isset($sideLinks) ? $sideLinks : array();
			$sideLinks = array_merge($sideLinks, $otherLinks[$type]);	// Flatten quick tips and checklists.
		}
		$sideLinks = Utility::random($sideLinks, 5);	// Filter by a random 5.
		$sideTypes = array_flip($sideTypes);	// Flip back, for later usage.
		
		/* Load template */
		$template = '/essentials/details.php';
		$cssClass = 'essentials';
		include(BLUPATH_TEMPLATES . $template);

	}
	
	/**
	 *	Details page: default sidebar
	 */
	protected function detail_sidebar(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$latestEssentials = $itemsModel->getLatest($this->_itemtype, 0, 5);
		
		/* Display sidebar */
		$this->sidebar(array(
			array(
				'latest',
				$this->itemtype_plural,
				$latestEssentials
			), 'slideshow_featured', 'marketplace'
		));
		
	}
	
	/**
	 *	A bunch of links of a particular type in main column.
	 */
	protected function detail_essentials_links($type, array $links){
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/essentials/details/links.php');
		
	}

}
?>
