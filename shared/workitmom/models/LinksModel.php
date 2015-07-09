<?php

//NOT FINISHED
class WorkitmomLinksModel extends BluModel {


	/**
	 *	Return a LinkObject object.
	 */
	public function getLink($id) {
		try {
			return BluApplication::getObject('link', (int) $id);
		} catch (NoDataException $exception) {
			return null;
		}
	}

	/**
	 *	Same as wrapLinks[see below], but returns single LinkObject object, instead of an array of LinkObjects objects.
	 */
	private function wrapLink($recordset){
		$item = null;
		if (Utility::is_loopable($recordset)){
			$record = $recordset[0];
			$item = $this->getLink($record['linkID']);
		}
		return $item;
	}

	/**
	 *	If we have an arbitrary recordset (2d array) wrap them into LinkObjects.
	 *
	 *	@args (Array) recordset: an array containing records, which are themselves arrays containing keys...
	 *		linkID: id of link
	 *	@return (Array) an array of LinkObjects
	 */
	private function wrapLinks($recordset){
		$wrappeditems = array();
		if (Utility::is_loopable($recordset)){
			foreach($recordset as $record){
				$wrappeditems[] = $this->wrapLink(array($record));
			}
		}
		return $wrappeditems;
	}

	/**
	 *	Get all links that are linked to a specific landing page.
	 *
	 *	@args (int) landing page id.
	 *	@args (string) section of the landing page we want to get links from.
	 *	@return (array) a list of LinkObject objects.
	 */
	public function getLandingpageLinksBySection($landingpageid, $section){

		$landingpageid = (int) $landingpageid;

		// Get article details from cache/DB
		$records = $this->_cache->get('landingpagelinks_'.$landingpageid);
		if ($records === false){

			$this->_db->setQuery("SELECT `linkID` FROM `xreflandingpagelinks` WHERE `landingPageID`=".$landingpageid." AND `sectionType`='".$section."'");
			$records = $this->_db->loadAssocList();

		    // Store in cache
			$this->_cache->set('landingpagelinks_'.$landingpageid, $records);

		}

		//spit out
		return $this->wrapLinks($records);

	}

	/**
	 *	LANDING PAGE (ESSENTIALS) SPECIFIC
	 *
	 *	Get a list of all the possible section types of links associated with landing pages.
	 */
	public function getLandingpageSections(){

		// Get article details from cache/DB
		$sections = $this->_cache->get('landingpage_sectionTypes');
		if ($sections === false){

			//get from db
			$this->_db->setQuery('DESC `xreflandingpagelinks` `sectionType`');
			$descString = $this->_db->loadAssoc();

			// format into array
			$ar = explode(',', preg_replace('|^enum\((.*)\)$|', '\1', $descString['Type']));
			$sections=array();
			foreach ($ar as $k=>$v){
				$sections[] = str_replace("'",'',$v);
			}

		    // Store in cache
			$this->_cache->set('landingpage_sectionTypes', $sections);

		}

		// spit out
		return $sections;

	}


	public function setLink($id, $linkTitle, $linkUrl) {
		$query = 'REPLACE INTO links (linkID, linkTitle, linkUrl)
					VALUES ("'.$id.'", "'.$linkTitle.'", "'.$linkUrl.'")
					WHERE linkID = "'.$id.'"';
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

}

?>