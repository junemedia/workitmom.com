<?php

class WorkitmomNewphotosModel extends BluModel {

	/**
	 *	Get photos.
	 */
	public function getPhotos($offset = null, $limit = null, &$total = null, array $options = array()){
		
		/* Prepare query parts */
		$query = array(
			'select' => array(
				'i.imageID AS `id`',
				'COUNT(c.commentID) AS `comment_count`',
				'COUNT(r.reportId) AS `report_count`'
			),
			'tables' => array(
				'`images` AS `i`',
				'`comments` AS `c` ON i.imageID = c.commentTypeObjectId
					AND c.commentType = "userphoto"
					AND c.commentDeleted != 1',
				'`reports` AS `r` ON i.imageID = r.objectId
					AND r.objectType = "userphoto"'
			),
			'where' => array(
				'i.url != ""',
				'i.imageOwner > 0'	// Negative imageOwner images are reserved.
			),
			'group' => 'i.imageID',
			'order' => 'i.uploadDate',
			'direction' => 'DESC'
		);
		if (Utility::iterable($options)){
			foreach($options as $key => $value){
				switch($key){
					case 'order':
						switch($value){
							case 'id':
								$query['order'] = 'i.imageID';
								break;
								
							case 'date':
								$query['order'] = 'i.uploadDate';
								break;
								
							case 'name':
								$query['order'] = 'i.title';
								break;
								
							case 'comments':
								$query['order'] = '`comment_count`';
								break;
						}
						break;
						
					case 'direction':
						if (!in_array(strtolower($value), array('asc', 'desc'))){ break; }
						$query['direction'] = strtoupper($value);
						break;
					
					case 'tags':	// NO SUCH FUNCTIONALITY AVAILABLE, BUT MIGHT NEED IT ONE DAY...
						/*
						if (!Utility::iterable($value)){
							if (!$value){ break; }
							$value = array($value);
						}
						foreach($value as &$tag){
							$tag = 't.tagName LIKE "%'.Database::escape($tag).'%"';
						}
						$query['tables'][] = '`userTags` AS `ut` ON u.UserID = ut.userId';
						$query['tables'][] = '`tags` AS `t` ON ut.tagId = t.tagId';
						$query['where'][] = '('.implode(' OR ', $value).')';
						*/
						break;
						
					case 'days':
						if (!$value){ break; }
						$query['where'][] = 'DATE_SUB(NOW(), INTERVAL '.(int) $value.' DAY) <= i.uploadDate';
						break;
						
					case 'user':
						$query['where'][] = 'i.imageOwner = '.(int) $value;
						break;
				}
			}
		}
		
		/* Build query string */
		$query = 'SELECT SQL_CALC_FOUND_ROWS '.implode(', ', $query['select']).'
			FROM '.implode('
				LEFT JOIN ', $query['tables']).'
			WHERE '.implode('
				AND ', $query['where']).'
			GROUP BY '.$query['group'].'
			ORDER BY '.$query['order'].' '.$query['direction'];
		
		/* Execute query */
		$this->_db->setQuery($query, $offset, $limit);
		$photos = $this->_db->loadAssocList('id');
		$total = $this->_db->getFoundRows();
		
		/* Return Ids */
		return $photos;
		
	}
	
	/**
	 *	Add photo details.
	 */
	public function addDetails(array &$photos){
		foreach($photos as $photoID => &$photo){
			$photo = $this->getPhoto($photoID);
		}
		unset($photo);
	}
	
	/**
	 *	Get a photo.
	 *
	 *	@param int Photo ID.
	 *	@return array Photo.
	 */
	public function getPhoto($photoId){
		
		/* Sanitise ID */
		$photoId = (int) $photoId;
		
		/* Build base data */
		$query = 'SELECT i.*, COUNT(c.commentID) AS `comment_count`
			FROM `images` AS `i`
				LEFT JOIN `comments` AS `c` ON i.imageID = c.commentTypeObjectId
					AND c.commentType = "userphoto"
					AND c.commentDeleted != 1
			WHERE i.imageID = '.$photoId.'
			GROUP BY i.imageID';
		$this->_db->setQuery($query, 0, 1);
		$photo_base = $this->_db->loadAssoc();
		
		/* Formatting */
		$photo = array(
			'id' => $photo_base['imageID'],
			'title' => $photo_base['title'] ? $photo_base['title'] : '[Untitled]',
			'description' => $photo_base['description'],
			'comment_count' => $photo_base['comment_count'],
			'link' => '/photoalbum/photo/'.$photo_base['imageID'].'/',
			'image' => $photo_base['url'],
			'imageDirectory' => 'user'
		);
		
		/* Get author */
		$personModel = $this->getModel('newperson');
		$photo['author'] = $personModel->getPerson(array('member' => $photo_base['imageOwner']));
		
		/* Get comment IDs */
		$commentsModel = $this->getModel('newcomments');
		$photo['comments'] = $commentsModel->getComments(0, 0, $photo['comment_count'], array(
			'type' => 'userphoto',
			'object' => $photo['id'],
			'order' => 'date',
			'direction' => 'DESC'
		));
		
		/* Return */
		return $photo;
		
	}

}

?>