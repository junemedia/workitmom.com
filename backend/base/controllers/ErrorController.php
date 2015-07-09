<?php

/**
 *	Backend Error controller.
 */
class ErrorController extends BackendController {

	/**
	 *	Default 404 message
	 */
	public function fourOhFour(){
		echo 'Sorry, this page does not exist. Please <a href="javascript:history.back();">go back.</a>';
	}
	
}

?>