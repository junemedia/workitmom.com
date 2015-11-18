<?php


class WorkitmomProfileController extends WorkitmomCommentsController {

	/**
	 *	The person's whose profile we're viewing.
	 */
	private $_person;

	/**
	 * Display home page
	 */
	public function view(){

		/* Arguments */
		$args = $this->_args;

		/* Breadcrumbs */
		BluApplication::getBreadcrumbs()->add('Members', '/connect/members/');

		/* Models */
		$personModel = $this->getModel('person');
		$userModel = $this->getModel('user');

		/* What to do */
		if (Utility::is_loopable($args)){

			/* Get the user to display */
			$username = urldecode(array_shift($args));
			try {
				$person = $personModel->getPerson(array('username' => $username));
				$this->_person =& $person;
			} catch (NoDataException $exception) {
				
				// Go to current user.
				return $this->_redirect('/profile/');
				
			}
			if (!$person){
				
				// CLUDGE
				return $this->_redirect('/profile/');
				
			} else if ($person->deleted){
				
				// User deleted, so no profile thankyou.
				return $this->_redirect('/connect/members/');
				
			}

		} else if (BluApplication::getUser()) {

			/* Redirect to the current user's profile. */
			return $this->_redirect('/profile/' . BluApplication::getUser()->username . '/');

		} else {

			/* No user to view, redirect to 'Meet members' page */
			return $this->_redirect('/connect/members/');

		}

		/* More breadcrumbs */
		BluApplication::getBreadcrumbs()->add($person->name, '/profile/' . $person->username . '/');

		/* Important variables. */
		$isLoggedIn = (bool) BluApplication::getUser();		// The user who's logged in.
		$isFriend = $isLoggedIn && $personModel->areFriends(BluApplication::getUser(), $person);
		$isSelf = $userModel->isSelf($person);
		$requested = $isLoggedIn && $personModel->hasFriendRequest(BluApplication::getUser(), $person);

		/* Set page title */
		$this->_doc->setTitle($isSelf ? 'My Profile' : $person->name . "'s Profile");
		$this->_doc->setAdPage(OpenX::PAGE_PROFILE);

		/* Which buttons do we show? */
		$showAddFriend = !$isFriend && !$isSelf && !$requested;
		$showRemoveFriend = $isFriend && !$isSelf;
		$showLeaveComment = !$isSelf;
		$showSendPrivateMessage = !$isSelf && $isFriend;

		/* Load template */
		include (BLUPATH_TEMPLATES . '/profile/profile.php');

	}

	/**
	 *	Prints out a button.
	 */
	protected function button($button){
		switch($button){
			case 'addfriend':
				$url = '?task=add_friend';
				$text = 'Add to friends';
				break;

			case 'removefriend':
				$url = '/account/friends?task=remove_friend&amp;id='.$this->_person->userid;
				$text = 'Remove from friends';
				break;

			case 'comment':
				$url = '#comment';
				$text = 'Leave a comment';
				break;

			case 'message':
				$url = '/account/write_message?recipient=' . $this->_person->userid;
				$text = 'Private message';
				break;

			default:
				return false;
				break;
		}
		echo '<a href="' . $url . '" class="button_dark scroll"><span>' . $text . '</span></a>';
		return true;
	}

	/**
	 *	Top module.
	 */
	public function info(){
		
		/* Looking at own profile? */
		$userModel = $this->getModel('user');
		$isSelf = $userModel->isSelf($this->_person);

		/* Which module do we show? */
		$defaultModule = 'blog';
		$module = Request::getString('info', $defaultModule);
		$module = strtolower($module);
		if (!in_array($module, array('life', 'family', 'blog', 'work'))){ $module = $defaultModule; }

		/* Load templates */
		include(BLUPATH_TEMPLATES . '/profile/blocks/info.php');

	}

	/**
	 *	Top module content - Life tab.
	 */
	protected function info_life(){

		/* Data */
		$person =& $this->_person;
		$life = $person->getLife();
		$tags = $person->getTags();

		$userModel = $this->getModel('user');
		$isSelf = $userModel->isSelf($person);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/profile/blocks/info/life.php');

	}

	/**
	 *	Top module content - family tab.
	 */
	protected function info_family(){

		/* Data */
		$person =& $this->_person;
		$family = $person->getFamily();

		$userModel = $this->getModel('user');
		$isSelf = $userModel->isSelf($person);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/profile/blocks/info/family.php');

	}

	/**
	 *	Top module content - Work tab.
	 */
	protected function info_work(){

		/* Data */
		$person =& $this->_person;
		$work = $person->getWork();

		$userModel = $this->getModel('user');
		$isSelf = $userModel->isSelf($person);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/profile/blocks/info/work.php');

	}

	/**
	 *	Top module content - blogs tab
	 */
	protected function info_blog(){

		/* Get data */
		$person =& $this->_person;

		/* Get blog. */
		$blogsModel = $this->getModel('blogs');
		$blog = $blogsModel->getBlog('member', $person->contentcreatorid);
		$posts = $blog->getLatestPosts(0, 3);

		$userModel = $this->getModel('user');
		$isSelf = $userModel->isSelf($person);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/profile/blocks/info/blog.php');

	}

	/**
	 *	Top module content - blogs tab individual post.
	 */
	protected function info_blog_individual(MemberblogpostObject $post){

		/* Get data */
		$link = SITEURL . '/blogs/members/' . $post->author->username . '/' . $post->id;

		/* Load template */
		include(BLUPATH_TEMPLATES . '/profile/blocks/info/blog_individual.php');

	}

	/**
	 *	My day snapshot module.
	 */
	protected function snapshot(){

		/* Filter empty data */
		$snapshots = $this->_person->getMyDays();
		$snapshotdate = key($snapshots);
		$mostrecentsnapshot = array_shift($snapshots);

		$person = &$this->_person;
		$userModel = $this->getModel('user');
		$isSelf = $userModel->isSelf($person);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/profile/blocks/snapshot.php');

	}

	/**
	 *	To-do list module.
	 */
	protected function to_do_list(){

		/* Data */
		$person =& $this->_person;
		$todoList = $person->getToDoList();
		$userModel = $this->getModel('user');
		$isSelf = $userModel->isSelf($person);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/profile/blocks/todolist.php');

	}

	/**
	 *	To-do list entry
	 */
	protected function to_do_list_individual($todo){

		/* Load tempate */
		include(BLUPATH_TEMPLATES . '/profile/blocks/todolist/individual.php');

	}

	/**
	 *	Friends module.
	 */
	protected function friends_block(){

		/* Data */
		$person =& $this->_person;

		$totalfriends = null;
		$profilemax = 12;
		$friendsPrivacy = (bool) $person->getPrivacy()->userProfileMemberNetwork;
		$friends = $friendsPrivacy ? $person->getFriends(null, $profilemax, $totalfriends, true) : false;

		$userModel = $this->getModel('user');
		$isSelf = $userModel->isSelf($person);

		/* Template */
		include(BLUPATH_TEMPLATES . '/profile/blocks/friends.php');

	}

	/**
	 *	Photos module
	 */
	protected function photos_block(){
	
		/* Get model */
		$photosModel = $this->getModel('newphotos');
		
		/* Data */
		$person =& $this->_person;
		$total = null;
		
		// Display-data
		$userModel = $this->getModel('user');
		$isSelf = $userModel->isSelf($person);
		
		$options = array(
			'user' => $person->userid,
			'order' => 'date'
		);

		if(!$isSelf)
		{
			$options['status'] = 1;
		}
		$photos = $photosModel->getPhotos(0, 12, $total,$options );
		$photosModel->addDetails($photos);

		// Display-data
		/*$userModel = $this->getModel('user');
		$isSelf = $userModel->isSelf($person);*/

		/* Load template */
		include(BLUPATH_TEMPLATES.'/profile/blocks/photos.php');

	}





	/**
	 *	"Recent activity" module.
	 */
	public function activity(){

		/* Which module do we show? */
		$defaultActivity = 'articles';
		$activity = Request::getString('activity', $defaultActivity);
		$activity = strtolower($activity);
		if (!in_array($activity, array('articles', 'questions', 'groups', 'discussions'))){ $activity = $defaultActivity; }

		/* Load template */
		include(BLUPATH_TEMPLATES . '/profile/blocks/activity.php');

	}

	/**
	 *	Activity module - articles tab.
	 */
	protected function activity_articles(){

		/* Triviality*/
		if (!isset($this->_person->contentcreatorid)){ return null; }

		/* Data */
		$alt = false;

		$itemsModel = $this->getModel('items');
		$articles = $itemsModel->getAuthor($this->_person, 'article', 0, 3);

		$userModel = $this->getModel('user');
		$person =& $this->_person;
		$isSelf = $userModel->isSelf($person);

		/* Template */
		include(BLUPATH_TEMPLATES . '/profile/blocks/activity/articles.php');

	}

	/**
	 *	Activity module - articles tab individual articles.
	 */
	protected function activity_articles_individual(ArticleObject $article){

		/* Alternating colours */
		static $alt = false;
		$alt = !$alt;

		/* Get data */
		$link = SITEURL . '/articles/detail/' . $article->id;

		/* Load template */
		include(BLUPATH_TEMPLATES . '/profile/blocks/activity/articles_individual.php');

	}

	/**
	 *	Activity module - questions tab.
	 */
	protected function activity_questions(){

		/* Triviality*/
		if (!isset($this->_person->contentcreatorid)){ return null; }

		/* Get models */
		$itemsModel = $this->getModel('items');
		$commentsModel = $this->getModel('comments');

		/* Data */
		$person =& $this->_person;
		$questions = $itemsModel->getAuthor($this->_person, 'question', 0, 2);
		$replies = $commentsModel->getAuthor($this->_person, 'question', 0, 2);

		/* Template */
		include(BLUPATH_TEMPLATES . '/profile/blocks/activity/questions.php');

	}

	/**
	 *	Activity module - questions tab individual question.
	 */
	protected function activity_questions_question_individual(QuestionObject $question){

		/* Alternating colours */
		static $alt = false;
		$alt = !$alt;

		/* Get data */
		$link = SITEURL . '/questions/detail/' . $question->id;

		/* Load template */
		include(BLUPATH_TEMPLATES . '/profile/blocks/activity/questions_question_individual.php');

	}

	/**
	 *	Activity module - questions tab individual replies.
	 */
	protected function activity_questions_reply_individual(ItemcommentObject $reply){

		/* Alternating colours */
		static $alt = false;
		$alt = !$alt;

		/* Get data */
		$question = $reply->getThing();
		$link = SITEURL . '/questions/detail/' . $question->id;

		/* Load template */
		include(BLUPATH_TEMPLATES . '/profile/blocks/activity/questions_reply_individual.php');

	}

	public function activity_groups()
	{
		// Get groups
		$groupsModel = BluApplication::getModel('groups');
		$ownedGroups = $groupsModel->getOwnedGroups($this->_person->userid);
		$joinedGroups = $groupsModel->getJoinedGroups($this->_person->userid);

		// Load template
		include(BLUPATH_TEMPLATES . '/profile/blocks/activity/groups.php');
	}

	public function activity_discussions()
	{
		// Get groups
		$groupsModel = BluApplication::getModel('groups');
		$createdDiscussions = $groupsModel->getUserTopics($this->_person->userid, 0, 10);

		// Load template
		include(BLUPATH_TEMPLATES . '/profile/blocks/activity/discussions.php');
	}



	###							CommentsController							###

	/**
	 *	Required by CommentsController
	 */
	protected function get_commentable_object(){

		/* Get */
		if (!isset($this->_person) || !$this->_person) {

			/* Get args */
			$args = $this->_args;
			if (!Utility::is_loopable($args)){ return null; }
			$username = urldecode(array_shift($args));

			/* Get person. */
			$personModel = $this->getModel('person');
			$person = $personModel->getPerson(array('username' => $username));

			/* Set */
			$this->_person = $person;
		}

		/* Return */
		return $this->_person;
	}

	###								End CommentsController							###

	/**
	 * Add a friend form
	 */
	public function add_friend()
	{
		// Require user
		if (!$user = $this->_requireUser('Please sign in, or sign up, to add a friend.')) {
			return false;
		}

		// Get friend details
		if (!isset($this->_args[0])) {
			return $this->_errorRedirect();
		}
		$friendUsername = urldecode($this->_args[0]);
		$personModel = $this->getModel('person');
		$friend = $personModel->getPerson(array('username' => $friendUsername));

		// Get personal message
		$message = Request::getString('message');
		
		/* Set header ad */
		$this->_doc->setAdPage(OpenX::PAGE_PROFILE);

		// Load template
		include(BLUPATH_TEMPLATES.'/profile/add_friend.php');
	}

	/**
	 * Submit a friend request
	 */
	public function add_friend_save()
	{
		// Require user
		if (!$user = $this->_requireUser('Please sign in, or sign up, to add a friend.')) {
			return false;
		}

		// Get friend details
		$friendId = Request::getInt('friendid');
		$personModel = $this->getModel('person');
		$friend = $personModel->getPerson(array('member' => $friendId));

		// Get personal message
		$message = Request::getString('message');

		// Already have request?
		if ($personModel->hasFriendRequest($user, $friend)){
			Messages::addMessage('You already have a pending friend request with <em>' . $friend->name . '</em>.');

		// Add new friend request
		} else {
			$success = $personModel->createFriendRequest($user->userid, $friend->userid, $message);

			// Add status message
			$message = $success ? 'You have sent <em>' . $friend->name . '</em> a network request.' : BluApplication::getSetting('errorMessage');
			$messageType = $success ? 'info' : 'error';
			Messages::addMessage($message, $messageType);
		}

		// Redirect back to the person's page
		$this->_redirect('/profile/'.$friend->username);
	}

	/**
	 * Articles
	 */
	public function articles()
	{
		$username = urldecode($this->_args[0]);

		// Get user details
		$personModel = $this->getModel('person');
		$person = $personModel->getPerson(array('username' => $username));

		// Get persons articles
		$itemsModel = BluApplication::getModel('newitems');
		$articles = $itemsModel->getOwnedItems($person->userid, 'article');

		// Load template
		include(BLUPATH_TEMPLATES.'/profile/articles.php');
	}

	/**
	 * Friends
	 */
	public function friends()
	{
		$username = urldecode($this->_args[0]);

		// Get user details
		$personModel = $this->getModel('person');
		$person = $personModel->getPerson(array('username' => $username));

		// Check privacy
		$friendsPrivacy = (bool) $person->getPrivacy()->userProfileMemberNetwork;
		if (!$friendsPrivacy) {
			return $this->_redirect('/profile/'.$person->username, $person->name.' has chosen to keep their friends list private.');
		}

		// Get data from request
		$page = Request::getInt('page', 1);
		$limit = 24;
		$offset = ($page - 1) * $limit;

		// Get member friends
		$total = true;
		$friends = $person->getFriends($offset, $limit, $total);
		
		// Set pagination
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '/profile/friends/'.$person->username.'?page='
		));

		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Members', '/connect/members');
		$breadcrumbs->add($person->name, '/profile/'.$person->username);
		$breadcrumbs->add('Friends', '/profile/friends/'.$person->username);

		// Set page title
		$this->_doc->setTitle('Friends'.BluApplication::getSetting('titleSeparator').$person->name.BluApplication::getSetting('titleSeparator').'Members');

		// Load template
		include(BLUPATH_TEMPLATES.'/profile/friends.php');
	}

	/**
	 * Photos
	 */
	public function photos()
	{
		/* Get user */
		$args = $this->_args;
		if (!Utility::iterable($args)){
			return $this->_errorRedirect();
		}
		$username = array_shift($args);

		// Get user details
		$personModel = $this->getModel('person');
		$person = $personModel->getPerson(array('username' => $username));

		// Check privacy
		$photosPrivacy = (bool) $person->getPrivacy()->userProfilePhotos;
		if (!$photosPrivacy) {
			return $this->_redirect('/profile/'.$person->username, $person->name.' has chosen to keep their photos private.');
		}

		// Get data from request
		$page = Request::getInt('page', 1);
		$limit = 24;

		// Get member photos
		$total = true;
		$photosModel = $this->getModel('newphotos');
		$photos = $photosModel->getPhotos(($page - 1) * $limit, $limit, $total, array(
			'user' => $person->userid,
			'order' => 'date'
		));
		$photosModel->addDetails($photos);
		
		/* Get pagination */
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '?page='
		));

		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Members', '/connect/members');
		$breadcrumbs->add($person->name, '/profile/'.$person->username);
		$breadcrumbs->add('Photos', '/profile/photos/'.$person->username);

		// Set page title
		$this->_doc->setTitle('Photos'.BluApplication::getSetting('titleSeparator').$person->name.BluApplication::getSetting('titleSeparator').'Members');

		// Load template
		include(BLUPATH_TEMPLATES.'/profile/photos.php');
	}

}


?>
