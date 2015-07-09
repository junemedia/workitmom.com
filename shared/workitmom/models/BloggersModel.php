<?php

/**
 *	Featured blogs.
 *
 *	The convention for variables used in this model is:
 *		$blogID: stands for the primary key in the `blogs` table. (WIM)
 *		$blog_ud: stands for the primary key in the `wp_blogs` table. (Wordpress)
 */
class WorkitmomBloggersModel extends BluModel {
	
	/**
	 *	Blog order to set as 'not shown'.
	 */
	const BLOG_ORDER_NOT_SHOWN = 12;

	/**
	 *	Get detailed information about a blog, including author details etc.
	 *
	 *	@args int blogId: the blog id found in the `blogs` table.
	 */
	public function getBlog($blogID){

		/* Parse argument */
		$blogID = (int) $blogID;

		/* Look in cache */
		$cacheKey = 'NEWfeaturedblog_'.$blogID;
		$blog = $this->_cache->get($cacheKey);
		if ($blog === false){

			/* Look in DB */
			$query = 'SELECT b.*, wpb.*
				FROM `blogs` AS `b`
					LEFT JOIN `wp_blogs` `wpb` ON b.blogHosted = wpb.blog_id
				WHERE b.blogID = '.$blogID;
			$this->_db->setQuery($query, 0, 1);
			$blog = $this->_db->loadAssoc();
			
			/* Aliases */
			$blog['title'] = $blog['blogTitle'];

			/* Set in cache. */
			$this->_cache->set($cacheKey, $blog);
			
		}

		/* Build extra stuff */
		// Get author.
		$personModel = $this->getModel('newperson');
		$blog['author'] = $personModel->getPerson(array('member' => $blog['blogOwner']));

		/* Return */
		return $blog;
		
	}

	/**
	 *	Append details to blogs.
	 */
	public function addBlogDetails(&$blogs){
		if (!Utility::iterable($blogs)){ return false; }
		foreach($blogs as $blogID => &$blog){
			$blog = $this->getBlog($blogID);
		}
		unset($blog);
	}

	/**
	 *	Get blogs.
	 *
	 *	@param int Offset.
	 *	@param int Limit.
	 *	@param &int Total
	 *	@param array Options.
	 *	@return array Blogs.
	 */
	public function getBlogs($offset = null, $limit = null, &$total = null, array $options = array()){

		/* Default query string */
		$query = $this->_getBlogsDefaultQuery();
		
		/* Pre-query options */
		if (Utility::iterable($options)){
			foreach($options as $key => $value){
				switch($key){
					case 'order':
						switch($value){
							case 'name':
								$query['order'] = 'b.blogTitle';
								break;
						}
						break;
						
					case 'direction':
						if (!in_array(strtolower($value), array('asc', 'desc'))){ break; }
						$query['direction'] = strtoupper($value);
						break;
				}
			}
		}
		
		/* Build query string. */
		$query = 'SELECT '.implode(', ', $query['select']).'
			FROM '.implode(' LEFT JOIN ', $query['tables']).'
			WHERE '.implode(' AND ', $query['where']).'
			ORDER BY '.$query['order'].' '.$query['direction'];
		$this->_db->setQuery($query, $offset, $limit);
		$blogs = $this->_db->loadAssocList('blogID');
		$total = $this->_db->getFoundRows();
		
		/* Build from cache. */
		$this->addBlogDetails($blogs);

		/* Post-query options */
		if (Utility::iterable($options)){
			foreach($options as $key => $value){
				switch($key){
					case 'key':
						/* Split up */
						$values = array_values($blogs);
						$keys = array_keys($blogs);

						/* Rearrange */
						for ($i = 0; $i < count($keys); $i++){
							if (isset($values[$i][$value])){
								$keys[$i] = $values[$i][$value];
							}
						}

						/* Combine */
						$blogs = array_combine($keys, $values);
						break;
				}
			}
		}

		/* Chop */
		return $blogs;
		
	}
	
	/**
	 *	Default getBlogs query.
	 */
	protected function _getBlogsDefaultQuery(){
		return array(
			'select' => array(
				'b.blogID'
			),
			'tables' => array(
				'`blogs` AS `b`',
				'`wp_blogs` AS `wpb` ON b.blogHosted = wpb.blog_id'
			),
			'where' => array(
				'wpb.blog_id IS NOT NULL',
				'wpb.public = 1',
				'wpb.deleted = 0'
			),
			'order' => 'b.blogOrder',
			'direction' => 'ASC'
		);
	}

	/**
	 *	Update blog order.
	 *
	 *	@param int Blog ID (from `blogs` table).
	 *	@param int Order.
	 */
	public function updateBlogOrder($blogID, $blogOrder){
		return $this->_edit('blogs', array(
			'blogOrder' => (int) $blogOrder
		), array(), array(
			'blogID' => (int) $blogID
		));
	}
	
	/**
	 *	Update blog category.
	 *
	 *	@param int Blog ID (from `blogs` table).
	 *	@param int Category name.
	 */
	public function updateBlogCategory($blogID, $category){
		return $this->_edit('blogs', array(
			'blogCategory' => Database::escape($category)
		), array(), array(
			'blogID' => (int) $blogID
		));
	}

	/**
	 *	Get detailed information on a SINGLE post from a particular blog.
	 *
	 *	@args (int) blogId: the `blogs` table ID.
	 */
	public function getPost($blogID, $postId){

		/* Parse argument */
		$blogID = (int) $blogID;
		$postId = (int) $postId;
		
		/* Get wordpress blog ID */
		$blogs = $this->getBlogs(0, 0, $total, array('key' => 'blogID'));
		if (!isset($blogs[$blogID]['blog_id'])){
			return array();
		}
		$blog = $blogs[$blogID];
		$blog_id = (int) $blog['blog_id'];

		/* Look in cache */
		$cacheKey = 'featuredblogs_'.$blogID.'_post_'.$postId;
		$post = $this->_cache->get($cacheKey);
		if ($post === false){

			/* Look in database */
			$query = 'SELECT wpp.*
				FROM `wp_'.$blog_id.'_posts` AS `wpp`
				WHERE wpp.ID = '.$postId;
			$this->_db->setQuery($query);
			$post = $this->_db->loadAssoc($query);

			/* Extra details */
			$post['id'] = $post['ID'];
			$post['title'] = $post['post_title'];
			//$post['url'] = $post['guid'];	//THIS IS WRONG
			$post['url'] = $blog['path'].'?p='.$post['ID'];
			$post['blogID'] = $blog['blogID'];
			$post['blog_id'] = $blog['blog_id'];
			$post['blogUrl'] = $blog['path'];

			/* Save in cache */
			$this->_cache->set($cacheKey, $post);

		}

		/* Return */
		return $post;

	}

	/**
	 *	Add details to posts.
	 *
	 *	Each 'post' should have a 'blogID' field also.
	 */
	protected function addPostDetails(&$posts){
		if (!Utility::iterable($posts)){ return false; }
		foreach($posts as $postId => &$post){
			if (!isset($post['blogID'])){ continue; }
			$post = $this->getPost($post['blogID'], $postId);
		}
	}

	/**
	 *	Get posts from ALL blogs, by criteria.
	 *
	 *	WARNING: INTENSIVE.
	 */
	protected function getPosts($offset = null, $limit = null, array $options = array()){
		
		/* Create temporary table. */
		$query = 'CREATE TEMPORARY TABLE IF NOT EXISTS `wp_posts` (
			`blogID` INT,
			`post_id` INT,
			`comment_count` INT,
			`post_date` DATETIME,
			PRIMARY KEY (`blogID`, `post_id`)
		)';
		$this->_db->setQuery($query);
		$this->_db->query();
	
		/* Get all blog IDs (keyed by wordpress id) */
		$blogs = $this->getBlogs(0, 0, $total, array('key' => 'blog_id'));
		if (!Utility::iterable($blogs)){
			return null;
		}

		/* Insert relevant posts from each blog, without any specific ordering, into the temporary table. */
		foreach($blogs as $blog_id => $blog){

			/* Format */
			$blog_id = (int) $blog_id;
			$blogID = (int) $blog['blogID'];

			/* Build query */
			$query = 'SELECT "'.$blogID.'" AS `blogID`, wpp.ID AS `post_id`, wpp.comment_count, wpp.post_date
				FROM `wp_'.$blog_id.'_posts` AS `wpp`
				WHERE wpp.post_status = "publish"
					AND wpp.post_type = "post"';
			$this->_appendFilteringCriteria($query, $options);

			/* Get all posts and put them in the temporary table. */
			$query = 'INSERT IGNORE INTO `wp_posts` (`blogID`, `post_id`, `comment_count`, `post_date`) '.$query;
			$this->_db->setQuery($query);
			$this->_db->query();

		}

		/* And now, search from the temporary table... */
		$query = 'SELECT *
			FROM `wp_posts` AS `wpp`
			ORDER BY wpp.'.$options['order'].' '.$options['direction'];
		$this->_db->setQuery($query, $offset, $limit);
		$posts = $this->_db->loadAssocList('post_id');

		/* Add details */
		$this->addPostDetails($posts);

		/* Return */
		return $posts;

	}

	/**
	 *	Get posts from a PARTICULAR blog, by criteria.
	 *
	 *	@args (int) blogId: the `blogs` table ID.
	 */
	protected function getBlogPosts($blogID, $offset = null, $limit = null, array $options = array()){
		
		/* Get wordpress blog ID */
		$blogID = (int) $blogID;
		$blogs = $this->getBlogs(0, 0, $total, array('key' => 'blogID'));
		if (!isset($blogs[$blogID]['blog_id'])){
			return array();
		}
		$blog_id = (int) $blogs[$blogID]['blog_id'];
		
		/* Build query */
		$query = 'SELECT "'.$blogID.'" AS `blogID`, wpp.ID AS `post_id`
			FROM `wp_'.$blog_id.'_posts` AS `wpp`
			WHERE wpp.post_status = "publish"
				AND wpp.post_type = "post"';
		$this->_appendFilteringCriteria($query, $options);
		$query .= '
			ORDER BY wpp.'.$options['order'].' '.$options['direction'];
		
		/* Get IDs */
		$this->_db->setQuery($query, $offset, $limit);
		$posts = $this->_db->loadAssocList('post_id');
		
		/* Build data */
		$this->addPostDetails($posts);
		
		/* Return */
		return $posts;
		
	}
	
	/**
	 *	Get latest posts from a PARTICULAR blog.
	 *
	 *	@args (int) blogId: the `blogs` table ID.
	 */
	public function getLatestBlogPosts($blogId, $offset = null, $limit = null, array $options = array()){
		$options = $this->_filterAliases($options);
		$this->_appendSortingCriteria('latest', $options);
		return $this->getBlogPosts($blogId, $offset, $limit, $options);
	}

	/**
	 *	Get most commented posts from a PARTICULAR blog.
	 *
	 *	@args (int) blogId: the `blogs` table ID.
	 */
	public function getMostCommentedBlogPosts($blogId, $offset = null, $limit = null, array $options = array()){
		$options = $this->_filterAliases($options);
		$this->_appendSortingCriteria('comments', $options);
		return $this->getBlogPosts($blogId, $offset, $limit, $options);
	}
	
	/**
	 *	Get most commented posts from ALL blogs.
	 */
	public function getMostCommentedPosts($offset = null, $limit = null, array $options = array()){
		$options = $this->_filterAliases($options);
		$this->_appendSortingCriteria('comments', $options);
		return $this->getPosts($offset, $limit, $options);
	}








	/**
	 *	Append sorting options.
	 */
	protected function _appendSortingCriteria($type, array &$options){
		switch($type){
			case 'comments':
				/* Same as 'commented' */

			case 'commented':
				/* Sort by most coments */
				$options['order'] = 'comment_count';
				$options['direction'] = 'DESC';
				break;

			case 'date':
				/* Same as latest */

			case 'latest':
				/* Sort by latest post */
				$options['order'] = 'post_date';
				$options['direction'] = 'DESC';
				break;
		}
	}
	
	/**
	 *	Append filtering options.
	 *
	 *	Should be placed after the WHERE clause in a query.
	 */
	protected function _appendFilteringCriteria(&$query, array $options){
		if (!Utility::iterable($options)){
			return false;
		}
		foreach($options as $filter => $args){
			switch($filter){
				case 'days':
					$query .= '
					AND DATE_SUB(NOW(), INTERVAL '.(int)$args.' DAY) <= wpp.post_date';
					break;
				
				case 'exclude':
					if (!Utility::iterable($args)){
						continue;
					}
					foreach($args as &$arg){
						$arg = (int) $arg;
					}
					$query .= '
					AND wpp.ID NOT IN ('.implode(', ', $args).')';
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
	
	/**
	 *	Switch blog IDs.
	 */
	public function getWimFromWordpress($blog_id){
		$blogs = $this->getBlogs(0, 0, $total, array('key' => 'blog_id'));
		return isset($blogs[$blog_id]['blogID']) ? (int) $blogs[$blog_id]['blogID'] : null;
	}
	
	/**
	 *	Switch blog IDs. Part deux.
	 */
	public function getWordpressFromWim($blogID){
		$blogs = $this->getBlogs(0, 0, $total, array('key' => 'blogID'));
		return isset($blogs[$blogID]['blog_id']) ? (int) $blogs[$blogID]['blog_id'] : null;
	}

}

?>
