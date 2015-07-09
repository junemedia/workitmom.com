<?php

/**
 * Front end controller base class
 *
 * @package BluApplication
 * @subpackage FrontendControllers
 */
abstract class FrontendController
{
	/**
	 *	Name of the controller
	 *
	 *	@access protected
	 *	@var string
	 */
	protected $_controllerName;

	/**
	 *	Holds a copy of the URL to be requested to invoke the particular method.
	 *
	 *	@access protected
	 *	@var string
	 */
	protected $_url;

	/**
	 *	Reference to application document object
	 *
	 *	@access protected
	 *	@var Document
	 */
	protected $_doc;

	/**
	 *	Requested arguments
	 *
	 *	@access protected
	 *	@var array
	 */
	protected $_args;

	/**
	 *	Requested redirect location
	 *
	 *	@access private
	 *	@var string
	 */
	private $_redirect = false;

	/**
	 *	Front end controller constructor
	 *
	 *	@access public
	 *	@param array Arguments
	 */
	public function __construct($args)
	{
		// Store arguments
		$this->_args = $args;

		// Get reference to application document
		$this->_doc = BluApplication::getDocument();

		// Set controller name
		$this->_controllerName = get_class($this);
		if (strpos($this->_controllerName, 'Controller') !== false) {
			$this->_controllerName = substr($this->_controllerName, 0, -10);
		}
		$siteId = BluApplication::getSetting('siteId');
		if (strpos($this->_controllerName, ucfirst($siteId)) !== false) {
			$this->_controllerName = substr($this->_controllerName, strlen($siteId));
		}
	}

	/**
	 *	Get redirect location
	 *
	 *	@access public
	 *	@return string Redirect URL
	 */
	public function getRedirect()
	{
		return $this->_redirect;
	}

	/**
	 * Set redirect location
	 *
	 * @param string Redirect URL
	 * @param string Optional message to show after redirection
	 * @param string Message type (info|warn|error)
	 * @param string Message location
	 */
	protected function _redirect($url, $msg = null, $msgType = 'info', $msgLocation = 'default')
	{
		if ($msg) {
			Messages::addMessage($msg, $msgType, $msgLocation);
		}
		if ($this->_doc->getFormat() == 'json') {
			echo json_encode(array('location' => SITEURL.$url));
		} else {
			$this->_redirect = $url;
		}
	}

	/**
	 *	Redirect to error page
	 *
	 *	@access protected
	 *	@param string Task name
	 *	@param string Controller name
	 */
	final protected function _errorRedirect($task = 'fourOhFour', $controller = 'error')
	{
		$errorController = BluApplication::getController($controller);
		$errorController->$task();
	}

	/**
	 * Output form (or any other task) in the correct format for the current request
	 *
	 * @param string Raw/JSON form task name
	 * @param string Full site task name
	 */
	protected function _viewForm($formTask, $siteTask = 'view')
	{
		switch ($this->_doc->getFormat()) {
		case 'raw':
			$this->$formTask;
			break;

		case 'json':
			ob_start();
			$this->$formTask();
			$content = ob_get_clean();
			echo json_encode(array('form' => $content));
			break;

		default:
			$this->$siteTask();
			break;
		}
	}

	/**
	 * Output messages for the given location in the correct format for the current request
	 *
	 * @param string Raw task name
	 * @param string Full site task name
	 * @param string Messages location
	 * @param bool Whether this response completes the action
	 */
	protected function _showMessages($rawTask = 'view', $siteTask = 'view', $location = 'default', $complete = false)
	{
		switch ($this->_doc->getFormat()) {
		case 'raw':
			$this->$rawTask();
			break;

		case 'json':
			echo json_encode(array('messages' => Messages::getMessages($location),
				'complete' => $complete));
			break;

		default:
			$this->$siteTask();
			break;
		}
	}

	/**
	 * Save an uploaded file
	 *
	 * @param string Upload queue Id
	 * @param string File upload field name
	 * @param bool Whether the field is required
	 * @param array Optional array of allowed types
	 * @param array Optional array of extra fields to store with the file in the queue
	 * @return array Result details
	 */
	protected function _saveUpload($queueId, $fieldName = 'fileupload', $required = false, $validTypes = null, $extraInfo = null)
	{
		// Get data from request
		$file = Request::getVar($fieldName, null, 'files');

		// Check we have a file
		if ($file['tmp_name']) {

			// Check file extension
			$ext = Utility::getFileExtension($file['name']);
			if ($validTypes && !in_array($ext, $validTypes)) {
				$result['result'] = 'error';
				$result['error'] = 'Please choose a file with one of the following extensions: '.implode(', ', $validTypes);

			// Check file uploaded correctly
			} elseif (!Upload::isValid($file)) {
				$result['result'] = 'failed';
				$result['error'] = 'Sorry, there was a problem with your upload.';

			// It would seem we're good to go
			} else {
				$result['result'] = 'success';
				$result['size'] = '';

				// Add extra fields
				if (!empty($extraInfo)) {
					$file = array_merge($file, $extraInfo);
				}

				// Save upload to queue
				Upload::saveToQueue($queueId, $file);
			}

		// Return error if file is required
		} elseif ($required) {
			$result['result'] = 'error';
			$result['error'] = 'You must enter a file to upload.';

		// No file, nothing to do
		} else {
			$result['result'] = 'empty';
		}

		return $result;
	}

	/**
	 *	Validation.
	 *
	 *	@access protected
	 *	@param mixed Input content, or array of inputs.
	 *	@param string Type of input to validate
	 *	@return bool
	 */
	final protected function _validate($input, $type)
	{
		/* Get model */
		$userModel = $this->getModel('user');

		/* Recursive */
		if (Utility::is_loopable($input) && ($type != 'validate-passwordconfirm')){
			$valid = true;
			foreach($input as $i){
				$valid = $valid && $this->_validate($i, $type);
			}
			return $valid;
		}
		if (Utility::is_loopable($type)){
			$valid = true;
			foreach($type as $t){
				$valid = $valid && $this->_validate($input, $t);
			}
			return $valid;
		}

		/* Test */
		switch($type){
			case 'email_used':
				return !$userModel->isEmailInUse($input);
				break;

			case 'validate-alphanum':
				return $this->_validate($input, 'required') && !preg_match('/[^A-Za-z0-9_]/', $input);
				break;

			case 'validate-email':
				return $this->_validate($input, 'required') && Email::isEmailAddress($input);
				break;

			case 'validate-passwordconfirm':
				return strlen($input[0]) > 0 && $input[0] == $input[1];
				break;

			case 'required':
				return (bool) $input;
				break;

			case 'username_used':
				return !$userModel->isUsernameInUse($input);
				break;

			case 'validate-captcha':
				return Captcha::checkCode($input);
				break;

			case 'validate-terms-required':
				return (bool) $input;
				break;

			case 'location':
				/* CAREFUL: RETURNS INT (or null), NOT BOOLEAN. */
				if (!$input){ return false; }
				$locationsModel = $this->getModel('locations');
				return $locationsModel->validate($input);
				break;

			default:
				return false;
				break;
		}
	}

	/**
	 *	Validation with messages.
	 *
	 *	@access protected
	 *	@param mixed 
	 *	@param string Type of input
	 *	@param string Message if invalid
	 *	@return bool
	 */
	final protected function _validateWithMessage($input, $type, $message)
	{
		$valid = $this->_validate($input, $type);
		if (!$valid){
			Messages::addMessage($message, 'error');
		}
		return $valid;
	}
	
	/**
	 *	Load top navigation
	 *
	 *	@access public
	 *	@param array Extra (overriding) variables
	 */
	public function topnav(array $displayVars = array())
	{
		// Get models
		$userModel = BluApplication::getModel('user');
		
		// Default topnav data (application details)
		Template::set(array(
			'top' => array_merge(array(
				'option' => BluApplication::getOption(),
				'task' => BluApplication::getTask(),
				'args' => BluApplication::getArgs(),
				'currentUser' => $userModel->getUser(),
				'breadcrumbs' => $this->_doc->getBreadcrumbs()
			), $displayVars)
		));
		
		// Clean up variables
		unset($userModel);
		extract(Template::get('top'));
		
		// Load template
		include(BLUPATH_TEMPLATES.'/nav/top.php');
	}
}

?>
