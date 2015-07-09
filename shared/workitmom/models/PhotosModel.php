<?php

class WorkitmomPhotosModel extends BluModel {

################################ OLD MODEL - migrate to NewphotosModel.

	/**
	 * Get a users photos
	 *
	 * @param int User ID
	 * @return array List of users photos
	 */
	public function getUserPhotos($userId, $offset = null, $limit = null, &$total = null, array $options = array())
	{
		$userId = (int) $userId;
		if ($userId <= 0) { return null; }

		$query = 'SELECT SQL_CALC_FOUND_ROWS i.*
			FROM images AS i
			WHERE i.imageOwner = '.(int)$userId.'
			ORDER BY i.uploadDate';
		$this->_db->setQuery($query, $offset, $limit);
		$photos = $this->_db->loadAssocList('imageID');
		$total = $this->_db->getFoundRows();
		
		return $photos;
	}

	/**
	 * Add a user photo
	 *
	 * @param int Listing ID
	 * @param string Uploaded file ID
	 * @param array File details
	 * @return int Image ID
	 */
	public function addUserPhoto($userId, $uploadId, $file, $caption = null)
	{
		// Determine path to asset file
		$origFileName = basename($file['name']);
		$assetFileName = md5(microtime().mt_rand(0, 250000)).'_'.$origFileName;
		$assetPath = BLUPATH_ASSETS.'/userimages/'.$assetFileName;

		// Move uploaded file into place
		if (!Upload::move($uploadId, $assetPath)) {
			return false;
		}

		// Add details to database
		$query = 'INSERT INTO images
			SET imageOwner = '.(int)$userId.',
				url = "'.Database::escape($assetFileName).'",
				title = "'.Database::escape($caption).'",
				uploadDate = NOW()';
		$this->_db->setQuery($query);
		$this->_db->query();
		return $this->_db->getInsertID();
	}

	/**
	 * Remove a photo
	 *
	 * @param int Image ID
	 * @return bool True on success, false otherwise
	 */
	function removeUserPhoto($userId, $photoId)
	{
		// Get file details
		$query = 'SELECT * FROM images
			WHERE imageID = '.(int)$photoId.'
				AND imageOwner = '.(int)$userId;
		$this->_db->setQuery($query);
		$photo = $this->_db->loadAssoc();
		if (!$photo) {
			return true;
		}

		// Delete file
		if ($photo['url']) {
			unlink(BLUPATH_ASSETS.'/userimages/'.$photo['url']);
		}

		// Remove from database
		$query = 'DELETE FROM images
			WHERE imageID = '.(int)$photoId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

















	/**
	 *	Get a member photoalbum.
	 */
	public function getPhotoalbum($criteria){
		try {
			if ($criteria instanceof PersonObject){
				$person = $criteria;
			} else if ($criteria instanceof MemberphotoObject){
				$person = $criteria->author;
			} else {
				return null;
			}
			return BluApplication::getObject('photoalbum', $person->userid);
		} catch (NoDataException $exception){
			return null;
		}
	}

	/**
	 *	Grab single record from database/cache/whatever.
	 */
	public function getPhoto($type, $id){
		try {
			return BluApplication::getObject($type . 'photo', (int) $id);
		} catch (NoDataException $exception){
			return null;
		}
	}

	/**
	 *	Get the latest member photos.
	 */
	public function getLatestMemberPhotos($offset = 0, $limit = 1, &$total = null){

		/* Get filters */
		$filterSQL = $this->_generateSQLFilters(array('member'));

		/* Build query */
		$query = 'SELECT SQL_CALC_FOUND_ROWS i.imageID
			FROM `images` AS `i`
			WHERE 1=1' . $filterSQL->where . '
			ORDER BY i.uploadDate DESC,
				i.imageID ASC';

		/* Execute */
		return $this->_getMemberPhotos($query, $offset, $limit, &$total);

	}

	/**
	 *	Get the most commented member photos.
	 */
	public function getMostCommentedMemberPhotos($offset = 0, $limit = 1, &$total = null){

		/* Get filters */
		$filterSQL = $this->_generateSQLFilters(array('member'));

		/* Build query */
		$query = "SELECT SQL_CALC_FOUND_ROWS i.imageID
			FROM `images` AS `i`
				LEFT JOIN (";
		//get images with comments
		$query .= "
					SELECT imageID, count(imageID) AS numComments
					FROM `images`
						LEFT JOIN `comments` ON commentTypeObjectId = imageId
					WHERE commentType = 'userphoto'
					GROUP BY imageID";
		$query .= "
				UNION";
		//add on images without comments, with a blank zero
		$query .= "
					SELECT imageID, 0 as numComments FROM images";
		$query .= ") `imageNumComments` ON i.imageID = imageNumComments.imageID
			WHERE 1=1" . $filterSQL->where . "
			GROUP BY i.imageID
			ORDER BY imageNumComments.numComments DESC,
				i.imageID ASC";

		/* Execute */
		return $this->_getMemberPhotos($query, $offset, $limit, &$total);

	}

	/**
	 *	Get a list of member photos.
	 */
	private function _getMemberPhotos($query, $offset = null, $limit = null, &$total = null){

		/* Get records */
		$records = $this->_fetch($query, null, $offset, $limit);
		$total = $this->_db->getFoundRows();

		/* Return */
		return $this->_wrapMemberPhotos($records, $limit == 1);

	}

	/**
	 *	Wrap a member photo ID into a MemberphotoObject.
	 */
	private function _wrapMemberPhotos($data, $single = false){

		/* No records */
		if (!Utility::is_loopable($data)){ return null; }

		/* Single MemberphotoObject, or array of MemberphotoObjects? */
		if ($single){
			return $this->getPhoto('member', $data['imageID']);
		} else {
			$photos = array();
			foreach($data as $datum){
				if ($photo = $this->getPhoto('member', $datum['imageID'])){
					$photos[] = $photo;
				}
			}
			return $photos;
		}

	}

	/**
	 *	SQL filters.
	 */
	private function _generateSQLFilters(array $filters){

		/* Prepare */
		$filterSQL = new stdClass();
		$filterSQL->where = '';

		/* No filters */
		if (!Utility::is_loopable($filters)){
			return $filterSQL;
		}

		/* Iterate */
		$filters = array_unique($filters);
		foreach($filters as $filter){
			switch(strtolower($filter)){
				case 'member':
					$filterSQL->where .= $this->_generateSQLMemberPhoto()->where;
					break;

				case 'slideshow':
					$filterSQL->where .= $this->_generateSQLSlideshowImage()->where;
					break;

				case 'blogspotlight':
					$filterSQL->where .= $this->_generateSQLBlogSpotlight()->where;
					break;
			}
		}

		/* Return */
		return $filterSQL;
	}

	/**
	 *	SQL filter: is member photo.
	 */
	private function _generateSQLMemberPhoto(){
		$sql = new stdClass();
		$sql->where = '
				AND i.imageOwner > 0';
		return $sql;
	}

	/**
	 *	SQL filter: is slideshow image.
	 */
	private function _generateSQLSlideshowImage(){
		$sql = new stdClass();
		$sql->where = '
				AND i.imageOwner = -1';
		return $sql;
	}

	/**
	 *	SQL filter: is Blog spotlight image.
	 */
	private function _generateSQLBlogSpotlight(){
		$sql = new stdClass();
		$sql->where = '
				AND i.imageOwner = -2';
		return $sql;
	}

}

?>