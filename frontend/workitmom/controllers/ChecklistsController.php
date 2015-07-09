<?php

/**
 *	Articles of type `articleType` = 'list'
 */
class WorkitmomChecklistsController extends WorkitmomItemsController {

	/**
	 *	Construct.
	 */
	public function __construct($args)
	{
		/* Set variables */
		$this->_itemtype = 'list';
		$this->itemtype_singular = 'Checklist';
		$this->itemtype_plural = 'Checklists';
		
		/* Add breadcrumb */
		BluApplication::getBreadcrumbs()->add('Save Time', '/savetime/');
		
		/* ItemsController constructor */
		parent::__construct($args);
		
		/* Set header ad */
		$this->_doc->setAdPage(OpenX::PAGE_ARTICLES);
	}

	/**
	 *	Default BROWSE page.
	 */
	public function view()
	{
		/* Load template */
		include (BLUPATH_TEMPLATES.'/checklists/landing.php');
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
	 *	Landing page: checklists intro.
	 */
	protected function landing_intro(){
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/checklists/landing/intro.php');
		
	}
	
	/**
	 *	Landing page: featured checklists.
	 */
	protected function landing_featured(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$featureditems = $itemsModel->getIndexFeatured($this->_itemtype, 0, 3);
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/checklists/landing/featured.php');
		
	}
	
	/**
	 *	Overrides ItemsController.
	 */
	protected function detail_sidebar(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$latestChecklists = $itemsModel->getLatest($this->_itemtype, 0, 5);
		
		/* Display sidebar. */
		$this->sidebar(array(
			array(
				'latest',
				$this->itemtype_plural,
				$latestChecklists
			),
		'slideshow_featured', 'marketplace', 'catch_your_breath'

		));
		
	}

}
?>
