<?php

/**
 *	Articles of type `articleType` = 'news'
 */
class WorkitmomNewsController extends WorkitmomItemsController {

	/**
	 *	Construct.
	 */
	public function __construct($args)
	{
		/* Set variables */
		$this->_itemtype = 'news';
		$this->itemtype_singular = 'News article';
		$this->itemtype_plural = 'News articles';

		/* Add breadcrumb */
		BluApplication::getBreadcrumbs()->add('Explore', '/explore/');
		
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
		$totalthisweek = null;
		$mostvoteditemsthisweek = $itemsModel->getMostVoted($this->_itemtype, 0, 4, $totalthisweek, array('withinDays' => 7));
		$totalthismonth = null;
		$mostvoteditemsthismonth = $itemsModel->getMostVoted($this->_itemtype, 0, 4, $totalthisweek, array('withinDays' => 30));
		
		/* Set header ad */
		$this->_doc->setAdPage(OpenX::PAGE_NEWS);

		/* Load template */
		include (BLUPATH_TEMPLATES . '/news/landing.php');
	}
	
	/**
	 *	Overrides ItemsController
	 *
	 *	The difference being you don't need to be registered to vote for news articles, 
	 *	because it will default to 'melinda', or indeed any guest account you may so wish.
	 */
	public function vote()
	{
		/* Require user */
		if (!$user = BluApplication::getUser()){
			$personModel = $this->getModel('person');
			$guestUsername = BluApplication::getSetting('guestUsername');
			$user = $personModel->getPerson(array('username' => $guestUsername));
		}

		/* Get arguments */
		$args = $this->_args;
		if (!Utility::iterable($args)){
			return $this->_errorRedirect();
		}
		$id = (int) array_shift($args);

		/* Get parameters */
		if (func_num_args() > 0){
			$func_args = func_get_args();
			$override_rating = array_shift($func_args);
		}
		if (isset($override_rating) && $override_rating > 0 && $override_rating < 6){
			$rating = $override_rating;
		} else {
			$rating = Request::getInt('rating', null);
			if (!$rating){
				return $this->_errorRedirect();
			}
		}

		/* Update model */
		$itemsModel = $this->getModel('items');
		$item = $itemsModel->getItem($id);
		$success = $item->addRating($rating, $user);

		/* Load template for BROWSE page */
		$message = ($success ? 'Thank you for rating this ' : 'It has not been possible to rate this ').strtolower($this->itemtype_singular) . '.';
		$messageType = $success ? 'info' : 'error';
		return $this->_redirect('/'.$this->_controllerName.'/detail/'.$id, $message, $messageType);
	}
	
	/**
	 *	Overrides ItemsController
	 */
	public function page_heading($extended = false){
		$title = 'Member News';
		include(BLUPATH_TEMPLATES.'/news/blocks/page_title.php');
	}

	/**
	 *	Overrides ItemsController
	 */
	protected function listing_individual_template()
	{
		return BLUPATH_TEMPLATES . '/items/landing/listing_individual_vote.php';
	}
	
	/**
	 *	Overrides ItemsController.
	 */
	protected function listing_sorter_customise_sorts(array &$sorts){
		
		/* Add an extra sorting option: by votes */
		$sorts['votes'] = 'Most Votes';
		
	}
	
	/**
	 *	No ads, thanks.
	 *
	 *	Overrides ItemsController
	 */
	protected function listing_ad() {}
	
}
?>
