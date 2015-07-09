<?php

class WorkitmomPollsModel extends BluModel {



	/**
	 *	Get a single poll
	 */
	public function getPoll($id){
		return BluApplication::getObject('poll', (int)$id);
	}

	/**
	 *	Gets the latest polls.
	 *
	 *	@return an array.
	 */
	public function getLatest($offset = 0, $limit = 1){

		// Get poll ids
		$query = 'SELECT p.pollId
			FROM polls AS p
			ORDER BY p.pollDate DESC';
		$this->_db->setQuery($query, $offset, $limit);
		$polls = $this->_db->loadAssocList();
		if (!$polls) {
			return false;
		}

		// Get poll details
		foreach ($polls as &$poll) {
			$poll = BluApplication::getObject('poll', $poll['pollId']);
		}

		// This is mighty unpleasant...
		if ($limit == 1){
			$polls = array_shift($polls);
		}

		return $polls;
	}

}

?>