<?php

/**
 *	Articles of type `articleType` = 'interview'
 */
class WorkitmomInterviewsController extends WorkitmomItemsController {

	/**
	 *	Construct.
	 */
	public function __construct($args)
	{
		/* Set variables */
		$this->_itemtype = 'interview';
		$this->itemtype_singular = 'Interview';
		$this->itemtype_plural = 'Interviews';

		/* Add breadcrumb */
		BluApplication::getBreadcrumbs()->add('Connect', '/connect/');

		/* ItemsController constructor */
		parent::__construct($args);
		
		/* Set header ad */
		$this->_doc->setAdPage(OpenX::PAGE_INTERVIEWS);
	}

	/**
	 *	Default BROWSE page.
	 */
	public function view()
	{
		/* Get items */
		$itemsModel = $this->getModel('items');
		$featureditems = $itemsModel->getIndexFeatured($this->_itemtype, 0, 2);

		/* Load template */
		include (BLUPATH_TEMPLATES . '/interviews/landing.php');
	}
	
	/**
	 *	Landing page: featured item.
	 */
	protected function landing_featured_individual(InterviewObject $item){
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/interviews/landing/featured.php');
		
	}

	/**
	 *	Overrides ItemsController.
	 */
	protected function listing_individual_template()
	{
		return BLUPATH_TEMPLATES . '/interviews/landing/listing_individual.php';
	}
	
	/**
	 *	Overrides ItemsController.
	 */
	protected function detail_title_template(){
		return BLUPATH_TEMPLATES.'/interviews/details/title.php';
	}

}
?>
