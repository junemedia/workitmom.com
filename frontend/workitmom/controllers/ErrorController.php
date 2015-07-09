<?php

/**
 * Error Controller
 *
 * @package BluApplication
 * @subpackage FrontendControllers
 */
class WorkitmomErrorController extends ClientFrontendController
{
	/*
	 * Generic error page
	 */
	public function view()
	{
		echo 'ERROR CONTROLLER';
	}

	/**
	 * 404 page
	 */
	public function fourOhFour()
	{
		// Add to breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Page not found', '/error/404');

		// Load 404 page template
		$this->_doc->setStatus('HTTP/1.0 404 Not Found');
		include(BLUPATH_TEMPLATES.'/error/landing.php');
	}

}

?>
