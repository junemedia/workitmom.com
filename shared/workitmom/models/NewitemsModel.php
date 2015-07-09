<?php

class WorkitmomNewitemsModel extends BluModel {

	/**
	 * Get an item
	 *
	 * @param int Item ID
	 * @param string Item type
	 * @return array Item details
	 */
	public function getItem($itemId)
	{
		// Sanitise
		$itemId = (int) $itemId;
		
		// Get basic item details
		$cacheKey = 'item_'.$itemId;
		$item = $this->_cache->get($cacheKey);
		if ($item === false) {
			$query = 'SELECT a.*, acs.articleCategoryID AS `categoryId`
				FROM article AS a
					LEFT JOIN articleCategory AS ac ON ac.articleId = a.articleID
					LEFT JOIN `articleCategories` AS `acs` ON ac.categoryId = acs.articleCategoryID
						AND acs.articleCategorySection = a.articleType
				WHERE a.articleID = '.$itemId.'
				GROUP BY a.articleID';
			$this->_db->setQuery($query);
			$item = $this->_db->loadAssoc();
			if (!$item) {
				return false;
			}
			$item['id'] = (int) $item['articleID'];
			
			/* Rename fields to friendly names */
			$item['title'] = $item['articleTitle'];
			$item['subtitle'] = $item['articleSubTitle'];
			$item['body'] = $item['articleBody'];
			$item['date'] = $item['articleTime'];
			$item['type'] = $item['articleType'];
			$item['views'] = (int) $item['articleViews'];
			$item['live'] = (bool) $item['articleLive'];
			$item['deleted'] = (bool) $item['articleDeleted'];
			$item['featured'] = $item['articleIndexFeature'] > 1;
			
			/* Get comment count */
			$query = 'SELECT COUNT(*)
				FROM `comments` AS `c`
				WHERE c.commentType = "article"
					AND c.commentDeleted != 1
					AND c.commentTypeObjectId = '.$item['id'];
			$this->_db->setQuery($query);
			$item['comment_count'] = (int) $this->_db->loadResult();

			// Generate teaser if we don't have one explicity set
			if (!$item['articleTeaser']) {
				$item['articleTeaser'] = Text::trim($item['articleBody'], 150);
			}

			// De-obfuscate privacy setting
			$item['privacy'] = ($item['articlePrivacy'] == 'public' ? 0 : 1);

			/* Get tags */
			$query = 'SELECT t.*
				FROM `tags` AS `t`
					LEFT JOIN `articleTags` AS `at` ON t.tagId = at.tagId
				WHERE at.articleId = '.$item['id'];
			$this->_db->setQuery($query);
			$item['tags'] = $this->_db->loadAssocList('tagId');
			foreach($item['tags'] as &$tag){
				$tag = $tag['tagName'];
			}
			unset($tag);

			// Store in cache
			$this->_cache->set($cacheKey, $item);
		}

		// Get full author details
		$personModel = $this->getModel('newperson');
		$item['author'] = $personModel->getPerson(array('contentcreator' => $item['articleAuthor']));

		// Determine image to use
		if ($item['articleImage']) {
			$item['imageDirectory'] = 'item';
			$item['image'] = $item['articleImage'];
		} else {
			$item['imageDirectory'] = 'user';
			$item['image'] = $item['author']['image'];
		}

		// Get category details
		$metaModel = $this->getModel('meta');
		$item['category'] = $metaModel->getCategory($item['categoryId']);
		
		// Generate link
		$item['link'] = $this->getItemLink($item);

		return $item;
	}
	
	/**
	 *	Build frontend link for item.
	 *
	 *	@param array Item.
	 */
	public function getItemLink($item){
		
		$link = '/';
		switch($item['type']){
			case 'article':
				$link .= 'articles/detail';
				break;

			case 'news':
				$link .= 'news/detail';
				break;

			case 'lifesaver':
				$link .= 'lifesavers/detail';
				break;

			case 'list':
			case 'checklist':
				$link .= 'checklists/detail';
				break;

			case 'quicktip':
				$link .= 'quicktips/detail';
				break;

			case 'landingpage':
			case 'essential':
				$link .= 'essentials/detail';
				break;

			case 'question':
				$link .= 'questions/detail';
				break;

			case 'interview':
				$link .= 'interviews/detail';
				break;

			case 'slideshow':
				$link .= 'slideshows/detail';
				break;
				
			case 'note':
				$link .= 'blogs/members/'.$item['author']['username'];
				break;
		}
		$link .= '/'.$item['id'];
		$link .= '/'.Utility::seo($item['title']);
		
		return $link;
	}

	/**
	 * Append details for each item in the given array
	 *
	 * @param array Array of items to add details to
	 */
	public function addDetails(&$items)
	{
		if (!empty($items)) {
			foreach ($items as $itemId => &$item) {
				$item = $this->getItem($itemId);
			}
			unset($item);
		}
	}
	
	/**
	 *	The almighty getItems.
	 */
	public function getItems($offset = null, $limit = null, &$total = null, array $options = array()){
		
		/* Prepare query parts */
		$query = array(
			'select' => array(
				'a.articleID AS `id`',
				'COUNT(c.commentID) AS `comment_count`'
			),
			'tables' => array(
				'`article` AS `a`',
				'`comments` AS `c` ON a.articleID = c.commentTypeObjectId 
					AND c.commentType = "article"
					AND c.commentDeleted != 1'
			),
			'where' => array(
				'a.articleAuthor IS NOT NULL',	// Bogus authors
				'a.articleTitle != ""',			// Bogus items.
				'a.articleDeleted != 1'			// Deleted items.
			),
			'group' => 'a.articleID',
			'order' => 'a.articleID',
			'direction' => 'ASC'
		);
		if (SITEEND != 'backend'){
			$query['where'][] = 'a.articleLive = 1';
		}
		if (Utility::iterable($options)){
			foreach($options as $key => $value){
				switch($key){
					case 'order':
						switch($value){
							case 'title':
								$query['order'] = 'a.articleTitle';
								break;
								
							case 'date':
								$query['order'] = 'a.articleTime';
								break;
								
							case 'views':
								$query['order'] = 'a.articleViews';
								break;
								
							case 'comments':
								$query['order'] = '`comment_count`';
								break;
								
							case 'category':
								$query['tables'][] = '`articleCategory` AS `ac` ON a.articleID = ac.articleId';
								$query['tables'][] = '`articleCategories` AS `acs` ON ac.categoryId = acs.articleCategoryID
					AND acs.articleCategorySection = a.articleType';
								$query['order'] = 'acs.articleCategoryName';
								break;
						}
						break;
						
					case 'direction':
						if (!in_array(strtolower($value), array('asc', 'desc'))){ break; }
						$query['direction'] = strtoupper($value);
						break;
						
					case 'type':
						$query['where'][] = 'a.articleType = "'.Database::escape($value).'"';
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
		$items = $this->_db->loadAssocList('id');
		$total = $this->_db->getFoundRows();
		
		/* Build object data */
		$this->addDetails($items);
		return $items;
		
	}

	/**
	 * Get saved items
	 *
	 * @param int User ID
	 * @return array List of saved items
	 */
	public function getSavedItems($userId, $type = 'article')
	{
		$query = 'SELECT us.objectID
			FROM userSaves AS us
			WHERE us.objectType = "'.Database::escape($type).'"
				AND us.userID = '.(int)$userId;
		$this->_db->setQuery($query);
		$items = $this->_db->loadAssocList('objectID');
		if (!$items) {
			return false;
		}

		// Get article details and return
		$this->addDetails($items);
		return $items;
	}

	/**
	 * Get owned items (ie. things the user has written)
	 *
	 * @param int User ID
	 * @return array List of owned items
	 */
	public function getOwnedItems($userId, $type = 'article')
	{
		$query = 'SELECT a.articleID
			FROM article AS a
				LEFT JOIN contentCreators AS cc ON cc.contentCreatorID = a.articleAuthor
				LEFT JOIN users AS u ON u.UserID = cc.contentCreatoruserID
			WHERE u.UserID = '.(int) $userId.'
				AND a.articleType = "'.Database::escape($type).'"
				AND a.articleLive = 1
				AND a.articleDeleted != 1';
		$this->_db->setQuery($query);
		$items = $this->_db->loadAssocList('articleID');
		if (!$items) {
			return false;
		}

		// Get article details and return
		$this->addDetails($items);
		return $items;
	}

	/**
	 * Edit item
	 *
	 * @param int User ID
	 * @param int Item ID
	 * @param string Title
	 * @param string Body
	 * @param int Category ID
	 * @param array Tags
	 * @param string Sub title
	 * @param string Teaser
	 * @param string Link
	 * @param string Privacy flag
	 * @return bool True on success, false otherwise
	 */
	public function editItem($userId, $itemId, $title = null, $body = null, $categoryId = null, $tags = null,
		$subTitle = null, $teaser = null, $link = null, $privacy = null)
	{
		// Get user details
		$personModel = BluApplication::getModel('person');
		$user = $personModel->getPerson(array('member' => $userId));

		// Check permission to edit
		$item = $this->getItem($itemId);
		if ($item['articleAuthor'] != $user->contentcreatorid) {
			return false;
		}

		// Build update
		$fields = array();
		if ($title) { $fields[] = 'a.articleTitle = "'.Database::escape($title).'"'; }
		if ($body) { $fields[] = 'a.articleBody = "'.Database::escape($body).'"'; }
		if ($subTitle) { $fields[] = 'a.articleTitle = "'.Database::escape($subTitle).'"'; }
		if ($teaser) { $fields[] = 'a.articleTeaser = "'.Database::escape($teaser).'"'; }
		if ($link) { $fields[] = 'a.articleLink = "'.Database::escape($link).'"'; }
		if ($privacy) { $fields[] = 'a.articlePrivacy = '.(int)($privacy == 'public' ? 0 : 1); }

		// Update article
		if (!empty($fields)) {
			$query = 'UPDATE article AS a SET '.implode(', ', $fields).'
				WHERE a.articleID = '.(int)$itemId.'
					AND a.articleAuthor = '.(int)$user->contentcreatorid;
			$this->_db->setQuery($query);
			$this->_db->query();
		}

		// Alter category mapping
		if ($categoryId && ($categoryId != $item['categoryId'])) {
			$this->updateCategory($itemId, $categoryId);
		}

		// Update tags
		if ($tags) {
			$this->updateTags($itemId, $item['tags'], $tags);
		}

		// Clear cache
		$this->_cache->delete('item_'.$itemId);

		return true;
	}

	/**
	 * Update an items tags (simply removes all and re-adds for simplicity)
	 *
	 * @param int Item ID
	 * @param array List of tag names
	 * @param array List of tag names
	 * @return bool Success
	 */
	public function updateTags($itemId, $oldTags, $newTags)
	{
		$metaModel = BluApplication::getModel('meta');
		$success = true;

		// Decrement and remove old tags
		if (!empty($oldTags)) {

			// Decrement count
			foreach ($oldTags as $oldTagName) {
				if ($metaModel->decrementTagCount($oldTagName) === false){
					$success = false;
				}
			}

			// Remove all article tags
			$query = 'DELETE FROM articleTags
				WHERE articleId = '.(int)$itemId;
			$this->_db->setQuery($query);
			if (!$unassociated = $this->_db->query()) {
				$success = false;
			}
			
		}

		// Add new tags
		if (!empty($newTags)) {
			foreach ($newTags as $newTagName) {
				if (!$tagInserted = $this->addTag($itemId, $newTagName)){
					$success = false;
				}
			}
		}
		
		// Return
		return $success;
	}
	
	/**
	 *	Add a tag to an item.
	 *
	 *	@param int Item ID.
	 *	@param string Tag
	 *	@return bool Success.
	 */
	public function addTag($itemId, $tag){
		
		// Get tag ID
		$metaModel = $this->getModel('meta');
		$tagId = $metaModel->addTag($tag);
		
		// Insert
		$query = 'INSERT INTO articleTags
			SET tagId = '.(int)$tagId.',
				articleId = '.(int)$itemId;
		$this->_db->setQuery($query);
		$tagInserted = (bool) $this->_db->query();
		
		// Return
		return $tagInserted;
		
	}
	
	/**
	 *	Remove a tag from an item.
	 *
	 *	@param int Item ID.
	 *	@param string Tag
	 *	@return bool Success.
	 */	
	public function deleteTag($itemId, $tag){
		
		// Get tag
		$metaModel = $this->getModel('meta');
		$tag = $metaModel->getTagByName($tag);
		
		// Remove from item
		$query = 'DELETE FROM articleTags
			WHERE articleId = '.(int)$itemId.'
				AND tagId = '.(int) $tag['tagId'];
		$this->_db->setQuery($query);
		$update['removed'] = (bool) $this->_db->query();
		
		// Decrement tag count
		$update['decremented'] = $metaModel->decrementTagCount($tag['tagName']);
		
		// Return
		return !in_array(false, $update);
		
	}

	/**
	 * Update item category (only one category per item for now)
	 *
	 * @param int Item ID
	 * @param int Category ID
	 * @return bool Success
	 */
	public function updateCategory($itemId, $categoryId)
	{
		$query = 'DELETE FROM articleCategory
			WHERE articleId = '.(int)$itemId;
		$this->_db->setQuery($query);
		$this->_db->query();

		$query = 'INSERT INTO articleCategory
			SET articleId = '.(int)$itemId.',
				categoryId = '.(int)$categoryId;
		$this->_db->setQuery($query);
		return (bool) $this->_db->query();
	}

	/**
	 * Delete an item
	 *
	 * @param int Item ID
	 * @return bool Success
	 */
	public function deleteItem($itemId)
	{
		// Sanitise
		$itemId = (int) $itemId;
		
		// Delete article
		$query = 'UPDATE `article` AS `a`
			SET a.articleDeleted = 1
			WHERE a.articleID = '.$itemId;
		$this->_db->setQuery($query);
		$deleted = $this->_db->query();

		// Clear cache
		$this->_cache->delete('item_'.$itemId);

		return $deleted;
	}

	/**
	 * Subscribe to an authors content
	 *
	 * @param int Subscriber ID
	 * @param int Content creator ID
	 * @param string Article type (not yet used)
	 * @return bool True on success, false otherwise
	 */
	public function subscribe($subscriberId, $authorId, $type = null)
	{
		$query = 'REPLACE INTO xrefusersubscribe
			SET xuserid = '.(int)$subscriberId.',
				xauthor = '.(int)$authorId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Unsubscribe from an authors content
	 *
	 * @param int Subscriber ID
	 * @param int Content creator ID
	 * @param string Article type (not yet used)
	 * @return bool True on success, false otherwise
	 */
	public function unsubscribe($subscriberId, $authorId, $type = null)
	{
		$query = 'DELETE FROM xrefusersubscribe
			WHERE xuserid = '.(int)$subscriberId.'
				AND xauthor = '.(int)$authorId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Check if a user is subscribed to an authors content
	 *
	 * @param int Subscriber ID
	 * @param int Content creator ID
	 * @param string Article type (not yet used)
	 * @return True if subscribed, false otherwise
	 */
	public function isSubscribed($subscriberId, $authorId, $type = null)
	{
		$query = 'SELECT xrefid
			FROM xrefusersubscribe
			WHERE xuserid = '.(int)$subscriberId.'
				AND xauthor = '.(int)$authorId;
		$this->_db->setQuery($query);
		return (bool) $this->_db->loadResult();
	}

	/**
	 * Get list of authors a user is subscribed to
	 *
	 * @param int User ID
	 * @return array Array of author (content creator) ids
	 */
	public function getSubscribedAuthors($userId)
	{
		// Get list of authors we're subscribed to
		$query = 'SELECT xauthor
			FROM xrefusersubscribe
			WHERE xuserid = '.(int)$userId;
		$this->_db->setQuery($query);
		return $this->_db->loadResultArray();
	}

	/**
	 * Get latest post from each blog the user is subscribed to
	 *
	 * @param int User ID
	 * @param string Item type
	 * @return array Array of blog post details
	 */
	public function getLatestSubscribedItems($userId, $type = 'article')
	{
		// Get latest post from each blog user is subscribed to
		$query = 'SELECT a.*
			FROM xrefusersubscribe AS x
				LEFT JOIN article AS a ON a.articleAuthor = x.xauthor
				LEFT JOIN article AS a2 ON a2.articleAuthor = a.articleAuthor
					AND a2.articleType = a.articleType
					AND a2.articleLive = 1
					AND a2.articleDeleted != 1
					AND a2.articleTime > a.articleTime
			WHERE x.xuserid = '.(int)$userId.'
				AND a.articleType = "'.Database::escape($type).'"
				AND a.articleLive = 1
				AND a.articleDeleted != 1
				AND a2.articleID IS NULL
			ORDER BY a.articleTime DESC';
		$this->_db->setQuery($query);
		$items = $this->_db->loadAssocList('articleID');
		if (!$items) {
			return false;
		}

		// Get article details and return
		$this->addDetails($items);
		return $items;
	}
	
	/**
	 *	Increase the number of views by one.
	 */
	public function increaseViews($itemId){

		/* update database */
		$specialChanges = array('articleViews' => '`articleViews` + 1');
		$criteria = array('articleID' => $itemId);
		$success = $this->_edit('article', array(), $specialChanges, $criteria);
		if ($success <= 0){ return false; }

		/* Flush cached object */
		return $success;

	}

	/**
	 *	Get item categories.
	 */
	public function getItemCategories($type){
		
		/* Build query */
		$query = 'SELECT acs.*
			FROM `articleCategories` AS `acs`
			WHERE acs.articleCategorySection = "'.Database::escape($type).'"';
		$this->_db->setQuery($query);
		
		/* Execute query */
		$categories = $this->_db->loadAssocList('articleCategoryID');
		
		/* Format */
		foreach($categories as &$category){
			$category = $category['articleCategoryName'];
		}
		
		/* Return */
		return $categories;
		
	}
	
}

?>