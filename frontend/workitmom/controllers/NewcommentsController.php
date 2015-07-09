<?php

/**
 *	Functionality for dealing with comments. Array version.
 */
abstract class WorkitmomNewcommentsController extends WorkitmomReportsController {

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
		if ($this->add_comment_call_model_add($arguments)){
			$message = $this->add_comment_success_message();
			$messageType = 'info';
		} else {
			$message = $this->add_comment_failure_message();
			$messageType = 'error';
		}

		/* Tidy up */
		Messages::addMessage($message, $messageType);
		return $this->_redirect($this->get_redirect_page());

	}

	/**
	 *	Add comment: parse new comment's arguments.
	 *
	 *	@param array Accumulating arguments.
	 */
	protected function add_comment_parse_args(&$args){
		$args['commentBody'] = Request::getString('body');
	}

	/**
	 *	Add comment: set success message.
	 *
	 *	@return string.
	 */
	protected function add_comment_success_message(){
		return 'Thank you for <a href="#comments" class="scroll">commenting</a>.';
	}

	/**
	 *	Add comment: set success message.
	 *
	 *	@return string.
	 */
	protected function add_comment_failure_message(){
		return 'Sorry, your comment could not be saved. Please try again.';
	}
	
	/**
	 *	Add comment: call model to add the comment to data layer.
	 *
	 *	@param array New comment arguments.
	 *	@return bool.
	 */
	abstract protected function add_comment_call_model_add(array $arguments);

	/**
	 *	Add comment: display template block
	 */
	protected function comments_add(array $options = array()){

		/* Options */
		if (Utility::iterable($options)){
			foreach($options as $key => $value){
				switch($key){
					case 'type':
						$type = $value;
						break;
				}
			}
		}

		/* Load template */
		include(BLUPATH_TEMPLATES . '/newcomments/add.php');

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
			$message = $this->report_comment_success_message();
			$messageType = 'info';
		} else {
			$message = $this->report_comment_failure_message();
			$messageType = 'error';
		}

		/* Tidy up */
		Messages::addMessage($message, $messageType);
		return $this->_redirect($this->get_redirect_page());

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
	 *	Report comment: success message.
	 *
	 *	@return string.
	 */
	protected function report_comment_success_message(){
		return 'The comment has been reported.';
	}
	
	/**
	 *	Report comment: failure message.
	 *
	 *	@return string.
	 */
	protected function report_comment_failure_message(){
		return 'Sorry, the comment could not be reported.';
	}
	

	/**
	 *	View comments: request handler.
	 */
	public function view_comments(array $options = array()){

		/* Options */
		if (Utility::iterable($options)){
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
		$comments = $object['comments'];
		if (empty($comments)){
			include(BLUPATH_TEMPLATES.'/newcomments/none.php');
			return null;
		}
		$commentsModel = $this->getModel('newcomments');
		$commentsModel->addDetails($comments);

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
		include(BLUPATH_TEMPLATES.'/newcomments/listing.php');

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

		/* Get display data */
		$object = $this->get_commentable_object();
		$commentCount = count($object['comments']);
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/newcomments/wrapper.php');

	}
	
	/**
	 *	View individual comment. Checks for delete privileges.
	 *
	 *	@param array Comment.
	 */
	protected function comments_view_individual($comment){
		
		/* Alternating colours */
		static $alt = false;
		$alt = !$alt;
		
		/* Check delete privileges */
		$commentsModel = $this->getModel('newcomments');
		$canDelete = $commentsModel->canDelete($comment);
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/newcomments/individual.php');
		
	}
	
	/**
	 *	Delete comment
	 */
	public function delete_comment() {
		
		/* Get model */
		$commentsModel = $this->getModel('newcomments');
		
		/* Get comment */
		$commentId = Request::getInt('comment');
		if (!$comment = $commentsModel->getComment($commentId)) {
			return false;
		}
		
		/* Delete it. */
		$deleted = $commentsModel->canDelete($comment) && $commentsModel->delete($comment['id']);
		
		/* Add message */
		if ($deleted){
			$message = $this->delete_comment_success_message();
			$messageType = 'info';
		} else {
			$message = $this->delete_comment_failure_message();
			$messageType = 'error';
		}
		
		/* Tidy up */
		Messages::addMessage($message, $messageType);
		return $this->_redirect($this->get_redirect_page());
		
	}
	
	/**
	 *	Delete comment: success message.
	 *
	 *	@return string.
	 */
	protected function delete_comment_success_message(){
		return 'Comment deleted.';
	}
	
	/**
	 *	Delete comment: failure message.
	 *
	 *	@return string.
	 */
	protected function delete_comment_failure_message(){
		return 'Comment could not be deleted.';
	}
	

	/**
	 *	Fetch the to-be-commented-on object.
	 *
	 *	@return mixed.
	 */
	abstract protected function get_commentable_object();
	
	/**
	 *	Fetch the page to redirect to.
	 *
	 *	(Replaces all instances of Uri::build in CommentsController.)
	 *
	 *	@return string Url.
	 */
	abstract protected function get_redirect_page();

}

?>
