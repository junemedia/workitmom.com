<?php

/**
 * Contact Model
 *
 * @package BluApplication
 * @subpackage BluModels
 */
class WorkitmomContactModel extends BluModel
{

	public function saveContactForm($name, $email, $uid, $body, $subtype){
		$query = 'INSERT INTO supportmessages
					(supportMessageName, supportMessageEmail, supportMessageUserID,
						supportMessageDate, supportMessageUserAgent,
							supportMessageBody, supportMessageType, supportMessageSubType)
				VALUES ("'.Database::escape($name).'", "'.Database::escape($email).'", "'.Database::escape($uid).'",
							NOW(), "'.$_SERVER['HTTP_USER_AGENT'].'",
						"'.Database::escape($body).'", "contactform", "'.Database::escape($subtype).'")';
		$this->_db->setQuery($query);
		$this->_db->query();
	}

}