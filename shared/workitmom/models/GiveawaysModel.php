<?php

/**
 *	Giveaways.
 */
class WorkitmomGiveawaysModel extends BluModel {
	
	/**
	 *	The item ID.
	 */
	const GIVEAWAY_ITEM_ID = 1200;
	
	/**
	 *	Get giveaway (which is actually an item).
	 */
	public function getGiveaway(){
		
		/* Get item */
		$itemsModel = $this->getModel('newitems');
		$giveaway = $itemsModel->getItem(self::GIVEAWAY_ITEM_ID);
		
		/* Return */
		return $giveaway;
		
	}
	
	/**
	 *	Set all comments to deleted.
	 */
	public function delete_comments(){
		
		/* Build query */
		$query = 'UPDATE `comments`
			SET commentDeleted = 1 
			WHERE commentType = "article"
				AND commentTypeObjectId = '.self::GIVEAWAY_ITEM_ID;
		$this->_db->setQuery($query);
		return $this->_db->loadSuccess();
		
	}
	
}

?>