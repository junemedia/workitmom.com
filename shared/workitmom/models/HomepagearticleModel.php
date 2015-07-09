<?

class WorkitmomHomepageArticleModel extends BluModel{

	/**
	 *	Grab single record from database/cache/whatever.
	 */
	public function getArticle($id){
		try {
			return BluApplication::getObject('homepagearticle', (int)$id);
		} catch (NoDataException $exception){
			return null;
		}
	}

	/**
	 *	Returns three articles.
	 */
	public function getHomepageArticles($limit = 5)
	{
		// Grab ids
		$query = "SELECT hpid
			FROM homepageswitcher
				WHERE hpLive = 1
			ORDER BY hpOrder ASC";
		$records = $this->_fetch($query, null, 0, $limit);

		// Wrap and return
		return $this->_wrapArticles($records);
	}

	/**
	 *	Wrap into HomepagearticleObjects.
	 */
	private function _wrapArticles($recordset){
		$articles = new stdClass();
		if (Utility::is_loopable($recordset)){
			foreach($recordset as $record){
				if (!isset($articles->major)){
					$articles->major = $this->getArticle((int) $record['hpid']);
				} else {
					if (!isset($articles->minors)){ $articles->minors = array(); }
					$articles->minors[] = $this->getArticle((int) $record['hpid']);
				}
			}
		}
		return $articles;
	}

}

?>