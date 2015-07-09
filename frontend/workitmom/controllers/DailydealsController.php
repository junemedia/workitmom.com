<?php

/**
 *	Articles of type `articleType` = 'dailydeal'
 */
class WorkitmomDailydealsController extends WorkitmomItemsController {

	/**
	 *	Construct.
	 */
	public function __construct($args)
	{
		/* Set variables */
		$this->_itemtype = 'dailydeal';
		$this->itemtype_singular = 'Daily Deal';
		$this->itemtype_plural = 'Daily Deals';

		/* Add breadcrumb */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('De-stress', '/destress');

		/* ItemsController constructor. */
		parent::__construct($args);
	}

	/**
	 *	Default BROWSE page.
	 */
	public function view(){

		/* Get items */
		$itemsModel = $this->getModel('items');
		$latestitems = $itemsModel->getLatest($this->_itemtype, 0, 4);
		$popularitems = $itemsModel->getMostCommented($this->_itemtype, 0, 3);
		$featureditem = $itemsModel->getFeatured($this->_itemtype, 0, 1);
		
		/* Set header ad */
		$this->_doc->setAdPage(OpenX::PAGE_MARKETPLACE);

		/* Load template */
		include (BLUPATH_TEMPLATES . '/dailydeals/landing.php');

	}

}

?>
