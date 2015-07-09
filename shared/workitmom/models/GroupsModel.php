<?php

/**
 * Groups Model
 */
class WorkitmomGroupsModel extends BluModel
{
	/**
	 *	Get groups that Nataly doesn't like
	 */
	public function getDislikedGroups(){
		return array(339, 538, 491, 322, 540);
	}
	
	/**
	 * Get individual group details
	 *
	 * @param int Group ID
	 * @retrun array Group details
	 */
	public function getGroup($groupId)
	{
		// Load from cache
		$cacheKey = 'group_'.$groupId;
		$group = $this->_cache->get($cacheKey);
		if ($group === false) {

			// Get from DB
			$query = 'SELECT g.*, GROUP_CONCAT(t.tagName) AS `tags`
				FROM groups AS g
					LEFT JOIN groupTags AS gt ON gt.groupId = g.id
					LEFT JOIN tags AS t ON t.tagId = gt.tagId
				WHERE g.id = '.(int) $groupId.'
				GROUP BY g.id';
			$this->_db->setQuery($query, 0, 1);
			$group = $this->_db->loadAssoc();
			if (!$group) {
				return false;
			}

			// Explode tag names
			if ($group['tags']) {
				$group['tags'] = explode(',', $group['tags']);
			}
			
			// Get last post id and date
			$query = 'SELECT p.id AS `last_post_id`, p.created AS `last_post_date`
				FROM `groupPosts` AS `p`
				WHERE p.status != -1
					AND p.groupId = '.(int)$groupId.'
				ORDER BY p.created DESC';
			$this->_db->setQuery($query, 0, 1);
			$lastPost = $this->_db->loadAssoc();
			if (Utility::iterable($lastPost)){
				$group = array_merge($group, $lastPost);
			}

			// Store in cache
			$this->_cache->set($cacheKey, $group);
		}

		// Check if user is member or has invite
		$group['isMember'] = $this->isMember($groupId);
		if (!$group['isMember']) {
			$group['hasInvite'] = $this->hasGroupInvite($groupId);
		} else {
			// Safe to assume they had an invite if they are a member
			$group['hasInvite'] = $group['isMember'];
		}

		// Check subscription status
		$group['isSubscribed'] = $this->isSubscribedToGroup($groupId);

		// Deny access to private groups if not member and no invite
		if (($group['type'] == 'private') && !($group['isMember'] || $group['hasInvite'])) {
			return false;
		}

		// Return group details
		return $group;
	}

	/**
	 * Append details for each group in the given array
	 *
	 * @param array Array of groups to add details to
	 */
	public function addDetails(&$groups)
	{
		if (!empty($groups)) {
			foreach ($groups as $groupId => &$group) {
				$group = $this->getGroup($groupId);
			}
		}
	}

	/**
	 * Get groups
	 *
	 * @param int Offset
	 * @param int Limit
	 * @param int Set to total if passed in as true
	 * @param string Order
	 * @param int Category ID to limit listings to
	 * @return array List of listings
	 */
	public function getGroups($offset, $limit, &$total, array $options = array())
	{		
		// Get current user
		$user = BluApplication::getUser();

		/* Prepare options */
		$query = array(
			'select' => array(
				'g.id AS `id`'
			),
			'tables' => array(
				'`groups` AS `g`'
			),
			'where' => array(
				'g.deleted != 1',
				'(g.type = "public"'.($user ? ' OR ug.userID IS NOT NULL' : '').')'
			),
			'group' => 'g.id',
			'order' => 'g.created',
			'direction' => 'DESC'
		);
		if ($user){
			$query['tables'][] = '`xrefusergroup` AS `ug` ON ug.groupID = g.id 
				AND ug.userID = '.(int)$user->userid;
		}
		if (Utility::iterable($options)){
			foreach($options as $key => $value){
				switch($key){
					case 'order':
						switch($value){
							case 'popular':
								$query['select'][] = 'COUNT(p.id) AS `postCount`';
								$query['tables'][] = '`groupPosts` AS `p` ON p.groupId = g.id 
									AND p.status != -1
									AND p.created >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)';
								$query['order'] = '`postCount`';
								$query['direction'] = 'DESC';
								break;
								
							case 'discussions':
								$query['select'][] = 'COUNT(t.id) AS `topicCount`';
								$query['tables'][] = '`groupTopics` AS `t` ON t.groupId = g.id';
								$query['order'] = '`topicCount`';
								$query['direction'] = 'DESC';
								break;
								
							case 'recent':
								$query['select'][] = 'MAX(p.created) AS `lastPostDate`';
								$query['tables'][] = '`groupTopics` AS `t` ON t.groupId = g.id
									AND t.status != -1';
								$query['tables'][] = '`groupPosts` AS `p` ON p.topicId = t.id
									AND p.status != -1';
								$query['order'] = '`lastPostDate`';
								$query['direction'] = 'DESC';
								break;
								
							case 'name':
								$query['order'] = 'g.name';
								$query['direction'] = 'ASC';
								break;
						}
						break;
						
					case 'direction':
						if (!in_array(strtolower($value), array('asc', 'desc'))){ break; }
						$query['direction'] = strtoupper($value);
						break;
						
					case 'category':
						/* Filter by category ID */
						if (!$value){
							break;
						}
						if ($user && ($value == 'joined')){
							$query['where'][] = 'ug.userID = '.(int)$user->userid;
						} else {
							$query['where'][] = 'g.categoryId = '.(int)$value;
						}
						break;
						
					case 'exclude':
						/* Exclude certain groups by ID */
						$query['where'][] = 'g.id NOT IN ('.implode(', ', (array) $value).')';
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
		$groups = $this->_db->loadAssocList('id');
		if (!$groups) {
			return false;
		}
		$total = $this->_db->getFoundRows();
		
		/* Build object data */
		$this->addDetails($groups);
		return $groups;
		
		
		### OLD STUFF ###
		// Legacy...
		$order = $options['order'];
		$category = $options['category'];
		// Get groups
		$query = 'SELECT g.id
				'.($order == 'popular' ? ', COUNT(gp.id) AS postCount' : '').'
				'.($order == 'discussions' ? ', COUNT(t.id) AS topicCount' : '').'
				'.($order == 'recent' ? ', MAX(gp.created) AS lastPostDate' : '').'
			FROM groups AS g
				'.($order == 'popular' ? 'LEFT JOIN groupPosts AS gp ON gp.groupId = g.id AND (gp.created >= DATE_SUB(CURDATE(), INTERVAL 7 DAY))' : '').'
				'.($order == 'discussions' ? 'LEFT JOIN groupTopics AS t ON t.groupId = g.id' : '').'
				'.($order == 'recent' ? 'LEFT JOIN groupPosts AS gp ON gp.groupId = g.id' : '');
		if ($user) {
			$query .= ' LEFT JOIN xrefusergroup AS ug ON ug.groupID = g.id AND ug.userID = '.(int)$user->userid;
		}
		$query .= '	WHERE g.deleted != 1 AND (g.type = "public"'.($user ? ' OR ug.userID IS NOT NULL' : '').')';
		if ($category) {
			if ($user && ($category == 'joined')) {
				$query .= ' AND ug.userID = '.(int)$user->userid;
			} else {
				$query .= ' AND g.categoryId = '.(int)$category;
			}
		}
		$query .= ' GROUP BY g.id';
		switch ($order) {
			case 'recent':
				$query .= ' ORDER BY lastPostDate DESC';
				break;
			case 'popular':
				$query .= ' ORDER BY postCount DESC';
				break;
			case 'name':
				$query .= ' ORDER BY g.name';
				break;
			case 'discussions':
				$query .= ' ORDER BY topicCount DESC';
				break;
			default:
				$query .= ' ORDER BY g.created DESC';
				break;
		}
		$this->_db->setQuery($query, $offset, $limit, (bool)$total);
		$groups = $this->_db->loadAssocList('id');
		if (!$groups) {
			return false;
		}

		// Get total
		if ($total) {
			$total = $this->_db->getFoundRows();
		}

		// Add group details and return
		$this->addDetails($groups);
		return $groups;
	}

	/**
	 * Get groups owned by a user
	 *
	 * @param int User ID
	 * @return array List of owned groups
	 */
	public function getOwnedGroups($userId)
	{
		// Get groups
		$query = 'SELECT g.id
			FROM groups AS g
			WHERE g.owner = '.(int)$userId.'
			ORDER BY g.created DESC';
		$this->_db->setQuery($query);
		$groups = $this->_db->loadAssocList('id');
		if (!$groups) {
			return false;
		}

		// Add group details and return
		$this->addDetails($groups);
		return $groups;
	}

	/**
	 * Get groups that a user has joined (not created)
	 *
	 * @param int User ID
	 * @return array List of owned groups
	 */
	public function getJoinedGroups($userId)
	{
		// Get groups
		$query = 'SELECT g.id
			FROM groups AS g
				LEFT JOIN xrefusergroup AS ug ON ug.groupID = g.id
			WHERE ug.userID = '.(int)$userId.'
				AND g.owner != '.(int)$userId.'
			ORDER BY ug.joined DESC';
		$this->_db->setQuery($query);
		$groups = $this->_db->loadAssocList('id');
		if (!$groups) {
			return false;
		}

		// Add group details and return
		$this->addDetails($groups);
		return $groups;
	}

	/**
	 * Get list of group categories
	 *
	 * @return array List of group categories
	 */
	public function getCategories()
	{
		$query = 'SELECT gc.*
			FROM groupCategories AS gc
			WHERE gc.enabled = 1';
		$this->_db->setQuery($query);
		$categories = $this->_db->loadAssocList('id');

		return $categories;
	}

	/**
	 * Get a users group invites
	 *
	 * @param int User ID (defaults to current user)
	 * @return array List of group invites
	 */
	public function getGroupInvites($userId = null)
	{
		$user = BluApplication::getUser($userId);
		if (!$user) {
			return false;
		}

		// Get invites
		$query = 'SELECT * FROM groupInvites
			WHERE toId = '.(int) $user->userid;
		$this->_db->setQuery($query);
		return $this->_db->loadAssocList('groupInviteId');
	}

	/**
	 * Check if a user has an invite to the given group
	 *
	 * @param int Group ID
	 * @param int User ID (defaults to current user)
	 * @param bool True if invite exists, false otherwise
	 */
	public function hasGroupInvite($groupId, $userId = null)
	{
		$user = BluApplication::getUser($userId);
		if (!$user) {
			return false;
		}

		// Check for invite
		$query = 'SELECT count(*) FROM groupInvites
			WHERE toId = '.(int) $user->userid.'
				AND groupId = '.(int)$groupId;
		$this->_db->setQuery($query);
		return (bool) $this->_db->loadResult();
	}

	/**
	 * Get group members
	 *
	 * @param int Group ID
	 * @param int Offset
	 * @param int Limit
	 * @param int Set to total
	 * @param string Order
	 * @return array List of group members
	 */
	public function getMembers($groupId, $offset = 0, $limit = 4, &$total = null, $order = 'joined')
	{
		$query = 'SELECT u.*
			FROM xrefusergroup AS x
				LEFT JOIN users AS u ON u.UserID = x.userID
			WHERE x.groupID = '.(int) $groupId;
		switch ($order) {
			case 'joined':
				$query .= ' ORDER BY x.joined DESC';
				break;
			case 'random':
				$query .= ' ORDER BY RAND()';
				break;
		}
		$this->_db->setQuery($query, $offset, $limit, $total);
		$members = $this->_db->loadResultArray();
		if ($total) {
			$total = $this->_db->getFoundRows();
		}

		// Get user details
		$personModel = BluApplication::getModel('person');
		$members = array_flip($members);
		foreach ($members as $userId => &$member) {
			$member = $personModel->getPerson(array('member' => $userId));
		}

		return $members;
	}

	/**
	 * Check if a user is a member of a group
	 *
	 * @param int Group ID
	 * @param int User ID (defaults to current user)
	 */
	public function isMember($groupId, $userId = null)
	{
		// Default to logged in user
		if ($userId === null) {
			$user = BluApplication::getUser();
			if (!$user) {
				return false;
			}
			$userId = $user->userid;
		}

		// Check for membership
		$query = 'SELECT count(*) FROM xrefusergroup
			WHERE userID = '.(int) $userId.'
				AND groupID = '.(int)$groupId;
		$this->_db->setQuery($query);
		return (bool) $this->_db->loadResult();
	}

	/**
	 * Create a group
	 *
	 * @param string Group title
	 * @param int Category ID
	 * @param string Group slug
	 * @param string Group type
	 * @param string Group blurb
	 * @param string Group description
	 * @param array Group tags
	 * @return int Group ID
	 */
	public function createGroup($title, $categoryId, $slug, $type, $blurb, $description, $tags)
	{
		$user = BluApplication::getUser();
		if (!$user) {
			return false;
		}

		// Add group
		$query = 'INSERT INTO groups
			SET name = "'.Database::escape($title).'",
				`owner` = '.(int) $user->userid.',
				slug = "'.Database::escape($slug).'",
				type = "'.Database::escape($type).'",
				description = "'.Database::escape($description).'",
				blurb = "'.Database::escape($blurb).'",
				`created` = NOW(),
				categoryId = '.(int) $categoryId;
		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			return false;
		}
		$groupId = $this->_db->getInsertID();

		// Add tag mappings
		if (!empty($tags)) {
			$metaModel = BluApplication::getModel('meta');
			foreach ($tags as $tag) {
				$tagId = $metaModel->addTag($tag);
				$query = 'INSERT INTO groupTags
					SET groupId = '.(int)$groupId.',
						tagId = '.(int)$tagId;
				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}
		
		// Return
		return $groupId;
	}

	/**
	 * Set group photo
	 *
	 * @param int Group ID
	 * @param string Uploaded file ID
	 * @param array File details
	 * @return bool True on success, false otherwise
	 */
	public function setPhoto($groupId, $uploadId, $file)
	{
		// Determine path to asset file
		$origFileName = basename($file['name']);
		$assetFileName = md5(microtime().mt_rand(0, 250000)).'_'.$origFileName;
		$assetPath = BLUPATH_ASSETS.'/groupimages/'.$assetFileName;

		// Move uploaded file into place
		if (!Upload::move($uploadId, $assetPath)) {
			return false;
		}

		// Add details to database
		$query = 'UPDATE groups
			SET photo = "'.Database::escape($assetFileName).'"
			WHERE id = '.(int)$groupId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Send group invites
	 *
	 * @param int Group ID
	 * @param array Array of user IDs to add the invites for
	 * @return bool True on success, false otherwise
	 */
	public function addInvites($groupId, $friendIds)
	{
		// Get group details
		$group = $this->getGroup($groupId);

		// Check permissions
		$user = BluApplication::getUser();
		if (!$group || !$user || ($group['owner'] != $user->userid)) {
			return false;
		}

		// Add invites
		foreach ($friendIds as $friendId) {
			$query = 'INSERT INTO groupInvites
				SET groupID = '.(int) $groupId.',
					fromId = '.(int) $user->userid.',
					toId = '.(int) $friendId.',
					status = "pending"';
			$this->_db->setQuery();
			$this->_db->query();
		}

		return true;
	}

	/**
	 * Join a group
	 *
	 * @param int Group ID
	 * @param int User ID (defaults to current user)
	 */
	public function joinGroup($groupId, $userId = null)
	{
		$user = BluApplication::getUser($userId);
		if (!$user) {
			return false;
		}

		// Get group details
		$group = $this->getGroup($groupId);

		// Allowed to join?
		if (!$group || (($group['type'] != 'public') && !$group['hasInvite'])) {
			return false;
		}

		// Add membership
		$query = 'INSERT INTO xrefusergroup
			SET userID = '.(int)$user->userid.',
				groupID = '.(int)$groupId;
		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			return false;
		}

		// Remove used invite
		if ($group['hasInvite']) {
			$query = 'DELETE FROM groupInvites
				WHERE toid = '.(int)$user->userid.'
					AND groupID = '.(int)$groupid;
			$this->_db->setQuery($query);
			$this->_db->query();
		}

		return true;
	}

	/**
	 * Leave a group
	 *
	 * @param int Group ID
	 * @param int User ID (defaults to current user)
	 */
	public function leaveGroup($groupId, $userId = null)
	{
		$user = BluApplication::getUser($userId);
		if (!$user) {
			return false;
		}

		// Remove membership
		$query = 'DELETE FROM xrefusergroup
			WHERE userID = '.(int) $user->userid.'
				AND groupID = '.(int)$groupId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Subscribe a user to new group discussions
	 *
	 * @param int Group id
	 * @return bool True on success, false otherwise
	 */
	public function subscribeGroup($userId, $groupId)
	{
		$query = 'REPLACE INTO groupSubscriptions
			SET userId = '.(int) $userId.',
				groupId = '.(int) $groupId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Unsubscribe a user from new group discussions
	 *
	 * @param int Group id
	 * @return bool True on success, false otherwise
	 */
	public function unsubscribeGroup($userId, $groupId)
	{
		$query = 'DELETE FROM groupSubscriptions
			WHERE userId = '.(int) $userId.'
				AND groupId = '.(int) $groupId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Get all subscribers to a group
	 *
	 * @param int Group ID
	 * @return array Array of user ids
	 */
	public function getGroupSubscribers($topicId)
	{
		$query = 'SELECT userId
			FROM groupSubscriptions
			WHERE groupId = '.(int) $topicId;
		$this->_db->setQuery($query);
		$users = $this->_db->loadResultArray();

		return $users;
	}

	/**
	 * Check if user is subscribed to a group
	 *
	 * @param int Group ID
	 * @param int Optional user ID to check
	 * @return bool Whether user is subscribed
	 */
	public function isSubscribedToGroup($groupId, $userId = null)
	{
		// Default to logged in user
		if ($userId === null) {
			$user = BluApplication::getUser();
			if (!$user) {
				return false;
			}
			$userId = $user->userid;
		}

		// Check status
		$query = 'SELECT userId
			FROM groupSubscriptions
			WHERE userId = '.(int)$userId.'
				AND groupId = '.(int)$groupId;
		$this->_db->setQuery($query);
		return (bool) $this->_db->loadResult();
	}

	/**
	 * Get group links
	 *
	 * @param int Group ID
	 * @param int Offset
	 * @param int Limit
	 * @param int Set to total
	 * @param string Order
	 * @return array List of group links
	 */
	public function getLinks($groupId, $offset = 0, $limit = 4, &$total = null, $order = 'created')
	{
		$query = 'SELECT r.*
			FROM resources AS r
			WHERE r.groupID = '.(int) $groupId.'
				AND r.resourceType = "link"';
		switch ($order) {
			case 'created':
				$query .= ' ORDER BY r.resourceTime DESC';
				break;
			case 'random':
				$query .= ' ORDER BY RAND()';
				break;
		}
		$this->_db->setQuery($query, $offset, $limit, $total);
		$links = $this->_db->loadAssocList('resourceID');
		if (!$links) {
			return false;
		}

		// Get total
		if ($total) {
			$total = $this->_db->getFoundRows();
		}

		// Get user details
		$personModel = BluApplication::getModel('person');
		foreach ($links as &$link) {
			$link['user'] = $personModel->getPerson(array('member' => $link['resourceUser']));
		}

		return $links;
	}
	
	/**
	 *	Get a group photo.
	 */
	public function getPhoto($groupPhotoId){
		
		/* Basic data */
		if (!$groupPhotoId){
			return false;
		}
		$query = 'SELECT ph.*
			FROM `groupPhotos` AS `ph`
			WHERE ph.groupPhotoID = '.(int)$groupPhotoId;
		$this->_db->setQuery($query, 0, 1);
		$groupPhoto = $this->_db->loadAssoc();
		
		// Get user details
		$personModel = BluApplication::getModel('person');
		$groupPhoto['user'] = $personModel->getPerson(array('member' => $groupPhoto['userID']));
		
		/* Return */
		return $groupPhoto;
		
	}

	/**
	 * Get group photos
	 *
	 * @param int Group ID
	 * @param int Offset
	 * @param int Limit
	 * @param int Set to total if true
	 * @param string Order
	 * @return array List of group links
	 */
	public function getPhotos($groupId, $offset = 0, $limit = 4, &$total = null, $order = 'created')
	{
		$query = 'SELECT gp.groupPhotoID
			FROM groupPhotos AS gp
			WHERE gp.groupID = '.(int)$groupId.'
				AND gp.status != -1';
		switch ($order) {
			case 'created':
				$query .= ' ORDER BY gp.photoTime DESC';
				break;
			case 'random':
				$query .= ' ORDER BY RAND()';
				break;
		}
		$this->_db->setQuery($query, $offset, $limit, $total);
		$photos = $this->_db->loadAssocList('groupPhotoID');
		if (!$photos) {
			return false;
		}

		// Get total
		if ($total) {
			$total = $this->_db->getFoundRows();
		}

		/* Build data */
		if (Utility::iterable($photos)){
			foreach($photos as $photoId => &$photo){
				$photo = $this->getPhoto($photoId);
			}
			unset($photo);
		}
		return $photos;
	}
	
	/**
	 *	Add a group photo.
	 */
	public function addPhoto($groupId, $uploadId, $file, $caption = null)
	{
		// Require user.
		if (!$user = BluApplication::getUser()){
			return false;
		}
		
		// Determine path to asset file
		$origFileName = basename($file['name']);
		$assetFileName = md5(microtime().mt_rand(0, 250000)).'_'.$origFileName;
		$assetPath = BLUPATH_ASSETS.'/groupphotoimages/'.$assetFileName;

		// Move uploaded file into place
		if (!Upload::move($uploadId, $assetPath)) {
			return false;
		}

		// Add details to database
		$query = 'INSERT INTO `groupPhotos`
			SET `groupPhotoName` = "'.Database::escape($assetFileName).'",
				`groupID` = '.(int)$groupId.',
				`userID` = '.(int)$user->userid.',
				`photoCaption` = "'.Database::escape($caption).'",
				`photoTime` = NOW()';
		$this->_db->setQuery($query);
		$this->_db->query();
		return $this->_db->getInsertID();
	}
	
	/**
	 *	Delete a group photo.
	 */
	public function deletePhoto($groupPhotoId){
		
		/* Update status flag for photo */
		$deleted = $this->_edit('groupPhotos', array('status' => -1), array(), array('groupPhotoID' => (int) $groupPhotoId));
		return $deleted;
		
	}

	/**
	 * The almighty get-all-topics method.
	 *
	 * Get forum topics that have 1 or more posts.
	 * (0 posts means the topic has been deleted).
	 *
	 * @param int Group ID
	 * @param int Offset
	 * @param int Limit
	 * @param int Set to total
	 * @param string Order
	 * @return array List of topics
	 */
	public function getTopics($offset = 0, $limit = 4, &$total = null, array $options = array())
	{
		/* Prepare options */
		$query = array(
			'select' => array(
				't.id AS `id`',
				'COUNT(p.id) AS `post_count`',
				'MAX(p.created) AS `latest_post_date`'
			),
			'tables' => array(
				'`groupTopics` AS `t`',
				'`groupPosts` AS `p` ON p.topicId = t.id
					AND p.status != -1',
				'`groups` AS `g` ON g.id = t.groupId'
			),
			'where' => array(
				't.status != -1'
			),
			'group' => 't.id',
			'having' => array(
				'`post_count` > 0'
			),
			'order' => 't.created',
			'direction' => 'DESC'
		);
		if (Utility::iterable($options)){
			foreach($options as $key => $value){
				switch($key){
					case 'order':
						switch($value){
							case 'latest_post':
								/* Sort by latest post from this discussion */
								$query['order'] = '`latest_post_date`';
								break;
								
							case 'created':
								/* Sort by date of creation of topic */
								$query['order'] = 't.created';
								break;
								
							case 'views':
								/* Sort by number of views */
								$query['order'] = 't.viewCount';
								break;
						}
						break;
						
					case 'direction':
						if (!in_array(strtolower($value), array('asc', 'desc'))){
							break; 
						}
						$query['direction'] = strtoupper($value);
						break;
						
					case 'group':
						/* Filter by a group */
						if (!$value){
							return false;
						}
						$query['where'][] = 't.groupId = '.(int)$value;
						break;
						
					case 'exclude_groups':
						/* Filter by groups */
						$query['where'][] = 't.groupId NOT IN ('.implode(', ', (array)$value).')';
						break;
						
					case 'category_name':
						/* Filter by category name */
						if ($categoryName = Database::escape($value)){
							$query['tables'][] = '`groupCategories` AS `gc` ON gc.id = g.categoryId
								AND gc.enabled = 1';
							$query['where'][] = 'gc.name LIKE "%'.$categoryName.'%"';
						}
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
			HAVING '.implode('
				AND ', $query['having']).'
			ORDER BY '.$query['order'].' '.$query['direction'];
		
		/* Execute query */
		$this->_db->setQuery($query, $offset, $limit);
		$topics = $this->_db->loadAssocList('id');
		$total = $this->_db->getFoundRows();
		
		/* Build data */
		if (Utility::iterable($topics)){
			foreach($topics as $topicId => &$topic){
				$topic = $this->getTopic($topicId);
			}
			unset($topic);
		}

		/* Return topics */
		return $topics;
		
		
		### OLD CODE ###
		$order = $options['order'];
		
		/* Get IDs */
		$query = 'SELECT t.id, COUNT(p.id) AS `post_count`
			FROM groupTopics AS t
				LEFT JOIN `groupPosts` AS `p` ON p.topicId = t.id AND p.status = 0
			WHERE t.groupId = '.(int) $groupId.'
			GROUP BY t.id
			HAVING `post_count` > 0';
		switch ($order) {
			case 'created':
				$query .= ' ORDER BY t.created DESC';
				break;
			case 'views':
				$query .= ' ORDER BY t.viewCount';
				break;
		}
		$this->_db->setQuery($query, $offset, $limit, $total);
		$topics = $this->_db->loadAssocList('id');
		
		/* Get found rows. */
		if ($total) {
			$total = $this->_db->getFoundRows();
		}
		
		/* Build data */
		if (Utility::iterable($topics)){
			foreach($topics as $topicId => &$topic){
				$topic = $this->getTopic($topicId);
			}
			unset($topic);
		}

		/* Return topics */
		return $topics;
	}

	/**
	 * Get random topics from featured groups
	 *
	 * @param int Limit
	 * @return array List of topics
	 */
	public function getFeaturedTopics($limit = 4)
	{
		$query = 'SELECT t.*, COUNT(p.id) AS `post_count`
			FROM groups AS g
				LEFT JOIN groupTopics AS t ON t.groupId = g.id
				LEFT JOIN `groupPosts` AS `p` ON p.topicId = t.id AND p.status = 0
			WHERE g.deleted != 1
				AND g.type = "public"
				AND g.featured = 1
			GROUP BY t.id
			HAVING `post_count` > 0
			ORDER BY RAND()';
		$this->_db->setQuery($query, 0, $limit);
		return $this->_db->loadAssocList('id');
	}

	/**
	 * Get latest created topics from current category
	 *
	 * @param string Category Name
	 * @param int Limit
	 * @return array List of topics
	 */
	public function getLatestCategoryTopics($categoryName = null, $limit = 3)
	{
		$query = 'SELECT t.*, COUNT(p.id) AS `post_count`
			FROM groups AS g
				LEFT JOIN groupTopics AS t ON t.groupId = g.id
				LEFT JOIN groupCategories AS c ON c.id = g.categoryId
				LEFT JOIN `groupPosts` AS `p` ON p.topicId = t.id AND p.status = 0
			WHERE g.deleted != 1
				AND g.type = "public"
				'.($categoryName ? 'AND c.name = "'.$categoryName.'" ' : '').'
			GROUP BY t.id
			HAVING `post_count` > 0
			ORDER BY t.created DESC';
		$this->_db->setQuery($query, 0, $limit);
		return $this->_db->loadAssocList('id');
	}
	
	/**
	 * Get most recently active topics from current category
	 *
	 * @param string Category Name
	 * @param int Limit
	 * @return array List of topics
	 */
	public function getRecentlyActiveCategoryTopics($categoryName = null, $limit = 3)
	{
		/* Get topics */
		$topic_query = 'SELECT t.*, COUNT(p.id) AS `post_count`
			FROM groupTopics AS t 
				LEFT JOIN groups AS g ON t.groupId = g.id
				LEFT JOIN groupCategories AS c ON c.id = g.categoryId
				LEFT JOIN `groupPosts` AS `p` ON p.topicId = t.id AND p.status = 0
			WHERE g.deleted != 1
				AND g.type = "public"
				'.($categoryName ? 'AND c.name = "'.$categoryName.'" ' : '').'
			GROUP BY t.id
			HAVING `post_count` > 0
			ORDER BY t.created DESC';
		$this->_db->setQuery($topic_query, 0, $limit);
		$topics = $this->_db->loadAssocList('id');
		
		/* Get topics' latest post */
		$post_query = 'SELECT p.*
			FROM `groupPosts` AS `p` 
			WHERE p.topicId IN ('.implode(', ', array_keys($topics)).') 
			GROUP BY p.topicId 
			ORDER BY p.created DESC';
		$this->_db->setQuery($post_query, 0, count($topics));	// Limit is superfluous, really.
		$topicLatestPosts = $this->_db->loadAssocList('topicId');
		
		/* Append latest posts to respective topics */
		foreach ($topicLatestPosts as $topicId => $post) {
			$topics[$topicId]['created'] = $post['created'];
			$topics[$topicId]['text'] = $post['text'];
		}
		
		/* Return */
		return $topics;
	}

	/**
	 * Get discussion topic
	 *
	 * @param int Topic id
	 * @return array Topic details
	 */
	public function getTopic($topicId)
	{
		// Fetch from cache
		$cacheKey = 'topic_'.(int)$topicId;
		$topic = $this->_cache->get($cacheKey);
		if ($topic === false){
			
			// Get topic
			$query = 'SELECT t.*, MAX(p.created) AS `last_post_date`
				FROM groupTopics AS t
					LEFT JOIN `groupPosts` AS `p` ON t.id = p.topicId
						AND p.status != -1
				WHERE t.id = '.(int)$topicId.'
				GROUP BY t.id';
			$this->_db->setQuery($query);
			$topic = $this->_db->loadAssoc();
			if (!$topic) {
				return false;
			}
			$topic['id'] = (int) $topic['id'];
			
			// Get topic data
			unset($topic['postCount']);	// Invalid data, use $topic['post_count'] instead...
			$this->getAllPosts(0, 1, $topic['post_count'], array(
				'topic' => $topic['id'],
				'order' => 'date',
				'direction' => 'DESC'
			));
			$topic['reply_count'] = max($topic['post_count'], 0);	// Shortcut, non-negativity too.
			
			// Store in cache
			$this->_cache->set($cacheKey, $topic);
			
		}

		// Check subscription status
		$topic['isSubscribed'] = $this->isSubscribedToTopic($topicId);

		return $topic;
	}

	/**
	 * Add discussion topic
	 *
	 * @param int User adding topic
	 * @param int Group id
	 * @param string Topic title
	 * @param string Topic post
	 * @return int Topic id on success, false otherwise
	 */
	public function addTopic($userId, $groupId, $title, $post)
	{
		// Add topic
		$query = 'INSERT INTO groupTopics
			SET title = "'.Database::escape($title).'",
				userId = '.(int)$userId.',
				postCount = 0,
				viewCount = 0,
				status = 0,
				sticky = 0,
				created = NOW(),
				groupId = '.(int) $groupId;
		$this->_db->setQuery($query);
		$this->_db->query();
		$topicId = $this->_db->getInsertID();

		// Add reply
		$this->addPost($userId, $topicId, $post);

		// Send alert to all subscribed users
		$subscribers = $this->getGroupSubscribers($groupId);
		if ($subscribers) {

			// Get group details
			$group = $this->getGroup($groupId);

			// Create alert
			$alertsModel = BluApplication::getModel('alerts');
			$alertId = $alertsModel->createAlert('newgroupdiscussion', array(
				'topicId' => $topicId,
				'topicTitle' => $title,
				'groupId' => $groupId,
				'groupTitle' => $group['name']
			), $userId);

			// Apply alert to subscribers
			foreach ($subscribers as $subscriberId) {
				if ($subscriberId != $userId) {
					$alertsModel->applyAlert($alertId, $subscriberId);
				}
			}
		}

		return $topicId;
	}

	/**
	 * Subscribe a user to topic updates
	 *
	 * @param int Topic id
	 * @return bool True on success, false otherwise
	 */
	public function subscribeTopic($userId, $topicId)
	{
		$query = 'REPLACE INTO groupTopicSubscriptions
			SET userId = '.(int) $userId.',
				topicId = '.(int) $topicId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Unsubscribe a user from topic updates
	 *
	 * @param int Topic id
	 * @return bool True on success, false otherwise
	 */
	public function unsubscribeTopic($userId, $topicId)
	{
		$query = 'DELETE FROM groupTopicSubscriptions
			WHERE userId = '.(int) $userId.'
				AND topicId = '.(int) $topicId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Get all subscribers to a topic
	 *
	 * @param int Topic ID
	 * @return array Array of user ids
	 */
	public function getTopicSubscribers($topicId)
	{
		$query = 'SELECT userId
			FROM groupTopicSubscriptions
			WHERE topicId = '.(int) $topicId;
		$this->_db->setQuery($query);
		$users = $this->_db->loadResultArray();

		return $users;
	}

	/**
	 * Check if user is subscribed to a topic
	 *
	 * @param int Topic ID
	 * @param int Optional user ID to check
	 * @return bool Whether user is subscribed
	 */
	public function isSubscribedToTopic($topicId, $userId = null)
	{
		// Default to logged in user
		if ($userId === null) {
			$user = BluApplication::getUser();
			if (!$user) {
				return false;
			}
			$userId = $user->userid;
		}

		// Check status
		$query = 'SELECT userId
			FROM groupTopicSubscriptions
			WHERE userId = '.(int)$userId.'
				AND topicId = '.(int)$topicId;
		$this->_db->setQuery($query);
		return (bool) $this->_db->loadResult();
	}

	/**
	 * Get discussion topics started by a user
	 *
	 * @param int User id
	 * @return array Topic details
	 */
	public function getUserTopics($userId, $offset = null, $limit = null)
	{
		$query = 'SELECT t.*
			FROM groupTopics AS t
				LEFT JOIN groups AS g ON g.id = t.groupId
			WHERE t.userId = '.(int)$userId.'
				AND g.deleted != 1';
		$this->_db->setQuery($query, $offset, $limit);
		return $this->_db->loadAssocList();
	}
	
	/**
	 *	Get *all* posts.
	 */
	public function getAllPosts($offset = null, $limit = null, &$total = null, array $options = array()){
		
		/* Prepare options */
		$query = array(
			'select' => array(
				'p.id AS `id`',
				'COUNT(r.reportId) AS `reports`'
			),
			'tables' => array(
				'`groupPosts` AS `p`',
				'`reports` AS `r` ON p.id = r.objectId
					AND r.objectType = "grouppost"'
			),
			'where' => array(
				'p.status != -1'
			),
			'group' => 'p.id',
			'order' => 'p.id',
			'direction' => 'ASC'
		);
		if (Utility::iterable($options)){
			foreach($options as $key => $value){
				switch($key){
					case 'order':
						switch($value){
							case 'id':
								$query['order'] = 'p.id';
								break;
								
							case 'poster':
								// Sort by username, not user ID.
								$query['tables'][] = '`users` AS `u` ON p.userId = u.UserID';
								$query['order'] = 'u.username';
								break;
								
							case 'text':
								$query['order'] = 'TRIM(p.text)';
								break;
								
							case 'date':
								$query['order'] = 'p.created';
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
						
					case 'topic':
						$query['where'][] = 'p.topicId = '.(int)$value;
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
		$posts = $this->_db->loadAssocList('id');
		$total = $this->_db->getFoundRows();
		
		/* Build object data */
		$this->addPostDetails($posts);
		return $posts;
		
	}

	/**
	 * Get discussion posts
	 *
	 * @param int Topic id
	 * @return array Topic posts
	 */
	public function getPosts($topicId, $offset = null, $limit = null, &$total = null, $order = 'created')
	{
		$query = 'SELECT SQL_CALC_FOUND_ROWS p.id
			FROM groupPosts AS p
			WHERE p.topicId = '.(int)$topicId.'
				AND p.status != -1';
		switch ($order) {
			case 'created':
				$query .= '
			ORDER BY p.created';
				break;
		}
		$this->_db->setQuery($query, $offset, $limit);
		$posts = $this->_db->loadAssocList('id');
		$total = $this->_db->getFoundRows();

		$this->addPostDetails($posts);
		return $posts;
	}
	
	/**
	 *	Get post details.
	 */
	public function addPostDetails(&$posts){
		if (Utility::iterable($posts)){
			foreach($posts as $postId => &$post){
				$post = $this->getPost($postId);
			}
		}
	}
	
	/**
	 *	Check if a discussion has (non-deleted) posts.
	 */
	public function hasPosts($topicId, $includeDeleted = false){
		
		/* Build query */
		$query = 'SELECT COUNT(*)
			FROM `groupPosts` AS `p`
			WHERE p.topicId = '.(int)$topicId;
		if (!$includeDeleted){
			$query .= '
				AND p.status != -1';
		}
		
		/* Run */
		$this->_db->setQuery($query);
		$posts = $this->_db->loadResult();
		
		/* Return */
		return (bool) $posts;
		
	}

	/**
	 * Get individual post
	 *
	 * @param int Post id
	 * @return array Post details
	 */
	public function getPost($postId)
	{
		/* Get base data */
		$query = 'SELECT p.*, COUNT(r.reportId) AS `reports`
			FROM `groupPosts` AS `p`
				LEFT JOIN `reports` AS `r` ON p.id = r.objectId AND r.objectType = "grouppost"
			WHERE p.id = '.(int)$postId.'
			GROUP BY p.id';
		$this->_db->setQuery($query);
		$post = $this->_db->loadAssoc();
		if (!Utility::iterable($post)){
			return null;
		}
		$post['id'] = (int) $post['id'];
		
		/* Build post */
		$post['date'] = $post['time'] = $post['created'];
		$post['deleted'] = $post['status'] == -1;
		$post['group'] = (int) $post['groupId'];
		$post['discussion'] = (int) $post['topicId'];
		$post['author'] = (int) $post['userId'];
		$post['reports'] = (int) $post['reports'];
		$post['link'] = '/groups/discussion/'.$post['discussion'];
		
		// Add author
		$personModel = $this->getModel('newperson');
		$post['author'] = $personModel->getPerson(array('member' => $post['author']));
		
		/* Return */
		return $post;
	}

	/**
	 * Add a discussion post
	 *
	 * @param int User adding post
	 * @param int Topic id
	 * @param string Post text
	 * @return bool True on success, false otherwise
	 */
	public function addPost($userId, $topicId, $text)
	{
		// Add post
		$query = 'INSERT INTO groupPosts
			SET groupId = (SELECT t.groupId FROM groupTopics AS t WHERE t.id = '.(int) $topicId.'),
				topicId = '.(int)$topicId.',
				userId = '.(int)$userId.',
				text = "'.Database::escape($text).'",
				created = NOW(),
				posterIP = "'.Database::escape(Request::getVisitorIPAddress()).'",
				status = 0,
				reportCount = 0';
		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			return false;
		}

		// Update topic post counts
		$query = 'UPDATE groupTopics AS t
			SET t.postCount = t.postCount + 1,
				t.lastPostTime = NOW()
			WHERE t.id = '.(int) $topicId;
		$this->_db->setQuery($query);
		$this->_db->query();

		// Send alert to all suscribed users
		$subscribers = $this->getTopicSubscribers($topicId);
		if ($subscribers) {

			// Get topic details
			$topic = $this->getTopic($topicId);

			// Create alert
			$alertsModel = BluApplication::getModel('alerts');
			$alertId = $alertsModel->createAlert('groupdiscussion', array(
				'topicId' => $topicId,
				'topicTitle' => $topic['title']
			), $userId);

			// Apply alert to subscribers
			foreach ($subscribers as $subscriberId) {
				if ($subscriberId != $userId) {
					$alertsModel->applyAlert($alertId, $subscriberId);
				}
			}
		}
	}

	/**
	 * Edit a discussion post
	 *
	 * @param int User id
	 * @param int Post id
	 * @param string Post text
	 * @return bool True on success, false otherwise
	 */
	public function editPost($userId, $postId, $text)
	{
		// Add post
		$query = 'UPDATE groupPosts
			SET	text = "'.Database::escape($text).'"
				WHERE id = '.(int)$postId.'
					AND userId = '.(int)$userId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Delete a discussion post
	 *
	 * @param int Post id
	 * @return bool True on success, false otherwise
	 */
	public function deletePost($postId)
	{
		// Format
		$postId = (int) $postId;
		if (!$postId){ return false; }
		
		// Set deleted
		$deleted = $this->_edit('groupPosts', array('status' => -1), array(), array('id' => $postId));
		
		// Return
		return $deleted;
		
		
		
		/*** OLD STUFF ****/
		// Fetch topic id
		$query = 'SELECT topicId FROM groupPosts
			WHERE id = '.(int)$postId;
		$this->_db->setQuery($query);
		if(!$topicId = $this->_db->loadResult()) return 'Sorry, this post does not exist.';
	
		// Delete post
		$query = 'DELETE FROM groupPosts
			WHERE id = '.(int)$postId;
		$this->_db->setQuery($query);
		$this->_db->query();
		
		// Update topic post counts
		$query = 'UPDATE groupTopics AS t
			SET t.postCount = t.postCount - 1
			WHERE t.id = '.(int) $topicId;
		$this->_db->setQuery($query);
		$this->_db->query();
		
		// Test if any posts remain
		$query = 'SELECT postCount FROM groupTopics AS t
			WHERE t.id = '.(int) $topicId;
		$this->_db->setQuery($query);
		if($this->_db->loadResult() == 0) {
			// No posts remain: destroy topic & all topic subscriptions
			$this->deleteEmptyTopic($topicId);
			return 'Topic deleted.';
		} else return 'Post deleted.';
		
	}
	
	/**
	 *	Delete empty topic
	 *	@param int Topic id
	 */
	private function deleteEmptyTopic($topicId)
	{
		$query = 'DELETE FROM groupTopics
			WHERE id = '.(int) $topicId.' 
			AND postCount = 0';
		$this->_db->setQuery($query);
		$this->_db->query();
		if($this->_db->getAffectedRows() == 1) {
			$query = 'DELETE FROM groupTopicSubscriptions
				WHERE topicId = '.(int) $topicId;
			$this->_db->setQuery($query);
			$this->_db->query();
		}
	}


	/**
	 * Report post
	 *
	 * @param int User id filing report
	 * @param int Post id
	 * @return bool True on success, false otherwise
	 */
	public function reportPost($userId, $postId)
	{
		$query = 'INSERT INTO groupPostReports
			SET postId = '.(int)$postId.',
				userId = '.(int)$userId.',
				reportTime = NOW()';
		$this->_db->setQuery($query);
		$this->_db->query();
		$reportId = $this->_db->getInsertID();

		// Can't report more than once
		if (!$reportId) {
			return false;
		}

		// Update post report count
		$query = 'UPDATE groupPosts
			SET reportCount = reportCount + 1
			WHERE id = '.(int)$postId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Check if a group name is in use
	 *
	 * @param string Slug
	 * @return bool True if in use, false otherwise
	 */
	public function isGroupSlugInUse($slug)
	{
		$query = 'SELECT id
			FROM groups
			WHERE slug = "'.Database::escape($slug).'"';
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}
	
	/**
	 *	Check if a user can delete a post.
	 */
	public function canOfferPostDeletion($postId) {
		
		// Require user.
		if (!$user = BluApplication::getUser()) {
			return false;
		}
		
		// Get post
		$post = $this->getPost($postId);
		
		// Check if current user is post author.
		if ($post['userId'] == $user->userid){
			return true;
		}
		
		// Check if admin
		if (in_array($user->username, explode(',', BluApplication::getSetting('admins')))){
			return true;
		}
		
		// Fail
		return false;
		
	}

}

?>
