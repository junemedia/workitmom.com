<?php

/**
 * Request Environment Library
 *
 * @package BluApplication
 * @subpackage SharedLib
 */
class Request
{

	/**
	 *	The snapshot key.
	 */
	const SNAPSHOT_KEY = 'requestVars_';

	/**
	 * Get a variable from the request
	 * Note: No cleaning is performed, so care should be taken if using this method directly
	 *
	 * @param string Key name
	 * @param mixed Default value
	 * @param string Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 * @return mixed Value
	 */
	public static function getVar($key, $default = null, $hash = 'default')
	{
		// Supplied with input hash array?
		if (is_array($hash)) {
			$input = $hash;
		} else {
			// Get the input hash
			$input = &self::_switchHash($hash);
		}

		// Get the var, or the default
		if (isset($input[$key])) {
			$var = $input[$key];
			if(get_magic_quotes_gpc() && !is_array($var)) {
	            $var = stripslashes($var);
	        }
	    } else {
	    	$var = $default;
	    }

		// Return the var
		return $var;
	}

	/**
	 * Get a string from the request
	 *
	 * @param string Key name
	 * @param string Default value
	 * @param string Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 *	@param string whether *not* to strip HTML tags.
	 * @return string Value
	 */
	public static function getString($key, $default = null, $hash = 'default', $allowHTML = false)
	{
		$string = trim(self::getVar($key, $default, $hash));
		if (!$allowHTML) {
			$string = strip_tags($string);
		}
		return is_string($string) ? $string : null;
	}

	/**
	 * Get an array from the request
	 *
	 * @param string Key name
	 * @param string Default value
	 * @param string Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 * @return string Value
	 */
	public static function getArray($key, $default = null, $hash = 'default')
	{
		return (array)self::getVar($key, $default, $hash);
	}

	/**
	 * Get an integer from the request
	 *
	 * @param string Key name
	 * @param int Default value
	 * @param string Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 * @return int Value
	 */
	public static function getInt($key, $default = null, $hash = 'default')
	{
		return (int)self::getVar($key, $default, $hash);
	}

	/**
	 * Get a float from the request
	 *
	 * @param string Key name
	 * @param int Default value
	 * @param string Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 * @return float Value
	 */
	public static function getFloat($key, $default = null, $hash = 'default')
	{
		return (float)self::getVar($key, $default, $hash);
	}

	/**
	 * Get a boolean from the request
	 *
	 * @param string Key name
	 * @param bool Default value
	 * @param string Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 * @return bool Value
	 */
	public static function getBool($key, $default = false, $hash = 'default')
	{
		return (bool)self::getVar($key, $default, $hash);
	}

	/**
	 * Get a filtered string command from the request
	 * Only allows the characters [A-Za-z0-9.-_]
	 *
	 * @param string Key name
	 * @param bool Default value
	 * @param string Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 * @return string Value
	 */
	public static function getCmd($key, $default = false, $hash = 'default')
	{
		$value = self::getVar($key, $default, $hash);
		return preg_replace('/[^A-Za-z0-9.-_]/', '', $value);
	}

	/**
	 * Set a value in the request
	 *
	 * @param string Key name
	 * @param mixed Value
	 * @param string Which hash to set the var in (POST, GET, FILES, COOKIE, METHOD)
	 */
	public static function setVar($key, $value, $hash = 'default')
	{
		// Get the input hash
		$input = &self::_switchHash($hash);

		// Set the value in the hash
		$input[$key] = $value;
	}


	/**
	 * Get the visitors IP address
	 *
	 * @return string Visitors IP address in dot notation xxx.xxx.xxx.xxx
	 */
	public static function getVisitorIPAddress()
	{
		// Get client IP
		if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
			$visitorIP = $_SERVER["HTTP_CLIENT_IP"];
		} else {
			$visitorIP = false;
		}

		// Get IPs of forwarder clients
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$forwardIPs = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);

			// Add client IP
			if ($visitorIP) {
				array_unshift($forwardIPs, $visitorIP);
				$visitorIP = false;
			}

			// Get last valid IP address in list
			foreach(array_reverse($forwardIPs) as $forwardIP) {
				if (!preg_match('/^(?:10|172\.(?:1[6-9]|2\d|3[01])|192\.168)\./', $forwardIP)) {
					if (ip2long($forwardIP) != false) {
						$visitorIP = $forwardIP;
					}
				}
			}
		}

		// Fall back to server remote address
		if (!$visitorIP) {
			$visitorIP = $_SERVER['REMOTE_ADDR'];
		}

		// DEBUG:
		// $visitorIP = '192.168.0.193';

		$permmisionsModel = BluApplication::getModel('permissions');
		if ($permmisionsModel->isBannedIP(ip2long($visitorIP))) {
			die("You are banned.");
		}
		return $visitorIP;
	}

	/**
	 * Get redirect for the given uri
	 *
	 * @param string Request URI
	 * @return string Redirect URL
	 */
	public static function getRedirect($uri)
	{
		$db = BluApplication::getDatabase();
		$cache = BluApplication::getCache();

		$redirects = $cache->get('redirects');
		$redirectsCache = $cache->get('redirectsCache');

		if ($redirectsCache === false) {
			$redirectsCache = array();
		}

		// Load redirects from db
		if ($redirects === false) {
			$query = 'SELECT fromURL, toURL, type FROM redirects ORDER BY LENGTH(fromURL) DESC';
			$db->setQuery($query);
			$redirects = $db->loadAssocList('fromURL');
			$cache->set('redirects', $redirects);
		}

		// No redirects - nothing to do
		if (empty($redirects)) {
			return false;
		}

		foreach ($redirects as $redirectKey => $redirect) {
			$redirectFrom[$redirectKey] = $redirectKey;
			$redirectTo[$redirectKey] = $redirect['toURL'].'|||'.$redirect['type'];
		}

		// Use the fast cache if it exists to avoid doing an expensive regex
		if (array_key_exists($uri, $redirectsCache)) {
			$url = $redirectsCache[$uri];
			$fromCache = true;
		} else {
			$url = preg_replace($redirectFrom, $redirectTo, $uri);
			$fromCache = false;
		}

		// We have a redirect
		if (strpos($url,'|||')) {
			list($destination, $type) = explode ('|||', $url);
			$responseCode = ($type == 'perm' ? 301 : 307);
			$constants = get_defined_constants(true);
			$userConstants = $constants['user'];
			$destination = str_replace(array_keys($userConstants), $userConstants, $destination);

			// Store in fast cache
			if ($fromCache == false) {
				$redirectsCache[$uri] = $url;
				$cache->set('redirectsCache', $redirectsCache);
			}

			return array('destinaton' => $destination, 'responseCode' => $responseCode);
		}

		return false;
	}

	/**
	 *	Takes a snapshot of the Request.
	 */
	public static function takeSnapshot($hash = 'default')
	{
		/* Get Request type. */
		$input = &self::_switchHash($hash);

		/* Store in Session */
		Session::set(self::SNAPSHOT_KEY.strtolower($hash), $input);

		/* Exit */
		return true;
	}

	/**
	 *	Fetch, and wipe, previous snapshot.
	 *	Don't return data, forcing to use this Request class's "get" methods.
	 */
	public static function fetchSnapshot($hash = 'default')
	{
		/* Get, and remove, from session. */
		$snapshot = self::getSnapshot($hash);
		self::deleteSnapshot($hash);
		if (!Utility::iterable($snapshot)){ return false; }

		/* Merge with current Request variables. */
		$current =& self::_switchHash($hash);
		$current = array_merge($current, (array) $snapshot);

		/* Exit */
		return true;
	}

	/**
	 *	Parse snapshot task.
	 */
	public static function parseSnapshotTask($hash = 'default'){

		/* Get snapshot */
		$snapshot = self::getSnapshot($hash);
		if (!Utility::iterable($snapshot)){ return false; }

		/* Get task */
		if (!isset($snapshot['task'])){ return false; }

		/* Return task */
		return $snapshot['task'];

	}

	/**
	 *	Get the snapshot from session.
	 */
	private static function getSnapshot($hash = 'default'){
		$snapshot = Session::get(self::SNAPSHOT_KEY.strtolower($hash));
		return Utility::iterable($snapshot) ? $snapshot : array();
	}

	/**
	 *	Remove, and return, snapshot from session.
	 */
	private static function deleteSnapshot($hash = 'default'){
		return Session::delete(self::SNAPSHOT_KEY.strtolower($hash), array());
	}

	/**
	 *	Convenience method.
	 */
	private static function &_switchHash($hash = 'default')
	{
		// Get global hash name
		$hash = strtoupper($hash);
		if ($hash === 'METHOD') {
			$hash = strtoupper($_SERVER['REQUEST_METHOD']);
		}

		// Get the input hash
		switch ($hash) {
			case 'GET':
				$input = &$_GET;
				break;

			case 'POST':
				$input = &$_POST;
				break;

			case 'FILES':
				$input = &$_FILES;
				break;

			case 'COOKIE':
				$input = &$_COOKIE;
				break;

			case 'ENV':
				$input = &$_ENV;
				break;

			case 'SERVER':
				$input = &$_SERVER;
				break;

			default:
				$hash = 'REQUEST';
				$input = &$_REQUEST;
				break;
		}

		/* Return */
		return $input;
	}

}

?>
