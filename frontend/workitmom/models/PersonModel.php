<?php

/**
 *	Frontend Bloggers Model.
 */
class WorkitmomFrontendPersonModel extends WorkitmomPersonModel {
	
	/**
	 *	Overrides WorkitmomPersonModel.
	 */
	public function getPerson(array $idarray){
		$person = parent::getPerson($idarray);
		return $person;
	}
	
}

?>