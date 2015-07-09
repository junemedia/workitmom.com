<?php

/**
 * Front end controller base class
 *
 * @package BluApplication
 * @subpackage FrontendControllers
 */
abstract class ClientFrontendController extends FrontendController
{
	/**
	 *	Shortcut.
	 *
	 *	@static
	 *	@access protected
	 *	@param string Model name
	 *	@return BluModel
	 */
	final protected static function &getModel($name)
	{
		return BluApplication::getModel($name);
	}

	/**
	 *	Saves the current page for later.
	 *
	 *	@access protected
	 */
	final protected function _setReferer()
	{
		// The $_SERVER variable to determine referral page
		$key = 'REQUEST_URI';

		// (Try to) store the referring URL
		$referer = array_key_exists($key, $_SERVER) ? $_SERVER[$key] : null;
		if ($referer) {
			Session::set('referer', $referer);
		}
	}

	/**
	 *	Checks if current user is set.
	 *	Used for pages where a user is required in order to continue.
	 *
	 *	@access protected
	 *	@param string Message if no user
	 *	@return bool
	 */
	final protected function _requireUser($message = null)
	{
		/* Do redirect or not. */
		if ($user = BluApplication::getUser()) {

			/* Retrieve previous snapshot of request variables */
			Request::fetchSnapshot();

			/* Continue with call stack. */
			return $user;

		} else {
			/* Save the current page request. */
			$this->_setReferer();
			Request::takeSnapshot();

			/* Redirect to login page. */
			if (!$message) {
				$message = 'Please login or register.';
			}
			$this->_redirect('/account', $message);

			/* Return "no-user". Caller function should ideally die afterwards. */
			return false;
		}
	}
	
	/**
	 *	Require user, and require them to be an admin too.
	 *
	 *	@access protected
	 *	@param string Message if no admin
	 *	@return bool
	 */
	final protected function _requireAdministrator($message = null)
	{
		// Get user
		if (!$user = $this->_requireUser($message)) {
			return false;
		}
		
		// Check administrative credentials
		if (!$user->isAdmin()) {
			$this->_redirect('/account');
			$user = false;
		}
		
		// Return administrator
		return $user;
	}

	/**
	 *	Add the controller-specific breadcrumb.
	 *
	 *	@access protected
	 */
	protected function _addBreadcrumb()
	{
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add($this->_controllerName, '/'.strtolower($this->_controllerName));
	}

	/**
	 *	Sidebar.
	 *
	 *	@access protected
	 *	@param array Names of modules to load
	 *	@param bool Whether the prepend the default modules too.
	 */
	final protected function sidebar(array $modulesToLoad = array(), $prependDefault = true, $currentPage = 'all')
	{
		/* Prepend default modules too. */
		if ($prependDefault){
			$defaultModules = array('newsletter', array('ad_mini', $this->_doc->getAdPage()));
			$modulesToLoad = array_merge($defaultModules, $modulesToLoad);
		}
		
		//Lock Right Rail - it should be the same on every page
		$modulesToLoad = array(
				array('ad_zedo','index'),
				'from_our_bloggers',
				'slideshow_featured',				
				'ad_ntent_right',
				array('ad_mini','index'),
				/*'indulge_yourself',*/
				'catch_your_breath'
			);
		if($currentPage=='home')
		{
			$modulesToLoad = array(
				array('ad_zedo','index'),
				'from_our_bloggers',
				'slideshow_featured',
				array('ad_mini','index'),
				'catch_your_breath'
			);
		}

		/* Commonly used stuff */
		$controller = $this->_controllerName;
		$user = BluApplication::getUser();
		$siteModules = BluApplication::getModules('site');

		/* Get and apply options */
		$options = Utility::array_pop($modulesToLoad, 'options');
		if (isset($options['category'])){
			$siteModules->set('category', $options['category']);
		}

		/* Catch empty */
		if (!Utility::iterable($modulesToLoad)){
			return false;
		}

		/* Modules */
		foreach ($modulesToLoad as $module) {

			/* Parse */
			if (Utility::iterable($module)){
				$moduleName = array_shift($module);			// First element is name.
				$moduleArgs = Utility::flatten($module);	// Everything else gets flattened into a one-dimensional array: extra data.
			} elseif ($module) {
				$moduleName = $module;
				$moduleArgs = array();
			}

			/* Include module template */
			include(BLUPATH_TEMPLATES . '/nav/sidebar/'.$moduleName.'.php');
		}
	}
	
	/**
	 *	Get user alerts too
	 *
	 *	@access public
	 *	@param array
	 */
	public function topnav(array $displayVars = array())
	{
		// Get user alerts
		$alertsModel = BluApplication::getModel('alerts');
		if ($user = BluApplication::getUser()) {
			$alerts = $alertsModel->getUserAlerts($user->userid);
			$displayVars['numAlerts'] = $alerts ? count($alerts) : 0;
		}
		
		// Continue
		return parent::topnav($displayVars);
	}
}

?>
