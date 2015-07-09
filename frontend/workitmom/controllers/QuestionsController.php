<?php

/**
 *	Articles of type `articleType` = 'question'
 */
class WorkitmomQuestionsController extends WorkitmomItemsController {

	/**
	 *	Construct.
	 */
	public function __construct($args)
	{
		/* Set variables */
		$this->_itemtype = 'question';
		$this->itemtype_singular = 'Question';
		$this->itemtype_plural = 'Questions';

		/* Add breadcrumb */
		BluApplication::getBreadcrumbs()->add('Connect', '/connect/');

		/* ItemsController */
		parent::__construct($args);
		
		// Set header
		$this->_doc->setAdPage(OpenX::PAGE_QUESTIONS);
	}

	/**
	 *	Default BROWSE page.
	 */
	public function view()
	{
		/* Load template */
		include (BLUPATH_TEMPLATES . '/questions/landing.php');
	}

	/**
	 *	Overrides ItemsController.
	 */
	protected function listing_individual_template()
	{
		return BLUPATH_TEMPLATES . '/questions/landing/listing_individual.php';
	}
	
	/**
	 *	Overrides ItemsController.
	 */
	protected function listing_sorter_customise_sorts(array &$sorts){
		
		/* Replace the text 'most comments' with 'most replies' */
		$sorts['comments'] = 'Most Replies';
		
	}

	/**
	 *	Overrides ItemsController
	 */
	protected function detail_template(&$template, &$cssClass){
		$cssClass = 'questions';
		$template = '/questions/details.php';
	}

	/**
	 *	Overrides ItemsController.
	 */
	protected function detail_title_template()
	{
		return BLUPATH_TEMPLATES . '/questions/details/title.php';
	}
	
	/**
	 *	Overrides ItemsController.
	 */
	public function detail(){
		
		/* Do the usual stuff first */
		parent::detail();
		
		/* Set header ad */
		$this->_doc->setAdPage(OpenX::PAGE_QUESTION); 
		
	}
	
	/**
	 *	Landing page: ask a question module.
	 */
	protected function landing_ask_question(){
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/questions/landing/ask_question.php');
		
	}
	
	/**
	 *	Landing page: featured question.
	 */
	protected function landing_featured_question(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$featureditem = $itemsModel->getIndexFeatured($this->_itemtype);
		$link = Uri::build($featureditem);
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/questions/landing/featured.php');
		
	}	
	
	/**
	 *	Overrides CommentsController.
	 *
	 *	Creates and sends an alert to the question author.
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
			$alertId = $alertsModel->createAlert('questionreply', array(
				'itemId' => $object->id,
				'itemTitle' => $object->title
			), $commentor->userid);
			
			/* Apply alert for article author */
			$alertsModel->applyAlert($alertId, $author->userid);

		}
		
		/* Continue logic */
		return $added;
		
	}
	
	/**
	 *	Overrides CommentsController.
	 */
	protected function add_comment_success_message(){
		return 'Thanks for your response to the question.';
	}
	
	/**
	 *	Submit a question.
	 */
	public function submit()
	{
		/* Require user */
		if (!$this->_requireUser('Please sign in, or sign up, to submit a question.')) { return false; }
		$user = BluApplication::getUser();

		/* Require content creator */
		$this->getModel('person')->ensureContentCreator($user);

		/* Get parameters */
		$content = Request::getString('form_question');
		if (!$content) {
			return $this->view();
		}

		/* Build new item arguments */
		$itemargs['articleLive'] = 1;
		$itemargs['articleAuthor'] = $user->contentcreatorid;
		$itemargs['articleTitle'] = $content;
		$itemargs['articleBody'] = $content;
		$itemargs['articleType'] = $this->_itemtype;

		/* Create item */
		$itemsModel = $this->getModel('items');
		$item = $itemsModel->createItem($itemargs);

		/* Finish and redirect */
		$this->_redirect($this->_getItemURL($item), "Thank you for submitting your " . $this->_itemtype . ".");
	}

}
?>
