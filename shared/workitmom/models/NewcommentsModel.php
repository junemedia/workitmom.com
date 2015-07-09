<?php

/**
 *	Comments model.
 */
class WorkitmomNewcommentsModel extends BluModel {
	
	/**
	 *	Get all comment types.
	 */
	public function getTypes(){
		
		/* Get available types from DB */
		$types = $this->_getEnums('comments', 'commentType');
		
		/* Slideshow comments are superfluous - redirect to article please. */
		$slideshowKey = array_search('slideshow', $types);
		if ($slideshowKey !== false){
			unset($types[$slideshowKey]);
		}
		
		/* Blog comments are deprecated */
		$blogKey = array_search('blog', $types);
		if ($blogKey !== false){
			unset($types[$blogKey]);
		}
		
		/* Return */
		return $types;
		
	}
	
	/**
	 *	Get comment.
	 */
	public function getComment($commentId){
		
		/* Build base */
		$query = 'SELECT c.*, COUNT(r.reportId) AS `reports`, COUNT(r2.reportId) AS `reported`
			FROM `comments` AS `c`
				LEFT JOIN `reports` AS `r` ON c.commentID = r.objectId 
					AND r.objectType = "comment"
				LEFT JOIN `reports` AS `r2` ON c.commentID = r.objectId 
					AND r.objectType = "comment" 
					AND r.status != "resolved"
			WHERE c.commentID = '.(int)$commentId.'
			GROUP BY c.commentID';
		$this->_db->setQuery($query, 0, 1);
		$comment = $this->_db->loadAssoc();
		if (empty($comment)){
			return false;
		}
		$comment['id'] = (int) $comment['commentID'];
		
		/* Build object data */
		$comment['objectType'] = $comment['commentType'];
		$comment['objectId'] = $comment['commentTypeObjectId'];
		$comment['text'] = $comment['commentBody'];
		$comment['time'] = $comment['commentTime'];
		$comment['date'] = Utility::formatDate($comment['commentTime']);
		$comment['reports'] = (int) $comment['reports'];
		$comment['reported'] = (bool) $comment['reported'];
		$comment['deleted'] = (int) (bool) $comment['commentDeleted'];
		
		/* Get author */
		$personModel = $this->getModel('newperson');
		$comment['author'] = $personModel->getPerson(array(
			'member' => $comment['commentOwner']
		));
		
		/* Return */
		return $comment;
		
	}
	
	/**
	 *	Get the frontend link for the commented object.
	 *
	 *	@return string.
	 */
	public function getCommentLink($commentId){
		
		/* Get comment */
		if (!$comment = $this->getComment($commentId)){
			return false;
		}
		
		/* Get link */
		switch($comment['objectType']){
			case 'article':
			case 'slideshow':
				$itemsModel = $this->getModel('newitems');
				$item = $itemsModel->getItem($comment['objectId']);
				$link = $item['link'];
				break;
				
			case 'userpage':
				$personModel = $this->getModel('newperson');
				$person = $personModel->getPerson(array(
					'member' => $comment['objectId']
				));
				$link = $person['link'];
				break;
				
			case 'userphoto':
				$photosModel = $this->getModel('newphotos');
				$photo = $photosModel->getPhoto($comment['objectId']);
				$link = $photo['link'];
				break;
				
			default:
				$link = false;
				
				// TEMPORARY: check if item exists.
				$itemsModel = $this->getModel('newitems');
				if ($item = $itemsModel->getItem($comment['objectId'])) {
					$link = $item['link'];
				}
				break;
		}
		
		/* Return link */
		return $link;
		
	}
	
	/**
	 *	Get commented object.
	 *
	 *	@param int Comment ID.
	 *	@return mixed.
	 */
	public function getCommentedObject($commentId){
		
		// Get comment
		if (!$comment = $this->getComment($commentId)){
			return false;
		}
		
		// Get commented object
		$object = null;
		switch($comment['objectType']){
			case 'article':
				$itemsModel = $this->getModel('newitems');
				$object = $itemsModel->getItem($comment['objectId']);
				break;
		}
		
		// Return
		return $object;
		
	}
	
	/**
	 *	Build comment data.
	 */
	public function addDetails(&$comments){
		if (!empty($comments)){
			foreach($comments as $commentId => &$comment){
				$comment = $this->getComment($commentId);
			}
			unset($comment);
		}
	}
	
	/**
	 *	Generate an array of comment IDs.
	 */
	public function getComments($offset, $limit, &$total, array $options = array()){
		
		/* Prepare query parts */
		$query = array(
			'select' => array(
				'c.commentID AS `id`',
				'COUNT(r.reportId) AS `reports`'
			),
			'tables' => array(
				'`comments` AS `c`',
				'`reports` AS `r` ON c.commentID = r.objectId 
					AND r.objectType = "comment"'
			),
			'where' => array(
				'c.commentType NOT IN ("blog", "slideshow")',
				'c.commentDeleted != 1'
			),
			'group' => 'c.commentID',
			'order' => 'c.commentID',
			'direction' => 'ASC'
		);
		if (!empty($options)){
			foreach($options as $key => $value){
				switch($key){
					case 'type':
						$query['where'][] = 'c.commentType = "'.Database::escape($value).'"';
						break;
						
					case 'order':
						switch($value){
							case 'date':
								$query['order'] = 'c.commentTime';
								break;
								
							case 'type':
								$query['order'] = 'c.commentType';
								break;
								
							case 'text':
								$query['order'] = 'TRIM(c.commentBody)';
								break;
								
							case 'id':
								$query['order'] = 'c.commentID';
								break;
								
							case 'reports':
								$query['order'] = '`reports`';
								break;
						}
						break;
						
					case 'direction':
						if (!in_array(strtolower($value), array('asc', 'desc'))){ break; }
						$query['direction'] = strtoupper($value);
						break;
						
					case 'object':
						$query['where'][] = 'c.commentTypeObjectId = '.(int) $value;
						break;
				}
			}
		}
		
		/* Build query string */
		$query = 'SELECT SQL_CALC_FOUND_ROWS '.implode(', ', $query['select']).'
			FROM '.implode('
				LEFT JOIN ', $query['tables']).'
			WHERE '.implode('
				AND ', $query['where']).($query['group'] ? "\r\n\t\t\t".'GROUP BY '.$query['group'] : '').'
			ORDER BY '.$query['order'].' '.$query['direction'];
		
		/* Execute query */
		$this->_db->setQuery($query, $offset, $limit);
		$comments = $this->_db->loadAssocList('id');
		$total = $this->_db->getFoundRows();
		
		/* Return IDs */
		return $comments;
		
	}
	
	/**
	 *	Bastard brother of CommentsModel::canOfferCommentDeletion.
	 */
	public function canDelete($comment){
		
		/* User logged in? */
		if (!$user = BluApplication::getUser()) {
			return false;
		}
		
		/* Comment author */
		$personModel = $this->getModel('newperson');
		$user = $personModel->getPerson(array('username' => $user->username));	// Convert to new Person object.
		if ($personModel->equals($comment['author'], $user)){
			return true;
		}
		
		/* Admin */
		if ($personModel->isAdmin($user)){
			return true;
		}
		
		/* Fail */
		return false;
		
	}
	
	/**
	 *	Set a comment as deleted.
	 */
	public function delete($commentId){
		
		/* Build arguments */
		$args = array('commentDeleted' => 1);
		$criteria = array('commentID' => (int)$commentId);
		
		/* Commit */
		$deleted = $this->_edit('comments', $args, array(), $criteria);
		
		/* Return */
		return $deleted;
		
	}
	
	
}

?>
