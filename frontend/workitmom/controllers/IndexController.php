<?php

/**
 * Index Controller
 *
 * @package BluApplication
 * @subpackage FrontendControllers
 */
class WorkitmomIndexController extends ClientFrontendController
{
	/**
	 * 	Display home page
	 */
	public function view()
	{
		// Get models
		$homepageArticleModel = $this->getModel('homepagearticle');
		$itemsModel = $this->getModel('items');

		// Get site modules
		$siteModules = BluApplication::getModules('site');

		// Churn out the useful stuff
		$homepageArticles = $homepageArticleModel->getHomepageArticles(5);
		$theEssentials = $itemsModel->getIndexFeatured('landingpage', 0, 6);
		
		$this->_doc->setTitle('Working Moms - Working Mothers Community');
		$this->_doc->setAdPage(OpenX::PAGE_INDEX);

		// Load page template
		include (BLUPATH_TEMPLATES . '/index/landing.php');
	}
	
	/**
	 *	Index page: featured question module.
	 */
	protected function landing_featured_question(){
		
		/* Get data */
		$itemsModel = $this->getModel('items');
		$featuredQuestion = $itemsModel->getIndexFeatured('question');
		$answers = $featuredQuestion->getComments();
		if (Utility::iterable($answers)){
			$featuredAnswer = array_shift($answers);
		}
		$link = Uri::build($featuredQuestion);
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/index/landing/featured_question.php');
		
	}

	/**
	 * Tell a friend
	 */
	public function tellafriend()
	{
		// Get tell action
		$action = '';
		if (isset($this->_args[0]) && isset($this->_args[1])) {
			$action = $this->_args[0];
			$itemId = $this->_args[1];
		}

		// Get user details and friends if possible
		$user = BluApplication::getUser();
		if ($user) {
			$sharer_name = $user->name;
			$sharer_email = $user->email;
			$friends = $user->getFriends();
		} else {
			$sharer_name = '';
			$sharer_email = '';
			$friends = null;
		}

		// Get item related to action
		if ($action == 'share') {
			$itemsModel = $this->getModel('items');
			$item = $itemsModel->getItem($itemId);

		// Get group
		} elseif ($action == 'group') {

			// Must have user
			if (!$this->_requireUser('Please log in to send invites to your group.')) {
				return false;
			}

			// Get group details
			$groupsModel = $this->getModel('groups');
			$group = $groupsModel->getGroup($itemId);

			// Check permission to send invite
			if (!$user || !$group || !$group['isMember']) {
				Messages::addMessage('Sorry, you do not have permission to send invites to that group.', 'error');
				return $this->_redirect('/groups/detail/'.$itemId);
			}
		}

		// Get friends/contacts
		$checkedFriends = Request::getVar('group_friends', array());
		$emails = Request::getVar('contact_email', null);
		$names = Request::getVar('contact_name', null);
		if (!empty($emails) && !empty($names)) {
			$contacts = array_combine($emails, $names);
		} else {
			$contacts = array();
		}

		// Add breadcrumbs and set title
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Tell a Friend', '/tellafriend/');
		$this->_doc->setTitle('Tell a Friend');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);

		// Load template
		include(BLUPATH_TEMPLATES.'/static/tellyourfriends.php');
	}

	/**
	 * Send tell a friend messages
	 */
	public function tellafriend_submit()
	{

		// Get data
		$action = Request::getString('action', 'share');
		$message = Request::getString('form_message');
		$itemType = Request::getString('item_type', 'little something');
		$itemId = Request::getString('item_id');
		$sharer_name = Request::getString('form_sharer_name');
		$sharer_email = Request::getString('form_sharer_email');

		// Check we have permission to send group invites
		if ($action == 'group') {

			// Get group details
			$groupsModel = $this->getModel('groups');
			$group = $groupsModel->getGroup($itemId);

			// Check permission
			$user = BluApplication::getUser();
			if (!$user || !$group || ($group['owner'] != $user->userid)) {
				Messages::addMessage('Sorry, you must be the group owner in order to send invites.', 'error');
				return $this->_showMessages('tellafriend', 'tellafriend');
			}
		}

		// Get contacts to send to
		$emails = Request::getVar('contact_email');
		$names = Request::getVar('contact_name');
		$contacts = array_combine($emails, $names);

		// Get friends and add to contacts
		$friends = Request::getVar('friends');
		$personModel = $this->getModel('person');
		if (!empty($friends)) {
			foreach ($friends as $friendId) {
				$friend = $personModel->getPerson(array('member' => $friendId));
				$contacts[$friend->email] = $friend->name;
			}
		}

		// Determine subject and thanks message
		switch ($action) {
			case 'share':
				$subject = $sharer_name.' wants to share '.Utility::prefixIndefiniteArticle($itemType).' with you!';
				$thanksMsg = 'Thank you for sending this '.$itemType.' with your friend'.((count($contacts) > 1) ? '' : 's');
				break;
			case 'group':
				$subject = $sharer_name.' has invited you to '.$group['name'].'!';
				$thanksMsg = 'Thank you for inviting your friend'.((count($contacts) > 1) ? '' : 's');
				break;
			default:
				$subject = $sharer_name.' has invited you to join '.BluApplication::getSetting('storeName');
				$thanksMsg = 'Thank you for inviting your friend'.((count($contacts) > 1) ? '' : 's');
				break;
		}


		// Send message to contacts
		foreach ($contacts as $email => $name) {
			$emailMsg = new Email();
			if ($emailMsg->isEmailAddress($email)) {
				$emailMsg->setRecipient($email, $name);
				$emailMsg->setSender($sharer_email, $sharer_name);
				$emailMsg->setSubject($subject);
				$emailMsg->setBody(nl2br(str_replace('[Friend]', $name, $message)));
				$emailMsg->send(true);
			}
		}

		// Add group invites
		if ($action == 'group') {
			$groupsModel->addInvites($itemId, $friends);
		}

		// Redirect
		return $this->_redirect('/', $thanksMsg);
	}

	/**
	 * Contact form
	 */
	public function contact()
	{
		// Get data from request
		$subject = Request::getString('form_subject');
		$email = Request::getString('form_email');
		$name = Request::getString('form_name');
		$message = Request::getString('form_message');

		// Set up document
		BluApplication::getBreadcrumbs()->add('Contact Us', '/contact/');
		$this->_doc->setTitle('Contact Us');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);

		// Load template
		include(BLUPATH_TEMPLATES.'/static/contact.php');
	}

	/**
	 * Send contact form
	 */
	public function contact_send()
	{
		// Check captcha
		$captcha = Request::getString('form_captcha');
		$validation['captcha'] = $this->_validateWithMessage(
			$captcha,
			'validate-captcha',
			'The 5 digit code you have entered is not correct. Please try again.'
		);

		// Show errors
		if (in_array(false, $validation)) {
			return $this->_showMessages('contact', 'contact');
		}

		$subject = Request::getString('form_subject');
		$email = Request::getString('form_email');
		$name = Request::getString('form_name');
		$message = Request::getString('form_message');

		$emailMsg = new Email();
		$vars = array(
			'senderSubject' => $subject,
			'senderName' => $name,
			'senderEmail' => $email,
			'senderMessage' => $message
		);
		BluApplication::getModel('contact')->saveContactForm($name, $email, BluApplication::getUser()->userid, $message, $subject);
		

		// One for you
		/////// $emailMsg->quickSend(BluApplication::getSetting('contactEmail'), "Work It, Mom!", "Work It, Mom! Contact - ".$subject, 'contact', $vars);
		// One for me
		$emailMsg->quickSend('editors@junemedia.com', "Work It, Mom!", "Work It, Mom! Contact - ".$subject, 'contact', $vars);


		return $this->_redirect(SITEURL . '/contact/', "Thank you for getting in touch with Work It, Mom! We will do our best to reply within 48 business hours.");
	}

	/**
	 * Report abuse
	 */
	public function abuse()
	{
		// Must be signed in
		if (!$reporter = $this->_requireUser('Please sign in to report abuse.')) {
			return false;
		}
		$email = Request::getString('form_email', $reporter->email);
		$name = Request::getString('form_name', $reporter->name);
		$message = Request::getString('form_message');
		$oType = 'Abuse';

		// If reporting a specific item
		$offendingItem = false;
		if (isset($this->_args[0])) {
			$offendingItem = BluApplication::getModel('items')->getItem($this->_args[0]);
		}
		if ($offendingItem) {
			$oType = $offendingItem->getType('single');
			$oTitle = $offendingItem->title;
			$oAuthorName = $offendingItem->author->name;
		}

		// Set up doc and load template
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Report ' . ucwords($oType), '/abuse/');
		$this->_doc->setTitle('Report ' . ucwords($oType));
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);
		include(BLUPATH_TEMPLATES.'/static/abuse.php');
	}

	public function abuse_submit()
	{
		// Must be signed in
		if (!$reporter = $this->_requireUser('Please sign in to report abuse.')) {
			return false;
		}

		// Get data from request
		$email = Request::getString('form_email');
		$name = Request::getString('form_name');
		$message = Request::getString('form_message');

		// Send e-mail
		$emailMsg = new Email();
		$vars = array(
			'senderName' => $name,
			'senderEmail' => $email,
			'senderMessage' => $message
		);
		$emailMsg->quickSend(BluApplication::getSetting('abuseEmail'), 'Work It, Mom!', 'Report of Abuse', 'reportabuse', $vars);

		// Return to homepage
		return $this->_redirect(SITEURL.'/', 'Thank you for submitting your report. We will get back to you as soon as possible if we decide we need to take any further action.');
	}

	/**
	 * Plaxo
	 */
	public function plaxo()
	{
		$this->_doc->setFormat('raw');
		include(BLUPATH_TEMPLATES.'/static/plaxo.php');
	}

	/**
	 * Info
	 */
	public function info()
	{
		// Load template
		if (isset($this->_args[0])) {
			include(BLUPATH_TEMPLATES.'/static/info/'.Utility::cleanFilename($this->_args[0]).'.php');
		}
	}

	public function team()
	{
		BluApplication::getBreadcrumbs()->add('Team', '/team/');
		$this->_doc->setTitle('Team');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);
		include(BLUPATH_TEMPLATES.'/static/team.php');
	}

	public function advisors()
	{
		/* Meta */
		$page = 'Advisors';
		BluApplication::getBreadcrumbs()->add($page, '/' . __FUNCTION__ . '/');
		$this->_doc->setTitle($page);
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/static/advisors.php');
	}

	public function press()
	{
		BluApplication::getBreadcrumbs()->add('Press', '/press/');
		$this->_doc->setTitle('Press');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);

		// Load template
		if (isset($this->_args[0])) {
			include(BLUPATH_TEMPLATES.'/static/press/'.Utility::cleanFilename($this->_args[0]).'.php');
		} else {
			include(BLUPATH_TEMPLATES.'/static/press.php');
		}
	}


	public function unsubscribe()
	{
		BluApplication::getBreadcrumbs()->add('Unsubscribe', '/unsubscribe/');
		$this->_doc->setTitle('Unsubscribe');
		include(BLUPATH_TEMPLATES.'/static/unsubscribe.php');
	}

	public function advertise()
	{
		BluApplication::getBreadcrumbs()->add('Advertise', '/advertise/');
		$this->_doc->setTitle('Advertise');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);
		include(BLUPATH_TEMPLATES.'/static/advertise.php');
	}

	public function partners()
	{
		BluApplication::getBreadcrumbs()->add('Our Friends', '/partners/');
		$this->_doc->setTitle('Our Friends');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);
		include(BLUPATH_TEMPLATES.'/static/partners.php');
	}

	public function links()
	{
		BluApplication::getBreadcrumbs()->add('Links', '/links/');
		$this->_doc->setTitle('Links');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);
		include(BLUPATH_TEMPLATES.'/static/links.php');
	}

	public function submission()
	{
		BluApplication::getBreadcrumbs()->add('Submission Guidelines', '/submission/');
		$this->_doc->setTitle('Submission Guidelines');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);
		include(BLUPATH_TEMPLATES.'/static/submission_guidelines.php');
	}

	public function community()
	{
		BluApplication::getBreadcrumbs()->add('Community Guidelines', '/community/');
		$this->_doc->setTitle('Community Guidelines');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);
		include(BLUPATH_TEMPLATES.'/static/community_guidelines.php');
	}

	public function privacy()
	{
		BluApplication::getBreadcrumbs()->add('Privacy Policy', '/privacy/');
		$this->_doc->setTitle('Privacy Policy');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);
		include(BLUPATH_TEMPLATES.'/static/privacy.php');
	}

	public function terms()
	{
		BluApplication::getBreadcrumbs()->add('Terms of Use', '/terms/');
		$this->_doc->setTitle('Terms of Use');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);
		include(BLUPATH_TEMPLATES.'/static/terms.php');
	}

	public function gettingstarted()
	{
		BluApplication::getBreadcrumbs()->add('Getting Started', '/gettingstarted/');
		$this->_doc->setTitle('Getting Started');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);
		include(BLUPATH_TEMPLATES.'/static/gettingstarted.php');
	}

	public function about()
	{
		BluApplication::getBreadcrumbs()->add('About Us', '/about/');
		$this->_doc->setTitle('About Us');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);
		include(BLUPATH_TEMPLATES.'/static/about.php');
	}

	public function help()
	{
		BluApplication::getBreadcrumbs()->add('Help & FAQs', '/help/');
		$this->_doc->setTitle('Help & FAQs');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);
		include(BLUPATH_TEMPLATES.'/static/help.php');
	}

	public function sitebadges()
	{
		BluApplication::getBreadcrumbs()->add('Site Badges', '/sitebadges/');
		$this->_doc->setTitle('Site Badges');
		$this->_doc->setAdPage(OpenX::PAGE_PRESS);
		include(BLUPATH_TEMPLATES.'/static/sitebadges.php');
	}

	/**
	 *	giveaway page
	 *
	 *	@access public
	 */
	public function giveaway()
	{ 
		Template::set('giveaway', true);
		$this->_doc->setTitle("Giveaway - Work It Mom!");
		include(BLUPATH_TEMPLATES.'/static/giveaway.php');
	}	
}

?>
