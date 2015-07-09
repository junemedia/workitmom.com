<?

/**
 *	Member blogs.
 */
class WorkitmomBlogsModel extends BluModel{
	
	/**
	 *	Blog order to set as 'not shown'.
	 */
	const BLOG_ORDER_NOT_SHOWN = 12;

	/**
	 *	Get a single blog.
	 *
	 *	@args (String) type: the type of blog we want (accepts either 'featured' or 'member')
	 *	@args (int) id: the id of the item (its specific function depends on what type of blog we want)
	 *		if type is 'featured', this will be the 'blogID' from the 'blogs' table.
	 *		if type is 'member', this will be the 'articleAuthor' from the 'article' table.
	 *
	 *	@return (mixed) either an object of type 'FeaturedblogObject' or 'MemberblogObject'.
	 */
	public function getBlog($type, $id){
		try {
			return BluApplication::getObject($type . 'blog', (int)$id);
		} catch (NoDataException $exception) {
			return null;
		}
	}

	/**
	 *	Get a MemberblogpostObject.
	 */
	public function getMemberBlogPost($id){
		try {
			return BluApplication::getObject('memberblogpost', (int)$id);
		} catch (NoDataException $exception) {
			return null;
		}
	}

	/**
	 *	Gets the specified number of FEATURED blogs, i.e. Wordpress blogs, not member "blogs".
	 *	Ordered by 'blogOrder' column in the table.
	 */
	public function getFeatured($offset = null, $limit = null, &$total = null){

		$filterSQL = $this->_generateSQLFilters('featured');

		//get featured blog ids. (the id should match in the 'blogs' table)
		$query = 'SELECT SQL_CALC_FOUND_ROWS b.blogID
			FROM `wp_blogs` AS `wpb`
				LEFT JOIN `blogs` `b` ON wpb.blog_id = b.blogHosted'.$filterSQL->leftjoin.'
			WHERE b.blogID IS NOT NULL'.$filterSQL->where.'
				AND b.blogOrder != '.self::BLOG_ORDER_NOT_SHOWN.'
			ORDER BY b.blogOrder ASC';

		// spit out
		return $this->_queryAndWrap($query, $offset, $limit, 'featured', $total);

	}

	/**
	 * Get single blog by name
	 *
	 * @param string Blog slug
	 * @return array Blog details
	 */
	public function getBlogBySlug($name)
	{
		$filterSQL = $this->_generateSQLFilters('featured');

		$query = "SELECT *
			FROM `wp_blogs` AS `wpb`
				LEFT JOIN `blogs` `b` ON wpb.blog_id = b.blogHosted" . $filterSQL->leftjoin . "
				LEFT JOIN users u ON b.blogOwner = u.UserID
				LEFT JOIN userVariables uv ON b.blogOwner = uv.userID
			WHERE b.blogID IS NOT NULL AND blogUrl = '".Database::Escape($name)."'
			ORDER BY b.blogOrder ASC";
		$this->_db->setQuery($query);
		$blog = $this->_db->loadAssoc();

		// Return blog details
		return $blog;
	}

	/**
	 *	Gets the most commented on MEMBER blogs.
	 *
	 *	@args (int) offset: where along the list we want to start returning data.
	 *	@args (int) limit: the number of blogs we want to return.
	 *	@return (mixed) Depends on limit.
	 */
	public function getMostCommented($offset, $limit, &$total = null){

		$filterSQL = $this->_generateSQLFilters('member');

		/*
		  Get blog ids - do this by ordering posts by the number of comments (join comments table - for those with no comments, left join wont even return the row, so union the results with all articles again, but plus '0' column), then group results by author.
		  Output "blogID" (which isn't even the blogID - because there isn't one. Return the author instead.)
		*/
		$query_withcomments = "SELECT a.articleAuthor AS `blogID`, COUNT(c.commentTypeObjectId) AS `count`
			FROM `article` AS `a`" . $filterSQL->leftjoin . "
				LEFT JOIN `comments` AS `c` ON a.articleID = c.commentTypeObjectId
			WHERE a.articleType = 'note'
				AND a.articleAuthor IS NOT NULL" . $filterSQL->where . "
				AND c.commentType = 'article'
				AND c.commentDeleted != 1
			GROUP BY `blogID`";

		$query_nocomments = "SELECT a.articleAuthor AS `blogID`, 0 AS `count`
			FROM `article` AS `a`" . $filterSQL->leftjoin . "
			WHERE a.articleType = 'note'
				AND a.articleAuthor IS NOT NULL" . $filterSQL->where;

		$query_all = "SELECT SQL_CALC_FOUND_ROWS *
			FROM ((".$query_withcomments.") UNION (".$query_nocomments.")) `u`
			GROUP BY u.blogID
			ORDER BY u.count DESC";

		//spit out
		return $this->_queryAndWrap($query_all, $offset, $limit, 'member', $total);

	}


	/**
	 *	Gets the most recently posted MEMBER blogs.
	 */
	public function getLatest($offset, $limit, &$total = null){

		$filterSQL = $this->_generateSQLFilters('member');

		// get blog ids - order posts by time, group by author
		$query = "SELECT SQL_CALC_FOUND_ROWS *
			FROM (
				SELECT a.articleAuthor AS `blogID`, a.articleTime
				FROM `article` AS `a`" . $filterSQL->leftjoin . "
				WHERE a.articleType = 'note'
					AND a.articleAuthor IS NOT NULL" . $filterSQL->where . "
				ORDER BY a.articleTime DESC
			) `x`
			GROUP BY x.blogID
			ORDER BY x.articleTime DESC";

		//spit out
		return $this->_queryAndWrap($query, $offset, $limit, 'member', $total);

	}

	/**
	 *	Get "Blog Spotlight", which is a non-WIM blog.
	 */
	public function getSpotlight(){

		/* Prepare */
		$spotlight = new stdClass();

		/* Get data from cache or db. */
		$record = $this->_cache->get('blog_spotlight');
		if ($record === false){
			$query = "SELECT *
				FROM `images` AS `i`
				WHERE i.imageOwner = '-2'
				ORDER BY i.uploadDate DESC";
			$this->_db->setQuery($query, 0, 1);
			$record = $this->_db->loadAssoc();
			$this->_cache->set('blog_spotlight', $record);
		}

		/* Set variables */
		$spotlight->title = $record['title'];
		$spotlight->description = $record['description'];
		$spotlight->url = $record['url'];
		$spotlight->image = $record['articleImageUrl'];

		/* Exit */
		return $spotlight;

	}

	public function getWeeklyTheme(){

		$query = 'SELECT articleTitle FROM article AS a
					LEFT JOIN articleCategory AS ac ON ac.articleId = a.articleID
					WHERE ac.categoryId = 66 AND a.articleType = "sitenews"';
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}






	###							PRIVATE CONVENIENCE FUNCTIONS							###

	/**
	 *	Filtering
	 *
	 *	@args (string) type: the blog type, i.e. 'featured' or 'member'
	 */
	private function _generateSQLFilters($type){

		// Get SQL parts
		switch($type){
			case 'member':
				$categorySQL = $this->_generateSQLCategory();
				$visibilitySQL = $this->_generateSQLVisibility();
				break;
			case 'featured':
				$categorySQL = $this->_generateSQLFeaturedCategory();
				break;
		}
		$taggingSQL = $this->_generateSQLTagging($type);

		// And build.
		$sql = new stdClass();
		$sql->leftjoin .= isset($categorySQL->leftjoin) ? $categorySQL->leftjoin : '';
		$sql->leftjoin .= isset($taggingSQL->leftjoin) ? $taggingSQL->leftjoin : '';

		$sql->where .= isset($categorySQL->where) ? $categorySQL->where : '';
		$sql->where .= isset($taggingSQL->where) ? $taggingSQL->where : '';
		$sql->where .= isset($visibilitySQL->where) ? $visibilitySQL->where : '';

		// Return
		return $sql;

	}

	/**
	 *	Category SQL parts.
	 */
	private function _generateSQLCategory(){
		$sql = new stdClass();
		$sql->leftjoin = $this->_category?"
				LEFT JOIN `articleCategory` `ac` ON a.articleId = ac.articleId
				LEFT JOIN `articleCategories` `acs` ON ac.categoryId = acs.articleCategoryID":"";
		$sql->where = $this->_category?"
				AND acs.articleCategoryName = '".Database::escape($this->_category)."'
				AND acs.articleCategorySection = 'note'":"";
		return $sql;
	}
	private function _generateSQLFeaturedCategory(){

		/* `blogCategory` table version of category name. */
		$blogCategory = '';
		switch($this->_category){
			case 'Balancing Act':
				$blogCategory = 'balancing';
				break;

			case 'Career & Money':
				$blogCategory = 'career';
				break;

			case 'Family & Home':
				$blogCategory = 'family';
				break;

			case 'Your Business':
				$blogCategory = 'business';
				break;

			case 'Just For You':
				$blogCategory = 'justforyou';
				break;

			default:
				break;
		}

		/* Generate SQL */
		$sql = new stdClass();
		$sql->leftjoin = $blogCategory ? "
				LEFT JOIN `blogCategory` `bc` ON b.blogID = bc.bcBlog" : "";
		$sql->where = $blogCategory ? "
				AND bc.bcCategory = '" . Database::escape($blogCategory) . "'" : "";

		/* Return */
		return $sql;

	}

	/**
	 *	Go code factorisation!
	 *
	 *	@args (string) type: the type of blog i.e. 'featured' or 'member'
	 */
	private function _generateSQLTagging($type){

		// Single-quote each tag, plus escape it
		$quotedtags = array();
		if (Utility::is_loopable($this->_tags)){
			foreach($this->_tags as $tag){
				$quotedtags[] = "'" . Database::escape($tag) . "'";
			}
		}

		// Get SQL parts
		$sql = null;
		switch($type){
			case 'member':
				$sql = new stdClass();
				$sql->leftjoin = Utility::is_loopable($quotedtags)?"
						LEFT JOIN `articleTags` `at` ON a.articleId = at.articleId
						LEFT JOIN `tags` `t` ON at.tagId = t.tagId":"";
				$sql->where = Utility::is_loopable($quotedtags)?"
						AND t.tagName IN (" . implode(", ", $quotedtags) . ")":"";
				break;
			case 'featured':
				$sql = new stdClass();
				$sql->leftjoin = Utility::is_loopable($quotedtags)?"
						LEFT JOIN `blogTags` `bt` ON b.blogID = bt.blogId
						LEFT JOIN `tags` `t` ON bt.tagId = t.tagId":"";
				$sql->where = Utility::is_loopable($quotedtags)?"
						AND t.tagName IN (" . implode(", ", $quotedtags) . ")":"";
				break;
		}

		return $sql;

	}

	/**
	 *	Whether to show deleted and live items. Determined by whether we are frontend or backend. (Backend sees everything).
	 */
	private function _generateSQLVisibility(){
		$sql = new stdClass();
		$sql->where = SITEEND == 'backend'?"":"
				AND a.articleDeleted != 1
				AND a.articleLive = 1";
		return $sql;
	}

	/**
	 *	Same as wrapBlogs[see below], but returns single BlogObject object, instead of an array of BlogObjects objects.
	 *
	 *	@args (array) recordset: the array containing a single array, containing the id needed to generate the BlogObject object.
	 *	@args (string) type: the type of BlogObject we want to return.
	 *	@args (mixed) a single BlogObject object.
	 */
	private function _wrapBlog(array $recordset, $type){
		$blog = null;
		if (Utility::is_loopable($recordset)){
			$record = $recordset[0];
			$blog = $this->getBlog($type, $record['blogID']);
		}
		return $blog;
	}

	/**
	 *	If we have an arbitrary recordset (2d array) wrap them into BlogObjects.
	 *
	 *	@args (Array) recordset: an array containing records, which are themselves arrays containing keys...
	 *		blogid: id of slide
	 *	@args (string) type: the type of BlogObjects we want to return.
	 *	@return (Array) an array of BlogObjects
	 */
	private function _wrapBlogs(array $recordset, $type){
		$wrappedblogs = array();
		if (Utility::is_loopable($recordset)){
			foreach($recordset as $record){
				$wrappedblogs[] = $this->_wrapBlog(array($record), $type);
			}
		}
		return $wrappedblogs;
	}

	/**
	 *	Convenience function.
	 *
	 *	@args (string) type: what type of BlogObject object(s) you want to return.
	 */
	private function _queryAndWrap($query, $offset, $limit, $type, &$total = null){

		$this->_db->setQuery($query, $offset, $limit);
		$records = $this->_db->loadAssocList();
		$total = $this->_db->getFoundRows();

		$blogs = null;
		//get the wrapped data
		if (Utility::is_loopable($records)){
			if ($limit == 1){
				//if we want a single item, don't return the useless array around it.
				$blogs = $this->_wrapBlog($records, $type);
			} else {
				// if we want more than a single item, return an array.
				$blogs = $this->_wrapBlogs($records, $type);
			}
		}

		//spit out
		return $blogs;

	}

	public function isBookmarked($itemid, $userid) {
		$query = "SELECT *
			FROM userSaves
			WHERE userID = '".$userid."'
				AND objectID = '".$itemid."'
				AND objectType = 'article'";
		$this->_db->setQuery($query);
		$result = $this->_db->loadAssoc();
		return Utility::is_loopable($result);
	}
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
}


?>
