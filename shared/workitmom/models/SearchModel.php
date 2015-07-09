<?php

/**
 * Performs searched for items
 */
class WorkitmomSearchModel extends BluModel {

	/**
	 *	Standard query for searching 'search' table.
	 */
	public function findItems($searchTerms, $type = null, $offset = null, $limit = null, &$total = null, array $options = array())
	{
		// Criteria array to string
		$criteria = is_array($searchTerms) ? implode(' ', array_unique($searchTerms)) : $searchTerms;

		// Type of thing we're trying to search for
		if ($type == 'article') {
			$typeClause = ' AND s.thingType IN ("article", "quicktip", "checklist", "landingpage", "slideshow")';
		} else {
			$typeClause = $type ? ' AND s.thingType = "'.$type.'"' : '';
		}

		/* Prepare other options */
		$excludeClause = isset($options['exclude']) && Utility::is_loopable($options['exclude']) ? '
				AND s.thingID NOT IN (' . implode(', ', $options['exclude']) . ')' : '';

		// Prepare full-text matching
		$matchClause = 'MATCH(s.thingTitle, s.thingCreator, s.thingText) AGAINST ("'.Database::escape($criteria).'")';

		// Get items
		$query = 'SELECT SQL_CALC_FOUND_ROWS s.*, ('.$matchClause.' * 100 / m.maxscore) AS score
			FROM search AS s,
				(SELECT MAX('.$matchClause.') AS maxscore
					FROM search AS s) AS m
			WHERE '.$matchClause.$typeClause.$excludeClause.'
			GROUP BY s.thingLink
			ORDER BY score DESC'; 
		$this->_db->setQuery($query, $offset, $limit);
		$items = $this->_db->loadAssocList('thingLink');
		$total = $this->_db->getFoundRows();

		return $items;
	}

	/**
	 * Update search index table
	 */
	public function updateIndex()
	{
		// Empty search table (so we can repopulate it)
		$this->_db->setQuery('TRUNCATE TABLE search');
		$this->_db->query();

		// Data to insert
		$data = array();

		// Articles
		$query = 'SELECT a.articleID, a.articleTime, a.articleTitle, a.articleBody,
				cc.contentCreatorID,
				uv.userImage, cc.contentCreatorImage, np.newsProviderImage,
				cc.fullName, u.username, u.firstname, u.lastname, np.newsProviderName,
				a.articleType, GROUP_CONCAT(t.tagName) AS tags, u.UserID, ac.articleCategoryName
			FROM article AS a
				LEFT JOIN articleCategory AS acx ON acx.articleID = a.articleID
				LEFT JOIN articleCategories AS ac ON ac.articleCategoryID = acx.categoryID
				LEFT JOIN contentCreators AS cc ON cc.contentCreatorID = a.articleAuthor
				LEFT JOIN newsProvider AS np ON np.newsProviderID = cc.contentCreatorNewsSiteID
				LEFT JOIN users AS u ON u.UserID = cc.contentCreatoruserID
				LEFT JOIN userVariables AS uv ON uv.userID = cc.contentCreatoruserID
				LEFT JOIN articleTags AS at ON at.articleID = a.articleID
				LEFT JOIN tags AS t ON t.tagId = at.tagId
			WHERE a.articleDeleted = 0
				AND a.articleLive = 1
			GROUP BY a.articleID';
		$this->_db->setQuery($query);
		$articles = $this->_db->loadAssocList();
		if (!empty($articles)) {
			foreach ($articles as $article) {

				// Determine URL
				$url = '';
				switch ($article['articleType']) {
					case 'article':
						$url = 'articles/detail/'.$article['articleID'];
						break;
					case 'note':
						$url = 'blogs/members/'.$article['username'].'/'.$article['articleID'];
						break;
					case 'interview':
						$url = 'interviews/detail/'.$article['articleID'];
						break;
					case 'question':
						$url = 'questions/detail/'.$article['articleID'];
						break;
					case 'news':
						$url = 'news/detail/'.$article['articleID'];
						break;
					case 'slideshow':
						$url = 'slideshows/detail/'.$article['articleID'];
						break;
					case 'quicktip':
						$url = 'quicktips/detail/'.$article['articleID'];
						break;
					case 'checklist':
						$url = 'checklists/detail/'.$article['articleID'];
						break;
					case 'landingpage':
						$url = 'essentials/detail/'.$article['articleID'];
				}

				// Determine item image
				if ($article['newsProviderImage']) {
					$image = $article['newsProviderImage'];
				} elseif ($article['contentCreatorImage']) {
					$image = $article['contentCreatorImage'];
				} else {
					$image = $article['userImage'];
				}

				// Determine item creator
				if ($article['newsProviderName']) {
					$creator = $article['newsProviderName'];
				} elseif ($article['fullName']) {
					$creator = $article['fullName'];
				} else {
					$creator = $article['firstname'].' '.$article['lastname'];
				}

				// Insert
				$query = 'INSERT DELAYED INTO search SET
					thingLink = "'.Database::escape($url).'",
					thingType = "'.$article['articleType'].'",
					thingTime = "'.$article['articleTime'].'",
					thingTitle = "'.Database::escape($article['articleTitle']).'",
					thingImage = "'.Database::escape($image).'",
					thingCreator = "'.Database::escape($creator).'",
					thingCreatorID = '.(int)$article['contentCreatorID'].',
					thingText = "'.Database::escape(strip_tags($article['articleTitle'].' '.$article['articleBody']).' '.$article['tags']).'",
					thingCategory = "'.Database::escape($article['articleCategoryName']).'"';
				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}

		// Groups
		$query = 'SELECT g.id, g.name, g.slug, g.photo,
				g.description, g.blurb, g.created,
				gc.name AS categoryName, u.UserID, u.firstname, u.lastname,
				GROUP_CONCAT(t.tagName) AS tags
			FROM groups AS g
				LEFT JOIN users AS u ON u.UserID = g.owner
				LEFT JOIN groupCategories AS gc ON gc.id = g.categoryId
				LEFT JOIN groupTags AS gt ON gt.groupId = g.id
				LEFT JOIN tags AS t ON t.tagId = gt.tagId
			WHERE g.deleted != 1
				AND g.type = "public"
			GROUP BY g.id';
		$this->_db->setQuery($query);
		$groups = $this->_db->loadAssocList();
		if (!empty($groups)) {
			foreach ($groups as $group) {

				// Insert
				$query = 'INSERT DELAYED INTO search SET
					thingLink = "'.Database::escape('groups/detail/'.$group['id']).'",
					thingType = "group",
					thingTime = "'.$group['created'].'",
					thingTitle = "'.Database::escape($group['name']).'",
					thingImage = "'.Database::escape($group['photo']).'",
					thingCreator = "'.Database::escape($group['firstname'].' '.$group['lastname']).'",
					thingCreatorID = '.(int)$group['UserID'].',
					thingText = "'.Database::escape(strip_tags($group['name'].' '.$group['description'].' '.$group['blurb']).' '.$group['tags']).'",
					thingCategory = "'.Database::escape($group['categoryName']).'"';
				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}

		// Discussion posts
		$query = 'SELECT p.id, p.topicId, p.groupId, p.text, p.created, t.title AS topicTitle,
				u.UserID, u.firstname, u.lastname, uv.userImage
			FROM groupPosts AS p
				LEFT JOIN groupTopics AS t ON t.id = p.topicId
				LEFT JOIN groups AS g ON g.id = p.groupId
				LEFT JOIN users AS u ON u.UserID = p.userId
				LEFT JOIN userVariables AS uv ON uv.userID = u.UserID
			WHERE g.deleted != 1
				AND g.type = "public"';
		$this->_db->setQuery($query);
		$posts = $this->_db->loadAssocList();
		if (!empty($posts)) {
			foreach ($posts as $post) {

				// Insert
				$query = 'INSERT DELAYED INTO search SET
					thingLink = "'.Database::escape('groups/discussion/'.$post['topicId']).'#post_'.$post['id'].'",
					thingType = "forum",
					thingTime = "'.$post['created'].'",
					thingTitle = "'.Database::escape($post['topicTitle']).'",
					thingImage = "'.Database::escape($post['userImage']).'",
					thingCreator = "'.Database::escape($post['firstname'].' '.$post['lastname']).'",
					thingCreatorID = '.(int)$post['UserID'].',
					thingText = "'.Database::escape(strip_tags($post['topicTitle'].' '.$post['text'])).'",
					thingCategory = "'.Database::escape($post['topicTitle']).'"';
				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}

		// Featured (Wordpress) blog Entries
		$query = 'SELECT b.blogURL, b.blogHosted, b.blogCategory,
				u.UserID, u.firstname, u.lastname, uv.userImage
			FROM blogs AS b
				LEFT JOIN users AS u ON u.UserID = b.blogOwner
				LEFT JOIN userVariables AS uv ON uv.userID = u.UserID
			WHERE b.blogHosted IS NOT NULL';
		$this->_db->setQuery($query);
		$blogs = $this->_db->loadAssocList();
		if (!empty($blogs)) {
			foreach ($blogs as $blog) {

				if ($blog['blogHosted'] == 999) {
					$table = 'mainwp_posts';
					$tagtable = 'mainwp_stp_tags';
					$blogurl = 'blog';
				} else {
					$table = 'wp_'.$blog['blogHosted'].'_posts';
					$tagtable = 'wp_'.$blog['blogHosted'].'_stp_tags';
					$blogurl = 'bloggers/'.$blog['blogURL'];
				}
				
				$this->_db->setQuery("SHOW TABLES LIKE '".$table."'");
				$checktable = $this->_db->loadAssocList();
				
				$this->_db->setQuery("SHOW TABLES LIKE '".$tagtable."'");
				$checkTagTable = $this->_db->loadAssocList();
				
				if(!empty($checktable) && !empty($checkTagTable))
				{
					// Get blog posts
					$query = 'SELECT p.ID, p.post_name, p.post_title, p.post_content, p.post_date,
							GROUP_CONCAT(tag_name) AS tags
						FROM '.$table.' AS p
							LEFT JOIN '.$tagtable.' AS t ON t.post_id = p.ID
						WHERE p.post_status = "publish"
							GROUP BY p.ID';
					$this->_db->setQuery($query);
					$posts = $this->_db->loadAssocList();
					if (!empty($posts)) {
						foreach ($posts as $post) {

							// Build URL
							$date = strtotime($post['post_date']);
							$url = $blogurl.'/'.date('Y', $date).'/'.date('m', $date).'/'.date('d', $date).'/'.$post['post_name'].'/';

							// Insert
							$query = 'INSERT DELAYED INTO search SET
								thingLink = "'.Database::escape($url).'",
								thingType = "blog",
								thingTime = "'.$post['post_date'].'",
								thingTitle = "'.Database::escape($post['post_title']).'",
								thingImage = "'.Database::escape($blog['userImage']).'",
								thingCreator = "'.Database::escape($blog['firstname'].' '.$blog['lastname']).'",
								thingCreatorID = '.(int)$blog['UserID'].',
								thingText = "'.Database::escape(strip_tags($post['post_title'].' '.$post['post_content']).' '.$post['tags']).'",
								thingCategory = "'.Database::escape($blog['blogCategory']).'"';
							$this->_db->setQuery($query);
							$this->_db->query();
						}
					}
				}
			}
		}

		// Optimize table
		$this->_db->setQuery('OPTIMIZE TABLE search');
		$this->_db->query();
	}

}

?>