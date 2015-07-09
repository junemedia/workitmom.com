<?php

/**
 *	Articles of type `articleType` = 'quicktip'
 */
class WorkitmomQuicktipsController extends WorkitmomItemsController {

	/**
	 *	Construct.
	 */
	public function __construct($args)
	{
		/* Set variables */
		$this->_itemtype = 'quicktip';
		$this->itemtype_singular = 'Quick Tip';
		$this->itemtype_plural = 'Quick Tips';
		
		/* Add breadcrumb */
		BluApplication::getBreadcrumbs()->add('Save Time', '/savetime/');
		
		/* ItemsController constructor */
		parent::__construct($args);
		
		/* Set header ads */
		$this->_doc->setAdPage(OpenX::PAGE_QUICKTIPS);
	}

	/**
	 *	Default BROWSE page.
	 */
	public function view()
	{
		/* Load template */
		include (BLUPATH_TEMPLATES.'/quicktips/landing.php');
	}
	
	/**
	 *	Landing page: intro.
	 */
	protected function landing_intro(){
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/quicktips/landing/intro.php');
		
	}
	
	/**
	 *	Landing page: featured quick tips.
	 */
	protected function landing_featured(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$featureditems = $itemsModel->getIndexFeatured($this->_itemtype, 0, 3);
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/quicktips/landing/featured.php');
		
	}
	
	/**
	 *	Overrides ItemsController.
	 */
	protected function listing_list_customise_css(array &$css){
		$css[] = 'grid_list';
	}

	/**
	 *	Overrides ItemsController.
	 */
	protected function listing_individual_template()
	{
		return BLUPATH_TEMPLATES . '/site/landing/listing_individual_grid.php';
	}
	
	/**
	 *	Details page: default sidebar.
	 */
	protected function detail_sidebar(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$latestQuicktips = $itemsModel->getLatest($this->_itemtype, 0, 5);
		
		/* Display sidebar. */
		$this->sidebar(array(
			array(
				'latest',
				$this->itemtype_plural,
				$latestQuicktips
			),
			'slideshow_featured',
			'marketplace',
			'catch_your_breath'
		));
		
	}

}
?>
