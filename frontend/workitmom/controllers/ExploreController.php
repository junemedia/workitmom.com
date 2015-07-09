<?php

/**
 *	This is the "Explore" section. There are no category specific data here.
 */
class WorkitmomExploreController extends WorkitmomCategoriesController {

	/**
	 * Display home page
	 */
	public function view()
	{
		/* Get arguments */
		$args = $this->_args;

		/* Add breadcrumb */
		$this->_addBreadcrumb();

		// Get models. Get data too.
		$itemsModel = $this->getModel('items');
		$articles = array(
			'balancing_act' => $itemsModel->set('category', 'Balancing Act')->getIndexFeatured('article', 0, 3),
			'career_and_money' => $itemsModel->set('category', 'Career & Money')->getIndexFeatured('article', 0, 3),
			'pregnancy_and_parenting' => $itemsModel->set('category', 'Pregnancy & Parenting')->getIndexFeatured('article', 0, 3),
			'your_business' => $itemsModel->set('category', 'Your Business')->getIndexFeatured('article', 0, 3),
			'justforyou' =>	$itemsModel->set('category', 'Just For You')->getIndexFeatured('article', 0, 3)
		);
		$itemsModel->set('category', null);

		// "Featured article"
		$featuredArticle = $itemsModel->getIndexFeatured('article');
		
		/* Set page title */
		$this->_doc->setTitle('Explore');
		$this->_doc->setAdPage(OpenX::PAGE_LANDING);

		// Load page template
		include (BLUPATH_TEMPLATES . '/explore/landing.php');
	}

	/**
	 *	"Balancing Act" category landing page.
	 */
	public function balancing_act()
	{
		/* Prepare page. */
		$this->_categorySlug = __FUNCTION__;
		$this->_category = 'Balancing Act';

		/* Add breadcrumb */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add($this->_controllerName, '/'.strtolower($this->_controllerName));
		$breadcrumbs->add($this->_category, '/'.strtolower($this->_controllerName).'/'.__FUNCTION__);

		/* Display page */
		$this->_categoryPage();
	}

	/**
	 *	"Career & Money" category landing page.
	 */
	public function career_and_money()
	{
		/* Prepare page. */
		$this->_categorySlug = __FUNCTION__;
		$this->_category = 'Career & Money';

		/* Add breadcrumb */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add($this->_controllerName, '/'.strtolower($this->_controllerName));
		$breadcrumbs->add($this->_category, '/'.strtolower($this->_controllerName).'/'.__FUNCTION__);

		/* Display page */
		$this->_categoryPage();
	}

	/**
	 *	"Family & Home" category landing page.
	 */
	public function pregnancy_and_parenting()
	{
		/* Prepare page. */
		$this->_categorySlug = __FUNCTION__;
		$this->_category = 'Pregnancy & Parenting';

		/* Add breadcrumb */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add($this->_controllerName, '/'.strtolower($this->_controllerName));
		$breadcrumbs->add($this->_category, '/'.strtolower($this->_controllerName).'/'.__FUNCTION__);

		/* Display page */
		$this->_categoryPage();
	}

	/**
	 *	"Your Business" category landing page.
	 */
	public function your_business()
	{
		/* Prepare page. */
		$this->_categorySlug = __FUNCTION__;
		$this->_category = 'Your Business';

		/* Add breadcrumb */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add($this->_controllerName, '/'.strtolower($this->_controllerName));
		$breadcrumbs->add($this->_category, '/'.strtolower($this->_controllerName).'/'.__FUNCTION__);

		/* Display page */
		$this->_categoryPage();
	}

	/**
	 *	Landing page - block of featured blogs.
	 */
	private function landing_category_block($articles)
	{
		/* Load common template */
		include(BLUPATH_TEMPLATES . '/explore/landing/articles.php');
	}

	/**
	 *	Landing page - Balancing act module.
	 */
	private function landing_balancing_act($articles)
	{
		/* Get data */
		$link = SITEURL . '/explore/balancing_act';

		/* Load template */
		include(BLUPATH_TEMPLATES . '/explore/landing/balancing_act.php');
	}

	/**
	 *	Landing page - Career and money module.
	 */
	private function landing_career_and_money($articles)
	{
		/* Get data */
		$link = SITEURL . '/explore/career_and_money';

		/* Load template */
		include(BLUPATH_TEMPLATES . '/explore/landing/career_money.php');
	}

	/**
	 *	Landing page - Family and Home module.
	 */
	private function landing_pregnancy_and_parenting($articles)
	{
		/* Get data */
		$link = SITEURL . '/explore/pregnancy_and_parenting';

		/* Load template */
		include(BLUPATH_TEMPLATES . '/explore/landing/pregnancy_and_parenting.php');
	}

	/**
	 *	Landing page - Your Business module.
	 */
	private function landing_your_business($articles)
	{
		/* Get data */
		$link = SITEURL . '/explore/your_business';

		/* Load template */
		include(BLUPATH_TEMPLATES . '/explore/landing/your_business.php');
	}

}

?>
