<?php

/**
 *	Articles of type `articleType` = 'article'
 */
class WorkitmomArticlesController extends WorkitmomItemsController {

	/**
	 * Construct.
	 */
	public function __construct($args)
	{
		/* Set variables */
		$this->_itemtype = 'article';
		$this->itemtype_singular = 'Article';
		$this->itemtype_plural = 'Articles';

		/* Add breadcrumb */
		BluApplication::getBreadcrumbs()->add('What\'s New', '/');

		/* ItemsController constructor. */
		parent::__construct($args);
		
		/* Set header ad */
		$this->_doc->setAdPage(OpenX::PAGE_ARTICLES);
	}

	/**
	 * Default BROWSE page.
	 */
	public function view()
	{
		/* Get items */
		$itemsModel = $this->getModel('items');
		$mostvieweditems = $itemsModel->getMostViewed($this->_itemtype, 0, 4, $dummy, array('days' => 7));

		/* Load template */
		include (BLUPATH_TEMPLATES . '/articles/landing.php');
	}
	
	/**
	 *	Browse page: index featured article.
	 */
	protected function landing_featured(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$featuredArticle = $itemsModel->getIndexFeatured($this->_itemtype);
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/articles/landing/featured.php');
		
	}
	
	/**
	 *	Overrides ItemsController.
	 */
	protected function detail_body_content(){
		
		/* Get parent */
		$content = parent::detail_body_content();
		
		/* Strip out divs. */
		return Utility::tag_replace('div', '', $content);
		
	}
	
	/**
	 *	Overrides ItemsController.
	 */
	protected function detail_breadcrumbs(){
		
		/* Get data */
		$item =& $this->_item;
		$category = $item->getCategory();
		
		/* Add breadcrumbs */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		if ($category){
			$breadcrumbs->add($category, SITEURL . '/articles/?category='.urlencode($category).'#articles_listing');
		}
		$breadcrumbs->add($item->title, $this->_getItemURL($item));
		
	}

	/**
	 *	Details page: default articles sidebar.
	 */
	protected function detail_sidebar(){
		$this->sidebar(array(
			'article_write',
			'slideshow_featured',
			'marketplace',
			'catch_your_breath'
		));
	}
	
	/**
	 *	Overrides CommentsController.
	 *
	 *	Creates and sends an alert to article author.
	 */
	public function add_comment(){
		
		/* Parent */
		$added = parent::add_comment();
		
		/* Send alert if successful */
		if ($added){
			
			/* Get commented object */
			$object = $this->get_commentable_object();
			
			/* Fetch the people involved. */
			$personModel = $this->getModel('newperson');
			$commentor = BluApplication::getUser();
			$author = $object->author;
			
			/* Create alert using commentor details */
			$alertsModel = $this->getModel('alerts');
			$alertId = $alertsModel->createAlert('articlereply', array(
				'itemId' => $object->id,
				'itemTitle' => $object->title
			), $commentor->userid);
			
			/* Apply alert for article author */
			$alertsModel->applyAlert($alertId, $author->userid);

		}
		
		/* Continue logic */
		return $added;
		
	}

}
?>
