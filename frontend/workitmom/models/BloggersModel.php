<?php

/**
 *	Frontend Bloggers Model.
 */
class WorkitmomFrontendBloggersModel extends WorkitmomBloggersModel {
	
	/**
	 *	Don't show blocked blogs.
	 *
	 *	Overrides WorkitmomBloggersModel.
	 */
	protected function _getBlogsDefaultQuery(){
		$default = parent::_getBlogsDefaultQuery();
		$default['where'][] = 'b.blogOrder != '.self::BLOG_ORDER_NOT_SHOWN;
		return $default;
	}
	
}

?>