<?php

/**
 *	Articles of type `articleType` = 'lifesaver'
 */
class WorkitmomLifesaversController extends WorkitmomItemsController {

	/**
	 *	Construct.
	 */
	public function __construct($args)
	{

		/* Set variables */
		$this->_itemtype = 'lifesaver';
		$this->itemtype_singular = 'Lifesaver';
		$this->itemtype_plural = 'Lifesavers';

		/* Add breadcrumb */
		BluApplication::getBreadcrumbs()->add('De-stress', '/destress/');

		/* ItemsController constructor */
		parent::__construct($args);

		// Set ads
		$this->_doc->setAdPage(OpenX::PAGE_LIFESAVERS);
	}

	/**
	 *	Default BROWSE page.
	 */
	public function view()
	{
		/* Get site modules */
		$siteModules = BluApplication::getModules('site');

		/* Load template */
		include (BLUPATH_TEMPLATES.'/lifesavers/landing.php');
	}
	
	/**
	 *	Overrides ItemsController
	 */
	public function page_heading($extended = false){
		$title = 'Member Lifesavers';
		include(BLUPATH_TEMPLATES.'/lifesavers/blocks/page_title.php');
	}

	/**
	 *	Overrides ItemsController
	 */
	protected function listing_categories($category, $sort)
	{
		return false;
	}

	/**
	 *	Overrides ItemsController.
	 */
	protected function listing_individual_template()
	{
		return BLUPATH_TEMPLATES . '/lifesavers/landing/listing_individual.php';
	}

	/**
	 *	Overrides ItemsController.
	 */
	protected function listing_sorter_customise_sorts(array &$sorts){

		/* Add an extra sorting option: by votes */
		$sorts['votes'] = 'Most Votes';

		/* Remove sorting by most comments - because you can't comment on lifesavers */
		unset($sorts['comments']);

	}

	/**
	 *	Overrides ItemsController
	 */
	public function detail(){
		/* No detail page for lifesavers */
		return $this->_redirect($this->_url);
	}
	
	/**
	 *	Overrides ItemsController
	 *
	 *	The difference being you don't need to be registered to vote for lifesavers, 
	 *	because it will default to 'melinda', or indeed any guest account you may so wish.
	 *
	 *	Also, it redirects to the browse page, rather than the detail page...because the detail page doesn't exist.
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
		return $this->_redirect('/'.$this->_controllerName.'/', $message, $messageType);
	}

	/**
	 *	Submit a lifesaver.
	 */
	public function submit() {

		/* Require user */
		if (!$this->_requireUser('Please sign in, or sign up, to submit a lifesaver.')) { return false; }
		$user = BluApplication::getUser();

		/* Require content creator */
		$this->getModel('person')->ensureContentCreator($user);

		/* Get parameters */
		$content = Request::getString('form_lifesaver');
		if (!$content) {
			return $this->view();
		}

		/* Build new item arguments */
		$itemargs['articleLive'] = 1;
		$itemargs['articleAuthor'] = $user->contentcreatorid;
		$itemargs['articleTitle'] = $content;
		$itemargs['articleBody'] = $content;
		$itemargs['articleType'] = $this->_itemtype;

		/* Create the item */
		$itemsModel = $this->getModel('items');
		$item = $itemsModel->createItem($itemargs);

		/* Finish and redirect - to the browse page, because lifesavers don't have detail pages. */
		Messages::addMessage();
		$this->_redirect($this->_url, "Thank you for submitting your " . $this->_itemtype . ".");

	}

	/**
	 *	Landing page: submit a lifesaver module.
	 */
	protected function landing_submit_lifesaver(){

		/* Load template */
		include(BLUPATH_TEMPLATES . '/lifesavers/landing/submit_lifesaver.php');

	}

}
?>
