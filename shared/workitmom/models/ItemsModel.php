<?

/**
 *	This model gets the stuff in the way that you would want to use them.
 *	For example, you might want to get the latest five slideshows, but of course you don't know the IDs, so you can't get each slideshow directly. So you call getLatest('slideshow', 0, 5) from this model. Done.
 *
 *	NOTE: from models, you only get IDs from queries. You then create instances of ItemObjects (passing the ID only) which then get the rest of the data automatically.
 *		Nothing needs to be done on this abstraction layer except getting IDs.
 */
class WorkitmomItemsModel extends BluModel {

	/**
	 *	Get an item.
	 *
	 *	@args (int) id: the id of the item ('articleID' in the 'article' table)
	 *	@return (mixed) an object of type '[$type]Model'.
	 */
	public function getItem($id){

		/* Sanitise */
		$id = (int) $id;

		/* Find item type. */
		$type = $this->getType($id);
		if (!$type) {
			return null;
		} else if ($type == 'note') {
			// Exception: this is a member blog post - not your traditional item.
			$blogsModel = BluApplication::getModel('blogs');
			return $blogsModel->getMemberBlogPost($id);
		}

		/* Get item */
		try {
			return BluApplication::getObject($type, $id);
		} catch (NoDataException $exception){
			return null;
		}
	}

	/**
	 *	Helpful little method.
	 */
	public function getType($id){

		/* Prepare */
		$id = (int) $id;
		$query = "SELECT a.articleType
			FROM `article` AS `a`
			WHERE a.articleId = " . $id;

		/* Execute */
		$record = $this->_fetch($query, 'item_' . $id . '_type', 0, 1);

		/* Return */
		return Utility::is_loopable($record) ? array_shift($record) : null;

	}

	/**
	 *	Gets one featured item from every item type.
	 *
	 *	@return associative array.
	 */
	public function getResources(){

		// Get item types.
		$query = "SELECT a.articleType
			FROM `article` AS `a`
			WHERE a.articleType IS NOT NULL
			GROUP BY a.articleType";
		$this->_db->setQuery($query);
		$itemtypes = $this->_db->loadAssocList('articleType');
		$itemtypes = array_keys($itemtypes);

		/* Filter item types */
		$itemtypes = array_diff($itemtypes, array(
			'sitenews',	// will do later
			'note',		// controlled by BlogsModel
			'tribute',	// not needed
			'resource'	// not needed
		));

		// Get featured item from each type.
		$resources = array();
		if (Utility::is_loopable($itemtypes)){
			foreach($itemtypes as $type){
				$resources[$type] = $this->getFeatured($type);
			}
		}

		// Exit
		return $resources;

	}

	/**
	 *	Get latest giveaway (only one, you only need one) as an array. Because this is a bodge.
	 */
	public function getGiveaway(){

		$filterSQL = $this->_generateSQLFilters();
		$query = 'SELECT *
			FROM `article` AS `a`' . $filterSQL->leftjoin . '
				LEFT JOIN `articleCategory` `ac` ON a.articleId = ac.articleId
			WHERE a.articleType = "sitenews"' . $filterSQL->where . '
				AND ac.categoryId = 73
			ORDER BY a.articleFeatured DESC';
		$record = $this->_fetch($query, null, 0, 1);
		return $record;

	}







	/**
	 *	Get featured items
	 *
	 *	@args (string) type: the 'articleType' from the 'article' table.
	 *	@args (int) offset: where in the resultset to start returning from.
	 *	@args (int) limit: the maximum number of results to return.
	 *	@args (reference) total: outputs the total number of items (disregarding offset/limit) returnable from the query.
	 *	@args (array) options: extra query options.
	 *
	 * 	@return (mixed) Array of ItemObjects, or single ItemObject IF limit is set to 1 (excl. for example, limit = 5 but database returns 1 record only <- that still returns an array)
	 */
	public function getFeatured($type, $offset = 0, $limit = 1, &$total = null, array $options = array()){

		$query = $this->_generateSQLFeatured($type, $options);
		return $this->_getItems($query, $offset, $limit, $total);

	}
	
	/**
	 *	Get index-featured items.
	 */
	public function getIndexFeatured($type, $offset = 0, $limit = 1, &$total = null, array $options = array()){
		$query = $this->_generateSQLIndexFeatured($type, $options);
		return $this->_getItems($query, $offset, $limit, $total);		
	}

	/**
	 *	Get items by time.
	 */
	public function getLatest($type, $offset = 0, $limit = 1, &$total = null, array $options = array()){

		$query = $this->_generateSQLLatest($type, $options);
		return $this->_getItems($query, $offset, $limit, $total);

	}

	/**
	 *	Get items by most comments.
	 */
	public function getMostCommented($type, $offset = 0, $limit = 1, &$total = null, array $options = array()){

		$query = $this->_generateSQLMostCommented($type, $options);
		return $this->_getItems($query, $offset, $limit, $total);

	}

	/**
	 * 	Get items by most voted for recently.
	 */
	public function getMostVoted($type, $offset = 0, $limit = 1, &$total = null, array $options = array()){

		$query = $this->_generateSQLMostVoted($type, $options);
		return $this->_getItems($query, $offset, $limit, $total);
	}

	/**
	 *	Get items in alphabetical order.
	 */
	public function getAlphabetical($type, $offset = 0, $limit = 1, &$total = null, array $options = array()){

		$query = $this->_generateSQLAlphabetical($type, $options);
		return $this->_getItems($query, $offset, $limit, $total);

	}

	/**
	 *	Get items by most views
	 */
	public function getMostViewed($type, $offset = 0, $limit = 1, &$total = null, array $options = array()){

		$query = $this->_generateSQLMostViewed($type, $options);
		return $this->_getItems($query, $offset, $limit, $total);

	}

	/**
	 *	Get items by author.
	 */
	public function getAuthor(PersonObject $person, $type = null, $offset = 0, $limit = 1, &$total = null, array $options = array()){

		if (!isset($person->contentcreatorid)){ return null; }
		$query = $this->_generateSQLAuthor($person->contentcreatorid, $type, $options);
		return $this->_getItems($query, $offset, $limit, $total);

	}

	/**
	 *	Get search items, along with their relevance
	 *
	 *	@args (array / string) search: a (list of) string(s) by which to search.
	 *	@args (string) type: the type of item to filter by.
	 *	@args (int) offset
	 *	@args (int) limit
	 *	@args (reference) total: number of possible results
	 *	@args (array) options: extra options for query.
	 *
	 *	@return (array) a list of associative arrays, each of the form
	 *		item		=>	(ItemObject) the data.
	 *		relevance	=>	(float) the relevancy to the search
	 */
	public function getSearched($search, $type = null, $offset = 0, $limit = 1, &$total = null, array $options = array()){

		// Get data.
		$query = $this->_generateSQLSearch($search, $type, $options);
		$this->_db->setQuery($query, (int) $offset, (int) $limit);
		$records = $this->_db->loadAssocList();

		// Format data.
		$itemsWithRelevancy = array();
		while(Utility::is_loopable($records) && $record = array_shift($records)){
			if ($item = $this->getItem($record['articleId'])){
				$itemsWithRelevancy[] = array(
					'item' 		=>	$item,
					'relevance'	=>	(float) $record['score']
				);
			}
		}

		// Return data
		return $itemsWithRelevancy;

	}
	
	/* Placeholder */
	public function getOwned($type, $offset = null, $limit = null, &$total = null, array $options = array()){

		if(!$user = BluApplication::getUser()) return false;
		$itemsModel = $this->getModel('newitems');
		$items = $itemsModel->getOwnedItems($user->userid, $type);
		foreach($items as $itemId => &$item){
			$item = $this->getItem($itemId);
		}
		return $items;

	}


	/**
	 *	Creates an item.
	 *
	 *	@return ItemObject the new item.
	 */
	public function createItem($args){

		/* Special arguments */
		$special = array(
			'articleTime' => 'NOW()'
		);

                // Check if user & content creator are live
                $query = 'SELECT cc.*
                        FROM `contentCreators` AS `cc`
                                LEFT JOIN `users` AS `u` ON cc.contentCreatoruserID = u.UserID
                        WHERE cc.contentCreatorID = '.(int) $args['articleAuthor'].'
                                AND u.terminatedtime > 0';
                $this->_db->setQuery($query);
                if ($this->_db->loadResult()) {
                        return false;
                }

		/* Create item */
		$itemID = $this->_create('article', $args, $special);

		/* Get item */
		$item = $this->getItem($itemID);

		/* Exit */
		return $item;

	}







	###							PRIVATE CONVENIENCE FUNCTIONS 							###

	/**
	 *	Base query for featured items.
	 */
	private function _generateSQLFeatured($type, array $options = array()){

		$filterSQL = $this->_generateSQLFilters($type, $options);

		$query = "SELECT SQL_CALC_FOUND_ROWS a.articleId, a.articleType
			FROM `article` AS `a`" . $filterSQL->leftjoin . "
			WHERE a.articleType = '".$type."'" . $filterSQL->where . "
			ORDER BY a.articleFeatured DESC,
				a.articleTime DESC";

		return $query;

	}
	
	/**
	 *	Base query for index featured items.
	 */
	private function _generateSQLIndexFeatured($type, array $options = array()){
	
		$filterSQL = $this->_generateSQLFilters($type, $options);

		$query = "SELECT SQL_CALC_FOUND_ROWS a.articleId, a.articleType
			FROM `article` AS `a`" . $filterSQL->leftjoin . "
			WHERE a.articleType = '".$type."'" . $filterSQL->where . "
			ORDER BY a.articleIndexFeature DESC,
				a.articleTime DESC";

		return $query;
		
	}

	/**
	 *	Base query for latest items.
	 */
	private function _generateSQLLatest($type, array $options = array()){

		$filterSQL = $this->_generateSQLFilters($type, $options);

		switch($type){
			case 'slideshow':
				$query = "SELECT SQL_CALC_FOUND_ROWS a.articleId, a.articleType
					FROM `article` AS `a`" . $filterSQL->leftjoin . "
						LEFT JOIN `xrefslideshowimages` `xref` ON a.articleId = xref.slideshowID
						LEFT JOIN `images` `i` ON xref.imageID = i.imageID
					WHERE a.articleType = '" . $type . "'" . $filterSQL->where . "
					GROUP BY a.articleId
					ORDER BY i.uploadDate DESC,
						a.articleTime DESC";			/* Order by last slide upload, then by whole slideshow upload */
				break;
			default:
				$query = "SELECT SQL_CALC_FOUND_ROWS a.articleId, a.articleType
					FROM `article` AS `a`" . $filterSQL->leftjoin . "
					WHERE a.articleType = '" . $type . "'" . $filterSQL->where . "
					ORDER BY a.articleTime DESC";
				break;
		}

		return $query;

	}

	/**
	 *	Base query for popular (most comments) items.
	 */
	private function _generateSQLMostCommented($type, array $options = array()){

		$filterSQL = $this->_generateSQLFilters($type, $options);

		// All articles (that have comment count > 0) with their comment count.
		$query_withcomments = "SELECT a.articleId, a.articleType, COUNT(*) AS `count`
			FROM `article` AS `a`" . $filterSQL->leftjoin . "
				LEFT JOIN `comments` `c` ON a.articleId = c.commentTypeObjectId
			WHERE a.articleType = '" . $type . "'" . $filterSQL->where . "
				AND c.commentType = 'article'
				AND c.commentDeleted != 1
			GROUP BY c.commentTypeObjectId";

		// All articles (including those with comment count 0).
		$query_nocomments = "SELECT a.articleId, a.articleType, 0 AS `count`
			FROM `article` AS `a`" . $filterSQL->leftjoin . "
			WHERE a.articleType = '" . $type . "'" . $filterSQL->where;

		// All articles, grouped by item id - so we get one row for each record with the CORRECT comment count.
		$query_all = "SELECT SQL_CALC_FOUND_ROWS *
			FROM ((".$query_withcomments.") UNION (".$query_nocomments.")) `u`
			GROUP BY u.articleId
			ORDER BY u.count DESC";

		return $query_all;

	}

	/**
	 *	Base query for most viewed items.
	 */
	private function _generateSQLMostViewed($type, array $options = array()){

		$filterSQL = $this->_generateSQLFilters($type, $options);

		$query = "SELECT SQL_CALC_FOUND_ROWS a.articleId, a.articleType
			FROM `article` AS `a`" . $filterSQL->leftjoin . "
			WHERE a.articleType = '" . $type . "'" . $filterSQL->where . "
			ORDER BY a.articleViews DESC";

		return $query;

	}

	/**
	 *	Base query for alphabetically sorted items.
	 */
	private function _generateSQLAlphabetical($type, array $options = array()){

		$filterSQL = $this->_generateSQLFilters($type, $options);

		$query = "SELECT SQL_CALC_FOUND_ROWS a.articleId, a.articleType
			FROM `article` AS `a`" . $filterSQL->leftjoin . "
			WHERE a.articleType = '" . $type . "'" . $filterSQL->where . "
			ORDER BY a.articleTitle ASC";

		return $query;

	}

	/**
	 *	Base query for most voted items
	 */
	private function _generateSQLMostVoted($type, array $options = array()){

		$filterSQL = $this->_generateSQLFilters($type, $options);

		$query = "SELECT SQL_CALC_FOUND_ROWS a.articleId, a.articleType, (a.articleRatingTimes * a.articleRating) AS `ratingweight`
			FROM `article` AS `a`" . $filterSQL->leftjoin . "
			WHERE a.articleType = '" . $type . "'" . $filterSQL->where . "
			ORDER BY `ratingweight` DESC";

		return $query;

	}

	/**
	 *	Base query for random items.
	 */
	private function _generateSQLRandom($type, array $options = array()){

		$filterSQL = $this->_generateSQLFilters($type, $options);

		$query = "SELECT SQL_CALC_FOUND_ROWS a.articleId, a.articleType
			FROM `article` AS `a`" . $filterSQL->leftjoin . "
			WHERE a.articleType = '" . $type . "'" . $filterSQL->where . "
			ORDER BY RAND()";

		return $query;

	}

	/**
	 *	Base query for filtering by author, ordered by latest.
	 */
	private function _generateSQLAuthor($authorid, $type = null, array $options = array()){

		$filterSQL = $this->_generateSQLFilters($type, $options);

		$query = "SELECT SQL_CALC_FOUND_ROWS a.articleId, a.articleType
			FROM `article` AS `a`" . $filterSQL->leftjoin . "
			WHERE a.articleAuthor = " . ((int) $authorid) . $filterSQL->where;
		if ($type) {
			$query .= "
				AND a.articleType = '" . $type . "'";
		}
		$query .= '
			ORDER BY a.articleTime DESC';

		return $query;

	}

	/**
	 *	Base query for item searching
	 *
	 *	@args (array) search: a list of strings by which to search.
	 */
	private function _generateSQLSearch($search, $type = null, array $options = array()){

		/* Criteria array to string */
		$criteria = Database::escape($search);
		$criteria = Utility::is_loopable($criteria) ? implode(" ", $criteria) : $criteria;
		$type = $type ? "
				AND a.articleType = '" . $type . "'" : "";

		/* Get SQL parts */
		$filterSQL = $this->_generateSQLFilters($type, $options);
		$match = "MATCH(a.articleBody, a.articleSubTitle, a.articleTitle) AGAINST ('" . $criteria . "')";

		/* Build SQL query */
		$query = "SELECT a.articleId, a.articleType, (" . $match . " * 100 / m.maxscore) AS `score`
			FROM `article` AS `a`" . $filterSQL->leftjoin . ",
				(
					SELECT MAX(" . $match . ") AS `maxscore`
					FROM `article` AS `a`" . $filterSQL->leftjoin . "
					WHERE 1=1" . $type . $filterSQL->where . "
				) `m`
			WHERE 1=1" . $type . $filterSQL->where . "
				AND " . $match . "
			ORDER BY `score` DESC, a.articleTime DESC";

		/* Return */
		return $query;

	}





	/**
	 *	Filtering
	 *
	 *	@args (string) type: the item type, i.e. 'article', 'quicktip' etc
	 *	@args (array) options:
	 *		(int) withinDays: the number of days within which the article was written.
	 *		(array) exclude: a list of IDs not to include in the resultset.
	 */
	private function _generateSQLFilters($type = null, array $options = array()){

		// Get SQL parts
		$categorySQL = $this->_generateSQLCategory($type);
		$taggingSQL = $this->_generateSQLTagging();
		$visibilitySQL = $this->_generateSQLVisibility();

		$extraSQL = new stdClass();
		$extraSQL->leftjoin = '';
		$extraSQL->where = '';
		if (Utility::is_loopable($options)){

			/* Uniqueness */
			$options = array_unique($options);

			/* Iterate */
			foreach($options as $key => $value){
				switch($key){
					case 'time':
						/* Same as withinDays */

					case 'days':
						/* Same as withinDays */

					case 'date':
						/* Same as withinDays */

					case 'withinDays':
						if (!$value){ break; }	// No filtering by the past 0 days, silly.
						$timeSQL = $this->_generateSQLTime($value);
						$extraSQL->where .= $timeSQL->where;
						break;

					case 'exclude':
						if (!Utility::is_loopable($value)){ break; }	// Needs an array of IDs to exclude.
						$excludeSQL = $this->_generateSQLExclusion($value);
						$extraSQL->where .= $excludeSQL->where;
						break;

					default:
						/* Do nothing */
						break;
				}
			}

		}

		// Bung 'em together
		$sql = new stdClass();
		$sql->leftjoin = $categorySQL->leftjoin . $taggingSQL->leftjoin . $extraSQL->leftjoin;
		$sql->where = $categorySQL->where . $taggingSQL->where . $visibilitySQL->where . $extraSQL->where;

		// Return
		return $sql;

	}

	/**
	 *	Sick and tired of repetitive code.
	 */
	private function _generateSQLCategory($type = null){
		$sql = new stdClass();
		$sql->leftjoin = $this->_category && $type ? "
				LEFT JOIN `articleCategory` `ac` ON a.articleId = ac.articleId
				LEFT JOIN `articleCategories` `acs` ON ac.categoryId = acs.articleCategoryID" : "";
		$sql->where = $this->_category && $type ? "
				AND acs.articleCategoryName = '".Database::escape($this->_category)."'
				AND acs.articleCategorySection = '".$type."'" : "";
		return $sql;
	}

	/**
	 *	Go code factorisation!
	 */
	private function _generateSQLTagging(){

		// Single-quote each tag, plus escape it
		$quotedtags = array();
		if (Utility::is_loopable($this->_tags)){ foreach($this->_tags as $tag){ $quotedtags[] = "'" . Database::escape($tag) . "'"; } }

		$sql = new stdClass();
		$sql->leftjoin = Utility::is_loopable($this->_tags)?"
				LEFT JOIN `articleTags` `at` ON a.articleId = at.articleId
				LEFT JOIN `tags` `t` ON at.tagId = t.tagId":"";
		$sql->where = Utility::is_loopable($this->_tags)?"
				AND t.tagName IN (" . implode(", ", $quotedtags) . ")":"";

		return $sql;

	}

	/**
	 *	Whether to show deleted and live items. Determined by whether we are frontend or backend. (Backend sees everything).
	 */
	private function _generateSQLVisibility(){
		$sql = new stdClass();
		$sql->where = SITEEND == 'backend'?"":"
				AND a.articleDeleted != 1
				AND a.articleLive = 1
				AND a.articleTime <= NOW()";
		return $sql;
	}

	/**
	 *	What recent date range by which to filter data.
	 */
	private function _generateSQLTime($withinDays) {
		$sql = new stdClass();
		$sql->where = '
				AND DATE_SUB(NOW(), INTERVAL ' . (int) $withinDays . ' DAY) <= a.articleTime';
		return $sql;
	}

	/**
	 *	Don't return rows with given IDs.
	 */
	private function _generateSQLExclusion(array $exclude){
		$sql = new stdClass();
		$sql->where = '
				AND a.articleID NOT IN (' . implode(', ', $exclude) . ')';
		return $sql;
	}






	/**
	 *	Same as wrapItems [see below], but returns single Item object, instead of an array of Item objects.
	 */
	private function _wrapItem(array $recordset){
		$item = null;
		if (Utility::is_loopable($recordset)){
			$record = $recordset[0];
			$item = $this->getItem($record['articleId']);
		}
		return $item;
	}

	/**
	 *	If we have an arbitrary recordset (2d array) wrap them into ItemObjects.
	 *
	 *	@args (Array) recordset: an array containing records, which are themselves arrays containing keys...
	 *		articleId: id of item
	 *		articleType: type of item.
	 *	@return (Array) an array of mixed objects, depending on what type of item each one is.
	 */
	private function _wrapItems(array $recordset){
		$wrappeditems = array();
		if (Utility::is_loopable($recordset)){ foreach($recordset as $record){ if ($item = $this->_wrapItem(array($record))){ $wrappeditems[] = $item; } } }
		return $wrappeditems;
	}

	/**
	 *	Get items
	 *
	 *	@args (string) query: sql string that should return a list of articleIds and articleTypes at bare minimum.
	 *
	 * 	@return (mixed) Array of ItemObjects, or single ItemObject IF limit is set to 1 (excl. for example, limit = 5 but database returns 1 record only <- that still returns an array)
	 */
	private function _getItems($query, $offset=0, $limit=1, &$total){

		/* Get parameters */
		$offset = (int)$offset;
		$limit = (int)$limit;

		/* Get raw data */
		$this->_db->setQuery($query, $offset, $limit);
		$records = $this->_db->loadAssocList();
		$total = $this->_db->getFoundRows();

		/* Get wrapped data */
		$return = null;
		if ($limit == 1){
			$return = $this->_wrapItem($records);
		} else {
			$return = $this->_wrapItems($records);
		}

		/* spit out */
		return $return;

	}


	/**
	 *	Validates a string against possible item category names from the database
	 *
	 *	@args (string) input: the category name to check.
	 */
	public function validateCategory($input, $section = 'article'){

		/* Check for triviality. */
		if (!$input){ return null; }
		$newinput = strtolower(trim($input));
		if ($newinput == 'all' || $newinput == 'view all'){ return null; }

		/* Now check against database. */
		$input = Database::escape($input);
		$query = "SELECT *
			FROM `articleCategories` AS `acs`
			WHERE acs.articleCategoryName = '".Database::escape($input)."'
				AND acs.articleCategorySection = '".Database::escape($section)."'";
		$result = $this->_fetch($query, null, 0, 1);

		/* Format */
		$category = Utility::is_loopable($result) ? Utility::toObject($result) : null;

		/* Exit */
		return $category;

	}

	/**
	 *	Check if item is bookmarked against user.
	 */
	public function isBookmarked($itemid, $userid) {

		/* Build query */
		$query = "SELECT *
			FROM userSaves
			WHERE userID = '" . (int) $userid . "'
				AND objectID = '" . (int) $itemid . "'
				AND objectType = 'article'";

		/* Execute query */
		$result = $this->_fetch($query);

		/* Return boolean */
		return Utility::is_loopable($result);

	}

	/**
	 *	Bookmark an item against a user.
	 */
	public function bookmarkItem($itemid, $userid) {

		/* Prepare */
		$args = array(
			'userID' => (int) $userid,
			'objectID' => (int) $itemid,
			'objectType' => 'article'
		);

		/* Insert */
		$saveID = $this->_create('userSaves', $args);

		/* Return */
		return (bool) $saveID;

	}

	/**
	 *	Unbookmark an item from a user.
	 */
	public function unbookmarkItem($itemid, $userid) {

		/* Prepare */
		$args = array(
			'userID' => (int) $userid,
			'objectID' => (int) $itemid,
			'objectType' => 'article'
		);

		/* Delete */
		$deletedRowCount = $this->_delete('userSaves', $args);

		/* Return */
		return (bool) $deletedRowCount;

	}

	/**
	 *	Whinge about an item.
	 */
	public function reportItem($item) {

		if(!$user = BluApplication::getUser()) return false;



		/*
		$args['supportMessageName'] = $user->name;
		$args['supportMessageEmail'] = $user->email;
		$args['supportMessageUserID'] = $user->userid;
		$special_args['supportMessageDate'] = 'NOW()';
		$args['supportMessageUserAgent'] = mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);
		$args['supportMessageBody'] = $user->name . ' has reported "' . $item->title . '" on ' . BluApplication::getSetting('storeName') . '!';
		$args['supportMessageType'] = 'reporteditem';
		$args['supportMessageSubType'] = 'feedback';
		*/
	}

}
?>
