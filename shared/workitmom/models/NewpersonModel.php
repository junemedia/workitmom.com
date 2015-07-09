<?php

/**
 *	New person model.
 */
class WorkitmomNewpersonModel extends BluModel {

	/**
	 *	Get a person's full details.
	 */
	public function getPerson(array $criteria, array $options = array()){

		/* Parse criteria */
		$allowedCriteria = array('contentcreator', 'member', 'username');
		$criteria = array_intersect_key($criteria, array_flip($allowedCriteria));
		if (!Utility::iterable($criteria)){ return null; }

		/* Get base data */
		$person = null;
		foreach($allowedCriteria as $criterium){
			if (!empty($person)){
				break;
			}
			if (isset($criteria[$criterium])){
				$input = $criteria[$criterium];
				switch(strtolower($criterium)){
					case 'contentcreator':
						// Get content creator.
						$query = 'SELECT cc.*
							FROM `contentCreators` AS `cc`
							WHERE cc.contentCreatorID = '.(int)$input;
						$person = $this->_fetch($query, null, 0, 1);
						
						// Append user if exists.
						if (isset($person['contentCreatoruserID'])){
							$query = 'SELECT u.*
								FROM `users` AS `u`
								WHERE u.UserID = '.(int) $person['contentCreatoruserID'];
							if ($user = $this->_fetch($query, null, 0, 1)){
								$person = array_merge($person, $user);
							}
						}
						break;
						
					case 'member':
						// Get user.
						$query = 'SELECT u.*
							FROM `users` AS `u`
							WHERE u.UserID = '.(int)$input;
						$person = $this->_fetch($query, null, 0, 1);
						if (empty($person)) {
							break;
						}
						
						// Append content creator if exists
						$query = 'SELECT cc.*
							FROM `contentCreators` AS `cc`
							WHERE cc.contentCreatoruserID = '.(int) $person['UserID'];
						if (($cc = $this->_fetch($query, null, 0, 1)) && Utility::iterable($cc)){
							$person = array_merge($person, $cc);
						}
						break;
						
					case 'username':
						// Get user
						$query = 'SELECT u.*
							FROM `users` AS `u`
							WHERE u.username = "'.Database::escape($input).'"';
						$person = $this->_fetch($query, null, 0, 1);
						if (empty($person)) {
							break;
						}
						
						// Append content creator if exists
						$query = 'SELECT cc.*
							FROM `contentCreators` AS `cc`
							WHERE cc.contentCreatoruserID = '.(int) $person['UserID'];
						if ($cc = $this->_fetch($query, null, 0, 1)){
							$person = array_merge($person, $cc);
						}
						break;
				}
			}
		}
		if (empty($person)){
			return null;
		}
		
		// Get extra user details.
		if (isset($person['UserID'])){
			
			// Cast to int
			$person['userid'] = (int) $person['UserID'];
			
			// Grab from cache
			$cacheKey = 'user_'.$person['userid'];
			$personDetails = $this->_cache->get($cacheKey);
			if ($personDetails === false){
				
				// Prepare build
				$personDetails = array();
				
				// Get user info
				$query = 'SELECT ui.*
					FROM `user_info` AS `ui`
					WHERE ui.userid = '.$person['userid'];
				$this->_db->setQuery($query, 0, 1);
				if ($personDetails['userInfo'] = $this->_db->loadAssoc()){
					$person['userInfo'] = $personDetails['userInfo'];
				}
			
				// Get user variables
				$query = 'SELECT uv.*
					FROM `userVariables` AS `uv`
					WHERE uv.userID = '.$person['userid'];
				$this->_db->setQuery($query, 0, 1);
				if ($personDetails['userVariables'] = $this->_db->loadAssoc()){
					$person['userVariables'] = $personDetails['userVariables'];
				}
			
				// Other stuff
				$person['url'] = $personDetails['url'] = '/profile/'.$person['username'];	// Clear this up later.
				$person['link'] = $personDetails['link'] = $person['url'];
				
				// Get user activity
				$query = 'SELECT COUNT(*)
					FROM `search` AS `s`
					WHERE s.thingCreatorID = '.$person['userid'];
				$this->_db->setQuery($query);
				$person['activity'] = (int) $this->_db->loadResult();
				
				// Store extra details in cache
				$this->_cache->set($cacheKey, $personDetails);
				
			} else {
				// Append extra cached details to person.
				$person = array_merge($person, $personDetails);
			}
			
		}
		
		// Get "user"-"content creator"-common details.
		$person['name'] = $personDetails['name'] = $this->_getPersonName($person);
		$person['image'] = $personDetails['image'] = $this->_getPersonImage($person);
		$person['imageDirectory'] = $personDetails['imageDirectory'] = 'user';
		

		/* Sort for ease of debugging */
		if (DEBUG){
			ksort($person);
		}
		
		/* ...and out. */
		return $person;

	}

	/**
	 *	Parse a person's name.
	 */
	protected function _getPersonName($person){

		/* Begin */
		$name = '';

		// Try content creator name
		if (isset($person['fullName'])){
			$name = $person['fullName'];
		}
		// Try member name
		if (!$name && isset($person['firstname'])){
			$name = $person['firstname'];
			if (isset($person['lastname'])){
				if ($name){
					$name .= ' ';
				}
				$name .= $person['lastname'];
			}
		}
		// Try username
		if (!$name && isset($person['username'])){
			$name = $person['username'];
		}

		/* Trim white space and return */
		return trim($name);

	}

	/**
	 *	Parse a person's image.
	 */
	protected function _getPersonImage($person){

		/* Begin */
		$image = '';

		// Try content creator image
		if (isset($person['contentCreatorImage'])){
			$image = $person['contentCreatorImage'];
		}

		// Override with member image
		if (isset($person['userVariables']['userImage'])){
			$image = $person['userVariables']['userImage'];
		}

		/* Trim and return */
		return trim($image);
		
	}
	
	/**
	 *	Add person details to ID.
	 */
	public function addDetails(&$people){
		if (Utility::iterable($people)){
			foreach($people as $userId => &$person){
				$person = $this->getPerson(array('member' => $userId));
			}
		}
	}

	/**
	 * Get people by criteria.
	 */
	public function getPeople($offset = null, $limit = null, &$total = null, array $options = array()){
	
		/* Prepare query parts */
		$query = array(
			'select' => array(
				'u.UserID AS `id`',
				'COUNT(s.thingID) as `activity`'
			),
			'tables' => array(
				'`users` AS `u`',
				'`user_info` AS `ui` ON u.UserID = ui.userid',
				'`search` AS `s` ON u.UserID = s.thingCreatorID'
			),
			'where' => array(
				'u.terminatedtime = 0',
				'u.UserID > 0',
				'u.signupStage >= 3'
			),
			'group' => 'u.UserID',
			'order' => 'u.joined',
			'direction' => 'DESC'
		);
		
		/* Parse options */
		if (Utility::iterable($options)){
			foreach($options as $key => $value){
				$this->_parseGetPeopleOption($query, $key, $value);
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
		$people = $this->_db->loadAssocList('id');
		$total = $this->_db->getFoundRows();
		
		/* Build object data */
		$this->addDetails($people);
		return $people;

	}
	
	/**
	 *	Build query from option, for self::getPeople.
	 *
	 *	@param &array Query to update.
	 *	@param string Option key.
	 *	@param string Option value.
	 */
	protected function _parseGetPeopleOption(array &$query, $key, $value){
		switch($key){
			case 'order':
				switch($value){
					case 'date':
						$query['order'] = 'u.joined';
						break;
						
					case 'name':
						$query['order'] = 'LTRIM(u.firstName), LTRIM(u.lastName)';	// Slow, but more "accurate".
						break;
						
					case 'industry':
						$query['order'] = 'ui.industry';
						break;
						
					case 'id':
						$query['order'] = 'u.UserID';
						break;
						
					case 'active':
						$query['order'] = '`activity`';
						break;
				}
				break;
				
			case 'direction':
				if (!in_array(strtolower($value), array('asc', 'desc'))){ break; }
				$query['direction'] = strtoupper($value);
				break;
				
			case 'name':
				$match = 'MATCH (u.firstName,u.lastName) AGAINST ("'.Database::escape($value).'*" IN BOOLEAN MODE)';
				$query['select'][] = $match.' AS `nameScore`';
				$query['where'][] = $match;
				$query['order'] = 'nameScore';
				$query['direction'] = 'DESC';
				break;
				
			case 'location':
				$query['tables'][] = '`location` AS `l` ON ui.locationID = l.locationID';
				$query['where'][] = 'l.locationLongName LIKE "%'.Database::escape($value).'%"';
				break;
				
			case 'industry':
				$query['where'][] = 'ui.industry = "'.Database::escape($value).'"';
				break;

			case 'interests':
				$match = 'MATCH (ui.interests) AGAINST ("'.Database::escape($value).'" IN BOOLEAN MODE)';
				$query['select'][] = $match.' AS `interestScore`';
				$query['where'][] = $match;
				$query['order'] = '`interestScore`';
				$query['direction'] = 'DESC';
				break;

			case 'job':
				$query['where'][] = 'ui.jobTitle LIKE "%'.Database::escape($value).'%"';
				break;
			
			case 'tags':
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
				break;
				
			case 'days':
				if (!$value){ break; }
				if (isset($options['order']) && $options['order'] == 'active'){
					$query['where'][] = 'DATE_SUB(NOW(), INTERVAL '.(int) $value.' DAY) <= s.thingTime';
				}
				break;
		}
	}

	/**
	 * Get pending friend requests
	 *
	 * @param int User ID
	 * @param int Offset
	 * @param int Limit
	 * @param int Total
	 * @return array List of friend requests (including friend details)
	 */
	public function getFriendRequests($userId, $offset = null, $limit = null, &$total = null, $recipient = true)
	{
		$query = 'SELECT *
			FROM contactrequests AS cr
			WHERE cr.status = "pending"
				AND cr.'.($recipient ? 'toID' : 'fromID').' = '.(int)$userId;
		$this->_db->setQuery($query, $offset, $limit, $total);
		$requests = $this->_db->loadAssocList($recipient ? 'fromID' : 'toID');
		if (!$requests) {
			return false;
		}

		// Get total
		if ($total) {
			$total = $this->_db->getFoundRows();
		}

		// Add friend details
		foreach ($requests as $friendId => &$request) {
			$request['friend'] = $this->getPerson(array('member' => $friendId));
		}

		return $requests;
	}
	
	/**
	 *	Checks if two people are equal.
	 *
	 *	Migrated from PersonObject.
	 *
	 *	@param array Person A.
	 *	@param array Person B.
	 *	@return bool.
	 */
	public function equals($alice, $bob){
		return $alice['userid'] == $bob['userid'];
	}
	
	/**
	 *	Checks if a person is an administrator.
	 *
	 *	@param array Person.
	 *	@return bool.
	 */
	public function isAdmin($person){
		
		/* Get a list of administrators */
		$adminUsernames = explode(',', BluApplication::getSetting('admins'));
		
		/* Test */
		return in_array($person['username'], $adminUsernames);
		
	}

}

?>
