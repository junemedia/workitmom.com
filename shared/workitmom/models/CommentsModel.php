<?php

/**
 *	The long awaited Comments model.
 */
class WorkitmomCommentsModel extends BluModel {

	/**
	 *	Get a CommentObject.
	 */
	public function getComment($id, $type = null){

		/* Get the type. */
		$id = (int) $id;
		if (!$type){
			$query = "SELECT c.commentType
				FROM `comments` AS `c`
				WHERE c.commentID = " . $id;
			$this->_db->setQuery($query);
			$type = $this->_db->loadResult();
		}

		/* Return. */
		$commentObject = null;
		try {
			switch($type){
				case 'item':
					/* Same as 'article' */

				case 'article':
					$commentObject = $this->getObject('itemcomment', $id);
					break;

				case 'user':
					/* Same as 'userpage' */

				case 'userpage':
					$commentObject = BluApplication::getObject('usercomment', $id);
					break;

				case 'memberblogpost':
					/* Same as 'blog' */

				case 'blog':
					$commentObject = BluApplication::getObject('memberblogpostcomment', $id);
					break;

				case 'photo':
					/* Same as 'userphoto' */

				case 'userphoto':
					$commentObject = BluApplication::getObject('memberphotocomment', $id);
					break;

				default:
					break;
			}
		} catch (NoDataException $exception) {}

		// bodge
		if (!isset($commentObject->reported)) {
			$query = 'SELECT COUNT(*)
				FROM `reports` AS `r`
				WHERE r.objectType = "comment"
					AND r.objectId = '.(int) $id.'
					AND r.status != "resolved"';
			$this->_db->setQuery($query, 0, 1);
			$commentObject->reported = (bool) $this->_db->loadResult();
		}
		
		
		return $commentObject;
	}

	/**
	 *	Get comments made *ON* a user.
	 */
	public function getUser(PersonObject $person){

		/* Triviality */
		if (!isset($person->userid)){ return null; }

		/* Cache */
		$cacheKey = 'comments_user_' . $person->userid;
		$comments = $this->_cache->get($cacheKey);
		if ($comments === false){

			/* Build query */
			$query = 'SELECT c.commentId AS `id`
				FROM `comments` AS `c`
				WHERE c.commentType = "userpage"
					AND c.commentDeleted != 1
					AND c.commentTypeObjectId = ' . $person->userid . '
				ORDER BY c.commentTime DESC';

			/* Get data */
			try {
				$records = $this->_fetch($query);
			} catch (NoDataException $exception){
				$records = array();
			}

			/* Format */
			$comments = array();
			foreach($records as $record){
				if ($comment = $this->getComment($record['id'], 'user')){
					$comments[] = $comment;
				}
			}

			/* Store in cache */
			$this->_cache->set($cacheKey, $comments);

		}

		/* Return */
		return $comments;

	}

	/**
	 *	Get a list of comments made *BY* a particular commentor.
	 */
	public function getAuthor(PersonObject $person, $thingType = null, $offset = null, $limit = null, &$total = null){

		/* Triviality */
		if (!isset($person->userid)){ return null; }

		/* Prepare */
		$criteria = new stdClass();
		$criteria->leftjoin = "";
		$criteria->where = "";

		$visibilitySQL = $this->_generateSQLVisibility();

		$commentType = null;
		switch($thingType){
			case 'article':
				/* Get all comments that this user has ever made on any article. */
				/* Code is identical to the 'question' switch block. */

			case 'question':
				/* Get all replies that this user made on questions. */
				$commentType = 'article';
				$criteria->leftjoin = "
				LEFT JOIN `article` `a` ON c.commentTypeObjectId = a.articleId";
				$criteria->where = "
				AND c.commentType = '" . $commentType . "'
				AND a.articleType = '" . $thingType . "'";
				break;

			case 'member':
				/* Get comments that this user has made on other members' profiles. */
				$commentType = 'userpage';
				$criteria->where = "
				AND c.commentType = '" . $commentType . "'";
				break;

			case null:
				/* Get all kinds of comments that the user has ever made. Are you sure? */
				break;

			default:
				/* Don't know what kind of comment. What exactly are you trying to do? */
				$criteria->where = "
				AND c.commentType = '" . Database::escape($thingType) . "'";
				break;
		}

		/* Build query */
		$query = "SELECT c.commentID AS `id`
			FROM `comments` AS `c`" . $criteria->leftjoin . "
			WHERE c.commentOwner = '" . $person->userid . "'" . $criteria->where . $visibilitySQL->where . "
					AND c.commentDeleted != 1
			ORDER BY c.commentTime DESC";

		/* Get data */
		$records = $this->_fetch($query, null, $offset, $limit);
		if ($limit == 1){
			$records = array($records);
		}

		/* Format */
		$comments = $this->_wrapComments($records);

		/* Return */
		return $comments;

	}

	/**
	 *	Get any. BODGE
	 */
	public function getAny($commentType, $commentTypeObjectId){

		$filters = $this->_generateSQLFilters();
		$query = 'SELECT c.commentID as `id`
			FROM `comments` AS `c`'.$filters->leftjoin.'
			WHERE c.commentType = "' . $commentType . '"'.$filters->where.'
				AND c.commentTypeObjectId = ' . $commentTypeObjectId . '
			ORDER BY c.commentTime DESC';
		$records = $this->_fetch($query);
		$comments = $this->_wrapComments($records);
		return $comments;

	}
	
	/**
	 *	Get the comment count for an object.
	 */
	public function getCount($objectType, $objectId){
		
		/* Build query */
		$query = 'SELECT COUNT(*)
			FROM `comments` AS `c`
			WHERE c.commentType = "'.Database::escape($objectType).'"
				AND c.commentTypeObjectId = '.(int) $objectId.'
				AND c.commentDeleted != 1';
		
		/* Execute */
		$this->_db->setQuery($query);
		$count = $this->_db->loadResult();
		
		/* Return */
		return (int) $count;
		
	}

	/**
	 *	Add a comment.
	 *
	 *	@args (array) args: associative array containing the data to be inserted.
	 * 	@return bool True on success, false otherwise
	 */
	public function addComment(array $args)
	{
		/* Default arguments for all new comments. */
		$args['commentReports'] = 0;
		$args['commentDeleted'] = 0;
		$special = array('commentTime' => 'NOW()');		/* This is an SQL function. */

		/* No empty comments allowed... */
		if (!isset($args['commentBody']) || !$args['commentBody']) { return null; }

		/* Create the comment using all accumulated arguments. */
		$commentID = $this->_create('comments', $args, $special);

		/* Return */
		return $commentID;
	}

	/**
	 *	Flag a comment. (Or report it, whatever you want to call it.)
	 */
	public function reportComment(CommentObject $comment, PersonObject $reporter){

		/* Set flag in `comments` table. */
		$commentUpdate = $this->_edit('comments', array(), array('commentReports' => 'commentReports + 1'), array('commentID' => $comment->id));
		if (!$commentUpdate){ return false; }
		$comment->flushCached();

		/* Add record in `commentReports` table. */
		$commentReport = array(
			'commentReporter'	=>	$reporter->userid,
			'commentId'			=>	$comment->id
		);
		$reported = $this->_create('commentReports', $commentReport, array('commentReportTime' => 'NOW()'));

		/* Return */
		return $reported;

	}

	/**
	 *	Delete a comment.
	 */
	public function deleteComment(CommentObject $comment){

		/* Set flag in `comments` table. */
		$args = array('commentDeleted' => 1);
		$criteria = array('commentID' => $comment->id);
		$deleted = $this->_edit('comments', $args, array(), $criteria);
		if ($deleted){ $comment->flushCached(); }

		/* Return */
		return $deleted;

	}
	
	/**
	 *	Test if logged in user can delete the given comment.
	 */
	public function canOfferCommentDeletion(CommentObject $comment) {
		
		/* User logged in? */
		if (!$user = BluApplication::getUser()) {
			return false;
		}
		
		/* Comment author */
		if ($comment->author && $comment->author->equals($user)){
			return true;
		}
		
		/* Admin */
		if (in_array($user->username, explode(',', BluApplication::getSetting('admins')))){
			return true;
		}
		
		/* Fail */
		return false;
		
	}




	###							PRIVATE CONVENIENCE FUNCTIONS							###

	/**
	 *	All filters
	 */
	private function _generateSQLFilters(){
		return $this->_generateSQLVisibility();
	}

	/**
	 *	Visibility filter.
	 */
	private function _generateSQLVisibility(){
		$sql = new stdClass();
		$sql->leftjoin = "";
		$sql->where = SITEEND == 'backend' ? "" : "
				AND c.commentDeleted != 1";
		return $sql;
	}

	/**
	 *	Wrap comments
	 */
	private function _wrapComments(array $recordset){
		$comments = array();
		foreach($recordset as $record){
			if ($comment = $this->getComment($record['id'])){
				$comments[] = $comment;
			}
		}
		return $comments;
	}

}
