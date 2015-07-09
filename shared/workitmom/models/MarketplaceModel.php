<?php

/**
 *	Marketplace model
 *
 *	@todo Rename class back to ClientMarketplaceModel in The Merge
 *
 *	@package BluApplication
 *	@subpackage SharedModels
 */
class WorkitmomMarketplaceModel extends BluModel
{
	/**
	 * Get a market place listing
	 *
	 * @param int Listing id
	 * @return array Listing details
	 */
	public function getListing($id, $requirePaid = false)
	{
		$query = 'SELECT m.*, mpc.*, l.*, mpp.endDate
			FROM marketplace AS m
				LEFT JOIN marketplacePayments AS mpp ON mpp.marketID = m.marketID
				LEFT JOIN marketplaceCategories AS mpc ON mpc.mpcID = m.mCategory
				LEFT JOIN location AS l ON m.mContactLocation = l.locationID
			WHERE m.marketID = '.(int)$id;
		if ($requirePaid) {
			$query .= ' AND mpp.startDate <= NOW()
				AND mpp.endDate >= NOW()
				AND mpp.paid = 1
				AND m.mLive = 1';
		}
		$this->_db->setQuery($query);
		$listing = $this->_db->loadAssoc();
		if (!$listing) {
			return false;
		}

		// Get images
		$query = 'SELECT *
			FROM marketplaceImages
			WHERE marketID = '.(int)$id.'
			ORDER BY mpiOrder';
		$this->_db->setQuery($query);
		$listing['images'] = $this->_db->loadAssocList('mpiID');

		// Store head image for convenience
		$headImage = reset($listing['images']);
		$listing['headImage'] = $headImage['mpiFile'];

		// Fix links
		for ($i = 1; $i <= 5; $i++) {
			$link =& $listing['mLink'.$i];
			if ($link && (strpos($link, '://') === false)) {
				$link = 'http://'.$link;
			}
		}

		// Store primary link with sensible name
		$listing['link'] = $listing['mLink1'];
		unset($listing['mLink1']);

		// Build array of extra links
		$listing['extraLinks'] = array();
		for ($i = 2; $i <= 5; $i++) {
			if ($listing['mLink'.$i]) {
				$listing['links'] = $listing['mLink'.$i];
			}
			unset($listing['mLink'.$i]);
		}

		return $listing;
	}

	/**
	 * Append details for each listing in the given array
	 *
	 * @param array Array of listings to add details to
	 */
	public function addDetails(&$listings)
	{
		if (!empty($listings)) {
			foreach ($listings as $listingId => &$listing) {
				$listing = $this->getListing($listingId);
			}
		}
	}

	/**
	 * Get a list of listings
	 *
	 * @param int Offset
	 * @param int Limit
	 * @param int Set to total
	 * @param string Sort order
	 * @param int Category ID to limit listings to
	 * @return array List of listings
	 */
	public function getListings($offset, $limit, &$total, $order = 'date', $category = null, $omit = null)
	{
		$user = BluApplication::getUser();

		// Get listings
		$query = 'SELECT SQL_CALC_FOUND_ROWS m.marketID
			FROM marketplace AS m
				LEFT JOIN marketplacePayments AS mpp ON mpp.marketID = m.marketID
			WHERE mpp.startDate <= NOW()
				AND mpp.endDate >= NOW()
				AND mpp.paid = 1
				AND m.mLive = 1';
		if ($category) {
			if ($user && ($category == 'owner')) {
				$query .= ' AND m.mUserID = '.(int)$user->userid;
			} else {
				$query .= ' AND m.mCategory = '.(int)$category;
			}
		}
		switch ($order) {
			case 'featured':
				$query .= ' AND mpp.marketFeatured >= 1 ORDER BY RAND()';
				break;
			case 'title':
				$query .= ' ORDER BY m.mTitle';
				break;
			default:
				$query .= ' ORDER BY mpp.startDate DESC';
				break;
		}
		$this->_db->setQuery($query, $offset, $limit, (bool)$total);
		$listings = $this->_db->loadAssocList('marketID');
		if (!$listings) {
			return false;
		}

		// Get total
		if ($total) {
			$total = $this->_db->getFoundRows();
		}

		// Add listing details and return
		$this->addDetails($listings);
		return $listings;
	}

	/**
	 * Get related market place listings
	 *
	 * @param int Category ID to try first
	 * @param int Listing to omit
	 * @return array List of listings
	 */
	public function getRelated($category, $omit, $limit = 5)
	{
		// Get market place listings, preferring those featured in the same category
		$query = 'SELECT m.marketID
			FROM marketplace AS m
				LEFT JOIN marketplacePayments AS mpp ON mpp.marketID = m.marketID
			WHERE mpp.startDate <= NOW()
				AND mpp.endDate >= NOW()
				AND mpp.paid = 1
				AND m.mLive = 1
				AND m.marketID != '.(int)$omit.'
			ORDER BY FIELD(m.mCategory, '.(int) $category.'),
				FIELD(mpp.marketFeatured, 2, 1, 0)';
		$this->_db->setQuery($query, 0, $limit);
		$listings = $this->_db->loadAssocList('marketID');
		if (!$listings) {
			return false;
		}

		// Add listing details and return
		$this->addDetails($listings);
		return $listings;
	}

	/**
	 * Get user listings (currently active only)
	 *
	 * @param int User ID
	 * @return array List of listings
	 */
	public function getOwnedListings($userId)
	{
		$query = 'SELECT m.marketID
			FROM marketplace AS m
				LEFT JOIN marketplacePayments AS mpp ON mpp.marketID = m.marketID
			WHERE mpp.startDate <= NOW()
				AND mpp.endDate >= NOW()
				AND mpp.paid = 1
				AND m.mLive = 1
				AND m.mUserID = '.(int) $userId;
		$this->_db->setQuery($query);
		$listings = $this->_db->loadAssocList('marketID');
		if (!$listings) {
			return false;
		}

		// Add listing details and return
		$this->addDetails($listings);
		return $listings;
	}

	/**
	 * Get marketplace categories
	 *
	 * @return array List of categories
	 */
	public function getCategories()
	{
		$query = 'SELECT mpc.*
			FROM marketplaceCategories AS mpc';
		$this->_db->setQuery($query);
		$categories = $this->_db->loadAssocList('mpcID');

		return $categories;
	}

	/**
	 * Get listing prices
	 *
	 * @return array Array of listing prices
	 */
	public function getListingPrices()
	{
		return array(
			'regular' => array(
				'title' => 'Regular Listing',
				'prices' => array(
					1 => 20,
					3 => 50,
					12 => 190
				)
			),
			'featured' => array(
				'title' => 'Featured Listing',
				'prices' => array(
					1 => 60,
					3 => 150,
					12 => 570
				)
			),
			'newsletter' => array(
				'title' => 'Featured Listing & Newsletter',
				'prices' => array(
					1 => 110,
					3 => 280
				)
			)
		);
	}

	/**
	 * Get number of listings featured at the given level
	 *
	 * @param int Level
	 * @return int Number of featured listings
	 */
	function getTotalFeatured($level = 1)
	{
		$query = 'SELECT COUNT(*)
			FROM marketplacePayments AS mpp
			WHERE mpp.marketFeatured >= '.(int) $level.'
				AND mpp.startDate < NOW()
				AND mpp.endDate > NOW()
				AND mpp.paid = 1';
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}

	/**
	 * Add a listing
	 *
	 * @param int User ID
	 * @return int Listing ID
	 */
	public function addListing($userId = null)
	{
		// Default to logged in user
		if (!$userId) {
			$user = BluApplication::getUser();
			if (!$user) {
				return false;
			}
			$userId = $user->userid;
		}

		// Add listing
		$query = 'INSERT INTO marketplace
			SET mStage = 1,
				mUserID = '.(int)$userId.',
				mLive = 0,
				mViewed = 0';
		$this->_db->setQuery($query);
		$this->_db->query();

		return $this->_db->getInsertID();
	}

	/**
	 * Update a listings details
	 *
	 * @param int Listing ID
	 * @param int Category ID
	 * @param string Title
	 * @param string Short title
	 * @param string Description
	 * @param string Website
	 * @param string Discounts
	 * @param array Contact details
	 * @param bool Whether to link to profile
	 * @param bool Whether to receive alerts
	 * @return bool True on success, false otherwise
	 */
	public function updateListing($listingId, $categoryId, $title, $shorttitle, $description,
		$website, $discounts, $contact, $linkToProfile, $receiveAlert)
	{
		$query = 'UPDATE marketplace
			SET mTitle = "'.Database::escape($title).'",
				mShortTitle = "'.Database::escape($shorttitle).'",
				mType = "product",
				mDescription = "'.Database::escape($description).'",
				mCategory = '.(int)$categoryId.',
				mLink1 = "'.Database::escape($website).'",
				mDiscounts = "'.Database::escape($discounts).'",
				mContactName = "'.Database::escape($contact['name']).'",
				mContactEmail = "'.Database::escape($contact['email']).'",
				mContactShowEmail = "'.Database::escape($contact['showEmail']).'",
				mContactPhone = "'.Database::escape($contact['phone']).'",
				mContactShowPhone = "'.Database::escape($contact['showPhone']).'",
				mContactLocation = "'.Database::escape($contact['location']).'",
				mContactShowLocation = "'.Database::escape($contact['showLocation']).'",
				mShowOnProfile = '.(int)$linkToProfile.',
				mReceiveAlert = '.(int)$receiveAlert.'
			WHERE marketID = '.(int)$listingId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Add a photo to a listing
	 *
	 * @param int Listing ID
	 * @param string Uploaded file ID
	 * @param array File details
	 * @return int Image ID
	 */
	public function addImage($listingId, $uploadId, $file, $order = 1)
	{
		// Determine path to asset file
		$origFileName = basename($file['name']);
		$assetFileName = md5(microtime().mt_rand(0, 250000)).'_'.$origFileName;
		$assetPath = BLUPATH_ASSETS.'/marketimages/'.$assetFileName;

		// Move uploaded file into place
		if (!Upload::move($uploadId, $assetPath)) {
			return false;
		}

		// Add details to database
		$query = 'REPLACE INTO marketplaceImages
			SET marketID = '.(int)$listingId.',
				mpiOrder = '.(int)$order.',
				mpiFile = "'.Database::escape($assetFileName).'"';
		$this->_db->setQuery($query);
		$this->_db->query();
		return $this->_db->getInsertID();
	}

	/**
	 * Remove an image
	 *
	 * @param int Image ID
	 * @return bool True on success, false otherwise
	 */
	function removeImage($imageId)
	{
		// Get file details
		$query = 'SELECT * FROM marketplaceImages
			WHERE mpiID = '.(int)$imageId;
		$this->_db->setQuery($query);
		$image = $this->_db->loadAssoc();
		if (!$image) {
			return true;
		}

		// Delete file
		if ($image['mpiFile']) {
			unlink(BLUPATH_ASSETS.'/marketimages/'.$image['mpiFile']);
		}

		// Remove from database
		$query = 'DELETE FROM marketplaceImages
			WHERE mpiID = '.(int)$imageId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Add payment for a listing
	 *
	 * @param int Listing ID
	 * @param int Feature level/type
	 * @param int Duration in months
	 * @param float Amount
	 * @param string Paypal payment token
	 * @param string Optional discount code used
	 * @return int Payment ID
	 */
	public function addPayment($listingId, $type, $duration, $amount, $token, $discount = null)
	{
		// Type to feature level magic number mapping
		$featureLevels = array(
			'regular' => 0,
			'featured' => 1,
			'newsletter' => 2
		);

		// Add payment
		$query = 'INSERT INTO marketplacePayments
			SET marketID = '.(int)$listingId.',
				marketFeatured = '.(int)$featureLevels[$type].',
				mppDuration = '.(int)$duration.',
				mppAmount = '.(float)$amount.',
				mppToken = "'.Database::escape($token).'",
				mppDiscount = "'.Database::escape($discount).'",
				startDate = NOW(),
				endDate = DATE_ADD(NOW(), INTERVAL '.$duration.' MONTH),
				paid = 1';
		$this->_db->setQuery($query);
		$this->_db->query();
		return $this->_db->getInsertID();
	}

	/**
	 * Set a marketplace listing live
	 *
	 * @param int Listing ID
	 * @return bool True on success, false otherwise
	 */
	public function setLive($listingId)
	{
		$query = 'UPDATE marketplace
			SET	mLive = 1
			WHERE marketID = '.(int)$listingId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Update view count
	 *
	 * @param int Listing ID
	 * @return bool True on success, false otherwise
	 */
	public function incrementViewCount($listingId)
	{
		$query = 'UPDATE marketplace
			SET	mViewed = mViewed + 1
			WHERE marketID = '.(int)$listingId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}
	
	/**
	 * Get discount pertaining to given code, if one exists
	 * 
	 * @param string Discount code
	 * @return mixed Discount row on success, null otherwise
	 */
	public function getDiscount($str)
	{
		$query = 'SELECT * FROM marketplaceDiscounts
			WHERE code = "'.Database::escape($str).'"';
		$this->_db->setQuery($query, 0, 1);
		return $this->_db->loadAssoc();
	}
	
	/**
	 * Get price with percentage discount applied
	 *
	 * @param int Original price
	 * @param int Percentage discount
	 * @return int Discounted price
	 */
	public function getDiscountedPrice($originalPrice, $percentage) {
		return $originalPrice * ((100-$percentage)/100);
	}
	
	/**
	 *	Increase the number of views by one.
	 */
	public function increaseViews($marketId){

		/* update database */
		$specialChanges = array('mViewed' => '`mViewed` + 1');
		$criteria = array('marketID' => $marketId);
		$success = $this->_edit('marketplace', array(), $specialChanges, $criteria);
		if ($success <= 0){ return false; }

		/* Flush cached object */
		return $success;

	}

}

?>
