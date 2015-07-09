<?php

/**
 * Groups controller
 */
class WorkitmomGroupsController extends WorkitmomReportsController
{
	/**
	 * Constructor
	 */
	public function __construct($args)
	{
		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Connect', '/connect');
		$breadcrumbs->add('Groups', '/groups');

		parent::__construct($args);
	}

	/**
	 * Groups landing page
	 */
	public function view()
	{
		// Get popular groups
		$groupsModel = $this->getModel('groups');
		$total = false;
		$popularGroups = $groupsModel->getGroups(0, 4, $total, array(
			'order' => 'popular',
			'exclude' => $groupsModel->getDislikedGroups()
		));

		// Set page title
		$this->_doc->setTitle('Groups');
		$this->_doc->setAdPage(OpenX::PAGE_GROUPS);

		// Load template
		include(BLUPATH_TEMPLATES.'/groups/landing.php');
	}

	/**
	 * Group listing box
	 */
	public function listing()
	{
		// Get data from request
		$categorySlug = Request::getCmd('category');
		$onlyOwner = Request::getCmd('onlyowner');
		$sort = Request::getCmd('sort', 'recent');
		$page = Request::getInt('page', 1);
		$limit = BluApplication::getSetting('listingLength');
		$offset = ($page - 1) * $limit;

		// Get user details
		$user = BluApplication::getUser();

		// Get list of group categories
		$groupsModel = $this->getModel('groups');
		$categories = $groupsModel->getCategories();

		// Get current category from slug
		$category = null;
		$categoryId = null;
		if ($categorySlug) {
			if ($categorySlug == 'joined') {
				$categoryId = 'joined';
			} else {
				foreach ($categories as $slugCategory) {
					if ($slugCategory['slug'] == $categorySlug) {
						$category = $slugCategory;
						$categoryId = $category['id'];
						break;
					}
				}
			}
		}

		// Get group details
		$total = true;
		$groups = $groupsModel->getGroups($offset, $limit, $total, array(
			'order' => $sort,
			'category' => $categoryId,
			'exclude' => $groupsModel->getDislikedGroups()
		));
		foreach ($groups as $groupId => &$group) {

			// Get latest topics
			$group['numTopics'] = true;
			$group['latestTopics'] = $groupsModel->getTopics(0, 4, $group['numTopics'], array(
				'group' => $groupId,
				'order' => 'latest_post'
			));

			// Get latest members
			$group['numMembers'] = true;
			$group['latestMembers'] = $groupsModel->getMembers($groupId, 0, 4, $group['numMembers']);
			
		}
		unset($group);
		
		/* Get pagination */
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '/groups?sort=' . urlencode($sort) . '&amp;category=' . urlencode($categoryId) . '&amp;page='
		));
		
		// Prepare sort/categories skyscaper ad.
		$this->_doc->setAdPage(OpenX::PAGE_GROUPS);

		// Load template
		include(BLUPATH_TEMPLATES.'/groups/landing/box.php');
	}

	/**
	 *	Listing box sorter
	 */
	protected function listing_sorter($sort, $page)
	{
		// Get user details
		$user = BluApplication::getUser();

		// Get data
		$category = Request::getString('category');
		$sorts = array(
			'recent' => 'Recent Posts',
			//'popular' => 'Most Popular',
			'name' => 'Title A-Z',
			'date' => 'Newest',
			'discussions' => 'Most Discussions'
		);
		$defaultSort = 'recent'; //BluApplication::getSetting('listingSort', 'date');
		$sort = Request::getString('sort', $defaultSort);
		$on = in_array($sort, array_keys($sorts));

		// Load template
		include(BLUPATH_TEMPLATES . '/site/landing/sorter.php');
	}

	/**
	 * Group detail page
	 */
	public function detail()
	{
		// Get group id from args
		$groupId = @$this->_args[0];

		// Get user details for permission checking
		$user = BluApplication::getUser();

		// Get group
		$groupsModel = $this->getModel('groups');
		$group = $groupsModel->getGroup($groupId);
		if (!$group) {
			return $this->_errorRedirect();
		}

		// Get group members
		$numMembers = true;
		$members = $groupsModel->getMembers($groupId, 0, 12, $numMembers, 'random');

		// Get latest topics
		$numTopics = true;
		$latestTopics = $groupsModel->getTopics(0, 5, $numTopics, array(
			'group' => $groupId,
			'order' => 'latest_post'
		));

		// Get links
		$numLinks = true;
		$links = $groupsModel->getLinks($groupId, 0, 3, $numLinks, 'random');

		// Get photos
		$numPhotos = true;
		$photos = $groupsModel->getPhotos($groupId, 0, 12, $numPhotos, 'random');

		// Get group owner details
		$personModel = $this->getModel('person');
		$owner = $personModel->getPerson(array('member' => $group['owner']));

		// Add breadcrumb
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add($group['name'], '/groups/detail/'.$groupId);

		// Set page title
		$this->_doc->setTitle($group['name'] . BluApplication::getSetting('titleSeparator') . 'Groups');
		$this->_doc->setAdPage(OpenX::PAGE_GROUP); 

		// Load template
		include(BLUPATH_TEMPLATES.'/groups/detail.php');
	}

	/**
	 * Group members listing
	 */
	public function members()
	{
		// Get group id from args
		$groupId = @$this->_args[0];

		// Get group
		$groupsModel = $this->getModel('groups');
		$group = $groupsModel->getGroup($groupId);
		if (!$group) {
			return $this->_errorRedirect();
		}

		// Get data from request
		$page = Request::getInt('page', 1);
		$limit = 24;
		$offset = ($page - 1) * $limit;

		// Get group members
		$total = true;
		$groupsModel = $this->getModel('groups');
		$members = $groupsModel->getMembers($groupId, $offset, $limit, $total, 'joined');
		
		// Set pagination
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '/groups/members/'.$groupId.'?page='
		));

		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add($group['name'], '/groups/detail/'.$groupId);
		$breadcrumbs->add('Members', '/groups/members/'.$groupId);

		// Set page title
		$this->_doc->setTitle('Members' . BluApplication::getSetting('titleSeparator') . $group['name'] . BluApplication::getSetting('titleSeparator') . 'Groups');
		$this->_doc->setAdPage(OpenX::PAGE_GROUP);

		// Load template
		include(BLUPATH_TEMPLATES.'/groups/members.php');
	}

	/**
	 * Group photos listing page.
	 */
	public function photos()
	{
		// Get group id from args
		$groupId = @$this->_args[0];

		// Get group
		$groupsModel = $this->getModel('groups');
		$group = $groupsModel->getGroup($groupId);
		if (!$group) {
			return $this->_errorRedirect();
		}
		
		// Get tab
		$tab = strtolower(Request::getCmd('tab', 'browse'));

		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add($group['name'], '/groups/detail/'.$groupId);
		$breadcrumbs->add('Members', '/groups/photos/'.$groupId);

		// Set page title
		$this->_doc->setTitle('Photos'.BluApplication::getSetting('titleSeparator').$group['name'].BluApplication::getSetting('titleSeparator').'Groups');
		$this->_doc->setAdPage(OpenX::PAGE_GROUP);

		// Load template
		include(BLUPATH_TEMPLATES.'/groups/photos.php');
	}
	
	/**
	 *	Group photos listing/browse tab.
	 */
	public function photos_listing(){
	
		// Get group id from args
		$groupId = @$this->_args[0];

		// Get group
		$groupsModel = $this->getModel('groups');
		$group = $groupsModel->getGroup($groupId);
		if (!$group) {
			return $this->_errorRedirect();
		}

		// Get data from request
		$page = Request::getInt('page', 1);
		$limit = 24;

		// Get group photos
		$total = true;
		$groupsModel = $this->getModel('groups');
		$photos = $groupsModel->getPhotos($groupId, ($page - 1) * $limit, $limit, $total, 'created');
		
		// Append user credentials
		$user = BluApplication::getUser();
		if (Utility::iterable($photos)){
			foreach($photos as &$photo){
				$photo['isOwner'] = $user && $photo['user'] && $user->userid == $photo['user']->userid;
			}
			unset($photo);
		}
		
		/* Get pagination */
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '/groups/photos/'.$groupId.'?tab=browse&page='
		));
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/groups/photos/browse.php');

	}
	
	/**
	 *	Group photos upload tab.
	 */
	public function photos_upload_tab(){
		
		// Require user.
		if (!$this->_requireUser('Please sign in to post a photo.')){
			return false;
		}
	
		// Get group id from args
		$groupId = @$this->_args[0];
		
		/* Upload queue */
		$queueId = Request::getString('queueid', md5(uniqid()));
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/groups/photos/upload.php');
		
	}
	
	/**
	 *	Group photos upload action.
	 *
	 *	Bears a lot of similarity to AccountController::photos_upload.
	 */
	public function photos_upload(){
	
		// Require user.
		if (!$this->_requireUser('Please sign in to post a photo.')){
			return false;
		}
		
		// Get group
		$groupId = Request::getInt('groupId');
		if (!$groupId){
			return $this->_errorRedirect();
		}
		$groupsModel = $this->getModel('groups');
		$group = $groupsModel->getGroup($groupId);
		if (!Utility::iterable($group)){
			return $this->_errorRedirect();
		}
		
		// Require group member
		if (!$group['isMember']){
			$message = 'You need to be a member of this group to post photos. <a href="'.SITEURL.'/groups/join/'.$groupId.'/">Join now.</a>';
			return $this->_redirect('/groups/photos/'.$groupId.'?tab=upload', $message);
		}
		
		// Check for errors
		$errors = false;

		// Add photos
		$queueId = Request::getString('queueid');
		for ($i = 1; $i <= 3; $i ++) {
			$caption = Request::getString('photocaption'.$i);
			$result = $this->_saveUpload($queueId, 'photoupload'.$i, false, array('png', 'jpg', 'jpeg', 'gif', 'bmp'), array('caption' => $caption));
			if (isset($result['error'])) {
				Messages::addMessage($result['error'], 'error');
				$errors = true;
			}
		}

		// Check for at least one new upload
		$assets = Upload::getQueue($queueId);
		if (empty($assets)) {
			Messages::addMessage('Please select a photo to upload.', 'error');
			$errors = true;
		}

		// Show errors
		if ($errors) {
			return $this->_redirect('/groups/photos/'.$groupId.'?tab=upload');
		}

		// Move uploaded files to their correct location
		foreach ($assets as $uploadId => $file) {
			$groupsModel->addPhoto($groupId, $uploadId, $file, $file['caption']);
		}
		Upload::clearQueue($queueId);

		// Done, view photos
		return $this->_redirect('/groups/photos/'.$groupId.'?tab=upload', 'Your photos have been uploaded. <a href="?tab=browse">Click here to see them.</a>');
		
	}
	
	/**
	 *	Delete a photo.
	 */
	public function photos_delete(){
		
		/* Require user. */
		if (!$user = $this->_requireUser('Please sign in to be able to delete a photo.')){
			return false;
		}
		
		/* Get photo. */
		$groupPhotoId = Request::getInt('id');
		if (!$groupPhotoId){
			return $this->_errorRedirect();
		}
		$groupsModel = $this->getModel('groups');
		$groupPhoto = $groupsModel->getPhoto($groupPhotoId);
		if (!Utility::iterable($groupPhoto)){
			return $this->_errorRedirect();
		}
		
		/* Check credentials. */
		if ($user->userid != $groupPhoto['user']->userid){
			return $this->_redirect('/groups/photos/'.(int)$groupPhoto['groupID'].'/', 'Only the owner of tbe photo can delete the photo.');
		}
		
		/* Delete */
		$deleted = $groupsModel->deletePhoto($groupPhotoId);
		
		/* Return */
		if ($deleted){
			Messages::addMessage('The photo has been successfully deleted.');
		} else {
			Messages::addMessage('The photo could not be deleted.', 'error');
		}
		return $this->_redirect('/groups/photos/'.(int)$groupPhoto['groupID'].'/');
		
	}

	/**
	 * Group links
	 */
	public function links()
	{
		// Get group id from args
		$groupId = @$this->_args[0];

		// Get group
		$groupsModel = $this->getModel('groups');
		$group = $groupsModel->getGroup($groupId);
		if (!$group) {
			return $this->_errorRedirect();
		}

		// Get data from request
		$page = Request::getInt('page', 1);
		$limit = 10;
		$offset = ($page - 1) * $limit;

		// Get group topics
		$total = true;
		$groupsModel = $this->getModel('groups');
		$links = $groupsModel->getLinks($groupId, $offset, $limit, $total, 'joined');
		
		// Set pagination
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '/groups/links/'.$groupId.'?page='
		));

		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add($group['name'], '/groups/detail/'.$groupId);
		$breadcrumbs->add('Links', '/groups/links/'.$groupId);

		// Set page title
		$this->_doc->setTitle('Links'.BluApplication::getSetting('titleSeparator').$group['name'].BluApplication::getSetting('titleSeparator').'Groups');
		$this->_doc->setAdPage(OpenX::PAGE_GROUP);

		// Load template
		include(BLUPATH_TEMPLATES.'/groups/links.php');
	}

	/**
	 * Group discussions listing
	 */
	public function discussions()
	{
		// Get group id from args
		$groupId = @$this->_args[0];

		// Get group
		$groupsModel = $this->getModel('groups');
		$group = $groupsModel->getGroup($groupId);
		if (!$group) {
			return $this->_errorRedirect();
		}

		// Get data from request
		$page = Request::getInt('page', 1);
		$limit = 10;
		$offset = ($page - 1) * $limit;

		// Get group topics
		$total = true;
		$groupsModel = $this->getModel('groups');
		$topics = $groupsModel->getTopics($offset, $limit, $total, array('group' => $groupId));
		
		// Set pagination
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '/groups/discussions/'.$groupId.'?page='
		));

		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add($group['name'], '/groups/detail/'.$groupId);
		$breadcrumbs->add('Discussions', '/groups/discussions/'.$groupId);

		// Set page title
		$this->_doc->setTitle('Discussions' . BluApplication::getSetting('titleSeparator') . $group['name'] . BluApplication::getSetting('titleSeparator') . 'Groups');
		$this->_doc->setAdPage(OpenX::PAGE_GROUPS);

		// Load template
		include(BLUPATH_TEMPLATES.'/groups/discussions.php');
	}

	/**
	 * Individual discussion topic
	 */
	public function discussion()
	{
		// Get topic id from args
		$topicId = @$this->_args[0];

		// Get user details
		$user = BluApplication::getUser();

		// Get topic
		$groupsModel = $this->getModel('groups');
		$topic = $groupsModel->getTopic($topicId);
		if (!$topic) {
			return $this->_errorRedirect();
		}

		// Get group details
		$group = $groupsModel->getGroup($topic['groupId']);

		// Get data from request
		$page = Request::getInt('page', 1);
		$limit = 10;
		$offset = ($page - 1) * $limit;

		// Get topic posts
		$total = null;
		$posts = $groupsModel->getPosts($topic['id'], $offset, $limit, $total);
		
		// Set pagination
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '/groups/discussion/'.$topic['id'].'?page='
		));

		// Get user details
		$personModel = $this->getModel('person');
		foreach ($posts as &$post) {
			$post['user'] = $personModel->getPerson(array('member' => $post['userId']));
			$post['canEdit'] = ($user && ($user->userid == $post['userId']));
			$post['canDelete'] = $groupsModel->canOfferPostDeletion($post['id']);
		}
		unset($post);

		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add($group['name'], '/groups/detail/'.$group['id']);
		$breadcrumbs->add($topic['title'], '/groups/discusssion/'.$topicId);

		// Set page title
		$this->_doc->setTitle($topic['title'] . BluApplication::getSetting('titleSeparator') . $group['name'] . BluApplication::getSetting('titleSeparator') . 'Groups');
		$this->_doc->setAdPage(OpenX::PAGE_GROUPS);

		// Load template
		include(BLUPATH_TEMPLATES.'/groups/discussion.php');
	}

	/**
	 * Create a group
	 */
	public function create()
	{
		if (!$user = $this->_requireUser('Please sign in to create a group.')) {
			return false;
		}

		// Get data from request
		$title = Request::getString('group_title');
		$categoryId = Request::getString('group_category');
		$type = Request::getCmd('group_type');
		$blurb = Request::getString('group_blurb');
		$desc = Request::getString('group_desc');
		$tags = Request::getString('group_tags');
		$queueId = Request::getString('queueid', md5(uniqid()));

		// Get group categories
		$groupsModel = $this->getModel('groups');
		$categories = $groupsModel->getCategories();

		// Set page title
		$this->_doc->setTitle('Create Group');
		$this->_doc->setAdPage(OpenX::PAGE_GROUP);

		// Load template
		include(BLUPATH_TEMPLATES.'/groups/create.php');
	}

	/**
	 * Save group
	 */
	public function create_save()
	{
		// Get data from request
		$title = Request::getString('group_title');
		$slug = Utility::underscorify($title);
		$categoryId = Request::getString('group_category');
		$type = Request::getCmd('group_type');
		$blurb = Request::getString('group_blurb');
		$desc = Request::getString('group_desc');
		$tags = Request::getString('group_tags');
		$queueId = Request::getString('queueid');

		// Get model
		$groupsModel = $this->getModel('groups');

		// Validate
		$errors = false;

		// Required fields
		if (!$title || !$categoryId || !$type || !$blurb || !$desc || !$tags) {
			Messages::addMessage('Please complete all group information.', 'error');
			$errors = true;

		// Check group url is not taken
		} elseif ($groupsModel->isGroupSlugInUse($slug)) {
			Messages::addMessage('Sorry, that group title is already taken.', 'error');
			$errors = true;
		}

		// Upload photo
		$result = $this->_saveUpload($queueId, 'photoupload', true, array('png', 'jpg', 'jpeg', 'gif', 'bmp'));
		if (isset($result['error'])) {
			Messages::addMessage($result['error'], 'error');
			$errors = true;
		}

		// Show errors
		if ($errors) {
			return $this->_showMessages('create');
		}

		// Create group
		$tags = explode(',', $tags);
		$groupId = $groupsModel->createGroup($title, $categoryId, $slug, $type, $blurb, $desc, $tags);

		// Set group photo
		$assets = Upload::getQueue($queueId);
		$file = reset($assets);
		$uploadId = key($assets);
		$groupsModel->setPhoto($groupId, $uploadId, $file);
		Upload::clearQueue($queueId);

		// View group
		return $this->_redirect('/groups/detail/'.$groupId);
	}

	/**
	 * Join a group
	 */
	public function join()
	{
		if (!$this->_requireUser('Please sign in to join a group.')) {
			return false;
		}

		// Get data from request
		$groupId = @$this->_args[0];

		// Join group
		$groupsModel = $this->getModel('groups');
		if ($groupsModel->joinGroup($groupId)) {
			$group = $groupsModel->getGroup($groupId);
			Messages::addMessage('Welcome to '.$group['name'].'.');
			return $this->_redirect('/groups/detail/'.$groupId);
		} else {
			Messages::addMessage('Sorry, you do not have permission to join that group.', 'error');
			return $this->_redirect('/groups');
		}
	}

	/**
	 * Leave a group
	 */
	public function leave()
	{
		if (!$this->_requireUser('You need to be signed in to leave your group.')) {
			return false;
		}

		// Get data from request
		$groupId = @$this->_args[0];

		// Leave group
		$groupsModel = $this->getModel('groups');
		if ($groupsModel->leaveGroup($groupId)) {
			$group = $groupsModel->getGroup($groupId);
			Messages::addMessage('You have left '.$group['groupName'].'.');
		}

		// View overview
		return $this->_redirect('/groups');
	}

	/**
	 * Subscribe to new group discussions
	 */
	public function subscribe()
	{
		if (!$user = $this->_requireUser('Please sign in to subscribe to group discussions.')) {
			return false;
		}

		// Get data from request
		$groupId = @$this->_args[0];

		// Subscribe
		$groupsModel = BluApplication::getModel('groups');
		$groupsModel->subscribeGroup($user->userid, $groupId);

		// Redirect to group
		return $this->_redirect('/groups/detail/'.$groupId, 'Thanks for subscribing.  You will now receive alerts about new discussions in this group.');
	}

	/**
	 * Unsubscribe from new group discussions
	 */
	public function unsubscribe()
	{
		if (!$user = $this->_requireUser('Please sign in to unsubscribe from group discussions.')) {
			return false;
		}

		// Get data from request
		$groupId = @$this->_args[0];

		// Subscribe
		$groupsModel = BluApplication::getModel('groups');
		$groupsModel->unsubscribeGroup($user->userid, $groupId);

		// Redirect to group
		return $this->_redirect('/groups/detail/'.$groupId, 'Your subscription has been removed. You will no longer receive alerts about new discussions in this group.');
	}

	/**
	 * Start a discussion
	 */
	public function start_discussion()
	{
		if (!$user = $this->_requireUser('Please sign in to start a discussion.')) {
			return false;
		}

		// Get data from request
		$groupId = Request::getInt('groupid');
		$title = Request::getString('disc_title');
		$post = strip_tags(Request::getString('disc_post'));
		$subscribe = Request::getBool('disc_subscribe');

		// Validate
		if (!$title || !$post) {
			Messages::addMessage('Please enter a discussion title and post.', 'error');
			return $this->_showMessages('start_discussion', 'details');
		}

		// Start topic
		$groupsModel = $this->getModel('groups');
		$topicId = $groupsModel->addTopic($user->userid, $groupId, $title, $post);

		// Subscribe to topic if requested
		if ($subscribe) {
			$groupsModel->subscribeTopic($user->userid, $topicId);
		}

		// Redirect to discusssion
		return $this->_redirect('/groups/discussion/'.$topicId);
	}

	/**
	 * Subscribe to a discussion
	 */
	public function subscribe_discussion()
	{
		if (!$user = $this->_requireUser('Please sign in to subscribe to a discussion.')) {
			return false;
		}

		// Get data from request
		$topicId = Request::getInt('topicid');

		// Subscribe
		$groupsModel = BluApplication::getModel('groups');
		$groupsModel->subscribeTopic($user->userid, $topicId);

		// Redirect to discusssion
		return $this->_redirect('/groups/discussion/'.$topicId, 'Thanks for subscribing. You will now receive alerts about replies to this discussion.');
	}

	/**
	 * Unsubscribe from a discussion
	 */
	public function unsubscribe_discussion()
	{
		if (!$user = $this->_requireUser('Please sign in to unsubscribe from a discussion.')) {
			return false;
		}

		// Get data from request
		$topicId = Request::getInt('topicid');

		// Unsubscribe
		$groupsModel = BluApplication::getModel('groups');
		$groupsModel->unsubscribeTopic($user->userid, $topicId);

		// Redirect to discusssion
		return $this->_redirect('/groups/discussion/'.$topicId, 'Your subscription has been removed. You will no longer receive alerts about replies to this discussion.');
	}

	/**
	 * Add a reply to a discussion
	 */
	public function add_post()
	{
		if (!$user = $this->_requireUser('Please sign in to reply to the discussion.')) {
			return false;
		}

		// Get data from request
		$topicId = Request::getInt('topicid');
		$post = strip_tags(Request::getString('reply_post'));
		$subscribe = Request::getBool('reply_subscribe');

		// Validate
		if (!$post) {
			Messages::addMessage('Please enter a reply post.', 'error');
			return $this->_showMessages('add_discussion_reply', 'discussion');
		}

		// Add reply
		$groupsModel = $this->getModel('groups');
		$groupsModel->addPost($user->userid, $topicId, $post);

		// Subscribe to topic if requested
		if ($subscribe) {
			$groupsModel->subscribeTopic($user->userid, $topicId);
		}

		// Redirect to discusssion
		return $this->_redirect('/groups/discussion/'.$topicId);
	}

	/**
	 * Edit a post
	 */
	public function edit_post()
	{
		if (!$user = $this->_requireUser('Please sign in to edit your posts.')) {
			return false;
		}

		// Get post
		$postId = Request::getInt('postid');
		$groupsModel = $this->getModel('groups');
		$post = $groupsModel->getPost($postId);

		// Get topic and group details
		$topic = $groupsModel->getTopic($post['topicId']);
		$group = $groupsModel->getGroup($topic['groupId']);

		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add($group['name'], '/groups/detail/'.$group['id']);
		$breadcrumbs->add($topic['title'], '/groups/discusssion/'.$topic['id']);
		$breadcrumbs->add('Edit Post', '/groups/edit_post/'.$postId);

		// Set page title
		$this->_doc->setTitle('Edit Post'.BluApplication::getSetting('titleSeparator').$topic['title'].BluApplication::getSetting('titleSeparator'). $group['name'].BluApplication::getSetting('titleSeparator').'Groups');
		$this->_doc->setAdPage(OpenX::PAGE_GROUPS);

		// Load template
		include(BLUPATH_TEMPLATES.'/groups/edit_post.php');
	}

	/**
	 * Save a post edit
	 */
	public function save_post()
	{
		if (!$user = $this->_requireUser('Please sign in to edit your posts.')) {
			return false;
		}

		// Get data from request
		$postId = Request::getInt('postid');
		$topicId = Request::getInt('topicid');
		$text = Request::getString('reply_post');

		// Validate
		if (!$text) {
			Messages::addMessage('Please enter some text.', 'error');
			return $this->_showMessages('edit_post');
		}

		// Save post
		$groupsModel = $this->getModel('groups');
		$success = $groupsModel->editPost($user->userid, $postId, $text);

		// Load template
		$msg = ($success ? 'Your post has been edited.' : 'Sorry, your post could not be edited.');
		$msgType = ($success ? 'info' : 'warn');
		Messages::addMessage($msg, $msgType);
		$this->_redirect('/groups/discussion/'.$topicId);
	}

	/**
	 * Delete post
	 */
	public function delete_post()
	{
		// Require user.
		if (!$user = $this->_requireUser('Please sign in or register to delete a post.')) {
			return false;
		}

		// Get data from request
		$postId = Request::getInt('postid');

		// Delete post
		$groupsModel = $this->getModel('groups');
		$deleted = $groupsModel->deletePost($postId);

		// Output message.
		$msg = $deleted ? 'Post deleted.' : 'Sorry, this post does not exist.';
		$msgType = $deleted ? 'info' : 'warn';
		Messages::addMessage($msg, $msgType);
		
		// Get topic
		$post = $groupsModel->getPost($postId);
		if ($groupsModel->hasPosts($post['topicId'])){
			
			// Back to the topic
			return $this->_redirect('/groups/discussion/'.$post['topicId'].'/');
			
		} else {
			
			// Back to groups landing page.
			Messages::addMessage('Topic deleted.');
			return $this->_redirect('/groups/');
			
		}
	}

	/**
	 * Report post
	 */
	public function report_post()
	{
		if (!$this->_requireUser('Please sign in or register to report a post.')) {
			return false;
		}

		// Get data
		$postId = Request::getInt('postid');
		
		/* Report object */
		if ($this->report('grouppost', $postId)){
			$message = 'The post has been reported.';
			$messageType = 'info';
		} else {
			$message = 'Sorry, the post could not be reported.';
			$messageType = 'error';
		}

		// Add message
		Messages::addMessage($message, $messageType);

		// Return to discussion
		return $this->discussion();
	}
	
	/**
	 *	Overrides ReportsController.
	 */
	protected function report_extra($reportId){
		return $this->report_legacy($reportId);
	}
	
	/**
	 *	Used for 'legacy' `groupPostReports` table.
	 */
	private function report_legacy($reportId){
		
		/* Get report */
		$reportsModel = $this->getModel('reports');
		$report = $reportsModel->getReport($reportId);
		
		/* Get user */
		$user = BluApplication::getUser();

		// Report post
		$groupsModel = $this->getModel('groups');
		$success = $groupsModel->reportPost($user->userid, $report['objectId']);
		
		/* Return */
		return $success;
		
	}

}

?>
