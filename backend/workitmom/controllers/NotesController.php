<?php

/**
 * Member blogs
 */
class WorkitmomNotesController extends WorkitmomItemsController {
	
	/**
	 *	Required by ItemsController.
	 */
	protected function _getType($key = null){
		$type = array(
			'key' => 'note',
			'singular' => 'Member Blog',
			'plural' => 'Member Blogs'
		);
		return Utility::multi_array_get($type, $key, $type);
	}
	
}

?>
