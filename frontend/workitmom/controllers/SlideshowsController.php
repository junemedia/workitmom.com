<?php

/**
 *	Articles of type `articleType` = 'slideshow'
 */
class WorkitmomSlideshowsController extends WorkitmomItemsController {

	/**
	 *	Construct.
	 */
	public function __construct($args)
	{
		/* Set variables */
		$this->_itemtype = 'slideshow';
		$this->itemtype_singular = 'Slideshow';
		$this->itemtype_plural = 'Slideshows';

		/* Add breadcrumb */
		BluApplication::getBreadcrumbs()->add('What\'s New', '/');
		
		/* ItemsController constructor */
		parent::__construct($args);
	}

	/**
	 *	Default BROWSE page.
	 */
	public function view()
	{
		/* Get items */
		$itemsModel = $this->getModel('items');
		$latestitems = $itemsModel->getLatest($this->_itemtype, 0, 9);
		
		/* Set header ad */
		$this->_doc->setAdPage(OpenX::PAGE_ARTICLES);

		/* Load template */
		include (BLUPATH_TEMPLATES.'/slideshows/landing.php');
	}
	
	/**
	 *	No ads, thanks.
	 *
	 *	Overrides ItemsController
	 */
	protected function listing_ad() {}
	
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
	 *	Landing page: featured slideshow #1.
	 */
	protected function landing_featured_main(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$featureditem = $itemsModel->getIndexFeatured($this->_itemtype);
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/slideshows/landing/featured.php');
		
	}
	
	/**
	 *	Landing page: featured slideshows #2-#5.
	 */
	protected function landing_featured_others(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$nextfeatureditems = $itemsModel->getIndexFeatured($this->_itemtype, 1, 4);
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/slideshows/landing/others.php');
		
	}
	
	/**
	 *	Overrides ItemsController.
	 */
	public function detail(){
		
		/* ItemsController logic */
		parent::detail();
		
		/* Set header ad */
		$this->_doc->setAdPage(OpenX::PAGE_ARTICLE);
		
	}
	
	/**
	 *	Overrides ItemsController
	 */
	protected function detail_template(&$template, &$cssClass){
		
		/* Prepare template */
		$cssClass = 'slideshows';
		$template = '/slideshows/details.php';
		
	}

	/**
	 *	Overrides ItemsController
	 */
	protected function detail_title_template()
	{
		return BLUPATH_TEMPLATES . '/slideshows/details/title.php';
	}

	/**
	 *	Overrides ItemsController
	 */
	public function detail_body($page = 1)
	{
		/* Load data */
		$item = $this->detail_item_get();
		$content = $this->detail_body_content();
		$type = $this->_itemtype;
		
		/* Get author, if guest author. */
		if ($item->author && !$item->author->isAdmin() && $item->author->name){
			$guestAuthor = $item->author;
		}

		/* Prepare pagination */
		$pagination = Pagination::simple(array(
			'limit' => 1,
			'total' => count($content),
			'current' => (int) $page,
			'url' => $this->_getItemURL($item) . '/'
		));

		/* Slideshow variables. */
		$slidenumber = $pagination->get('current');
		$slide = $content[$pagination->get('current') - 1];
		$slidecount = $pagination->get('pages');
		
		/* ...more controls */
		$hasPrevious = $slidenumber > 1;
		$hasNext = $slidenumber < $slidecount;
		
		/* Set page title = slideshow title, pages 2 to n also preceded by current slide title */
		$this->_doc->setTitle($page == 1 ? Text::trim($item->title, 50) : Text::trim($slide->title, 30).BluApplication::getSetting('titleSeparator').Text::trim($item->title, 30));
		
		/* Load template */
		include($this->detail_body_template());

	}

	/**
	 *	Overrides ItemsController
	 */
	protected function detail_body_content()
	{
		return $this->detail_item_get()->slides;
	}

	/**
	 *	Overrides ItemsController
	 */
	protected function detail_body_template()
	{
		return BLUPATH_TEMPLATES . '/slideshows/details/body.php';
	}
	
	/**
	 *	Overrides ItemsController.
	 */
	protected function detail_pullquote($num_req = 3)
	{
		// Get data 
		$item = $this->detail_item_get();
		$links = $item->links;

		// Nothing to show?
		if (!Utility::iterable($links)) {
			return false;
		}

		// Load template
		include($this->detail_pullquote_template());
	}

	/**
	 *	Overrides ItemsController
	 */
	protected function detail_pullquote_template()
	{
		return BLUPATH_TEMPLATES.'/slideshows/details/pullquote.php';
	}

}
?>
