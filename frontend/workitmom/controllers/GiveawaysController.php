<?php

/**
 *	Giveaways controller.
 */
class WorkitmomGiveawaysController extends ClientFrontendController {

	/**
	 *	Giveaway data.
	 */
	private $_giveaway;

	/**
	 *	Default (details) page.
	 */
	public function view(){
	
		return $this->_redirect('/');

		/* Get data */
		$itemsModel = $this->getModel('items');
		$giveaway = $this->_giveaway = $itemsModel->getGiveaway();

		/* Add breadcrumbs */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Shop', SITEURL . '/shop/');
		$breadcrumbs->add('Giveaways', SITEURL . '/giveaways/');
		
		/* Set header ad. */
		$this->_doc->setAdPage(OpenX::PAGE_MARKETPLACE);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/giveaways/details.php');

	}



	### 							Comments stuff - needs to be sorted out, ideally would extend CommentsController.					###

	/**
	 *	Add a comment.
	 */
	public function add_comment(){

		/* Require user */
		if (!$this->_requireUser('You must be logged in to post a comment')){ return false; }
		$user = BluApplication::getUser();

		/* Get giveaway data */
		$itemsModel = $this->getModel('items');
		$giveaway = $this->_giveaway = $itemsModel->getGiveaway();

		/* Build arguments */
		$args = array();
		$args['commentType'] = 'article';
		$args['commentTypeObjectId'] = $giveaway['articleId'];
		$args['commentOwner'] = $user->userid;
		$args['commentBody'] = Request::getString('body');

		/* Add comment */
		$commentsModel = $this->getModel('comments');
		$success = (bool) $commentsModel->addComment($args);

		/* Add message */
		$message = $success ? 'Thankyou for commenting.' : 'Sorry, your comment could not be saved, please try again.';
		$messageType = $success ? 'info' : 'error';

		/* Redirect */
		return $this->_redirect('/giveaways/', $message, $messageType);

	}

	/**
	 *	Report a comment.
	 */
	public function report_comment(){

		/* Require user */
		if (!$this->_requireUser('Please sign in or register to report a comment.')) { return false; }
		$user = BluApplication::getUser();

		/* Get model */
		$commentsModel = $this->getModel('comments');

		/* Get parameters */
		$commentID = Request::getInt('comment', null);

		/* Update model */
		$success = false;
		if ($commentID){
			$comment = $commentsModel->getComment($commentID, 'item');
			$success = $comment->report($user);
		}

		/* Add message */
		$message = $success ? 'The comment has been reported.' : 'Sorry, the comment could not be reported.';
		$messageType = $success ? 'info' : 'error';

		/* Redirect */
		return $this->_redirect('/giveaways/', $message, $messageType);

	}

	/**
	 *	Giveaway page add a comment block.
	 */
	protected function comments_add(array $options = array()){

		/* Load template */
		include(BLUPATH_TEMPLATES.'/comments/add.php');

	}

	/**
	 *	View comments: request handler.
	 */
	public function view_comments(array $options = array()){
		/* Get comments */
		$commentsModel = $this->getModel('comments');
		$comments = $commentsModel->getAny('article', $this->_giveaway['articleID']);
		if (!Utility::is_loopable($comments)){
			echo '<p class="text-content">No comments made yet. <a class="scroll" href="#comment">Make a comment.</a></p>';
			return null;
		}

		/* Prepare pagination */
		$commentPagination = Pagination::simple(array(
			'limit' => BluApplication::getSetting('commentListingLength', 12),
			'total' => count($comments),
			'current' => Request::getInt('comments', 1),
			'url' => '?comments='
		));
		$commentPagination->chop($comments);

		/* Display */
		$alt = false;
		include(BLUPATH_TEMPLATES.'/comments/listing.php');

	}

	/**
	 *	View comments: display template module.
	 */
	protected function comments_view(array $options = array()){
		
		/* Get comment count */
		$commentsModel = $this->getModel('comments');
		$comments = $commentsModel->getAny('article', $this->_giveaway['articleID']);
		$commentCount = count($comments);

		/* Load template */
		include(BLUPATH_TEMPLATES.'/comments/wrapper.php');

	}

	/**
	 * View individual comment.
	 *
	 * Checks for delete privileges.
	 */
	protected function comments_view_individual($comment){

		/* Alternating colours */
		static $alt = false;
		$alt = !$alt;

		/* Check delete privileges */
		$commentsModel = $this->getModel('comments');
		$canDelete = $commentsModel->canOfferCommentDeletion($comment);

		/* Load template */
		include(BLUPATH_TEMPLATES.'/comments/individual.php');
	}

	###							END Comments stuff							###









	/**
	 *	Giveaway page title
	 */
	protected function detail_title(){

		/* Get data */
		$giveaway =& $this->_giveaway;
		$date = Utility::formatDate($giveaway['articleTime']);
		$commentsModel = $this->getModel('comments');
		$comments = $commentsModel->getAny('article', $this->_giveaway['articleID']);
		$commentCount = count($comments);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/giveaways/details/title.php');

	}

	/**
	 *	Giveaway page content.
	 */
	protected function detail_body(){

		/* Get data */
		$giveaway =& $this->_giveaway;
		$title = $giveaway['articleTitle'];	// This is fixed in the database! Duh.
		$body = $giveaway['articleBody'];
		$image = $giveaway['articleImage'];

		/* Load template */
		include(BLUPATH_TEMPLATES . '/giveaways/details/body.php');

	}

}

?>