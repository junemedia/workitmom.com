<?php

/**
 * Registration Controller
 *
 * @package BluApplication
 * @subpackage FrontendControllers
 */
class WorkitmomRegisterController extends ClientFrontendController
{
	/**
	 * Registration stages
	 *
	 * @var array
	 */
	private $_stages = array();

	/**
	 *	Current stage (Registration).
	 *
	 * @var int
	 */
	private $_stage;

	/**
	 *	Constructor
	 */
	public function __construct($args)
	{
		parent::__construct($args);

		// Get user details
		$user = BluApplication::getUser();

		// Get stage to view
		$this->_stage = Session::get('registerStage', $user ? 2 : 1);

		// Set up registration stages
		$this->_stages = array(
			array('id' => 's1_basic',
				'title' => 'Basic Information',
				'edit' => !$user),
			array('id' => 's2_optional',
				'title' => 'Optional Information',
				'edit' => true));

		// Allow skip to edit previous stages
		if (isset($this->_args[0]) && is_numeric($this->_args[0]) && ($this->_args[0] > 0) && ($this->_args[0] < $this->_stage)) {
			$this->_stage = (int)$this->_args[0];
		}
	}

	/**
	 *	Main sign up page.
	 */
	public function view()
	{
		// Current stage details
		$registerStages = $this->_stages;
		$currentStageNum = $this->_stage;

		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Sign Up');

		// Set page title
		$this->_doc->setTitle('Sign Up');

		// Load template
		include (BLUPATH_TEMPLATES.'/register/main.php');
	}

	/**
	 * Register stage
	 */
	public function view_stage()
	{
		$stageName = $this->_stages[$this->_stage - 1]['id'];
		if ($this->_doc->getFormat() == 'json') {

			// Render stage
			ob_start();
			$this->$stageName();
			$content = ob_get_clean();

			// Build response
			$response = array('stageDetails' => $this->_stages, 'stageNum' => $this->_stage, 'content' => $content);
			echo json_encode($response);
		} else {
			$this->$stageName();
		}
	}

	/**
	 * Stage 1: Basic information
	 */
	public function s1_basic()
	{
		// Don't let logged in users sign up again
		if (BluApplication::getUser()) {
			return $this->_gotoStage(2);
		}

		// Get data from request
		$username = Request::getString('form_username');
		$displayName = Request::getString('form_display_name');
		$email = Request::getString('form_email');
		$location = Request::getString('form_location');
		$referral = Request::getString('form_referral');
		$captcha = Request::getString('wimcaptcha');
		$terms_conditions = Request::getBool('form_terms');
		$newsletter = Request::getBool('form_newsletter', true);

		// Load template
		include(BLUPATH_TEMPLATES.'/register/stages/s1_basic.php');
	}

	/**
	 *	Registration stage 1: validation and save.
	 */
	public function s1_basic_save()
	{
		// Get data from request
		$displayName = Request::getString('form_display_name');
		$choppedName = explode(' ', $displayName);
		$firstName = isset($choppedName[0]) ? $choppedName[0] : null;
		$lastName = count($choppedName) > 1 ? implode(array_slice($choppedName, 1), " ") : null;

		// Get model
		$userModel = $this->getModel('user');

		// Validate data (somewhat simplified as verbose messages will normally be shown client-side)
		$validation = array();

		// Required fields
		$username = Request::getString('form_username');
		$email = Request::getString('form_email');
		$password = Request::getString('form_password');
		$validation['required'] = $this->_validateWithMessage(
			array($username, $firstName, $email, $password),
			'required',
			'Not all of the required information was given.'
		);

		// Check username is valid, and not already in use
		$validation['username'] = $this->_validateWithMessage(
			$username,
			'validate-alphanum',
			'Please use only letters (a-z) or numbers (0-9) only in this field. No spaces or other characters are allowed.'
		) && $this->_validateWithMessage(
			$username,
			'username_used',
			'Sorry, that username already appears to have been taken. Please use another, or check that you are not already registered.'
		);

		// Syntactically sound, and non-registered email address.
		$validation['email'] = $this->_validateWithMessage(
			$email,
			'validate-email',
			'Please enter a valid e-mail address.'
		) && $this->_validateWithMessage(
			$email,
			'email_used',
			'Sorry, that e-mail already appears to be in use. Please use another, or check that you are not already registered.'
		);

		// Check for consistent passwords
		$password2 = Request::getString('form_password_confirm');
		$validation['password'] = $this->_validateWithMessage(
			array($password, $password2),
			'validate-passwordconfirm',
			'The passwords you entered did not match. Please check that you typed them correctly. (Remember that passwords are case sensitive.)'
		);

		// Check captcha
		$captcha = Request::getString('wimcaptcha');
		$validation['captcha'] = $this->_validateWithMessage(
			$captcha,
			'validate-captcha',
			'The 5 digit code you have entered is not correct. Please try again.'
		);

		// Terms and Conditions
		$terms_conditions = Request::getBool('form_terms');
		$validation['terms_conditions'] = $this->_validateWithMessage(
			$terms_conditions,
			'validate-terms-required',
			'You need to accept the <em>' . BluApplication::getSetting('storeName') . '</em> terms and conditions in order to sign up.'
		);

		// Location
		$locationName = Request::getString('form_location');
		$locationID = $this->_validateWithMessage(
			$locationName,
			'location',
			'You need to enter your nearest location.'
		);
		$validation['location'] = (bool) $locationID;

		// Show errors
		if (in_array(false, $validation)) {
			return $this->_showMessages('view_stage', 'view');
		}

		// Register (and log in)
		$referral = Request::getString('form_referral');
		$newsletter = Request::getString('form_newsletter');
		$ipaddr = Request::getVisitorIPAddress();  //Get register address
		$freeCountry = array("US","CA","MX");

		$checkCountry = $userModel->getCountryByIp($ipaddr);
		//print_r($checkCountry);exit;
		
		$userCountry = '';		
		$tags = '';
		
		//No ip relative info 
		if(!$checkCountry || empty($checkCountry))
		{
			$tags = get_meta_tags('https://secure.geobytes.com/IpLocator.htm?GetLocation&template=php3.txt&IpAddress='.$ipaddr.'&pt_email=hmarshak@amperemedia.com&pt_password=757149a2');
			if($tags['known'] == 'true')
			{
				$userCountry = $tags['iso2'];
				$details = json_encode($tags);
				//print_r($details);exit;
				//Save the ip relative info
				$userModel->saveIpAddress($ipaddr,$userCountry,$details);
			}
		}
		else
		{
			$userCountry = $checkCountry['country'];
		}
		
		
		if(in_array($userCountry,$freeCountry))
		{
			$user = $userModel->register($username, $firstName, $lastName, $email, $password, $locationID, $referral, $newsletter,$ipaddr);
		}
		else
		{
			return $this->_showMessages('view_stage', 'view');
		}

		//$user = $userModel->register($username, $firstName, $lastName, $email, $password, $locationID, $referral, $newsletter,$ipaddr);
		
		if(!$user)
		{
			return $this->_showMessages('view_stage', 'view');
		}
		
		// Send welcome e-mail
		$emailMsg = new Email();
		$vars = array(
			'firstname' => $firstName,
			'username' => $username
		);
		$emailMsg->quickSend($email, $displayName, 'Welcome to Work It, Mom!', 'welcome', $vars);

		// Move to next stage
		$this->_stages[0]['edit'] = false;
		$this->_gotoStage(2, true);
	}

	/**
	 *	Stage 2: Optional Information
	 */
	public function s2_optional()
	{
		// Require user
		if (!$this->_requireUser('Please sign in to edit your account.')){
			return false;
		} else {
			// Old snapshots that were taken out (in $this->_requireUser) need to be put back in.
			Request::takeSnapshot();
		}

		// Get data from request
		$queueId = Request::getString('queueid', md5(uniqid()));

		// Get model and user
		$userModel = $this->getModel('user');
		$user = BluApplication::getUser();

		// Load fixed data
		$enumsHousehold = $userModel->getHouseholdEnums();
		$enumsEducation = $userModel->getEducationEnums();
		$industries = $userModel->getIndustries();
		asort($industries);	// Sort by industry name.

		// Load user data, if possible, to prefill form
		$tags = implode(',', $user->getTags());
		$userInfo = $user->getUserInfo();

		// Load template
		include(BLUPATH_TEMPLATES.'/register/stages/s2_optional.php');
	}

	/**
	 *	Registration stage 2: validation and save.
	 */
	public function s2_optional_save()
	{
		/* Require user */
		if (!$this->_requireUser('Please sign in to change your details.')){
			return false;
		} else {
			// Old snapshots that were taken out (in $this->_requireUser) need to be put back in.
			Request::takeSnapshot();
		}
		$user = BluApplication::getUser();

		// Get model
		$userModel = $this->getModel('user');

		// Get data from request
		$queueId = Request::getString('queueid');
		$args = array();
		$args['employmentType'] = Request::getString('form_howwork');
		$args['employerName'] = Request::getString('form_employer');
		$args['jobTitle'] = Request::getString('form_jobtitle');
		$args['employerName'] = Request::getString('form_business_name');
		$args['industry'] = Request::getInt('form_industry');
		$args['numChildren'] = Request::getInt('form_children');
		$args['childrenAge'] = Request::getString('form_ages');
		$args['statement'] = Request::getString('form_aboutyou');
		$args['own_age'] = Request::getInt('form_age');

		$household = Request::getInt('form_household');
		$householdEnums = $userModel->getHouseholdEnums();
		$args['household'] = isset($householdEnums[$household]) ? $householdEnums[$household] : 0;

		$education = Request::getInt('form_education');
		$educationEnums = $userModel->getEducationEnums();
		$args['education'] = isset($educationEnums[$education]) ? $educationEnums[$education] : 0;

		// Save data through the model
		$userModel->registerExtra($args);

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
			return $this->_gotoStage(2);
		}

		// Move uploaded photos to their correct location
		$assets = Upload::getQueue($queueId);
		if (!empty($assets)) {
			foreach ($assets as $uploadId => $file) {
				$userModel->setPhotoFromUpload($uploadId, $file);
			}
			Upload::clearQueue($queueId);

		// Use default avatar
		} else {
			$avatar = Request::getString('avatar');
			$userModel->setPhoto('avatar'.$avatar.'.png');
		}

		// Build redirect string.
		$redirect = Session::delete('referer', '/account/');
		$redirect .= '?task='.Request::parseSnapshotTask();

		// Redirect
		return $this->_redirect($redirect, Session::get('justRegistered') ? 'Thanks for joining Work It, Mom!' : 'Your details have been updated.');
	}

	/**
	 * Moves the user to a different stage of the checkout
	 *
	 * @param string New stage name
	 */
	private function _gotoStage($stage, $justRegistered = false)
	{
		// Clear submission status
		unset($_REQUEST['submit']);

		// Store stage
		$this->_stage = $stage;
		Session::set('registerStage', $stage);
		Session::set('justRegistered', $justRegistered ? 1 : 0);

		// Display stage/checkout depending on format
		if ($this->_doc->getFormat() == 'site') {
			$this->view();
		} else {
			$this->view_stage();
		}
	}

}

?>
