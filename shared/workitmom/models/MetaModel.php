<?php

/**
 * Meta Model
 *
 * Deals with meta data, such as categories, or tags.
 */
class WorkitmomMetaModel extends BluModel {

	/**
	 * Add (or update count on existing) tag
	 *
	 * @param string Tag name
	 * @return int Tag ID
	 */
	public function addTag($tag)
	{
		$tag = trim($tag);

		// Add/increment tag count
		$query = 'INSERT INTO tags
			SET tagName = "'.Database::escape($tag).'",
				tagCount = 1
			ON DUPLICATE KEY UPDATE tagId = LAST_INSERT_ID(tagId),
				tagCount = tagCount + 1';
		$this->_db->setQuery($query);
		$this->_db->query();

		// Return tag ID
		return $this->_db->getInsertID();
	}

	/**
	 * Take 1 off tag count
	 *
	 * @param string Tag name
	 * @return bool Success
	 */
	public function decrementTagCount($tag)
	{
		// Decrement
		$query = 'UPDATE tags
			SET tagCount = tagCount - 1
			WHERE tagName = "'.Database::escape($tag).'"';
		$this->_db->setQuery($query);
		return (bool) $this->_db->query();
	}
	
	/**
	 *	Get a tag.
	 *
	 *	@param string Tag name
	 *	@return array Tag.
	 */
	public function getTagByName($tag){
		$query = 'SELECT t.*
			FROM `tags` AS `t`
			WHERE t.tagName = "'.Database::escape($tag).'"';
		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
	}
	
	/**
	 *	Set a tag on an object.
	 */
	public function setTag($objectType, $objectId, $tagId){
		// tagId or tag itself?
	}
	
	/**
	 *	Get tags for an object.
	 */
	public function getTags($objectType, $objectId){
		
		/* Format */
		$objectId = (int) $objectId;
		
		/* Get data */
		$cacheKey = 'tags_'.$objectType.'_'.$objectId;
		$tags = $this->_cache->get($cacheKey);
		if ($tags === false){
			
			/* Query database */
			$query = 'SELECT t.tagName AS `tag`
				FROM `tags` AS `t`
					LEFT JOIN `articleTags` `at` ON at.tagId = t.tagId
				WHERE at.articleId = '.$objectId;
			$this->_db->setQuery($query);
			$tags = $this->_db->loadAssocList();
			
			/* Reduce */
			if (Utility::iterable($tags)){
				foreach($tags as &$tag){
					$tag = $tag['tag'];
				}
			}
			
			/* Store in cache */
			$this->_cache->set($cacheKey, $tags);

		}
		
		/* Return */
		return $tags;
		
	}

	/**
	 * Get item category
	 *
	 * @param int Category ID
	 * @return array Category details
	 */
	public function getCategory($id)
	{
		$query = 'SELECT ac.*
			FROM articleCategories AS ac
			WHERE ac.articleCategoryId = '.(int)$id;
		$this->_db->setQuery($query);
		$category = $this->_db->loadAssoc();
		if (!Utility::iterable($category)){
			return false;
		}
		
		$category['id'] = $category['articleCategoryID'];
		$category['name'] = $category['articleCategoryName'];

		return $category;
	}

	/**
	 * Get all available categories for a given item type
	 *
	 * @param string Item type
	 * @return array List of categories
	 */
	public function getCategories($type)
	{
		$query = 'SELECT ac.*
			FROM articleCategories AS ac
			WHERE ac.articleCategorySection = "'.$type.'"
			ORDER BY ac.articleCategoryOrder';
		$this->_db->setQuery($query);
		$categories = $this->_db->loadAssocList('articleCategoryID');

		return $categories;
	}

	/**
	 * Get a category ID
	 *
	 * @param string Category name
	 * @return int Category ID
	 */
	public function getCategoryId($categoryName)
	{
		$query = 'SELECT acs.articleCategoryID
			FROM articleCategories AS acs
			WHERE acs.articleCategoryName = "'.Database::escape($categoryName).'"';
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}
}

?>
