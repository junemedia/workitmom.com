<?php

/**
 *	Handles Member blogs.
 */
class WorkitmomNewblogsModel extends BluModel {

	/**
	 *	Get blog post.
	 */
	public function getPost($postId){
		
		/* Get post */
		$cacheKey = 'NEWmemberblogpost_'.$postId;
		$post = $this->_cache->get($cacheKey);
		if ($post === false){
		
			/* Build query */
			$query = 'SELECT *
				FROM `article` AS `a`
				WHERE a.articleType = "note"
					AND a.articleID = '.(int)$postId;
				
			/* Execute */
			$this->_db->setQuery($query, 0, 1);
			$record = $this->_db->loadAssoc();
			if (!Utility::iterable($record)){ return null; }
			
			/* Build object */
			$post = array();
			$post['id'] = (int) $record['articleID'];
			$post['title'] = $record['articleTitle'];
			$post['body'] = $record['articleBody'];
			$post['date'] = $record['articleTime'];
			$post['author'] = (int) $record['articleAuthor'];
			
			/* Get tags */
			$metaModel = $this->getModel('meta');
			$post['tags'] = $metaModel->getTags('article', $post['id']);
			
			/* Get comment count */
			$commentsModel = $this->getModel('comments');
			$post['comment_count'] = $commentsModel->getCount('article', $post['id']);
			
			/* Set in cache */
			$this->_cache->set($cacheKey, $post);
			
		}
		
		/* Add non-directly cached data */
		$personModel = $this->getModel('newperson');
		$post['author'] = $personModel->getPerson(array('contentcreator' => $post['author']));
		$post['url'] = SITEURL."/blogs/members/".$post['author']['username']."/".$post['id'].'/'.Utility::seo($post['title']);
		
		/* Return */
		return $post;
		
	}
	
	/**
	 *	Add details to a post.
	 */
	public function addPostDetails(array &$posts){
		if (!Utility::iterable($posts)){ return false; }
		foreach($posts as $postId => &$post){
			$post = $this->getPost($postId);
		}
	}

	/**
	 *	Gets latest posts from a user.
	 */
	public function getLatestUserPosts($userId, $offset = null, $limit = null, &$total = null, array $options = array()){
		
		/* Get user */
		$personModel = $this->getModel('newperson');
		$person = $personModel->getPerson(array('member' => $userId));

		/* Build query. */
		$query = 'SELECT SQL_CALC_FOUND_ROWS a.articleID
			FROM `article` AS `a`
			WHERE a.articleType = "note"';
		$this->_appendFilteringCriteria($query, $options);
		$query .= '
				AND a.articleAuthor = '.(int) $person['contentCreatorID'].'
			ORDER BY a.articleTime DESC';

		/* Get IDs */
		$this->_db->setQuery($query, $offset, $limit);
		$posts = $this->_db->loadAssocList('articleID');
		
		/* Pass out total */
		$total = $this->_db->getFoundRows();
		
		/* Return. */
		$this->addPostDetails($posts);
		return $posts;
		
	}
	
	/**
	 *	Get member blog posts by criteria.
	 */
	public function getPosts($offset = null, $limit = null, &$total = null, array $options = array()){
		
		/* Prepare query parts */
		$query = array(
			'select' => array(
				'a.articleID AS `id`',
				'COUNT(c.commentID) AS `comments`'
			),
			'tables' => array(
				'`article` AS `a`',
				'`comments` AS `c` ON a.articleID = c.commentTypeObjectId 
					AND c.commentType = "article"
					AND c.commentDeleted != 1'
			),
			'where' => array(
				'a.articleType = "note"',
				'a.articleAuthor IS NOT NULL',
			),
			'group' => 'a.articleID',
			'order' => 'a.articleID',
			'direction' => 'ASC'
		);
		if (SITEEND != 'backend'){
			$query['where'][] = 'a.articleDeleted != 1';
			$query['where'][] = 'a.articleLive = 1';
		}
		if (Utility::iterable($options)){
			foreach($options as $key => $value){
				switch($key){
					case 'order':
						switch($value){
							case 'date':
								$query['order'] = 'a.articleTime';
								break;
								
							case 'comments':
								$query['order'] = '`comments`';
								break;
						}
						break;
						
					case 'direction':
						if (!in_array(strtolower($value), array('asc', 'desc'))){ break; }
						$query['direction'] = strtoupper($value);
						break;
						
					case 'tags':
						if (!Utility::iterable($value)){
							if (!$value){ break; }
							$value = array($value);
						}
						foreach($value as &$tag){
							$tag = 't.tagName LIKE "%'.Database::escape($tag).'%"';
						}
						$query['tables'][] = '`articleTags` AS `at` ON a.articleID = at.articleId';
						$query['tables'][] = '`tags` AS `t` ON at.tagId = t.tagId';
						$query['where'][] = '('.implode(' OR ', $value).')';
						break;
						
					case 'author':
						$query['where'][] = 'a.articleAuthor = '.(int)$value;
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
		$posts = $this->_db->loadAssocList('id');
		$total = $this->_db->getFoundRows();
		
		/* Build object data */
		$this->addPostDetails($posts);
		return $posts;
		
	}
	
	/**
	 *	Append filtering options.
	 *
	 *	Should be placed after the WHERE clause in a query.
	 */
	protected function _appendFilteringCriteria(&$query, array $options){
		
		/* Automatic options */
		$options['visibility'] = SITEEND;
		
		/* Apply options. */
		foreach($options as $filter => $args){
			switch($filter){
				case 'days':
					$query .= '
					AND DATE_SUB(NOW(), INTERVAL '.(int)$args.' DAY) <= a.articleTime';
					break;
					
				case 'visibility':
					if ($args == 'frontend'){
						$query .= '
					AND a.articleDeleted != 1
					AND a.articleLive = 1';
					}
					break;
			}
		}
		
	}

	/**
	 *	Filter options by their alias names.
	 */
	private function _filterAliases(array $options){

		/* Prepare new options */
		$newOptions = array();
		foreach($options as $key => $option){

			/* Find alias */
			switch($key){
				case 'within_days':
					$newKey = 'days';
					break;
			}

			/* Copy over */
			$newOptions[isset($newKey) ? $newKey : $key] = $option;

			/* Unset new key for next loop */
			unset($newKey);

		}

		/* Return */
		return $newOptions;

	}
	
}

?>
