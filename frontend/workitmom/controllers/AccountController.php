<?php

/**
 * Account Controller
 *
 * @package BluApplication
 * @subpackage FrontendControllers
 */
class WorkitmomAccountController extends ClientFrontendController
{
	/**
	 *	The current user, if they exist.
	 */
	private $_user;
	
	/**
	 *	Common account section functionality.
	 */
	public function __construct($args){
		
		/* Parent */
		parent::__construct($args);
		
		/* Set header ad for all "My Account" pages...unless they get overriden later on. */
		$this->_doc->setAdPage(OpenX::PAGE_MEMBERS);
		
	}

	/**
	 *	Default controller page.
	 */
	public function view()
	{
		/* Require user */
		$user = BluApplication::getUser();
		if (!$user){
			/* Redirect to sign-in page. */
			$this->sign_in();
			return false;
		} else {
			/* Mimic FrontendController. */
			Request::fetchSnapshot();
		}

		// Add breadcrumb
		$this->_uri = '/account';
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('My Account', '/account');

		// Get models
		$userModel = BluApplication::getModel('user');
		$alertsModel = BluApplication::getModel('alerts');
		$itemsModel = BluApplication::getModel('items');
		$blogsModel = BluApplication::getModel('blogs');

		// Get data
		$myDays = $user->getMyDays('day', 1);
		$myDay = array_shift($myDays);
		$todoList = $user->getToDoList();
		$latestAlerts = $alertsModel->getUserAlerts($user->userid, 0, 3);
		$featuredQuestion = $itemsModel->getIndexFeatured('question');

		// Get latest blog posts
		$blogPosts = null;
		if ($user->contentcreatorid) {
			$blog = $blogsModel->getBlog('member', $user->contentcreatorid);
			$blogPosts = $blog->getLatestPosts(0, 3);
		}

		// Set page title
		$this->_doc->setTitle('My Account');

		// Load template
		include(BLUPATH_TEMPLATES.'/account/main.php');
	}

	/**
	 *	Sign in page.
	 */
	private function sign_in()
	{
		// Clear ballsed session ids
		setcookie('blu-session-id', '', time() - 3600, '/', 'workitmom.com');
		setcookie('blu-session-id', '', time() - 3600, '/', '.workitmom.com');

		// Show sign in page
		$this->_doc->setTitle('Sign In');
		return include (BLUPATH_TEMPLATES . '/account/sign_in.php');
	}

	/**
	 * Remove alert
	 */
	public function remove_alert()
	{
		// Get alert id from request
		$alertId = Request::getInt('alert');

		// Get user details for permission checking
		$user = BluApplication::getUser();

		// Mark alert as seen
		$alertsModel = BluApplication::getModel('alerts');
		$alertsModel->markAlertSeen($user->userid, $alertId);

		// Return to alerts page
		return $this->_redirect('/account/alerts', 'The alert has been removed.');
	}

	/**
	 * Remove all alerts
	 */
	public function remove_all_alerts()
	{
		// Get alert id from request
		$alertId = Request::getInt('alert');

		// Get user details for permission checking
		$user = BluApplication::getUser();

		// Mark alert as seen
		$alertsModel = BluApplication::getModel('alerts');
		$alertsModel->markAllAlertsSeen($user->userid);

		// Return to alerts page
		return $this->_redirect('/account/alerts', 'All your alerts have been removed.');
	}

	/**
	 * Login function.
	 */
	public function login()
	{
		/* Get Model */
		$userModel = $this->getModel('user');

		/* Check for logged in user. */
		if (BluApplication::getUser()) {

			/* Stop people logging in twice - it's OK, you really don't need to! */
			$redirect = '/account/';

		} else {

			/* Attempt login */
			// Get data from request
			$identifier = Request::getString('form_identifier', null);
			$pass = Request::getString('form_password', null);

			// Attempt login
			if (!$identifier || !$pass){

				/* Try again. */
				$redirect = '/account/';
				$message = 'Please enter both a username and a password';

			} else {

				/* Try to login */
				$identifierType = $this->identify_identifier($identifier);
				$credentials = array($identifierType => $identifier);
				if (!$userModel->login($credentials, $pass)) {

					/* Try again, but displaying a message */
					$redirect = '/account/';		// Self
					$message = 'Sorry, that username/password didn\'t match what we have on file. Please try again.';
					$message .= '<br/>If you think you might have forgotten your password, <a href="/account/forgot?'.$identifierType.'='.$identifier.'">please click here</a> to request a reminder.';

				} else {

					/* Success, build redirect string */
					$redirect = Session::delete('referer', '/account/');
					$task = Request::parseSnapshotTask();
					if ($task){
						$redirect .= '?task='.$task;
					}

				}

			}

		}

		// Redirect/view overview
		$message = isset($message) ? $message : null;
		$this->_redirect($redirect, $message);
	}

	/**
	 *	Check whether the given identifier is a username or email address.
	 *
	 *	@return string 'username' or 'email'.
	 */
	private function identify_identifier($identifier){
		return strpos($identifier, '@') === false ? 'username' : 'email';
	}

	/**
	 * Send password reminder - by email
	 */
	public function password_reminder()
	{
		// Get data from request
		$email = Request::getString('form_identifier');

		// Get user details
		$userModel = $this->getModel('user');
		if (!$user = $userModel->getUserFromEmail($email)) {

			// Not registered
			Messages::addMessage("The e-mail address ".$email." does not appear to be registered with our site.", 'error');

		} else {

			// Send email
			$this->_sendReminder($user['UserID']);
			Messages::addMessage('A new password has been e-mailed to '.$email.'.', 'info');

		}

		// View login page
		return $this->_showMessages('view');

	}

	/**
	 *	Send password reminder - by username or email.
	 */
	public function forgot(){

		/* Get arguments */
		$requestType = null;
		$foundPerson = null;

		/* Get models. */
		$userModel = $this->getModel('user');
		$personModel = $this->getModel('person');

		/* Parse identifier */
		if ($username = Request::getString('username')){
			$requestType = 'username';
			$foundPerson = $userModel->getUserFromUsername($username);
			$foundPerson = $personModel->getPerson(array('member' => $foundPerson['UserID']));
		} else if ($email = Request::getString('email')){
			$requestType = 'email';
			$foundPerson = $userModel->getUserFromEmail($email);
			$foundPerson = $personModel->getPerson(array('member' => $foundPerson['UserID']));
		}

		/* Get person */
		if ($requestType){
			if ($foundPerson){
				/* Send email. */
				$this->_sendReminder($foundPerson->userid);
				Messages::addMessage('A new password has been e-mailed to '.$foundPerson->name.'\'s inbox.', 'info');
			} else {
				/* Could not find user. */
				$message = 'Could not find a user with the ';
				if ($requestType == 'username'){
					$message .= 'username <em>'.$username.'</em>';
				} else if ($requestType == 'email'){
					$message .= 'email address <em>'.$email.'</em>';
				}
				Messages::addMessage($message, 'error');
			}
		}

		/* Return to login page.*/
		return $this->_redirect('/'.$this->_controllerName.'/');

	}

	/**
	 *	Send password reminder.
	 */
	private function _sendReminder($userId){

		/* Get person */
		$personModel = $this->getModel('person');
		$user = $personModel->getPerson(array('member' => (int)$userId));

		// Generate new password
		$password = Utility::createRandomPassword();
		$userModel = $this->getModel('user');
		$userModel->updatePassword($password, $user->userid);

		// Send e-mail
		$emailMsg = new Email();
		$vars = array('firstName' => $user->firstname, 'lastName' => $user->lastname, 'email' => $user->email, 'password' => $password);
		return $emailMsg->quickSend($user->email, $user->firstname.' '.$user->lastname, 'Your new '.BluApplication::getSetting('storeName').' password', 'passwordreminder', $vars);

	}

	/**
	 * Logout
	 */
	public function logout()
	{
		// Logout
		$userModel = BluApplication::getModel('user');
		$userModel->logout();

		/* Clear session variables */
		Session::clear(array('messages'));

		// Redirect to homepage
		$this->_redirect('/');
	}

	/**
	 *	Messages overview page.
	 */
	public function messages()
	{
		if (!$this->_requireUser('Please sign in to see your messages.')) {
			return false;
		}
		$user = BluApplication::getUser();

		// Add breadcrumb
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('My Account', '/account/');
		$breadcrumbs->add('My Messages', '/account/messages');

		// Set page title
		$this->_doc->setTitle('My Messages');

		// Get folder
		$folder = Request::getCmd('folder', 'inbox');

		// Load current user's messages
		include(BLUPATH_TEMPLATES . '/account/messages.php');
	}

	/**
	 *	Messages overview: listing.
	 */
	public function messages_listing()
	{
		$folder = Request::getCmd('folder', 'inbox');
		$page = Request::getInt('page', 1);
		$limit = 20;
		$offset = ($page - 1) * $limit;

		// Get messages
		$user = BluApplication::getUser();
		$messagesModel = BluApplication::getModel('messages');
		$total = true;
		$messages = $messagesModel->getUserMessages($user->userid, $folder, $offset, $limit, $total);

		// Build pagination
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '/account/messages/?folder='.$folder.'&amp;page='
		));

		// Load template
		include(BLUPATH_TEMPLATES.'/account/messages/listing.php');
	}

	/**
	 * Message detail page
	 */
	public function message()
	{
		if (!$user = $this->_requireUser('Please sign in view your messages.')) {
			return false;
		}

		// Get message ID
		if (!isset($this->_args[0])) {
			return $this->_redirect('/account/messages/');
		}
		$messageId = (int)$this->_args[0];

		// Get message and set as read
		$messagesModel = BluApplication::getModel('messages');
		$message = $messagesModel->getMessage($messageId, $user->userid);
		if (!$message) {
			return $this->_redirect('/account/messages/', 'Sorry, that message could not be found.');
		}
		$messagesModel->setRead($messageId, $user->userid);

		// Get message history
		$secondaryUserId = ($message['type'] == 'sent') ? $message['toID'] : $message['fromID'];
		$messageHistory = $messagesModel->getMessageHistory($user->userid, $secondaryUserId, $message['sent'], 0, 5);

		// What folder are we in?
		$folder = ($message['type'] == 'sent') ? 'sent' : 'inbox';

		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('My Messages', '/account/messages/?folder='.$folder.'?folder=sent');
		$breadcrumbs->add($message['subject'], '/account/message/'.$messageId);

		// Set page title
		$this->_doc->setTitle($message['subject'].BluApplication::getSetting('titleSeparator').'My Messages');

		// Display message
		include(BLUPATH_TEMPLATES .'/account/message.php');
	}

	/**
	 * Messages write page
	 */
	public function write_message()
	{
		// Require logged-in user (the sender)
		if (!$user = $this->_requireUser('Please sign in to send a message.')) {
			return false;
		}

		// Get data from request
		$subject = Request::getString('subject');
		$message = Request::getString('message');
		$replyId = Request::getInt('reply');
		$recipientId = Request::getInt('recipient');

		// Reply to a message?
		$replyMessage = null;
		if ($replyId) {
			$messagesModel = BluApplication::getModel('messages');
			$replyMessage = $messagesModel->getMessage($replyId, $user->userid);

		// Send to specific user?
		} elseif ($recipientId) {
			$personModel = BluApplication::getModel('person');
			$recipientUser = $personModel->getPerson(array('member' => $recipientId));
		}

		// Got a message to reply to?
		if ($replyMessage) {
			$messageHistory = $messagesModel->getMessageHistory($user->userid, $replyMessage['sender']->userid, $replyMessage['sent']+1, 0, 5);

			// Set default subject
			if (!$subject) {
				$subject = 'RE: '.$replyMessage['subject'];
			}

		// Get list of friends for to list
		} else {
			$friends = $user->getFriends();
			$checkedFriends = Request::getVar('recipients', array());
		}

		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('My Messages', '/account/messages');
		$breadcrumbs->add('Write a message', '/account/write_message');

		// Set page title
		$this->_doc->setTitle('Write a message'.BluApplication::getSetting('titleSeparator').'My Messages');

		// Load the template
		include(BLUPATH_TEMPLATES . '/account/write_message.php');
	}

	/**
	 * Send a message
	 */
	public function write_message_send()
	{
		if (!$user = $this->_requireUser('Please sign in to send a message.')) {
			return false;
		}

		// Get data from request
		$recipients = Request::getVar('recipients');
		$subject = Request::getString('subject');
		$message = Request::getString('message');

		// Validate
		$validation = array();

		// Require at least one recipient
		$validation['recipients'] = $this->_validateWithMessage(
			$recipients,
			'required',
			'You must select at least one recipient.'
		);

		// Require message content
		$validation['message'] = $this->_validateWithMessage(
			array($subject, $message),
			'required',
			'You need to enter a subject and a message.'
		);

		// Show errors
		if (in_array(false, $validation)) {
			return $this->_showMessages('write_message', 'write_message');
		}

		// Send message(s)
		$messagesModel = BluApplication::getModel('messages');
		foreach ($recipients as $recipientId) {
			$messagesModel->sendMessage($user->userid, $recipientId, $subject, $message);
		}

		// Go to sent messages
		return $this->_redirect('/account/messages?folder=inbox', 'Your message has been sent.');
	}


	/**
	 *	Delete a message.
	 */
	public function delete_message()
	{
		if (!$user = $this->_requireUser('Please sign in to see your messages.')) {
			return false;
		}

		// Get data from request
		$messageId = Request::getInt('id');

		// Delete
		$messagesModel = BluApplication::getModel('messages');
		$deleted = $messagesModel->deleteMessage($messageId, $user->userid);

		// Display info message
		if ($deleted){
			Messages::addMessage('The message has been deleted.');
		} else {
			Messages::addMessage('The message could not be deleted, please try again.', 'error');
		}

		// Display inbox page
		return $this->messages();
	}

	/**
	 * Account Details page.
	 */
	public function details()
	{
		// Add breadcrumb
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('My Account', '/account/');
		$breadcrumbs->add('My Profile Details', '/account/details');

		if (!$this->_requireUser('Please sign in to change your account details.')) {
			return false;
		}

		// Get current tab
		$tab = Request::getCmd('tab', 'basic');

		// Set page title
		$this->_doc->setTitle('My Profile Details');

		// Load template
		include(BLUPATH_TEMPLATES.'/account/details.php');
	}

	/**
	 * Account details: basic information tab.
	 */
	public function details_basic()
	{
		// Load fixed data
		$userModel = BluApplication::getModel('user');
		$enumsHousehold = $userModel->getHouseholdEnums();
		$enumsEducation = $userModel->getEducationEnums();

		// Get user data, if possible, to prefill form
		$user = BluApplication::getUser();
		$userTags = $user->getTags();
		$userInfo = $user->getUserInfo();

		// Get data from request, falling back to user info
		$queueId = Request::getString('queueid', md5(uniqid()));
		$displayName = Request::getString('form_display_name', $user->name);
		$email = Request::getString('form_email', $user->email);
		$location = Request::getString('form_location', $user->location);
		$timezone = Request::getFloat('form_timezone', $user->timezone);
		$tags = Request::getString('form_tags', implode(', ', $userTags));
		$aboutYou = Request::getString('form_aboutyou', $user->describeyourself);
		$household = Request::getInt('form_household', array_search($userInfo->household, $enumsHousehold));
		$age = Request::getInt('form_age', $userInfo->own_age);
		$education = Request::getInt('form_education', array_search($userInfo->education, $enumsEducation));

		// Load template
		include(BLUPATH_TEMPLATES.'/account/details/basic.php');
	}

	/**
	 * Account details: basic information tab - save.
	 */
	public function details_basic_save()
	{
		if (!$this->_requireUser('Please sign in to change your details.')) {
			return false;
		}
		$user = BluApplication::getUser();

		// Get data from request
		$queueId = Request::getString('queueid');

		// Build arguments
		$args = array();
		$special = array();

		// Get model
		$userModel = BluApplication::getModel('user');

		// Start validating
		$validation = array();

		// Display name
		$displayName = Request::getString('form_display_name');
		$choppedName = explode(' ', $displayName);
		$args['lastname'] = array_pop($choppedName);
		$args['firstname'] = implode(' ', $choppedName);
		$special['nameChanges'] = '`nameChanges` + 1';

		// Email address
		$email = Request::getString('form_email');
		$validation['email'] = $this->_validateWithMessage(
			$email,
			'validate-email',
			'Please enter a valid e-mail address.'
		) && ($email == $user->email || $this->_validateWithMessage(
			$email,
			'email_used',
			'Sorry, that e-mail already appears to be in use.'
		));
		if ($validation['email']){
			$args['email'] = $email;
		}

		// Check for consistent passwords
		$password = Request::getString('form_password');
		$password2 = Request::getString('form_password_confirm');
		$validation['password'] = $password && $password2 && $this->_validateWithMessage(
			array($password, $password2),
			'validate-passwordconfirm',
			'The passwords you entered did not match. Please check that you typed them correctly. (Remember that passwords are case sensitive.)'
		);

		// Location
		$locationName = Request::getString('form_location');
		$locationID = $this->_validateWithMessage(
			$locationName,
			'location',
			'You need to enter your nearest location.'
		);
		$validation['location'] = (bool) $locationID;
		if ($validation['location']){
			$args['locationID'] = $locationID;
		}

		// Time zone
		$timezone = Request::getInt('form_timezone', 0);
		$args['timezone'] = $timezone;
		
		// About yourself
		if ($describeyourself = Request::getString('form_aboutyou')) {
			$args['describeyourself'] = $describeyourself;
		}

		// Household income
		$household = Request::getInt('form_household');
		$householdEnums = $userModel->getHouseholdEnums();
		if (isset($householdEnums[$household]) && $household >= 0){
			$args['household'] = $householdEnums[$household];
		}

		// User's age
		$args['own_age'] = Request::getInt('form_age');

		// Education
		$education = Request::getInt('form_education');
		$educationEnums = $userModel->getEducationEnums();
		if (isset($educationEnums[$education]) && $education >= 0){
			$args['education'] = $educationEnums[$education];
		}

		// Save users ARGS & user_info ARGS
		$user->edit($args, $special);

		// Save new password, if one has been provided
		if ($validation['password']) {
			$userModel->updatePassword($password, $user->userid);
		}

		// Save new tags
		$tags = Request::getString('form_tags');
		$tags = $tags ? explode(',', $tags) : array();
		if (Utility::is_loopable($tags)) {
			$user->updateTags($tags);
		}

		// Upload new photo (if any)
		$result = $this->_saveUpload($queueId, 'photoupload', false, array('png', 'jpg', 'jpeg', 'gif', 'bmp'));
		if (isset($result['error'])) {
			Messages::addMessage($result['error'], 'error');
			return $this->_showMessages('details_basic', 'details');
		}

		// Move uploaded photos to their correct location
		$assets = Upload::getQueue($queueId);
		if (!empty($assets)) {
			foreach ($assets as $uploadId => $file) {
				$userModel->setPhotoFromUpload($uploadId, $file);
			}
			Upload::clearQueue($queueId);

		// Use an avatar
		} elseif ($avatar = Request::getString('avatar')) {
			$userModel->setPhoto('avatar'.$avatar.'.png');
		}

		// Redirect
		$this->_redirect('/account/details?tab=basic', 'Basic information updated.');
	}

	/**
	 *	Account details page: work tab.
	 */
	public function details_work()
	{
		// Get user details
		$user = BluApplication::getUser();
		$userInfo = $user->getUserInfo();

		$userModel = BluApplication::getModel('user');
		$industries = $userModel->getIndustries();

		// Load template
		include(BLUPATH_TEMPLATES.'/account/details/work.php');
	}

	/**
	 *	Account details page: work tab - save.
	 */
	public function details_work_save()
	{
		// Require user
		if (!$this->_requireUser('Please sign in to change your account details.')) {
			return false;
		}
		$user = BluApplication::getUser();

		// Get data from request
		$args = array();
		$args['employmentType'] = Request::getString('form_howwork');
		if($args['employmentType'] == 'employed' || $args['employmentType'] == 'parttime') {
			$args['employerName'] = Request::getString('form_employer');
			$args['jobTitle'] = Request::getString('form_jobtitle');
			$args['industry'] = Request::getInt('form_industry');
		}
		else if($args['employmentType'] == 'self') {
			$args['employerName'] = Request::getString('form_business_name');
			$args['industry'] = Request::getInt('form_industry_entrepreneur');
		}
		else if($args['employmentType'] == 'consultant') {
			$args['industry'] = Request::getInt('form_industry_freelance');
		}
		$args['dreamjob'] = Request::getString('form_dreamjob');
		$args['worstjobthing'] = Request::getString('form_worstjobthing');
		$args['bestjobthing'] = Request::getString('form_bestjobthing');
		$args['jobstress'] = Request::getString('form_jobstress');
		$args['joblike'] = Request::getString('form_joblike');
		$args['jobhours'] = Request::getString('form_jobhours');

		// Save data
		$success = $user->edit($args);

		$this->_redirect('/account/details?tab=work', 'Work details updated.');
	}

	/**
	 *	Account details: family tab.
	 */
	public function details_family()
	{
		// Get user details
		$user = BluApplication::getUser();
		$userInfo = $user->getUserInfo();

		// Load template
		include(BLUPATH_TEMPLATES.'/account/details/family.php');
	}

	/**
	 *	Account details: family tab save.
	 */
	public function details_family_save()
	{
		// Require user
		if (!$this->_requireUser('Please sign in to change your account details.')) {
			return false;
		}
		$user = BluApplication::getUser();

		// Get data from request
		$args = array();
		$args['numChildren'] = Request::getInt('form_children');
		$args['childrenAge'] = Request::getString('form_ages');
		$args['relationship'] = Request::getString('form_relationship');
		$args['parentadvice'] = Request::getString('form_parentadvice');
		$args['parentignore'] = Request::getString('form_parentignore');
		$args['kidactivity'] = Request::getString('form_kidactivity');

		// Save data
		$user->edit($args);

		$this->_redirect('/account/details?tab=family', 'Family information updated.');
	}

	/**
	 *	Account details: life tab.
	 */
	public function details_life()
	{
		// Get user details
		$user = BluApplication::getUser();
		$userInfo = $user->getUserInfo();

		// Load template
		include(BLUPATH_TEMPLATES.'/account/details/life.php');
	}

	/**
	 *	Account details: life tab save.
	 */
	public function details_life_save()
	{
		// Require user
		if (!$this->_requireUser('Please sign in to change your account details.')) {
			return false;
		}
		$user = BluApplication::getUser();

		// Get data from request
		$args = array();
		$args['statement'] = Request::getString('form_statement');
		$args['interests'] = Request::getString('form_interests');
		$args['url'] = Request::getString('form_url');
		$args['destress'] = Request::getString('form_destress');
		$args['bestadvice'] = Request::getString('form_bestadvice');
		$args['describeyourself'] = Request::getString('form_describeyourself');
		$args['book'] = Request::getString('form_book');
		$args['movie'] = Request::getString('form_movie');

		// Save data
		$user->edit($args);

		$this->_redirect('/account/details?tab=life', 'Life details updated.');
	}

	/**
	 *	Account details: privacy tab.
	 */
	public function details_privacy()
	{
		// Get user details
		$user = BluApplication::getUser();
		$userPrivacy = $user->getPrivacy();

		// Load template
		include(BLUPATH_TEMPLATES.'/account/details/privacy.php');
	}

	/**
	 *	Account details: privacy tab save.
	 */
	public function details_privacy_save()
	{
		// Require user
		if (!$user = $this->_requireUser('Please sign in to change your account details.')) {
			return false;
		}

		// Get data from request
		$args = array();
		$args['userProfileWork'] = Request::getInt('form_profilework');
		$args['userProfileFamily'] = Request::getInt('form_profilefamily');
		$args['userProfileLife'] = Request::getInt('form_profilelife');
		$args['userProfileGroups'] = Request::getInt('form_profilegroups');
		$args['userProfileMemberNetwork'] = Request::getInt('form_profilemembernetwork');
		$args['userProfileArticles'] = Request::getInt('form_profilearticles');
		$args['userProfileQuestions'] = Request::getInt('form_profilequestions');
		$args['userProfileComments'] = Request::getInt('form_profilecomments');
		$args['userProfilePhotos'] = Request::getInt('form_profilephotos');

		// Save data
		$user->edit($args);

		$this->_redirect('/account/details?tab=privacy', 'Privacy settings saved.');
	}

	/**
	 *	Account details: alerts tab.
	 */
	public function details_alerts()
	{
		// Get user details
		$user = BluApplication::getUser();

		// Get user alert preferences
		$alertsModel = BluApplication::getModel('alerts');
		$alertPrefs = $alertsModel->getUserAlertPrefs($user->userid);

		// Load template
		include(BLUPATH_TEMPLATES.'/account/details/alerts.php');
	}

	/**
	 *	Account details: alerts tab - save.
	 */
	public function details_alerts_save()
	{
		// Require user
		if (!$this->_requireUser('Please sign in to change your account details.')) {
			return false;
		}
		$user = BluApplication::getUser();

		// Get prefs from request
		$alertPrefs = Request::getVar('alertPrefs');

		// Save alert preferences
		$alertsModel = BluApplication::getModel('alerts');
		$alertsModel->saveUserAlertPrefs($user->userid, $alertPrefs);

		$this->_redirect('/account/details?tab=alerts', 'Alerts settings saved.');
	}

	/**
	 *	Account details: delete account tab.
	 */
	public function details_delete(){
		// Require user
		if (!$this->_requireUser('Please sign in to change your account details.')){
			return false;
		}
		$user = BluApplication::getUser();

		// Load template
		include(BLUPATH_TEMPLATES.'/account/details/delete.php');
	}

	/**
	 *	Account details: delete account tab - save.
	 */
	public function details_delete_save(){

		// Require user
		if (!$user = $this->_requireUser('Please sign in to change your account details.')){
			return false;
		}

		// Get optional parameters
		$parameters = array('reason' => Request::getString('reason', null));

		// Delete user.
		$userModel = $this->getModel('user');
		$deleted = $userModel->delete($user, $parameters);

		// Redirect.
		if ($deleted){
			Messages::addMessage('Your '.BluApplication::getSetting('storeName').' account has been deleted.');
			return $this->logout();
		} else {
			Messages::addMessage('Sorry, your account couldn\'t be deleted, please try again.', 'error');
			return $this->_redirect('/'.$this->_controllerName.'/details?tab=delete');
		}

	}

	/**
	 * My alerts
	 */
	public function alerts()
	{
		// Add breadcrumb
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('My Account', '/account/');
		$breadcrumbs->add('My Alerts', '/account/alerts');

		// Require user
		if (!$user = $this->_requireUser('Please sign in to view your alerts.')) {
			return false;
		}

		// Get alerts
		$alertsModel = BluApplication::getModel('alerts');
		$alerts = $alertsModel->getUserAlerts($user->userid);

		// Set page title
		$this->_doc->setTitle('My Alerts');

		// Load template
		include(BLUPATH_TEMPLATES.'/account/alerts.php');
	}

	/**
	 * Alert go!
	 */
	public function alert_go()
	{
		// Check for correct number of params
		if (empty($this->_args) || count($this->_args) < 3) {
			return $this->_redirect('/account/alerts');
		}

		// Get alert ID, user ID, read key
		$alertId = (int) $this->_args[0];
		$userId = (int) $this->_args[1];
		$readKey = $this->_args[2];

		// Get alert details
		$alertsModel = BluApplication::getModel('alerts');
		$alert = $alertsModel->getAlert($userId, $alertId);
		if (!$alert) {
			return $this->_redirect('/account/alerts');
		}

		// Check read key hash and mark alert as read if valid
		if ($alertsModel->checkReadKey($userId, $alertId, $readKey)) {
			$alertsModel->markAlertSeen($userId, $alertId);
		}

		// Follow link
		$this->_redirect($alert['link']);
	}

	/**
	 * My groups
	 */
	public function groups()
	{
		// Add breadcrumb
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('My Account', '/account/');
		$breadcrumbs->add('My Groups', '/account/groups');

		// Require user
		if (!$user = $this->_requireUser('Please sign in to view your groups.')) {
			return false;
		}

		// Get current tab
		$tab = strtolower(Request::getCmd('tab', 'joined'));

		// Get groups
		$groupsModel = BluApplication::getModel('groups');
		$ownedGroups = $groupsModel->getOwnedGroups($user->userid);
		$joinedGroups = $groupsModel->getJoinedGroups($user->userid);

		// Set page title
		$this->_doc->setTitle('My Groups');

		// Load template
		include(BLUPATH_TEMPLATES . '/account/groups.php');
	}

	/**
	 * My articles
	 */
	public function articles()
	{
		// Add breadcrumb
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('My Account', '/account/');
		$breadcrumbs->add('My Articles', '/account/articles');

		// Require user
		if (!$this->_requireUser('Please sign in to view your articles.')) {
			return false;
		}
		$user = BluApplication::getUser();

		// Get current tab
		$tab = strtolower(Request::getCmd('tab', 'saved'));

		// Get items
		$itemsModel = BluApplication::getModel('newitems');
		$ownedArticles = $itemsModel->getOwnedItems($user->userid, 'article');
		$savedArticles = $itemsModel->getSavedItems($user->userid, 'article');

		// Set page title
		$this->_doc->setTitle('My Articles');

		// Load template
		include(BLUPATH_TEMPLATES . '/account/articles.php');
	}

	/**
	 * My blogs
	 */
	public function blogs()
	{
		// Add breadcrumb
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('My Account', '/account/');
		$breadcrumbs->add('My Blogs', '/account/blogs');

		// Require user
		if (!$user = $this->_requireUser('Please sign in to view your blogs.')) {
			return false;
		}

		// Get current tab
		$tab = strtolower(Request::getCmd('tab', 'subscribed'));

		// Get users blog posts
		$blogsModel = BluApplication::getModel('blogs');
		$userBlog = $blogsModel->getBlog('member', $user->contentcreatorid);
		if ($userBlog) {
			$userBlogPosts = $userBlog->getLatestPosts();
		}

		// Get unread posts from subscribed blogs
		$itemsModel = BluApplication::getModel('newitems');
		$subscribedPosts = $itemsModel->getLatestSubscribedItems($user->userid, 'note');

		// Set page title
		$this->_doc->setTitle('My Blogs');

		// Load template
		include(BLUPATH_TEMPLATES . '/account/blogs.php');
	}

	/**
	 * Edit a blog post
	 */
	public function edit_blog_post()
	{
		// Require user
		if (!$user = $this->_requireUser('Please sign in to edit a blog post.')) {
			return false;
		}

		// Get blog post details
		$postId = $this->_args[0];
		$itemsModel = BluApplication::getModel('newitems');
		$post = $itemsModel->getItem($postId, 'note');
		if (!$post) {
			return $this->_redirect('/account', 'Sorry, that blog post could not be found.', 'error');
		}

		// Get available categories
		$metaModel = BluApplication::getModel('meta');
		$categories = $metaModel->getCategories('note');

		// Get data from request/blog
		$title = Request::getString('title', $post['articleTitle']);
		$body = Request::getString('body', $post['articleBody']);
		$tags = Request::getString('tags', implode(',', $post['tags']));
		$categoryId = Request::getString('category', $post['categoryId']);
		$privacy = Request::getCmd('privacy', $post['privacy']);

		// Load template
		include(BLUPATH_TEMPLATES . '/account/edit_blog_post.php');
	}

	/**
	 * Save a blog post
	 */
	public function edit_blog_post_save()
	{
		// Require user
		if (!$user = $this->_requireUser('Please sign in to edit a blog post.')) {
			return false;
		}

		// Get data from request
		$postId = Request::getInt('postid');
		$title = Request::getString('title');
		$body = Request::getString('body');
		$categoryId = Request::getInt('category');
		$tags = explode(',', Request::getString('tags'));
		$privacy = Request::getCmd('privacy');

		// Validate
		if (!$title || !$body || !$categoryId) {
			Messages::addMessage('Please complete all required fields.', 'error');
			return $this->_showMessages();
		}

		// Save item
		$itemsModel = BluApplication::getModel('newitems');
		$itemsModel->editItem($user->userid, $postId, $title, $body, $categoryId, $tags, null, null, null, $privacy);

		// Redirect to overview page
		$this->_redirect('/account/blogs?tab=owned', 'Your blog post has been edited.');
	}

	/**
	 * Delete a blog post
	 */
	public function delete_blog_post()
	{
		// Require user
		if (!$user = $this->_requireUser('Please sign in to delete a blog post.')) {
			return false;
		}
		
		// Get item
		$postId = $this->_args[0];
		$itemsModel = $this->getModel('newitems');
		$item = $itemsModel->getItem($postId);
		
		// If authorised...
		if ($item['author']['userid'] == $user->userid){
			
			// Delete post
			$itemsModel->deleteItem($postId);
			
			// Alert
			Messages::addMessage('Your blog post has been deleted.');
			
		}

		// Redirect to my blogs page
		$this->_redirect('/account/blogs?tab=owned');
	}

	/**
	 * My marketplace listings
	 */
	public function marketplace()
	{
		// Add breadcrumb
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('My Account', '/account/');
		$breadcrumbs->add('My Marketplace Listings', '/account/marketplace');

		// Require user
		if (!$this->_requireUser('Please sign in to view your marketplace listings.')) {
			return false;
		}
		$user = BluApplication::getUser();

		// Get listings
		$marketplaceModel = BluApplication::getModel('marketplace');
		$ownedListings = $marketplaceModel->getOwnedListings($user->userid);

		// Set page title
		$this->_doc->setTitle('My Marketplace Listings');

		// Load template
		include(BLUPATH_TEMPLATES . '/account/marketplace.php');
	}

	/**
	 * My photos
	 */
	public function photos()
	{
		// Require user
		if (!$user = $this->_requireUser('Please sign in to view your photos.')) {
			return false;
		}

		// Get current tab
		$tab = strtolower(Request::getCmd('tab', 'manage'));

		// Get data from request
		$photoCaption1 = Request::getString('photocaption1');
		$photoCaption2 = Request::getString('photocaption2');
		$photoCaption3 = Request::getString('photocaption3');
		$queueId = Request::getString('queueid', md5(uniqid()));

		// Get photos
		$photosModel = $this->getModel('newphotos');
		$total = null;
		$photos = $photosModel->getPhotos(0, 0, $total, array(
			'user' => $user->userid,
			'order' => 'date'
		));
		$photosModel->addDetails($photos);
		
		// Add breadcrumb
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('My Account', '/account/');
		$breadcrumbs->add('My Photos', '/account/photos');

		// Set page title
		$this->_doc->setTitle('My Photos');

		// Load template
		include(BLUPATH_TEMPLATES.'/account/photos.php');
	}

	/**
	 * Upload photos
	 */
	public function photos_upload()
	{
		// Require user
		if (!$this->_requireUser('Please sign in to view your photos.')) {
			return false;
		}
		$user = BluApplication::getUser();

		// Check for errors
		$errors = false;

		// Add photos
		$queueId = Request::getString('queueid');
		for ($i = 1; $i <= 3; $i ++) {
			$caption = Request::getString('photocaption'.$i);
			$result = $this->_saveUpload($queueId, 'photoupload'.$i, false, array('png', 'jpg', 'jpeg', 'gif', 'bmp'), array('caption' => $caption));
			if (isset($result['error'])) {
				Messages::addMessage($result['error'], 'error');
				$errors = true;
			}
		}

		// Check for at least one new upload
		$assets = Upload::getQueue($queueId);
		if (empty($assets)) {
			Messages::addMessage('Please select a photo to upload.', 'error');
			$errors = true;
		}

		// Show errors
		if ($errors) {
			return $this->_showMessages('photos');
		}

		// Move uploaded files to their correct location
		if (!empty($assets)) {
			$photosModel = BluApplication::getModel('photos');
			foreach ($assets as $uploadId => $file) {
				$photosModel->addUserPhoto($user->userid, $uploadId, $file, $file['caption']);
			}
			Upload::clearQueue($queueId);
		}

		// Done, view photos
		return $this->_redirect('/account/photos?tab=manage', 'Your photos have been uploaded.');
	}

	/**
	 * Delete a photo
	 */
	public function photos_delete()
	{
		// Require user
		if (!$this->_requireUser('Please sign in to manage your photos.')) {
			return false;
		}
		$user = BluApplication::getUser();

		// Get data from request
		$photoId = Request::getInt('id');

		// Delete photo
		$photosModel = BluApplication::getModel('photos');
		$photosModel->removeUserPhoto($user->userid, $photoId);

		// Done, view photos
		return $this->_redirect('/account/photos?tab=manage', 'Your photo has been deleted.');
	}

	/**
	 *	My Friends page.
	 */
	public function friends()
	{
		// Add breadcrumb
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('My Account', '/account/');
		$breadcrumbs->add('My Friends', '/account/friends');

		if (!$this->_requireUser('Please sign in to see your Friends area.')) {
			return false;
		}

		// Set page title
		$this->_doc->setTitle('My Friends');

		// Display settings
		$tab = Request::getString('tab', 'friends');

		// Load template
		include(BLUPATH_TEMPLATES . '/account/friends.php');
	}

	/**
	 *	Friends page: tab switchboard.
	 */
	protected function friends_tab($tab){

		/* Switch */
		switch(strtolower($tab)){
			case 'requests':
				/* Network requests */
				return $this->friends_tab_requests();
				break;

			case 'friends':
				/* Same as default */

			default:
				/* Friends tab */
				return $this->friends_tab_friends();
				break;
		}

	}

	/**
	 *	Friends page: tab - network requests.
	 */
	protected function friends_tab_requests()
	{
		// Get arguments
		$page = Request::getInt('page', 1);
		$limit = BluApplication::getSetting('listingLength', 9);
		$offset = ($page - 1) * $limit;

		// Get requests
		$user = BluApplication::getUser();

		$personModel = BluApplication::getModel('newperson');
		$total = true;
		$requests = $personModel->getFriendRequests($user->userid, $offset, $limit, $total, true);

		// Set up pagination
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '?page='
		));

		// Load template
		include(BLUPATH_TEMPLATES . '/account/friends/tab_requests.php');
	}

	/**
	 *	Friends page: tab - friends.
	 */
	protected function friends_tab_friends(){

		/* Get arguments */
		$page = Request::getInt('page', 1);

		/* Get parameters */
		$total = null;
		$limit = BluApplication::getSetting('bigListingLength', 5);

		/* Get friends */
		$user = BluApplication::getUser();
		$friends = $user->getFriends(($page - 1) * $limit, $limit, $total);

		/* Paginate */
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '?page='
		));

		/* Load template */
		include(BLUPATH_TEMPLATES . '/account/friends/tab_friends.php');

	}

	/**
	 *	Friends page: individual friend.
	 */
	protected function friends_individual(PersonObject $person){

		/* Alternating colours */
		static $alt = false;
		$alt = !$alt;

		/* Get data */
		$job = $person->job;
		$about = $person->about;

		/* Load template */
		include(BLUPATH_TEMPLATES . '/account/friends/blocks/friend_individual.php');

	}

	/**
	 * Remove a friend
	 */
	public function remove_friend()
	{
		if (!$user = $this->_requireUser('Please sign in to manage your friends.')) {
			return false;
		}

		// Get data from request
		$friendId = Request::getInt('id');

		// Remove friend
		$personModel = BluApplication::getModel('person');
		$personModel->removeFriend($user->userid, $friendId);

		// Return to friends list
		return $this->_redirect('/account/friends', 'Friend removed.');
	}

	/**
	 * Accept a friend
	 */
	public function accept_friend()
	{
		if (!$user = $this->_requireUser('Please sign in to manage your friends.')) {
			return false;
		}

		// Get data from request
		$friendId = Request::getInt('id');

		// Remove friend
		$personModel = BluApplication::getModel('person');
		$personModel->acceptFriend($user->userid, $friendId);

		// Return to friends list
		return $this->_redirect('/account/friends', 'Friend accepted.');
	}

	/**
	 * Reject a friend
	 */
	public function reject_friend()
	{
		if (!$user = $this->_requireUser('Please sign in to manage your friends.')) {
			return false;
		}

		// Get data from request
		$friendId = Request::getInt('id');

		// Remove friend
		$personModel = BluApplication::getModel('person');
		$personModel->rejectFriend($user->userid, $friendId);

		// Return to friends list
		return $this->_redirect('/account/friends', 'Friend rejected.');
	}

	/**
	 *	My Day Archive page.
	 */
	public function myday()
	{
		if (!$user = $this->_requireUser('Please sign in to view your My Day Snapshot archive.')) {
			return false;
		}

		// Add breadcrumb
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('My Account', '/account/');
		$breadcrumbs->add('My Day Archive', '/account/myday');

		// Get data
		$myDays = $user->getMyDays();

		// Get person details
		$userModel = $this->getModel('user');
		$isSelf = $userModel->isSelf($user);

		// Set page title
		$this->_doc->setTitle('My Day Archive');

		// Load template
		include(BLUPATH_TEMPLATES . '/account/myday.php');
	}

	/**
	 * Save my day
	 */
	public function myday_save()
	{
		// Get data
		$myDay = Request::getVar('myday');
		$public = Request::getBool('public');
		$tellfriends = Request::getBool('tellfriends');

		// Save my day
		$userModel = BluApplication::getModel('user');
		$userModel->saveMyDay($myDay, $public, $tellfriends);

		// Return to account overview
		Messages::addMessage('Thank you, your day has been updated.');
		return $this->_redirect('/account');
	}

}

?>
