<?php


class WorkitmomDestressController extends WorkitmomCategoriesController {

	/**
	 *	Overrides FrontendController.
	 */
	protected function _addBreadcrumb(){
		$this->_uri = '/destress';
		BluApplication::getBreadcrumbs()->add('De-stress', $this->_uri . '/');
	}

	/**
	 * Display home page
	 */
	public function view()
	{
		/* Get arguments */
		$args = $this->_args;

		/* Get models. Get data too. */
		$itemsModel = $this->getModel('items');
		$articles = array(
			'justforyou' 		=>	$itemsModel->set('category', 'Just for You')->getIndexFeatured('article', 0, 3)
		);
		$itemsModel->set('category', null);
		
		/* Get site modules */
		$siteModules = BluApplication::getModules('site');

		/* Set page title */
		$this->_doc->setTitle('De-stress');
		$this->_doc->setAdPage(OpenX::PAGE_CONTEST);

		/* Load page template */
		include (BLUPATH_TEMPLATES . '/destress/landing.php');
	}

	/**
	 *	Landing page - block of featured blogs.
	 */
	private function landing_category_block($articles){

		/* Load common template */
		include(BLUPATH_TEMPLATES . '/destress/landing/articles.php');

	}

	/**
	 *	Landing page: "Just for you" block.
	 */
	private function landing_just_for_you($articles){

		/* Get data */
		$link = SITEURL . '/destress/just_for_you';

		/* Load template */
		include(BLUPATH_TEMPLATES . '/destress/landing/just_for_you.php');

	}

	/**
	 *	Landing page: 'Poll' block.
	 */
	private function landing_poll(){

		/* Get models */
		$pollsModel = $this->getModel('polls');

		/* Get data */
		$poll = $pollsModel->getLatest();

		/* Load template */
		include(BLUPATH_TEMPLATES.'/destress/landing/poll.php');

	}

	/**
	 *	"Just For You" category landing page.
	 */
	public function just_for_you(){

		/* Prepare page. */
		$this->_categorySlug = __FUNCTION__;
		$this->_category = 'Just For You';

		/* Add breadcrumb */
		$this->_addBreadcrumb();
		BluApplication::getBreadcrumbs()->add($this->_category, $this->_uri . '/' . __FUNCTION__ . '/');

		/* Display page */
		$this->_categoryPage();

	}

	/**
	 *	Polls tasks / browse page.
	 */
	public function polls() {

		/* Redirect */
		if ($subtask = Request::getString('subtask')){
			switch(strtolower($subtask)){
				case 'vote':
					return $this->poll_vote();
					break;

				case 'results':
					return $this->poll_results();
					break;

				case 'discuss':
					return $this->poll_discuss();
					break;

				default:
					break;
			}
		}

		/* Arguments */
		$args = $this->_args;

		/* Add breadcrumb */
		$this->_uri .= '/polls';
		BluApplication::getBreadcrumbs()->add('De-stress', '/destress/');
		BluApplication::getBreadcrumbs()->add('Polls', '/polls/');

		/* Set page title */
		$this->_doc->setTitle('Polls');
		$this->_doc->setAdPage(OpenX::PAGE_CONTEST);

		/* Load template */
		include_once(BLUPATH_TEMPLATES . '/destress/polls.php');

	}



	/**
	 *	Ajax request for placing a vote.
	 */
	private function poll_vote() {

		/* Get model */
		$pollsModel = $this->getModel('polls');

		/* Get poll object */
		$pollid = Request::getInt('poll_id');
		if (!$pollid){ $this->_errorRedirect(); }
		$poll = $pollsModel->getPoll($pollid);

		/* Get parameters */
		$answerid = Request::getInt('answer_id');
		if (!$answerid){ $this->_errorRedirect(); }

		/* Vote on it */
		$success = (bool) $poll->vote($answerid);

		/* Output */
		switch($this->_doc->getFormat()){
			case 'json':
				echo json_encode($success);
				break;

			default:
				break;
		}

		/* Exit */
		return true;

	}

	/**
	 *	Ajax request for results page section.
	 */
	private function poll_results() {

		// Get models
		$pollsModel = $this->getModel('polls');

		// Get request data
		$pollID = Request::getInt('poll_id');

		// Get output data
		$poll = $pollsModel->getPoll($pollID);

		/* Output data */
		switch($this->_doc->getFormat()){
			case 'json':

				/* Format */
				$results = array();
				foreach($poll->answers as $answer){
					$results[] = array(
						'text' => $answer->text,
						'votes' => $answer->votes
					);
				}

				/* Output */
				echo json_encode($results);
				break;

			default:

				/* Load template */
				include_once(BLUPATH_TEMPLATES . '/destress/polls/results.php');
				break;
		}

	}

	/**
	 *	Polls page: featured poll.
	 */
	private function polls_featured(){

		/* Load model */
		$pollsModel = $this->getModel('polls');

		/* Get data */
		$poll = $pollsModel->getLatest();

		/* Load template */
		include(BLUPATH_TEMPLATES.'/destress/polls/featured.php');

	}

	/**
	 *	Polls page: latest polls.
	 */
	private function polls_latest(){

		/* Get model */
		$pollsModel = $this->getModel('polls');

		/* Get data */
		$polls = $pollsModel->getLatest(1, 3);

		// Other recent polls
		include(BLUPATH_TEMPLATES.'/destress/polls/latest.php');

	}

	/**
	 *	Poll discuss
	 */
	private function poll_discuss(){
		var_dump(Request::getString('poll'));
		die();
	}

}


?>
