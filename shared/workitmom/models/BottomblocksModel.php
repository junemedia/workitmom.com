<?

class WorkitmomBottomblocksModel extends BluModel {

	public function getBlocks() {
		$this->_db->setQuery('SELECT * FROM footerblocks ORDER BY id');
		$this->_db->query();
		return $this->_db->loadAssocList('id');
	}

}


?>