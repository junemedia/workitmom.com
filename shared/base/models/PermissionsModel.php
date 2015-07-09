<?php

/**
 * Permissions Model
 *
 * @package BluApplication
 * @subpackage BluModels
 */
class PermissionsModel extends BluModel {

	/**
	 * Check if IP is banned
	 *
	 * @param string IP address
	 * @return bool True/false
	 */
	public function isBannedIP($ip)
	{
		$query = 'SELECT * FROM ipblocking WHERE dcip1 <= "'.$ip.'" AND dcip2 >= "'.$ip.'"';
		$this->_db->setQuery($query);
		$this->_db->query();
		return ($this->_db->getFoundRows() > 0);
	}
}
