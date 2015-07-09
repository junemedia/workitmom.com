<?php

/**
 *	Marketplace is a big bodge. Needs to be tidied.
 */
class WorkitmomShopController extends ClientFrontendController{

	/**
	 *	The current product.
	 */
	private $_product;

	/**
	 * Display home page
	 */
	public function view(){

		// Add breadcrumb
		$this->_addBreadcrumb();

		// Set page title
		$this->_doc->setTitle('Shop');
		$this->_doc->setAdPage(OpenX::PAGE_MARKETPLACE);

		// Get daily deal and news
		$itemsModel = $this->getModel('items');
		$featuredDeals = $itemsModel->getFeatured('dailydeal', 0, 3);
		$latestNews = $itemsModel->getLatest('news', 0, 4);

		// Get marketplace listings
		$marketplaceModel = $this->getModel('marketplace');
		$total = false;
		$featuredListings = $marketplaceModel->getListings(0, 4, $false, 'popular');

		// Load page template
		include (BLUPATH_TEMPLATES . '/shop/landing.php');
	}

	/**
	 *	Show item detail box.
	 */
	protected function product_box(ProductObject $product)
	{
		/* Display helper */
		$modules = BluApplication::getModules('marketplace', $product);

		/* Load templates */
		include(BLUPATH_TEMPLATES . '/shop/product/box.php');
	}

	/**
	 *	"You may also like" block.
	 */
	protected function product_related()
	{
		/* Load templates */
		include(BLUPATH_TEMPLATES . '/blocks/marketplace/you_may_also_like.php');
	}
	
	/**
	 *	Landing page: giveaways block.
	 */
	protected function landing_giveaways(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$giveaway = $itemsModel->getGiveaway();
		
		/* Mangle data */
		$title = $giveaway['articleTitle'];	// This is always empty! Duh.
		$abridgedBody = Text::trim($giveaway['articleBody'], 110, false);
		$image = $giveaway['articleImage'];
		$link = SITEURL . '/giveaways/';
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/shop/landing/giveaway.php');
		
	}

}

?>
