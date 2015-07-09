<?php

/**
 *	Provides default functionality for category landing pages.
 */
abstract class WorkitmomCategoriesController extends ClientFrontendController {

	/**
	 *	The default category page template to include.
	 */
	protected $_template = '/category/landing.php';

	/**
	 *	The category name.
	 */
	protected $_category = null;

	/**
	 *	Category landing pages.
	 */
	protected function _categoryPage()
	{
		/* Other variables */
		$categorySlug = $this->_categorySlug;
		$category = $this->_category;

		/* Set page title */
		$this->_doc->setTitle($category);
		$this->_doc->setAdPage(OpenX::PAGE_LANDING);

		/* Set up SiteModules */
		$siteModules = BluApplication::getModules('site');
		$siteModules->set('category', $category);

		// Get items model, remember to search items with the category parameter everywhere.
		$itemsModel = $this->getModel('items');
		$itemsModel->set('category', $category);

		// Get useful resources - i.e. one featured item of every item type, as an associative array with the database values 'articleType' as keys.
		$resources = $itemsModel->getResources();

		// "Meet a member" - takes the first featured interview.
		$featuredInterview = $itemsModel->getFeatured('interview');

		// "Working mom news"
		$latestNews = $itemsModel->getLatest('news', 0, 3);

		/* Sidebar - Get the essentials */
		$essentialguides = $itemsModel->getLatest('landingpage', 0, 6);
		
		/* Load template */
		include (BLUPATH_TEMPLATES . $this->_template);
	}

	/**
	 *	Featured article heading block.
	 */
	protected function category_featured_article()
	{
		/* Get model */
		$itemsModel = $this->getModel('items');
		$itemsModel->set('category', $this->_category);

		/* Get data */
		$featuredArticle = $itemsModel->getIndexFeatured('article');
		$link = Uri::build($featuredArticle);
		$rating = $featuredArticle->rating;

		/* Load template */
		include(BLUPATH_TEMPLATES . '/category/landing/featured_article.php');
	}

}

?>
