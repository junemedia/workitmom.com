<?php

/**
 *	Model for reporting content.
 */
class WorkitmomReportsModel extends BluModel {
	
	/**
	 *	Get a report.
	 */
	public function getReport($reportId){
		
		/* Prepare */
		$reportId = (int) $reportId;
		
		/* Check cache */
		$cacheKey = 'report_'.$reportId;
		$report = $this->_cache->get($cacheKey);
		if ($report === false){
		
			/* Get base data. */
			$query = 'SELECT *
				FROM `reports` AS `r`
				WHERE r.reportId = '.$reportId;
			$this->_db->setQuery($query, 0, 1);
			$report = $this->_db->loadAssoc();
			if (empty($report)){
				return null;
			}
			
			/* Alias */
			$report['date'] = $report['time'];
			$report['resolved'] = $report['status'] == 'resolved';
			
			/* Build */
			$report['id'] = (int) $report['reportId'];
			$object = $this->getReportedObject($report['id']);
			switch($report['objectType']){
				case 'article':
					$report['text'] = $object->title;
					$itemsModel = $this->getModel('newitems');
					$item = $itemsModel->getItem($report['objectId']);
					$report['link'] = $item['link'];
					break;
					
				case 'comment':
					$report['text'] = $object->body;
					$commentsModel = $this->getModel('newcomments');
					$report['link'] = $commentsModel->getCommentLink($report['objectId']);
					break;
					
				case 'grouppost':
					$report['text'] = $object['text'];
					$groupsModel = $this->getModel('groups');
					$post = $groupsModel->getPost($report['objectId']);
					$report['link'] = $post['link'];
					break;
					
				case 'userphoto':
					$report['text'] = $object->title;
					$report['link'] = Uri::build($object);
					break;
			}
			
			/* Store in cache */
			$this->_cache->set($cacheKey, $report);
		
		}
		
		/* Get author */
		$personModel = $this->getModel('newperson');
		$report['author'] = $personModel->getPerson(array('member' => $report['reporter']));
		
		/* Return */
		return $report;
		
	}
	
	/**
	 *	Add report details.
	 *
	 *	@args (array) &report: Ids to add details to.
	 */
	public function addDetails(&$reports){
		if (!empty($reports)){
			foreach($reports as $reportId => &$report){
				$report = $this->getReport($reportId);
			}
			unset($report);
		}
	}
	
	/**
	 *	Get related reports - other reports on the same item.
	 */
	public function getRelated($reportId, $offset = null, $limit = null, &$total = null, array $options = array()){	
		
		/* Get original report */
		$report = $this->getReport($reportId);
		if (!Utility::iterable($report)){ return false; }
		
		/* Standard parameters */
		if (!isset($options['show_original']) || !$options['show_original']){
			$options = array_merge($options, array(
				'exclude' => array($report['id'])
			));
		}
		$options['id'] = (int) $report['objectId'];	// Get other reports that have same objectId as given report.
		
		/* Get other reports */
		switch($report['objectType']){
			case 'article':
			case 'comment':
			case 'grouppost':
			case 'userphoto':
				$options['type'] = $report['objectType'];
				break;
				
			default: 
				return array();
				break;
		}
		$related = $this->getReports($offset, $limit, $total, $options);
		
		/* Return */
		return $related;
		
	}
	
	/**
	 *	Get the object that was reported on.
	 */
	public function getReportedObject($reportId){
		
		/* Get data */
		$query = 'SELECT r.objectType, r.objectId
			FROM `reports` AS `r`
			WHERE r.reportId = '.(int) $reportId;
		$this->_db->setQuery($query, 0, 1);
		$report = $this->_db->loadAssoc();
		if (empty($report)){
			return null;
		}
		
		/* Get reported object. */
		switch($report['objectType']){
			case 'article':
				$itemsModel = $this->getModel('items');
				return $itemsModel->getItem($report['objectId']);
				break;
				
			case 'comment':
				$commentsModel = $this->getModel('comments');
				return $commentsModel->getComment($report['objectId']);
				break;
				
			case 'grouppost':
				$groupsModel = $this->getModel('groups');
				return $groupsModel->getPost($report['objectId']);
				break;
				
			case 'userphoto':
				$photosModel = $this->getModel('photos');
				return $photosModel->getPhoto('user', $report['objectId']);
				break;
			
			default:
				return null;
				break;
		}		
		
	}
	
	/**
	 *	Get all reports on an object.
	 */
	public function getReports($offset = null, $limit = null, &$total = null, array $options = array()){
		
		/* Build query */
		$query = 'SELECT SQL_CALC_FOUND_ROWS r.reportId
			FROM `reports` AS `r`
			WHERE 1=1';
		
		/* Add options */
		if (Utility::iterable($options)){
			
			/* Filter */
			foreach($options as $key => $value){
				switch($key){
					case 'status':
						/* Filter by report status */
						$query .= '
				AND r.status = "'.Database::escape($value).'"';
						break;
						
					case 'not_status':
						/* Filter by report status, part deux. */
						$query .= '
				AND r.status != "'.Database::escape($value).'"';
						break;
						
					case 'reporter':
						/* Filter by reporter. */
						$query .= '
				AND r.reporter = '.(int)$value;
						break;
					
					case 'id':
						/* Filter by OBJECT id */
						$query .= '
				AND r.objectId = '.(int)$value;
						break;
						
					case 'type':
						/* Filter by object type. */
						$query .= '
				AND r.objectType = "'.Database::escape($value).'"';
						break;
						
					case 'exclude':
						/* Filter out particular IDs. */
						if (!Utility::iterable($value)){ break; }
						foreach($value as &$v){ $v = (int) $v; }
						unset($v);
						$query .= '
				AND r.reportId NOT IN ('.implode(', ', $value).')';
						break;
				}
			}
			
			/* Sort */
			if (!isset($options['order'])){
				$options['order'] = 'date';
			}
			switch(strtolower($options['order'])){
				case 'id':
					$query .= '
			ORDER BY r.reportId';
					break;
					
				case 'status':
					$query .= '
			ORDER BY r.status';
					break;
					
				case 'type':
					$query .= '
			ORDER BY r.objectType';
					break;
					
				case 'date':
				case 'time':
				default:
					$query .= '
			ORDER BY r.time';
					break;
			}
			if (!isset($options['direction'])){
				$options['direction'] = 'asc';
			}
			switch(strtolower($options['direction'])){
				case 'desc':
					$query .= ' DESC';
					break;
					
				case 'asc':
				default:
					$query .= ' ASC';
					break;
			}
			
		}
		
		/* Get IDs */
		$this->_db->setQuery($query, $offset, $limit);
		$reports = $this->_db->loadAssocList('reportId');
		$total = $this->_db->getFoundRows();
		
		/* Build data */
		return $reports;
		
	}
	
	/**
	 *	Report an item.
	 */
	public function reportItem($itemId, $reason = null){
		return $this->_reportObject($itemId, 'article', $reason);
	}
	
	/**
	 *	Report a comment.
	 */
	public function reportComment($commentId, $reason = null){
		return $this->_reportObject($commentId, 'comment', $reason);
	}
	
	/**
	 *	Report a discussion post.
	 */
	public function reportGrouppost($postId, $reason = null){
		return $this->_reportObject($postId, 'grouppost', $reason);
	}
	
	/**
	 *	Report a member photo.
	 */
	public function reportUserphoto($photoId, $reason = null){
		return $this->_reportObject($photoId, 'userphoto', $reason);
	}
	
	/**
	 *	Report an object.
	 */
	private function _reportObject($objectId, $objectType, $reason = null){
		
		/* Require user. */
		if (!$user = BluApplication::getUser()){
			return false;
		}
		
		/* Build args */
		$report = array();
		$report['objectType'] = $objectType;
		$report['objectId'] = (int)$objectId;
		$report['reporter'] = $user->userid;
		if ($reason){
			$report['reason'] = $reason;
		}
		$report['status'] = 'pending';
		$report_special = array('time' => 'NOW()');
		
		/* Commit to database */
		$reportId = $this->_create('reports', $report, $report_special, true);
		
		/* Return */
		return $reportId;
		
	}
	
	/**
	 *	Get all possible report statuses.
	 */
	public function getStatuses(){
		
		/* Parent method. */
		return $this->_getEnums('reports', 'status');
		
	}
	
}

?>