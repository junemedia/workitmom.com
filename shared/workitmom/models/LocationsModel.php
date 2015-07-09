<?php

/**
 * Locations Model
 *
 * @package BluApplication
 * @subpackage BluModels
 */
class WorkitmomLocationsModel extends BluModel
{
	/**
	 *	Search locations by locationLongName.
	 */
	public function search($criteria, $limit = 1, &$total = null)
	{
		/* Get parameters */
		$name = Database::escape($criteria);

		/* Build query */
		$matchClause = 'MATCH(l.locationName) AGAINST ("'.$name.'")';
		$query = 'SELECT SQL_CALC_FOUND_ROWS l.*, '.$matchClause.' AS `score`
			FROM `location` AS `l`
			WHERE '.$matchClause.'
				OR l.locationSound = "'. soundex($name).'"
			ORDER BY `score` DESC,
				l.popularity DESC';

		/* Execute query */
		$locations = $this->_fetch($query, null, 0, $limit);
		$total = $this->_db->getFoundRows();

		/* Return */
		return $locations;

	}

	/**
	 *	Validate a location name.
	 *
	 *	@returns (int) the location ID, or null.
	 */
	public function validate($criteria)
	{
		/* Sanitise input */
		$criteria = Database::escape($criteria);

		/* Get location details */
		if ($criteria === ''){
			return 0;
		} else {
			$location = $this->getLocationByName($criteria);
		}

		/* Return location ID, or null */
		return isset($location['locationID']) ? $location['locationID'] : null;
	}

	/**
	 * Get location details
	 *
	 * @param int Location id
	 * @return array Array of location details
	 */
	public function getLocation($id)
	{
		$id = (int) $id;
		$query = 'SELECT *
			FROM location
			WHERE locationID = '.$id;
		return $this->_fetch($query, 'location_'.$id, 0, 1);
	}

	/**
	 * Get location by long name
	 *
	 * @param string Location long name
	 * @return int Location id
	 */
	public function getLocationByName($name)
	{
		$query = 'SELECT l.*
			FROM `location` AS `l`
			WHERE l.locationLongName = "'.Database::escape($name).'"';
		return $this->_fetch($query, null , 0, 1);
	}

	/**
	 * Find the locations closest to the given lngitude and latitude point
	 *
	 * @param float Longitude
	 * @param float Latitude
	 * @return array Array of locations ordered by distance from point
	 */
	public function getNearestLocations($lat, $lng)
	{
		// Calculate distance of each location from point
		$locations = $this->getLocations();
		foreach ($locations as &$location) {
			$location['distanceFromPoint'] = Utility::distanceBetween($location['latitude'], $location['longitude'], $lat, $lng);
		}

		// Return array of locations ordered by distance from point (closest first)
		$locations = array_values(Utility::quickSort($locations, 'distanceFromPoint'));
		return $locations;
	}

	/**
	 * Get latitude and longitude co-ordinates for the given postcode
	 *
	 * @param string Town or postcode
	 * @return array Array of lngitude and latitude
	 */
	public function getCoordinates($location)
	{
		$apiKey = BluApplication::getSetting('GMapsAPIKey');

	    // Connect to the google geocode service
		$response = Utility::curl('http://maps.google.com/maps/geo?q='.$location.'&gl=uk&output=csv&key='.$apiKey);

		if (!$response)
			return false;

		list($status, $zoom, $lat, $lng) = explode(',', $response);

		if ($status == 200) {
			return array('lat' => $lat, 'lng' => $lng);
		} else {
			return false;
		}
	}

	/*
	 * Get nearest store to given address
	 */
	public function getNearest($shippingAddress)
	{
		$location = $this->getCoordinates($shippingAddress);
		$ll = $this->getNearestLocations($location['lat'], $location['lng']);
		return $ll[0];
	}
}

?>
