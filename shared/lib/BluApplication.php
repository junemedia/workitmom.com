<?php

/**
 * BluApplication BluApplication
 *
 * @package BluApplication
 * @subpackage SharedLib
 */
class BluApplication
{
	/**
	 *	Backend switch.
	 */
	const BACKEND_SWITCH = 'oversight';
	
	/**
	 *	Default frontend controller
	 */
	const DEFAULT_FRONTEND_CONTROLLER = 'index';
	
	/**
	 *	Default backend controller.
	 */
	const DEFAULT_BACKEND_CONTROLLER = 'admin';
	
	/**
	 * Requested uri
	 *
	 * @var string
	 */
	private static $_uri;

	/**
	 * Requested argumemts (parsed from URL)
	 *
	 * @var array
	 */
	private static $_args;

	/**
	 * Ad config array
	 *
	 * @var array
	 */
	private static $_ads;

	/**
	 * Requested config (DEBUG only)
	 *
	 * @var string
	 */
	private static $_configName = false;

	/**
	 * Requested language
	 *
	 * @var string
	 */
	private static $_languageCode;

	/**
	 * Requested currency
	 *
	 * @var string
	 */
	private static $_currencyCode;

	/**
	 * Requested option (i.e. controller)
	 *
	 * @var string
	 */
	private static $_option = self::DEFAULT_FRONTEND_CONTROLLER;

	/**
	 * Requested task (i.e. controller method)
	 *
	 * @var string
	 */
	protected static $_task = 'view';

	/**
	 * Redirect url requested by dispatched controller
	 *
	 * @var string
	 */
	private $_redirect = false;

	/**
	 * Breadcrumbs.
	 *
	 * @var string
	 */
	private static $_breadcrumbs = null;
	
	/**
	 *	Settings
	 *
	 *	@access private
	 *	@var array
	 */
	private static $_settings;

	/**
	 * BluApplication object constructor
	 */
	private function __construct()
	{

		// Register error handler
		register_shutdown_function(array(new Error(),'shutdownHandler'));
		set_exception_handler(array(new Error(), 'handleException'));
		set_error_handler(array(new Error(),'handleError'));



		// Get config name from domain if in DEBUG mode
		//if (DEBUG) {
		//	$subDomains = explode('.', $_SERVER['HTTP_HOST']);
		//	if (file_exists(BLUPATH_BASE.'/config.'.$subDomains[1].'.php')) {
		//		self::$_configName = $subDomains[1];
		//	}
		//}

		// Get arguments from request URI
		$baseUrl = self::getSetting('baseUrl');
		self::$_uri = preg_replace('$^'.$baseUrl.'/$', '', $_SERVER['REQUEST_URI']).'/';
		$args = Request::getString('args', self::$_uri);
		$qsPos = strpos($args, '?');
		if ($qsPos !== FALSE) {
			$args = substr($args, 0, $qsPos);
		}
		$args = trim($args, '/');
		if (!empty($args)) {
			$args = explode('/', trim($args, '/'));
		}

		// Detect site-end and set default controller
		if (!empty($args) && ($args[0] == self::BACKEND_SWITCH)) {
			array_shift($args);
			define('SITEEND', 'backend');
			self::$_option = self::DEFAULT_BACKEND_CONTROLLER;
		} else {
			define('SITEEND', 'frontend');
		}

		// Set up session
		Session::start();

		// Set default timezone
		date_default_timezone_set(self::getSetting('timeZone'));

		// Get current site ID
		$siteId = self::getSetting('siteId');

		// Get visitor country
		$visitorCountry = Country::getVisitorCountry();

		// Shift language from arguments
		if (!empty($args) && Language::isValidLanguageCode(strtolower($args[0]))) {
			$languageCode = array_shift($args);
		} elseif (Language::isValidLanguageCode($visitorCountry['countryLangCode'])) {
			$languageCode = $visitorCountry['countryLangCode'];
		} else {
			$languageCode = self::getSetting('defaultLang', 'en');
		}
		self::$_languageCode = strtolower($languageCode);

		// Site URLs (these are defined for convenience as they are used *everywhere*)
		$baseUrl = self::getSetting('baseUrl');
		define('SITEURL', $baseUrl.(SITEEND == 'backend' ? '/'.self::BACKEND_SWITCH : '').($languageCode == 'EN' ? '' : '/'.strtolower($languageCode)));
		define('FRONTENDSITEURL', $baseUrl.($languageCode == 'EN' ? '' : '/'.strtolower($languageCode)));
		define('SITESECUREURL', 'https://'.$_SERVER['HTTP_HOST'].SITEURL);
		define('SITEINSECUREURL', 'http://'.$_SERVER['HTTP_HOST'].SITEURL);
		define('FRONTENDSITESECUREURL', 'https://'.$_SERVER['HTTP_HOST'].FRONTENDSITEURL);
		define('FRONTENDSITEINSECUREURL', 'http://'.$_SERVER['HTTP_HOST'].FRONTENDSITEURL);
		define('ASSETURL', $baseUrl.'/assets');
		define('SITEASSETURL', $baseUrl.'/'.SITEEND.'/'.$siteId);
		define('COREASSETURL', $baseUrl.'/'.SITEEND.'/base');

		// Do redirects based upon site specific rules
		if ($redirect = Request::getRedirect(self::$_uri)) {   // This should be better (use document)
			header('Location: '.$redirect['destinaton'], true, $redirect['responseCode']);
			die();
		}

		// Determine controller
		$option = strtolower(Request::getString('controller', empty($args[0]) ? '' : $args[0]));
		if ($option && ($option != 'frontend') && ($option != 'backend')) {
			$controllerName = ucfirst($option).'Controller';
			if (file_exists(BLUPATH_BASE.'/'.SITEEND.'/base/controllers/'.$controllerName.'.php') || file_exists(BLUPATH_BASE.'/'.SITEEND.'/'.$siteId.'/controllers/'.$controllerName.'.php')) {
				if (!empty($args) && ($option == $args[0])) {
					array_shift($args);
				}
				self::$_option = $option;
			}
		}

		// Determine controller name
		$controllerName = ucfirst(strtolower(self::$_option)).'Controller';
		$controllerName = self::_includeController(self::$_option);

		// Shift task from arguments
		if (!empty($args)) {
			$task = strtolower($args[0]);
			if (method_exists($controllerName, $task)) {
				array_shift($args);
				self::$_task = $task;
			}
		}

		// Override task in request?
		if ($task = Request::getString('task')) {
			$task = strtolower($task);
			if (method_exists($controllerName, $task)) {
				self::$_task = $task;
			}
		}

		// Store remaining arguments
		self::$_args = $args;

		// Set template path
		define('BLUPATH_TEMPLATES', BLUPATH_BASE.'/'.SITEEND.'/'.$siteId.'/templates');
		define('BLUPATH_BASE_TEMPLATES', BLUPATH_BASE.'/'.SITEEND.'/base/templates');
		define('BLUPATH_LANGUAGE', BLUPATH_BASE.'/'.SITEEND.'/'.$siteId.'/languages/'.strtolower($languageCode));
		define('BLUPATH_ASSETS', BLUPATH_BASE.'/assets/'.$siteId);
		define('BLUPATH_CACHE', BLUPATH_BASE.'/cache/'.$siteId);

		// Include ads.
		include_once(BLUPATH_BASE.'/openads/adconfig.php');
		self::$_ads = OpenX::$zoneMapping;

	}

	/**
	 * Returns a reference to the global {@link BluApplication} object, only creating it
	 * if it doesn't already exist
	 */
	public static function &getInstance()
	{
		static $instance;
		if (!$instance) {
			$c = __CLASS__;
			$instance = new $c();
		}
		return $instance;
	}

	/**
	 * Get configuration setting
	 *
	 * @param string Configuration setting name
	 *	@param mixed Fallback value
	 * @return mixed Configuration setting value
	 */
	public static function getSetting($item, $default = null)
	{
		// Load configuration settings
		if (empty(self::$_settings)) {

			// Load static config
			$config = new Config();
			self::$_settings = get_object_vars($config);
			
			$siteId = self::$_settings['siteId'];

			// Load database settings
			$cache = self::getCache();
			$dbSettings = $cache->get('settings');
			if ($dbSettings === false) {
				$db = self::getDatabase();
				
				// Get for site specific if there is an entry in siteId - new clients
				$query = 'SELECT c.* 
					FROM `config` AS `c`
					WHERE c.siteId = "'.$db->escape($siteId).'"';
				$db->setQuery($query);
				$dbSettings = $db->loadResultAssocArray('configKey', 'configValue');
				
				// if there is no entry in config siteId column get everything what is blank - old clients
				if (empty($dbSettings)) {
					$query = 'SELECT c.* 
						FROM `config` AS `c`
						WHERE c.siteId = ""';
					$db->setQuery($query);
					$dbSettings = $db->loadResultAssocArray('configKey', 'configValue');
				}

				// Unserialize all settings
				foreach ($dbSettings as &$dbSetting) {
					$dbSetting = unserialize($dbSetting);
				}
				
				// Store in cache
				$cache->set('settings', $dbSettings);
			}
			
			// One settings array to rule them all
			self::$_settings = array_merge(self::$_settings, $dbSettings);
		}

		// Return setting, or default if not set
		if (strpos($item, ':')) {
			list($parentItem, $childItem) = explode(':', $item);
			return isset(self::$_settings[$parentItem][$childItem]) ? self::$_settings[$parentItem][$childItem] : $default;
		} else {
			return isset(self::$_settings[$item]) ? self::$_settings[$item] : $default;
		}
	}

	/**
	 * Get the global database object
	 *
	 * @return object Database
	 */
	public static function &getDatabase()
	{
		static $instance;
		if (!is_object($instance)) {
			$instance = Database::getInstance(self::getSetting('databaseHost'), self::getSetting('databaseUser'), self::getSetting('databasePass'), self::getSetting('databaseName'));
		}
		return $instance;
	}

	/**
	 * Get the global cache object
	 *
	 * @return object Cache
	 */
	public static function &getCache()
	{
		static $instance;
		if (!is_object($instance)) {
			$instance = Cache::getInstance(self::getSetting('memcacheHost'), self::getSetting('memcachePort'));
		}
		return $instance;
	}

	/**
	 * Get the global language object
	 *
	 * @return object Language
	 */
	public static function &getLanguage()
	{
		static $instance;

		// Determine language to use and create language object
		if (!is_object($instance)) {
			$instance = Language::getInstance(self::$_languageCode);
		}
		return $instance;
	}

	/**
	 * Get a data model object
	 *
	 * @param string Model name
	 * @return FrontendModel Model instance
	 */
	public static function &getModel($name)
	{
		static $instances;
		if (!isset($instances)) {
			$instances = array();
		}

		// Get model name
		$name = ucfirst(strtolower($name)).'Model';
		$foundName = $name;

		// Get model arguments
		$args = func_get_args();
		$args = array_slice($args, 1);
		$signature = $name.'_'.serialize($args);

		// Load model instance
		if (empty($instances[$signature])) {
			$siteId = self::getSetting('siteId');

			// Include base shared model
			require_once(BLUPATH_BASE.'/shared/base/models/BluModel.php');

			// Include shared model if exists
			$sharedModelPath = BLUPATH_BASE.'/shared/base/models/'.$name.'.php';
			if (file_exists($sharedModelPath)) {
				require_once($sharedModelPath);
				$foundName = $name;
			}

			// Include site shared model if exists
			$siteSharedModelPath = BLUPATH_BASE.'/shared/'.$siteId.'/models/'.$name.'.php';
			if (file_exists($siteSharedModelPath)) {
				require_once($siteSharedModelPath);
				$foundName = ucfirst($siteId).$name;
			}

			// Include end model if exists
			$endModelPath = BLUPATH_BASE.'/'.SITEEND.'/base/models/'.$name.'.php';
			if (file_exists($endModelPath)) {
				require_once($endModelPath);
				$foundName = ucfirst(SITEEND).$name;
			}

			// Load site model if exists
			$siteModelPath = BLUPATH_BASE.'/'.SITEEND.'/'.$siteId.'/models/'.$name.'.php';
			if (file_exists($siteModelPath)) {
				require_once($siteModelPath);
				$foundName = ucfirst($siteId).ucfirst(SITEEND).$name;
			}

			// Get model instance
			$reflectionObj = new ReflectionClass($foundName);
			$instances[$signature] = $reflectionObj->newInstanceArgs($args);
		}
		return $instances[$signature];
	}

	/**
	 * 	Get a data model object - pretty much a direct copy from getModel function.
	 *	The need for this is that we need to separate classes representing real-world concepts from classes that control them, even though both types need access to the database and thus both categorise as models.
	 *
	 * 	@param string Model name
	 * 	@return BluObject Model instance
	 */
	public static function getObject($name)
	{
		// Get model name
		$name = ucfirst(strtolower($name)).'Object';

		// Get model arguments
		$args = func_get_args();
		$args = array_slice($args, 1);

		// Load site-specific model if exists
		$siteId = self::getSetting('siteId');
		$siteObjectPath = BLUPATH_BASE.'/'.SITEEND.'/'.$siteId.'/models/objects/'.$name.'.php';
		if (file_exists($siteObjectPath)) {
			require_once ($siteObjectPath);
			$name = ucfirst($siteId).$name;
		}

		// Get model instance
		$reflectionObj = new ReflectionClass($name);
		return $reflectionObj->newInstanceArgs($args);

	}

	/**
	 * Include source files required for the given plugin
	 *
	 * @param string Plugin type
	 * @param string Plugin id
	 * @return string Plugin class name
	 */
	private static function _includePlugin($type, $name)
	{
		$type = strtolower($type);
		$name = ucfirst(strtolower($name));
		
		// Include base plugin
		require_once(BLUPATH_BASE.'/shared/plugins/Plugin.php');
		
		// Include type base plugin
		$typeBaseFile = BLUPATH_BASE.'/shared/plugins/'.$type.'/'.ucfirst($type).'.php';
		if (!file_exists($typeBaseFile)) {
			return false;
		}
		require_once($typeBaseFile);
		
		// Include the plugin itself
		$pluginFile = BLUPATH_BASE.'/shared/plugins/'.$type.'/'.ucfirst($type).$name.'.php';
		if (!file_exists($pluginFile)) {
			return false;
		}
		require_once($pluginFile);
		
		// Return class name
		return ucfirst($type).$name;
	}

	/**
	 * Get a plugin object
	 *
	 * @param string Plugin name
	 * @return Plugin Plugin instance
	 */
	public static function &getPlugin($type, $name)
	{
		static $instances;
		if (!isset($instances)) {
			$instances = array();
		}

		// Get plugin name
		$type = ucfirst(strtolower($type));
		$name = ucfirst(strtolower($name));
		$pluginName = $type.$name;

		// Get plugin arguments
		$args = func_get_args();
		$args = array_slice($args, 2);
		$signature = $pluginName.'_'.serialize($args);

		// Load plugin instance
		if (empty($instances[$signature])) {
self::_includePlugin($type, $name);	// HACK, PLEASE REMOVE ONCE PLUGINS ARE UP TO DATE AGAIN
			$reflectionObj = new ReflectionClass($pluginName);
			$instances[$signature] = $reflectionObj->newInstanceArgs($args);
		}

		return $instances[$signature];
	}
	
	/**
	 * Include source files required for the given controller
	 *
	 * @param string Controller name
	 * @return string The name of the found controller, or false
	 */
	private static function _includeController($name)
	{
		// Get controller name
		$name = ucfirst(strtolower($name)).'Controller';
		$foundName = false;

		// Prepare
		$siteId = self::getSetting('siteId');

		// Include end base controller
		require_once(BLUPATH_BASE.'/'.SITEEND.'/base/controllers/'.ucfirst(SITEEND).'Controller.php');
		
		// Include client-specific end base controller
		$baseControllerPath = BLUPATH_BASE.'/'.SITEEND.'/base/controllers/Client'.ucfirst(SITEEND).'Controller.php';
		$siteBaseControllerPath = BLUPATH_BASE.'/'.SITEEND.'/'.$siteId.'/controllers/Client'.ucfirst(SITEEND).'Controller.php';		
		if (file_exists($siteBaseControllerPath)) {
			require_once($siteBaseControllerPath);
		} elseif (file_exists($baseControllerPath)) {
			require_once($baseControllerPath);
		}

		// Include end controller if exits
		$endControllerPath = BLUPATH_BASE.'/'.SITEEND.'/base/controllers/'.$name.'.php';
		if (file_exists($endControllerPath)) {
			require_once($endControllerPath);
			$foundName = $name;
		}

		// Load site-specific end controller if exists
		$siteControllerPath = BLUPATH_BASE.'/'.SITEEND.'/'.$siteId.'/controllers/'.$name.'.php';
		if (file_exists($siteControllerPath)) {
			require_once ($siteControllerPath);
			$foundName = ucfirst($siteId).$name;
		}
		
		return $foundName;
	}

	/**
	 * Get a controller instance
	 *
	 * @param string Controller name
	 * @return FrontendController Controller instance
	 */
	public static function &getController($name, $args = null)
	{
		// Include controller
		$foundName = self::_includeController($name);
		if (!$foundName) {
			return false;
		}

		// Get controller instance
		$controller = new $foundName($args);
		return $controller;
	}

	/**
	 * Get a module helper object
	 *
	 * @param string name
	 * @return Modules instance
	 */
	public static function &getModules($name)
	{
		static $instances;
		if (!isset($instances)) {
			$instances = array();
		}

		// Get modules-helper name
		$name = ucfirst(strtolower($name)).'Modules';

		// Get modules-helper arguments
		$args = func_get_args();
		$args = array_slice($args, 1);
		$signature = $name.'_'.serialize($args);

		// Load modules-helper instance
		if (empty($instances[$signature])) {
			// Load site-specific modules-helper if exists
			$siteId = self::getSetting('siteId');
			$siteModulesPath = BLUPATH_BASE.'/'.SITEEND.'/'.$siteId.'/modules/'.$name.'.php';
			if (file_exists($siteModulesPath)) {
				require_once ($siteModulesPath);
				$name = ucfirst($siteId).$name;
			}

			// Get modules-helper instance
			$reflectionObj = new ReflectionClass($name);
			$instances[$signature] = $reflectionObj->newInstanceArgs($args);
		}
		return $instances[$signature];
	}

	/**
	 * Get the global document object
	 *
	 * @return object Document
	 */
	public static function &getDocument()
	{
		static $instance;
		if (!is_object($instance)) {
			$format = Request::getString('format', 'site');
			$instance = Document::getInstance($format);
		}
		return $instance;
	}

	/**
	 * Shortcut to get the current user
	 *
	 * @return PersonObject or null
	 */
	public static function getUser()
	{
		$userModel = self::getModel('user');
		return $userModel->getUser();
	}

	/**
	 * Get the requested arguments
	 *
	 * @return array Array of arguments
	 */
	public static function getArgs()
	{
		return self::$_args;
	}

	/**
	 * Get available adverts
	 *
	 * @return array List of adverts
	 */
	public static function getAds()
	{
		return self::$_ads;
	}

	/**
	 * Get the option requested
	 *
	 * @return string The current option name
	 */
	public static function getOption()
	{
		return self::$_option;
	}

	/**
	 * Get the task requested
	 *
	 * @return string The current task name
	 */
	public static function getTask()
	{
		return self::$_task;
	}

	/**
	 * Get the associated breadcrumb trail.
	 *
	 * @return string
	 */
	public static function getBreadcrumbs()
	{
		return self::$_breadcrumbs;
	}

	/**
	 * Dispatches the application
	 *
	 * Pulls the relevant options from the request and calls the correct
	 * controller method.
	 */
	public function dispatch()
	{
		// Start breadcrumb instance
		self::$_breadcrumbs = new Breadcrumbs();

		// Buffer output
		ob_start();

		// Render controller
		$controller = $this->getController(self::getOption(), self::getArgs());
		$taskName = self::$_task;
		$controller->$taskName();

		// Get redirect if requested
		$this->_redirect = $controller->getRedirect();

		// Set document contents
		$document = $this->getDocument();
		$document->setContents(ob_get_clean());
		$document->setBreadcrumbs(self::$_breadcrumbs);
		
		// Get top nav, after main controller task has been completed.
		switch ($document->getFormat()) {
			case 'site':
			case 'print':
				ob_start();
				$controller->topnav();
				$document->setContents(ob_get_clean(), 'topnav');
				break;
		}
	}

	/**
	 * Renders the BluApplication
	 */
	public function render()
	{
		// Redirect if requested
		if ($this->_redirect) {

			// Append referrer for return redirections from login
			$redirect = str_replace('return=1', 'redirect='.base64_encode(self::$_uri), $this->_redirect);
			$url = ($_SERVER['SERVER_PORT'] == 80 ? 'http://' : 'https://');
			$url.= $_SERVER['SERVER_NAME'].SITEURL.$redirect;
			header('Location: '.$url);
			return;
		}
		
		// Force caching-off for backend.
		if (SITEEND == 'backend'){
			header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		}

		// Get document
		$document = $this->getDocument();
		$format = $document->getFormat();

		// Buffer output (optionally using Gzip)
		$useGzip = $format == 'asset' ? false : self::getSetting('useGzip', true);
		$bufferCallback = ($useGzip ? 'ob_gzhandler' : null);
		ob_start($bufferCallback);

		// Render document
		$document->render();

		// Flush buffer
		ob_flush();
	}
}
?>
