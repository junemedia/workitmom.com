<?php

/**
 *	Functionality for dealing with comments.
 */
abstract class WorkitmomCommentsController extends WorkitmomReportsController {

	/**
	 *	Add comment: request handler.
	 */
	public function add_comment(){

		/* Require user */
		if (!$commenter = $this->_requireUser('Please sign in, or sign up, to post a comment.')) {
			return false;
		}

		/* Build comment arguments from request variables. */
		$arguments = array();
		$this->add_comment_parse_args($arguments);
		$arguments['commentOwner'] = $commenter->userid;

		/* Do the deed. */
		$object = $this->get_commentable_object();
		if ($success = $object->addComment($arguments)){
			$message = $this->add_comment_success_message();
			$messageType = 'info';
		} else {
			$message = $this->add_comment_failure_message();
			$messageType = 'error';
		}

		/* Add a message */
		Messages::addMessage($message, $messageType);

		/* Redirect back to profile page. */
		$this->_redirect(Uri::build($object));
		return (bool) $success;

	}

	/**
	 *	Add comment: parse new comment's arguments.
	 */
	protected function add_comment_parse_args(&$args){
		$args['commentBody'] = Request::getString('body');
	}

	/**
	 *	Add comment: set success message.
	 */
	protected function add_comment_success_message(){
		return 'Thank you for <a href="#comments" class="scroll">commenting</a>.';
	}

	/**
	 *	Add comment: set failure message.
	 */
	protected function add_comment_failure_message(){
		return 'Sorry, your comment could not be saved. Please try again.';
	}

	/**
	 *	Add comment: display template block
	 */
	protected function comments_add(array $options = array()){

		/* Options */
		if (Utility::is_loopable($options)){
			foreach($options as $key => $value){
				switch($key){
					case 'type':
						$type = $value;
						break;
				}
			}
		}

		/* Load template */
		include(BLUPATH_TEMPLATES.'/comments/add.php');

	}

	/**
	 *	Report comments: request handler.
	 */
	public function report_comment(){
		
		/* Require user. */
		if (!$this->_requireUser('Please sign in to report a comment.')){
			return false;
		}
		
		/* Get arguments */
		$commentId = Request::getInt('comment');
		
		/* Report object. */
		if ($this->report('comment', $commentId)){
			$message = 'The comment has been reported.';
			$messageType = 'info';
		} else {
			$message = 'Sorry, the comment could not be reported.';
			$messageType = 'error';
		}

		/* Add message */
		Messages::addMessage($message, $messageType);

		/* Redirect to the commented object's detail page. */
		$object = $this->get_commentable_object();
		return $this->_redirect(Uri::build($object));

	}
	
	/**
	 *	Overrides ReportsController.
	 */
	protected function report_extra($reportId){
		return $this->report_legacy($reportId);
	}
	
	/**
	 *	For leverage of legacy 'commentReports' table, and 'commentReported' field in 'comments' table.
	 */
	private function report_legacy($reportId){
		
		/* Get report */
		$reportsModel = $this->getModel('reports');
		$report = $reportsModel->getReport($reportId);
		
		/* Report comment in comments table - for performance. */
		$commentsModel = $this->getModel('comments');
		$comment = $commentsModel->getComment($report['objectId']);
		$reported = $commentsModel->reportComment($comment, BluApplication::getUser());
		
		/* Return */
		return $reported;
		
	}
	

	/**
	 *	View comments: request handler.
	 */
	public function view_comments(array $options = array()){

		/* Options */
		if (Utility::is_loopable($options)){
			foreach($options as $key => $value){
				switch($key){
					case 'extraCss':
						$extraCss = $value;
						break;
				}
			}
		}

		/* Get comments */
		$object = $this->get_commentable_object();
		$comments = $object->getComments();
		if (!Utility::is_loopable($comments)){
			echo '<p class="text-content">No comments yet.';
			// echo '<a class="scroll" href="#comment">Make a comment.</a>';
			echo '</p>';
			return null;
		}

		/* Prepare pagination */
		$limit = BluApplication::getSetting('commentListingLength', 12);
		$total = count($comments);
		$page = Request::getInt('comments', 1);
		$commentPagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
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

		/* Options */
		if (Utility::is_loopable($options)){
			foreach($options as $key => $value){
				switch($key){
					case 'type':
						$type = $value;
						break;
				}
			}
		}

		$commentCount = $this->get_commentable_object()->getCommentCount();
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/comments/wrapper.php');

	}
	
	/**
	 *	View individual comment.
	 *
	 *	Checks for delete privileges.
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
	
	/**
	 *	Delete comment
	 */
	public function delete_comment() {
		
		/* Get model */
		$commentsModel = $this->getModel('comments');
		
		/* Get comment */
		$commentId = Request::getInt('comment');
		if (!$comment = $commentsModel->getComment($commentId)) {
			return false;
		}
		
		/* Try to delete. */
		if ($commentsModel->canOfferCommentDeletion($comment)) {
			$commentsModel->deleteComment($comment);
			$object = $this->get_commentable_object();
			return $this->_redirect(Uri::build($object), 'Reply deleted.');
		}
		else return false;
		
	}

	/**
	 *	Fetch the to-be-commented-on object.
	 *
	 *	@return CommentObject (or PersonObject).
	 */
	abstract protected function get_commentable_object();

}

?>
