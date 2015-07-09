<?

class WorkitmomDailyinspirationModel extends BluModel {

	public function getDailyInspiration() {
		$this->_db->setQuery('SELECT * FROM dailyinspiration ORDER BY id');
		$this->_db->query();
		return $this->_db->loadAssocList('id');
	}

}


?>