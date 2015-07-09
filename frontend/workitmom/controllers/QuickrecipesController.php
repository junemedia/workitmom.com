<?php

/**
 *	Articles of type `articleType` = 'quickrecipe'
 */
class WorkitmomQuickrecipesController extends WorkitmomItemsController {

	/**
	 *	Construct.
	 */
	public function __construct($args)
	{
		/* Set variables */
		$this->_itemtype = 'recipe';
		$this->itemtype_singular = 'Quick Recipe';
		$this->itemtype_plural = 'Quick Recipes';

		/* Add breadcrumb */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('What\'s New', '/');

		/* ItemsController constructor. */
		parent::__construct($args);
	}

	/**
	 *	Default BROWSE page.
	 */
	public function view()
	{
		/* Set header ad. */
		$this->_doc->setAdPage(OpenX::PAGE_QUESTIONS);	// Err...looks suspectingly like a quick Ctrl c + v...
		
		/* Load template */
		include (BLUPATH_TEMPLATES . '/quickrecipes/landing.php');
	}

	/**
	 *	Landing page: featured quick recipes.
	 */
	protected function landing_featured()
	{
		/* Get data */
		$itemsModel = $this->getModel('items');
		$featuredRecipe = $itemsModel->getLatest($this->_itemtype);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/quickrecipes/landing/featured.php');

	}

	/**
	 *	Overrides ItemsController.
	 */
	protected function detail_template(&$template, &$cssClass)
	{
		$template = '/quickrecipes/detail.php';
	}
	
	/**
	 *	Overrides ItemsController.
	 */
	public function detail(){
		
		/* Do the usual stuff first */
		parent::detail();
		
		/* Set header ad */
		$this->_doc->setAdPage(OpenX::PAGE_ARTICLE); 
		
	}

	/**
	 *	Overrides ItemsController.
	 */
	public function listing()
	{
		/* Get request variables */
		$page = Request::getInt('page', 1);

		/* Get parameters. */
		$total = true;
		$limit = BluApplication::getSetting('listingLength', 9);
		
		/* Get model */
		$itemsModel = $this->getModel('items');
		
		/* Get the latest (featured) recipe. */
		$the_latest = $itemsModel->getLatest('recipe');

		/* Get data - Nataly doesn't want the "featured" (latest) recipe shown again in the listing. */
		$items = $itemsModel->getLatest('recipe', ($page - 1) * $limit, $limit, $total, array('exclude' => array($the_latest->id)));

		/* Prepare pagination */
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '?page='
		));

		/* Load template */
		include($this->listing_template());
	}

	/**
	 *	Overrides ItemsController.
	 */
	protected function listing_template(){
		return BLUPATH_TEMPLATES . '/quickrecipes/landing/box.php';
	}

}

?>