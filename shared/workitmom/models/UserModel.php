<?php

/**
 * User Model
 *
 * @package BluApplication
 * @subpackage BluModels
 */
class WorkitmomUserModel extends BluModel
{
	/**
	 * Current user details
	 *
	 * @var PersonObject
	 */
	private $_user = null;

	/**
	 * Whether the visitor is blocked
	 *
	 * @var bool
	 */
	private static $_blocked;

	/**
	 * Get logged in users details
	 *
	 * @return (PersonObject) User details, or (null) if no logged in user
	 */
	public function getUser()
	{
		if (!$this->_user) {
			$userId = Session::get('UserID');
			if ($userId) {
				try {
					$personModel = $this->getModel('person');
					$this->_user = $personModel->getPerson(array('member' => $userId));
				} catch (NoDataException $e){
					Session::delete('UserID'); // Invalid user id in session
					$this->_user = null;
	
					$_SESSION['debuglogin'] = "could not get user array('member' => $userId)";
				}
			}
		}
		return $this->_user;
	}

	/**
	 *	Checks if a person is the logged in user.
	 *
	 *	@return boolean.
	 */
	public function isSelf(PersonObject $person){
		return $this->getUser() && $this->getUser()->equals($person);
	}

	/**
	 * Log a user in
	 *
	 * @param string Potential email address OR username
	 * @param string Password
	 * @return array User details, or false on failure
	 */
	public function login(array $identifier, $password)
	{
		/* Check for bogus credentials */
		if (!Utility::iterable($identifier)){ return false; }
		if (!current($identifier)){ return false; }
		if (!$password){ return false; }

		/* Get user */
		switch(key($identifier)){
			case 'username':
				$user = $this->getUserFromUsername(current($identifier));
				break;

			case 'email':
				$user = $this->getUserFromEmail(current($identifier));
				break;

			default:
				return false;
				break;
		}
		if (!Utility::iterable($user)){
			return false;
		}

		/* Authenticate */
		if ($user['password'] != $this->_hashPassword($user['username'], $password)) {
			return false;
		}

		// Regenerate session id to prevent session fixation
		Session::regenerateID();

		// Store ID in session
		Session::set('UserID', $user['UserID']);

		// Get full user (person) details (allowing for updates done as part of the login migration)
		$this->_user = $this->getUser();

		/* Update user's "last login" time. */
		$this->_user->login();

		/* Exit */
		return $this->_user;

	}

	/**
	 * Registers a new users and logs them in
	 *
	 * @param string Username
	 * @param string First name
	 * @param string Last name
	 * @param string Email address
	 * @param string Password
	 * @param int Location ID
	 * @param string (optional) Referrer.
	 * @return string User details, or false on failure
	 */
	public function register($username, $firstName, $lastName, $email, $password, $locationID, $referral = null, $newsletter, $ipaddr = null)
	{
		/* Prepare `user` table insert */
		$args = array();
		$args['username'] = $username;
		$args['firstname'] = $firstName;
		$args['fnsndex'] = metaphone($firstName);
		$args['lastname'] = $lastName;
		$args['lnsndex'] = metaphone($lastName);
		$args['email'] = $email;
		$args['password'] = $this->_hashPassword($username, $password);
		$args['signupStage'] = 2;
		$args['ipaddr'] = Database::escape($ipaddr); //Collect ip address

		$special = array();
		$special['joined'] = 'NOW()';

		if(isset($_COOKIE['_test_a'])) //browser register
		{		
			/* Commit insert and grab new ID. */
			$userID = $this->_create('users', $args, $special);

			/* `user_info` table insert */
			$ui_args = array();
			$ui_args['userid'] = $userID;
			$ui_args['locationID'] = $locationID;
			$ui_args['newsLetterOptin'] = $newsletter ? 1 : 0;
			if ($referral) {
				$ui_args['referrer'] = $referral;
			}
			$this->_create('user_info', $ui_args);

			/* `userVariables` table insert */
			$uv_args = array();
			$uv_args['userID'] = $userID;
			$this->login(array('username' => $username), $password);
			$this->_create('userVariables', $uv_args);

			// Set default privacy prefs
			$up_args = array();
			$up_args['userID'] = $this->_user->userid;
			$up_args['userProfileWork'] = 1;
			$up_args['userProfileFamily'] = 1;
			$up_args['userProfileLife'] = 1;
			$up_args['userProfileGroups'] = 1;
			$up_args['userProfileMemberNetwork'] = 1;
			$up_args['userProfileArticles'] = 1;
			$up_args['userProfileQuestions'] = 1;
			$up_args['userProfileComments'] = 1;
			$up_args['userProfilePhotos'] = 1;
			$this->_create('userPrivacyPrefs', $up_args);

			// Add "Nataly" as a friend
			$personModel = $this->getModel('person');
			$nataly = $personModel->getPerson(array('username' => 'DaringFemale'));	// Get by username, as this is more consistent than getting by ID
			$firstFriend_args = array();
			$firstFriend_args['contactorID'] = $nataly->userid;
			$firstFriend_args['userID'] = $this->_user->userid;
			$this->_create('xrefusercontactor', $firstFriend_args);

			/* Auto login, and fetch user */
			$this->login(array('username' => $username), $password);

			/* Subscribe to newsletter */
			if ($newsletter){
				$this->registerNewsletter();
			}

			/* Return new user PersonObject */
			return $this->_user;
		}
		else
		{
			$userID = $this->_create('junk_users', $args, $special);
			return false;
		}

	}

	/**
	 *	Edits the `user_info` table.
	 */
	public function registerExtra(array $arguments){

		/* Require user */
		if (!BluApplication::getUser()){ return false; }
		$user = BluApplication::getUser();

		/* Edit user. */
		$arguments = array_merge($arguments, array('signupStage' => 3));
		$success = $user->edit($arguments);

		/* Return */
		return $success;

	}

	/**
	 *	Registers a user for newsletters (Constant Contact).
	 *
	 *	SHOULD IDEALLY MIGRATE TO BLUCOMMERCE PLUGIN.
	 */
	public function registerNewsletter() {
		/* Get user */
		if (!$this->_user){ return false; }
		$user = $this->_user;

		/* Prepare CURL - URL */
		$url = "http://wim.popularliving.com/wim_registration.php";

		/* Prepare CURL - parameters */
		$newsletter = array();
		$newsletter['email'] = $user->email;
		$newsletter['sublists'] = '553,558';
		$newsletter['subcampid'] = '3212';
		$newsletter['ipaddr'] = $_SERVER['REMOTE_ADDR'];
		$newsletter['keycode'] = 'j9458dxv64gh';

		/* Submit CURL */
		$response = Utility::curl($url, $newsletter);

		/* Return */
		return $response;
	}

	/**
	 *	Delete a person's account.
	 */
	public function delete(PersonObject $person, array $options = array()){

		/* Delete member */
		if (isset($person->userid)){
			$changes = array('terminatedReason' => isset($options['reason']) ? Database::escape($options['reason']) : '');
			$special = array('terminatedtime' => 'UNIX_TIMESTAMP()');
			$criteria = array('UserID' => $person->userid);
			$memberDeleted = $this->_edit('users', $changes, $special, $criteria);
		}

		/* Delete content creator */
		if (isset($person->contentcreatorid)){
			$changes = array('isLive' => 0);
			$criteria = array('contentCreatorID' => $person->contentcreatorid);
			$ccDeleted = $this->_edit('contentCreators', $changes, array(), $criteria);
		}

		/* Success? */
		return (isset($person->userid) && $memberDeleted) || (isset($person->contentcreatorid) && $ccDeleted);

	}

	/**
	 * Check whether an e-mail address is in use
	 *
	 * @param string Email address
	 * @return mixed ID of customer using address if used, false if available
	 */
	public function isEmailInUse($email)
	{
		$query = 'SELECT u.UserID
			FROM `users` AS `u`
			WHERE u.email = "'.Database::escape($email).'"';
		$this->_db->setQuery($query, 0, 1);
		return $this->_db->loadResult();
	}

	/**
	 * Check whether a username is in use
	 *
	 * @param string Username
	 * @return mixed ID of customer using username if used, false if available
	 */
	public function isUsernameInUse($username)
	{
		$query = 'SELECT u.UserID
			FROM `users` AS `u`
			WHERE u.username = "'.Database::escape($username).'"';
		$this->_db->setQuery($query, 0, 1);
		return $this->_db->loadResult();
	}

	/**
	 *	Get user details from username
	 *
	 *	@param string Username
	 *	@return array Array of user details
	 */
	public function getUserFromUsername($username)
	{
		$query = 'SELECT *
			FROM `users` AS `u`
			WHERE u.username = "'.Database::escape($username).'"
				AND u.terminatedtime = 0';
		$this->_db->setQuery($query, 0, 1);
		$user = $this->_db->loadAssoc();

		return $user;
	}

	/**
	 * Get user details from e-mail address
	 *
	 * @param string E-mail address
	 * @return array Array of user details
	 */
	public function getUserFromEmail($email)
	{
		$query = 'SELECT *
			FROM `users` AS `u`
			WHERE u.email = "'.Database::escape($email).'"
				AND u.terminatedtime = 0';
		$this->_db->setQuery($query, 0, 1);
		$user = $this->_db->loadAssoc();

		return $user;
	}

	/**
	 * Update details
	 *
	 * @param string First name
	 * @param string Last name
	 * @param string E-mail address
	 * @param string New password
	 * @return bool True on success, false otherwise
	 */
	public function updateDetails($args)
	{
		// TEST, if no errors, it means it doesn't get used and so can be deleted.
		return false;


		$user = BluApplication::getUser();
		if (!$user) { return false; }
		return $user->edit();


		$query = 'UPDATE users
			SET ' ;
		if ($firstName || $lastName) {
			$query .= 'firstname = "'.Database::escape($firstName).'",
						lastname = "'.Database::escape($lastName).'"';
		} else {
			$query .= 'firstName = firstname ';
		}

		if ($email) {
			$query .= ', email = "'.Database::escape($email).'"';
		}
		if ($password) {
			$query .= ', password = "'.$this->_hashPassword(Session::get('username'), $password).'"';
		}
		$query.= ' WHERE UserID = '.(int)Session::get('UserID');
		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			return false;
		}

		// Clear user details cache
		$this->_user = null;

		// Amend password saved in session
		Session::set('password', $password);

		return true;
	}

	/**
	 * Update current user's password
	 *
	 * @param string New password
	 * @param string User ID to change password for
	 * @return bool True on success, false otherwise
	 */
	public function updatePassword($password, $uid)
	{
		// Set password
		if (!$this->_setPassword($uid, $password)) {
			return false;
		}

		// Clear user details cache
		$this->_user = null;

		// Amend password saved in session
		Session::set('password', $password);

		return true;
	}

	/**
	 * Set user password
	 *
	 * @param string New password
	 * @param int User ID to update
	 * @return bool True on success, false otherwise
	 */
	private function _setPassword($userId, $password)
	{
		// Get username
		$query = 'SELECT u.username
			FROM `users` AS `u`
			WHERE u.UserID = '.(int)$userId;
		$this->_db->setQuery($query);
		$username = $this->_db->loadResult();
		if(!$username) {
			return false;
		}

		// Update user password
		$query = 'UPDATE users
			SET password = "'.$this->_hashPassword($username, $password).'"
			WHERE UserID = '.(int)$userId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Log the user out
	 */
	public function logout()
	{
		// Clear user
		$this->_user = null;
		Session::delete('UserID');
		Session::delete('password');
		Session::delete('username');
	}

	/**
	 *	Hash a password.
	 *
	 *	@param string Username.
	 *	@param string Password to hash.
	 *	@return string Hash.
	 */
	private function _hashPassword($username, $password)
	{
		return md5(strtolower(trim($password)).BluApplication::getSetting('passwordSalt').$username);
	}

	/**
	 * Set the users photo from an uploaded file
	 *
	 * @param string Uploaded file ID
	 * @param array File details
	 */
	public function setPhotoFromUpload($uploadId, $file)
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
		return $this->setPhoto($assetFileName);
	}

	/**
	 * Set the users photo from an uploaded file
	 *
	 * @param string File name
	 */
	public function setPhoto($fileName)
	{
		// Get current photo details
		$query = 'SELECT userImage FROM userVariables
			WHERE userID = '.(int)Session::get('UserID');
		$this->_db->setQuery($query);
		$oldFileName = $this->_db->loadResult();

		// Delete old photo if we are setting a new one and it isn't an avatar
		$avatars = array('avatar1.png', 'avatar2.png', 'avatar3.png');
		if (!in_array($oldFileName, $avatars) && ($oldFileName != $fileName)) {
			unlink(BLUPATH_ASSETS.'/userimages/'.$oldFileName);
		}

		// Add details of new photo to database
		$query = 'UPDATE userVariables
			SET userImage = "'.Database::escape($fileName).'"
			WHERE userID = '.(int)Session::get('UserID');
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 *	Get `user_info.household` enums.
	 */
	public function getHouseholdEnums(){
		return $this->_getEnums('user_info', 'household');
	}

	/**
	 *	Get `user_info.education` enums.
	 */
	public function getEducationEnums(){
		return $this->_getEnums('user_info', 'education');
	}

	/**
	 *	Get industries (ID and name)
	 */
	public function getIndustries(){

		/* Query */
		$query = 'SELECT *
			FROM `industries` AS `i`
			ORDER BY i.industryID';
		$records = $this->_fetch($query, 'industries');

		/* Format */
		$industries = array();
		if (Utility::is_loopable($records)){
			foreach($records as $record){
				$industries[$record['industryID']] = $record['industryName'];
			}
		}

		/* Return */
		return $industries;

	}

	/**
	 * Save my day
	 */
	public function saveMyDay($myDay, $public, $tellfriends)
	{
		// Get user details
		$user = BluApplication::getUser();
		if (!$user) {
			return false;
		}

		// Save answers
		foreach ($myDay as $myId => $answer) {

			// Update DB
			$query = 'REPLACE INTO users_myday
				SET user_id = '.(int) $user->userid.',
					myId = '.(int)$myId.',
					myAnswer = "'.Database::escape($answer).'",
					myDate = CURRENT_DATE(),
					myUpdated = NOW(),
					myPublic = '.(int) $public.',
					myTellFriend = '.(int) $tellfriends;
			$this->_db->setQuery($query);
			$this->_db->query();

			// Stress-o-meter alerts
			if ($tellfriends && (($myId == 11) && ($answer > 80))) {

				// Add alert
				$alertsModel = BluApplication::getModel('alerts');
				$alertId = $alertsModel->createAlert('stressed', array(), $user->userid);

				// Send alert to friends
				$friends = $user->getFriends();
				if ($friends) {
					foreach ($friends as $friend) {
						$alertsModel->applyAlert($alertId, $friend->userid);
					}
				}
			}
		}

		return true;
	}
	
	/**
	 * Get the visitors country by the ip address
	 *
	 * @param string ip address
	 */
	public function getCountryByIp($ipaddr)
	{
		if(!$ipaddr)
		{
			return false;
		}
		
		$query = 'SELECT * FROM ipcheckstore WHERE ipaddr ="'.Database::escape($ipaddr).'"';
		$this->_db->setQuery($query, 0, 1);
		$country = $this->_db->loadAssoc();
		return $country;
	}
	
	/**
	 * Save the visitors ip info
	 *
	 * @param string ip address
	 * @param string country
	 */
	public function saveIpAddress($ip,$country,$details)
	{
		$ipInfo = array();
		$ipInfo['ipaddr'] = Database::escape($ip);
		$ipInfo['country'] = $country;
		$ipInfo['details'] = $details;
		
		$special = array();
		$special['createDate'] = 'NOW()';
		
		$ipId = $this->_create('ipcheckstore', $ipInfo, $special);
		
		return $ipId;
	}

}

?>
