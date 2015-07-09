<?php

class WorkitmomPersonModel extends BluModel {


	/**
	 *	Get a single person.
	 *
	 *	@args (associative array) idarray: same as in PersonObject constructor.
	 *	@return (PersonObject) the person.
	 */
	public function getPerson(array $idarray) {
		try {
			return BluApplication::getObject('person', $idarray);
		} catch (NoDataException $exception){
			return null;
		}
	}

	/**
	 *	Get latest members.
	 */
	public function getLatest($offset = 0, $limit = 20, &$total = null, array $options = array()){

		/* Get filters */
		$filterSQL = $this->_generateSQLFilters($options);

		/* Build query */
		$query = 'SELECT SQL_CALC_FOUND_ROWS u.UserID
			FROM `users` AS `u`'.$filterSQL->leftjoin.'
			WHERE u.signupStage >= 3' . $filterSQL->where . '
			ORDER BY u.joined DESC';

		/* Fetch and return */
		return $this->_getPeople($query, $offset, $limit, array('member' => 'UserID'), $total);

	}

	/**
	 *	Get members by alphabet.
	 */
	public function getAlphabetical($offset = 0, $limit = 20, &$total = null){

		/* Get filters */
		$filterSQL = $this->_generateSQLFilters();

		/* Build query */
		$query = 'SELECT SQL_CALC_FOUND_ROWS u.UserID
			FROM `users` AS `u`
			WHERE 1=1' . $filterSQL->where . '
			ORDER BY u.firstName ASC,
				u.lastName ASC';

		/* Fetch and return */
		return $this->_getPeople($query, $offset, $limit, array('member' => 'UserID'), $total);

	}

	/**
	 *	Get members by industry.
	 */
	public function getIndustry($offset = 0, $limit = 20, &$total = null){

		/* Get filters */
		$filterSQL = $this->_generateSQLFilters();

		/* Build query */
		$query = 'SELECT SQL_CALC_FOUND_ROWS u.UserID
			FROM `users` AS `u`
				LEFT JOIN `user_info` `ui` ON u.UserID = ui.userid
			WHERE 1=1' . $filterSQL->where . '
			ORDER BY ui.industry ASC';

		/* Fetch and return */
		return $this->_getPeople($query, $offset, $limit, array('member' => 'UserID'), $total);

	}

	/**
	 *	Get members that are online.
	 */
	public function getOnline($offset = 0, $limit = 20, &$total = null){
		// NOT USED - NO AVAILABLE DATA IN THE DATABASE.
	}

	/**
	 *	Get active members.
	 *
	 *	(Not very efficient query, could do with some modification.)
	 */
	public function getActive($offset = 0, $limit = 20, &$total = null){

		/* Get filters */
		$filterSQL = $this->_generateSQLFilters();

		/* Build query */
		$query = 'SELECT SQL_CALC_FOUND_ROWS u.UserID
			FROM `users` AS `u`
				LEFT JOIN (
					SELECT COUNT(*) AS `activity`, s.thingCreatorID AS `userID`
					FROM `search` AS `s`
					GROUP BY s.thingCreatorID
				) `active` ON u.UserID = active.userID
			WHERE 1=1' . $filterSQL->where . '
			ORDER BY active.activity DESC';

		/* Execute */
		return $this->_getPeople($query, $offset, $limit, array('member' => 'UserID'), $total);
	}

	/**
	 *	Get members by search criteria.
	 */
	public function getSearched(array $criteria, $offset = 0, $limit = 20, &$total = null){

		/* Triviality */
		if (!Utility::is_loopable($criteria)){
			return $limit == 1 ? null : array();
		}

		/* Get filters */
		$filterSQL = $this->_generateSQLFilters();

		/* Prepare */
		$leftjoins = array();
		$sortbys = array('u.joined DESC');
		$extramatchstring = '';
		$critstring = '';
		$criteria = array_unique($criteria);

		/* Parts based on Duncan's code. */
		if (Utility::is_loopable($criteria)){
			foreach($criteria as $key => $value){
				if (!$value){ continue; }
				switch($key){
					case 'name':
						$critstring='
						AND MATCH (u.firstname,u.lastname) AGAINST ("' . $value . '*" IN BOOLEAN MODE) ';
						$extramatchstring=', MATCH (u.firstname,u.lastname) AGAINST ("' . $value . '*" IN BOOLEAN MODE) as score ';
						array_unshift($sortbys, 'score DESC');
						break;

					case 'location':
						$critstring .= '
						AND l.locationLongName LIKE "' . $value . '" ';
						$leftjoins[] = 'user_info';
						$leftjoins[] = 'location';
						break;

					case 'industry':
						$critstring.='
						AND ui.industry = "' . $value . '" ';
						$leftjoins[] = 'user_info';
						break;

					case 'interests':
						$critstring.='
						AND MATCH (ui.interests) AGAINST ("' . $value . '" IN BOOLEAN MODE) ';
						$extramatchstring.=', MATCH (ui.interests) AGAINST ("' . $value . '" IN BOOLEAN MODE) as interestscore ';
						array_unshift($sortbys, 'interestscore DESC');
						$leftjoins[] = 'user_info';
						break;

					case 'job':
						$critstring.='
						AND ui.jobTitle like "%' . $value . ' %" ';
						$leftjoins[] = 'user_info';
						break;

					case 'tag':
						$critstring .= '
						AND u.userId in (
							SELECT userId
							FROM userTags
								LEFT JOIN tags ON userTags.tagid=tags.tagid
							WHERE tagname LIKE "%' . $value . '%"
						) ';
						break;
				}
			}
		}
		$leftjoins = array_unique($leftjoins);
		$sortbys = implode(', ', $sortbys);

		/* Build query */
		$query = 'SELECT SQL_CALC_FOUND_ROWS u.UserID' . $extramatchstring . '
			FROM `users` AS `u`';
		if (Utility::is_loopable($leftjoins)){
			foreach($leftjoins as $leftjoin){
				switch($leftjoin){
					case 'user_info':
						$query .= '
				LEFT JOIN `user_info` AS `ui` ON u.UserID = ui.userid';
						break;

					case 'location':
						$query .= '
				LEFT JOIN `location` AS `l` ON ui.locationID = l.locationID';
						break;
				}
			}
		}
		$query .= '
			WHERE u.signupStage >= 3' . $critstring . '
			ORDER BY ' . $sortbys;

		/* Execute */
		return $this->_getPeople($query, $offset, $limit, array('member' => 'UserID'), $total);

	}

	/**
	 *	Get a list of friends of a member.
	 */
	public function getFriends($userid, $offset = 0, $limit = 20, &$total = null){
		$person = $this->getPerson(array('member' => $userid));
		return $person->getFriends($offset, $limit, $total);
	}

	/**
	 *	Checks if the person is a content creator. If not, make them become one.
	 *
	 *	@param PersonObject person: the person to check.
	 *	@param array args: if no content creator set for this person, create with these arguments.
	 */
	public function ensureContentCreator(PersonObject &$person, $args = null){

		/* If no content creator set, set one. */
		if(!$person->contentcreatorid) {

			/* Gather details */
			// To be overwritten
			$weak = array();
			$weak['fullName'] = $person->name;

			// Not to be overwritten
			$strong = array();

			// Merge all arguments
			$ccargs = array_merge($weak, (array) $args, $strong);

			/* Create a new content creator */
			$contentCreator = $this->createContentCreator($ccargs);

			/* Assign to the person */
			$person->setContentCreator($contentCreator);

		}

	}

	/**
	 *	Creates a Content Creator, and returns it.
	 */
	public function createContentCreator(array $args) {
		$id = $this->_create('contentCreators', $args);
		try{
			return BluApplication::getObject('contentcreator', $id);
		} catch (NoDataException $exception) {
			return null;
		}
	}

	/**
	 * Create friend request
	 *
	 * @param int User ID requesting friendship
	 * @param int Friend ID
	 * @return bool True on success, false otherwise
	 */
	public function createFriendRequest($userId, $friendId, $message)
	{
		// Make a contact request
		$query = 'INSERT INTO contactrequests
			SET fromID = '.(int)$userId.',
				toID = '.(int)$friendId.',
				status = "pending",
				message = "'.Database::escape($message).'"';
		$this->_db->setQuery($query);
		$this->_db->query();

		// Create alert, and apply it
		$alertsModel = BluApplication::getModel('alerts');
		$alertId = $alertsModel->createAlert('network', array(), $userId);
		$alertsModel->applyAlert($alertId, $friendId);

		return true;
	}

	/**
	 * Accept friend request
	 *
	 * @param int User ID requesting friendship
	 * @param int Friend ID
	 * @return bool True on success, false otherwise
	 */
	public function acceptFriend($userId, $friendId)
	{
		// Remove request
		$query = 'DELETE FROM contactrequests
			WHERE fromID = '.(int)$friendId.'
				AND toID = '.(int)$userId;
		$this->_db->setQuery($query);
		$this->_db->query();
		if (!$this->_db->getAffectedRows()) {
			return false;
		}

		// Add relationship
		$query = 'INSERT INTO xrefusercontactor
			SET contactorID = '.(int)$friendId.',
				userID = '.(int)$userId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Accept friend request
	 *
	 * @param int User ID requesting friendship
	 * @param int Friend ID
	 * @return bool True on success, false otherwise
	 */
	public function rejectFriend($userId, $friendId)
	{
		// Remove request
		$query = 'UPDATE contactrequests
			SET status = "rejected"
			WHERE fromID = '.(int)$friendId.'
				AND toID = '.(int)$userId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 *	Check if friend request already exists between two users, regardless of status of request.
	 */
	public function hasFriendRequest(PersonObject $from, PersonObject $to){

		/* Build query */
		$query = 'SELECT *
			FROM `contactrequests` AS `r`
			WHERE r.fromID = "' . $from->userid . '"
				AND r.toID = "' . $to->userid . '"';

		/* Execute query */
		$records = $this->_fetch($query, 'contactrequest_' . $from->userid . '_' . $to->userid);

		/* Return result */
		return Utility::is_loopable($records);
	}

	/**
	 * Remove a friend
	 *
	 * @param int User ID
	 * @param int Friend ID
	 */
	public function removeFriend($userId, $friendId)
	{
		// Remove contact requests
		$query = 'DELETE FROM contactrequests
			WHERE (fromID = '.(int)$userId.' AND toID = '.(int)$friendId.')
				OR (fromID = '.(int)$friendId.' AND toID = '.(int)$userId.')';
		$this->_db->setQuery($query);
		$this->_db->query();

		// Remove friend associations
		$query = 'DELETE FROM xrefusercontactor
			WHERE (contactorID = '.(int)$userId.' AND userID = '.(int)$friendId.')
				OR (contactorID = '.(int)$friendId.' AND userID = '.(int)$userId.')';
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	/**
	 *	Check if is friend
	 */
	public function areFriends(PersonObject $judge, PersonObject $hopeful) {
		$friends = $judge->getFriends();
		foreach((array) $friends as $potential) {
			if($hopeful->equals($potential)) return true;
		} return false;
	}


	###							PRIVATE CONVENIENCE FUNCTIONS							###

	/**
	 *	Same as wrapLinks[see below], but returns single LinkObject object, instead of an array of LinkObjects objects.
	 */
	private function _wrapPerson(array $record, array $type){

		if (!Utility::is_loopable($type)){ return null; }

		$person = null;
		if (Utility::is_loopable($record)){
			$person = $this->getPerson(array(key($type) => (int)$record[current($type)]));
		}

		return $person;
	}

	/**
	 *	If we have an arbitrary recordset (2d array) wrap them into PersonObjects.
	 *
	 *	@args (Array) recordset: an array containing records, which are themselves arrays containing keys...
	 *		UserID: id of link
	 *	@return (Array) an array of LinkObjects
	 */
	private function _wrapPeople(array $recordset, array $type){
		$wrappedpeople = array();
		if (Utility::is_loopable($recordset)){
			foreach($recordset as $record){
				if ($person = $this->_wrapPerson($record, $type)){
					$wrappedpeople[] = $person;
				}
			}
		}
		return $wrappedpeople;
	}

	/**
	 *	Get items
	 *
	 *	@args (string) query: sql string that should return a list of articleIds and articleTypes at bare minimum.
	 *	@args (array) type: associative array with:
	 *		key: either 'member' (default) or 'contentcreator', depending on what the query will return. This is will be what is returned.
	 *		value: the column to look for the value in. This is how the PersonObject will be searched for.
	 * 	@return (mixed) Array of PersonObjects, or single PersonObject IF limit is set to 1 (excl. for example, limit = 5 but database returns 1 record only <- that still returns an array)
	 */
	private function _getPeople($query, $offset=0, $limit=1, $type = null, &$total = null){

		//get which data to display
		$records = $this->_fetch($query, null, $offset, $limit);
		$total = $this->_db->getFoundRows();

		//get the wrapped data
		$type = Utility::is_loopable($type) ? $type : array('member' => 'UserID');
		if ($limit == 1){
			$return = $this->_wrapPerson($records, $type);
		} else {
			$return = $this->_wrapPeople($records, $type);
		}

		//spit out
		return $return;

	}

	/**
	 *	Common filters.
	 */
	private function _generateSQLFilters(array $options = array()){

		/* Default filters */
		$activeSQL = $this->_generateSQLActive();
		$existsSQL = $this->_generateSQLExists();

		$extraSQL = new stdClass();
		$extraSQL->leftjoin = '';
		$extraSQL->where = $activeSQL->where . $existsSQL->where;

		/* Optional filters */
		if (Utility::iterable($options)){
			if (isset($options['tags']) && Utility::iterable($options['tags'])){
				$tagSQL = $this->_generateSQLTags($options['tags']);
				$extraSQL->leftjoin .= $tagSQL->leftjoin;
				$extraSQL->where .= $tagSQL->where;
			}
		}

		/* Return */
		return $extraSQL;

	}

	/**
	 *	Filter members by tag.
	 */
	private function _generateSQLTags(array $tags){
		$sql = new stdClass();
		$sql->leftjoin = '
				LEFT JOIN `userTags` AS `ut` ON u.UserID = ut.userId
				LEFT JOIN `tags` AS `t` ON ut.tagId = t.tagId';
		foreach($tags as &$tag){
			$tag = 't.tagName LIKE "%'.Database::escape($tag).'%"';
		}
		$sql->where = '
				AND ('.implode('
					OR ', $tags).')';
		return $sql;
	}

	/**
	 *	Filter by members who haven't terminated their accounts.
	 */
	private function _generateSQLActive(){
		$sql = new stdClass();
		$sql->where = '
				AND u.terminatedtime = 0';
		return $sql;
	}

	/**
	 *	Filter by members that exist. Bug fix.
	 */
	private function _generateSQLExists(){
		$sql = new stdClass();
		$sql->where = '
				AND u.UserID > 0';
		return $sql;
	}

}

?>
