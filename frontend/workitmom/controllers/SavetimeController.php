<?php


class WorkitmomSaveTimeController extends ClientFrontendController {

	/**
	 *	Overrides FrontendController
	 */
	protected function _addBreadcrumb(){
		$this->_uri = '/savetime';
		BluApplication::getBreadcrumbs()->add('Save time', $this->_uri . '/');
	}

	/**
	 * Display home page
	 */
	public function view()
	{	
		/* Set page title */
		$this->_doc->setTitle('Save Time');
		$this->_doc->setAdPage(OpenX::PAGE_QUICKTIPS);
		
		/* Load template */
		include (BLUPATH_TEMPLATES . '/savetime/landing.php');
	}
	
	/**
	 *	Landing page: quicktips block
	 */
	private function landing_quicktips(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$quicktips = $itemsModel->getIndexFeatured('quicktip', 0, 3);
		if (!Utility::is_loopable($quicktips)) { return false; }
		
		/* Load template*/
		include(BLUPATH_TEMPLATES . '/savetime/landing/quicktips.php');
		
	}
	
	/**
	 *	Landing page: essentials block
	 */
	private function landing_essentials(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$essentials = $itemsModel->getIndexFeatured('landingpage', 0, 3);
		if (!Utility::is_loopable($essentials)) { return false; }
		
		/* Load template*/
		include(BLUPATH_TEMPLATES . '/savetime/landing/essentials.php');
		
	}
	
	/**
	 *	Landing page: recipes block
	 */
	private function landing_recipes(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$recipes = $itemsModel->getLatest('recipe', 0, 3);	
		if (!Utility::is_loopable($recipes)) { return false; }
		
		/* Load template*/
		include(BLUPATH_TEMPLATES . '/savetime/landing/recipes.php');
		
	}
	
	/**
	 *	Landing page: checklists block
	 */
	private function landing_checklists(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$checklists = $itemsModel->getIndexFeatured('list', 0, 3);	
		if (!Utility::is_loopable($checklists)) { return false; }		
		
		/* Load template*/
		include(BLUPATH_TEMPLATES . '/savetime/landing/checklists.php');
		
	}
	
	/**
	 *	Landing page: block data.
	 */
	private function landing_block_data(array $items){
		include(BLUPATH_TEMPLATES . '/savetime/landing/blocks/featured.php');
	}

}


?>
