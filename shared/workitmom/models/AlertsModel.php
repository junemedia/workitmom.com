<?php

/**
 *	For dealing with alerts.
 */
class WorkitmomAlertsModel extends BluModel {

	/**
	 * Alert types
	 *
	 * @var array
	 */
	private $_alertMeta;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Set up alert meta
		$this->_alertMeta = array(
			'questionreply' => array(
				'title' => 'Replies to my questions',
				'link' => '/questions/detail/[itemId]',
				'format_list' => '<a href="[profileLink]">[displayName]</a> replied to your question <a href="[goLink]">[itemTitle]</a>',
				'format_email_daily' => '<a href="[profileLink]">[displayName]</a> replied to your question <a href="[goLink]">[itemTitle]</a>',
				'format_email' => '<a href="[profileLink]">[displayName]</a> replied to your question <a href="[goLink]">[itemTitle]</a> on Work It, Mom!',
				'format_emailsubject' => '[displayName] replied to your question on Work It, Mom!'
			),
			'articlereply' => array(
				'title' => 'Comments on my articles',
				'link' => '/articles/detail/[itemId]',
				'format_list' => '<a href="[profileLink]">[displayName]</a> replied to your article <a href="[goLink]">[itemTitle]</a>',
				'format_email_daily' => '<a href="[profileLink]">[displayName]</a> replied to your article <a href="[goLink]">[itemTitle]</a>',
				'format_email' => '<a href="[profileLink]">[displayName]</a> replied to your article <a href="[goLink]">[itemTitle]</a> on Work It, Mom!',
				'format_emailsubject' => '[displayName] replied to your article on Work It, Mom!'
			),
			'notereply' => array(
				'title' => 'Comments on my blog posts',
				'link' => '/blogs/member_blog_post/[itemId]',
				'format_list' => '<a href="[profileLink]">[displayName]</a> replied to your blog post <a href="[goLink]">[itemTitle]</a>',
				'format_email_daily' => '<a href="[profileLink]">[displayName]</a> replied to your blog post <a href="[goLink]">[itemTitle]</a>',
				'format_email' => '<a href="[profileLink]">[displayName]</a> replied to your blog post <a href="[goLink]">[itemTitle]</a> on Work It, Mom!',
				'format_emailsubject' => '[displayName] replied to your blog post on Work It, Mom!'
			),
			'newgroupdiscussion' => array(
				'title' => 'New discussions in subscribed groups',
				'link' => '/groups/discussion/[topicId]',
				'format_list' => '<a href="[profileLink]">[displayName]</a> started a new discussion <a href="[goLink]">[topicTitle]</a> in [groupTitle]',
				'format_email_daily' => '<a href="[profileLink]">[displayName]</a> started a new discussion <a href="[goLink]">[topicTitle]</a> in [groupTitle]',
				'format_email' => '<a href="[profileLink]">[displayName]</a> started a new discussion <a href="[goLink]">[topicTitle]</a> in [groupTitle] on Work It, Mom!',
				'format_emailsubject' => '[displayName] posted in the [topicTitle] discussion on Work It, Mom!'
			),
			'groupdiscussion' => array(
				'title' => 'Reply to subscribed group discussion',
				'link' => '/groups/discussion/[topicId]',
				'format_list' => '<a href="[profileLink]">[displayName]</a> posted in the <a href="[goLink]">[topicTitle]</a> discussion',
				'format_email_daily' => '<a href="[profileLink]">[displayName]</a> posted in the <a href="[goLink]">[topicTitle]</a> discussion',
				'format_email' => '<a href="[profileLink]">[displayName]</a> posted in the <a href="[goLink]">[topicTitle]</a> discussion on Work It, Mom!',
				'format_emailsubject' => '[displayName] posted in the [topicTitle] discussion on Work It, Mom!'
			),
			'friendnote' => array(
				'title' => 'New blog posts by my friends',
				'link' => '/blogs/member_blog_post/[itemId]',
				'format_list' => '<a href="[profileLink]">[displayName]</a> has written a new blog post titled <a href="[goLink]">[itemTitle]</a>',
				'format_email_daily' => '<a href="[profileLink]">[displayName]</a> has written a new blog post titled <a href="[goLink]">[itemTitle]</a>',
				'format_email' => '<a href="[profileLink]">[displayName]</a> has written a new blog post titled <a href="[goLink]">[itemTitle]</a> on Work It, Mom!',
				'format_emailsubject' => '[displayName] has written a new blog post on Work It, Mom!'
			),
			'profilereply' => array(
				'title' => 'Comments on your profile',
				'link' => '/profile',
				'format_list' => '<a href="[profileLink]">[displayName]</a> left a comment on your <a href="[goLink]">profile</a>',
				'format_email_daily' => '<a href="[profileLink]">[displayName]</a> left a comment on your <a href="[goLink]">profile</a>',
				'format_email' => '<a href="[profileLink]">[displayName]</a> left a comment on your <a href="[goLink]">profile</a> on Work It, Mom!',
				'format_emailsubject' => '[displayName] commented on your Work It, Mom! profile'
			),
			'message' => array(
				'title' => 'New message in my inbox',
				'link' => '/account/message/[messageId]',
				'format_list' => '<a href="[profileLink]">[displayName]</a> sent you a <a href="[goLink]">message</a>',
				'format_email_daily' => '<a href="[profileLink]">[displayName]</a> sent you a <a href="[goLink]">message</a>',
				'format_email' => '<a href="[profileLink]">[displayName]</a> has sent you a <a href="[goLink]">message</a> on Work It, Mom!',
				'format_emailsubject' => 'You\'ve received a message from [displayName] on Work It, Mom!'
			),
			'stressed' => array(
				'title' => 'Friend is stressed',
				'link' => '/profile/[username]',
				'format_list' => '<a href="[goLink]">[displayName]</a> is stressed!',
				'format_email_daily' => '<a href="[goLink]">[displayName]</a> is stressed!',
				'format_email' => '<a href="[goLink]">[displayName]</a> is stressed on Work It, Mom!',
				'format_emailsubject' => '[displayName] is stressed on Work It, Mom!'
			),
			'network' => array(
				'title' => 'Friend requests',
				'link' => '/account/friends?tab=requests',
				'format_list' => '<a href="[profileLink]">[displayName]</a> sent you a <a href="[goLink]">friend request</a>',
				'format_email_daily' => '<a href="[profileLink]">[displayName]</a> sent you a <a href="[goLink]">friend request</a>',
				'format_email' => '<a href="[profileLink]">[displayName]</a> sent you a <a href="[goLink]">friend request</a> on Work It, Mom!',
				'format_emailsubject' => '[displayName] wants to be your friend on Work It, Mom!'
			),
			'articlepublished' => array(
				'title' => 'Article is published',
				'link' => '/articles/detail/[itemId]',
				'format_list' => 'Your article "<a href="[goLink]">[itemTitle]</a>" has been published',
				'format_email_daily' => 'Your article "<a href="[goLink]">[itemTitle]</a>" has been published',
				'format_email' => 'Your article "<a href="[goLink]">[itemTitle]</a>" has been published',
				'format_emailsubject' => 'Your article has been published'
			),
			'newspublished' => array(
				'title' => 'News story is published',
				'link' => '/news/detail/[itemId]',
				'format_list' => 'Your news story submission "<a href="[goLink]">[itemTitle]</a>" is now live!',
				'format_email_daily' => 'Your news story submission "<a href="[goLink]">[itemTitle]</a>" is now live!',
				'format_email' => 'Your news story submission "<a href="[goLink]">[itemTitle]</a>" is now live!',
				'format_emailsubject' => 'Your news story submission is now live!'
			),
			/*'tributepublished' => array(
				'title' => 'Mother\'s Day Tribute is published',
				'link' => '/articles/detail/[itemId]',
				'format_list' => 'Your mother\'s day tribute "<a href="[goLink]">[itemTitle]</a>" is now live!',
				'format_email_daily' => 'Your mother\'s day tribute "<a href="[goLink]">[itemTitle]</a>" is now live!',
				'format_email' => 'Your mother\'s day tribute "<a href="[goLink]">[itemTitle]</a>" is now live!',
				'format_emailsubject' => 'Your mother\'s day tribute is now live!'
			),*/
			/*'blognotify' => array(
				'title' => 'Comments on other blog posts',
				'link' => '/blogs/member_blog/[itemId]',
				'format_list' => 'Someone replied to the blog post <a href="[goLink]">[itemTitle]</a>',
				'format_email_daily' => 'Someone replied to the blog post <a href="[goLink]">[itemTitle]</a>',
				'format_email' => 'Someone replied to the blog post <a href="[goLink]">[itemTitle]</a> on Work It, Mom!',
				'format_emailsubject' => 'Someone replied to the blog post [itemTitle] on Work It, Mom!'
			),*/
			'photocomment' => array(
				'title' => 'Comments on my photos',
				'link' => 'DETERMINED DYNAMICALLY',
				'format_list' => '<a href="[profileLink]">[displayName]</a> commented on your <a href="[goLink]">photo</a>!',
				'format_email_daily' => '<a href="[profileLink]">[displayName]</a> commented on your <a href="[goLink]">photo</a>!',
				'format_email' => '<a href="[profileLink]">[displayName]</a> commented on your <a href="[goLink]">photo</a>!',
				'format_emailsubject' => 'Somebody commented on your photo!'
			),
			'friendarticlepublished' => array(
				'title' => 'New articles posted by friends in my network',
				'link' => '/articles/detail/[itemId]',
				'format_list' => '<a href="[profileLink]">[displayName]</a> has written a new article titled <a href="[goLink]">[itemTitle]</a>',
				'format_email_daily' => '<a href="[profileLink]">[displayName]</a> has written a new article titled <a href="[goLink]">[itemTitle]</a>',
				'format_email' => '<a href="[profileLink]">[displayName]</a> has written a new article titled <a href="[goLink]">[itemTitle]</a> on Work It, Mom!',
				'format_emailsubject' => '[displayName] has written a new article on Work It, Mom!'
			)
		);
	}

	/**
	 * Get a single Alert
	 *
	 * @param int User ID
	 * @param int Alert ID
	 * @return array Alert details
	 */
	public function getAlert($userId, $alertId)
	{
		// Alert listing formats
		$formats = array('format_list', 'format_email_daily', 'format_email', 'format_emailsubject');

		// Try and grab from cache
		$cacheKey = 'alert_'.$alertId;
		$alert = $this->_cache->get($cacheKey);
		if ($alert === false) {

			// Load from database
			$query = 'SELECT *
				FROM alerts AS a
				WHERE a.alertID = '.(int)$alertId;
			$this->_db->setQuery($query);
			$alert = $this->_db->loadAssoc();
			if (!$alert) {
				return false;
			}

			// Merge in type meta data
			$alert = array_merge($alert, $this->_alertMeta[$alert['alertType']]);

			// Unserialize details and transform strings
			$alert['alertDetails'] = unserialize($alert['alertDetails']);

			// Get link to member photo object
			if ($alert['alertType'] == 'photocomment') {
				/*
				$photosModel = BluApplication::getModel('photos');
				$memberPhoto = $photosModel->getPhoto('member', $alert['alertDetails']['photoId']);
				$alert['link'] = Uri::build($memberPhoto, false);
				*/
				$photosModel = $this->getModel('newphotos');
				$memberPhoto = $photosModel->getPhoto($alert['alertDetails']['photoId']);
				$alert['link'] = $memberPhoto['link'];

			// Perform standard replacements into link string
			} else {
				foreach ($alert['alertDetails'] as $key => $value) {
					$alert['link'] = str_replace('['.$key.']', $value, $alert['link']);
				}
			}

			// Perform replacements into format strings
			if (isset($alert['alertDetails']['username'])) {
				$alert['alertDetails']['profileLink'] = FRONTENDSITEINSECUREURL.'/profile/'.$alert['alertDetails']['username'];
			}
			foreach ($formats as $format) {
				foreach ($alert['alertDetails'] as $key => $value) {
					$alert[$format] = str_replace('['.$key.']', $value, $alert[$format]);
				}
			}

			// Store in cache
			$this->_cache->set($cacheKey, $alert);
		}

		// Build unique user go links so we can mark alerts read when clicked
		$readKey = $this->_getReadKey($userId, $alertId);
		$alert['goLink'] = '/account/alert_go/'.$alertId.'/'.$userId.'/'.$readKey;

		// Perform replacements into format strings
		foreach ($formats as $format) {
			$alert[$format] = str_replace('[goLink]', FRONTENDSITEINSECUREURL.$alert['goLink'], $alert[$format]);
		}

		// Return alert
		return $alert;
	}

	/**
	 * Generate a read key
	 *
	 * @param int User ID
	 * @param int Alert ID
	 * @return string Read key (hash of the above)
	 */
	protected function _getReadKey($userId, $alertId)
	{
		return md5($alertId.$userId.BluApplication::getSetting('alertReadSalt'));
	}

	/**
	 * Get all alerts for a person
	 *
	 * @param int User ID
	 * @return array Alerts
	 */
	public function getUserAlerts($userId, $offset = null, $limit = null, &$total = null)
	{
		/* Get alert IDs */
		$query = 'SELECT x.alertID
			FROM xrefuseralert AS x
			WHERE x.userID = '.(int)$userId.'
				AND x.hidden = 0
				AND x.seen = 0
			ORDER BY x.xrefuseralertid DESC';
		$this->_db->setQuery($query, $offset, $limit);
		$alerts = $this->_db->loadAssocList('alertID');
		$total = $this->_db->getFoundRows();
		if (!$alerts) {
			return false;
		}

		// Get alert details
		foreach ($alerts as $alertId => &$alert) {
			$alert = $this->getAlert($userId, $alertId);
		}

		return $alerts;
	}

	/**
	 * Check alert read key
	 *
	 * @param int Alert ID
	 * @param int User ID
	 * @param string Read key (hash of the above)
	 * @return bool True on match, false otherwise
	 */
	public function checkReadKey($userId, $alertId, $readKey)
	{
		$correctKey = $this->_getReadKey($userId, $alertId);
		return ($readKey == $correctKey);
	}

	/**
	 * Mark an alert as seen
	 */
	public function markAlertSeen($userId, $alertId)
	{
		$query = 'UPDATE xrefuseralert
			SET seen = 1
			WHERE userID = '.(int)$userId.'
				AND alertID = '.(int)$alertId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Mark all alert as seen
	 */
	public function markAllAlertsSeen($userId)
	{
		$query = 'UPDATE xrefuseralert
			SET seen = 1
			WHERE userID = '.(int)$userId;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Get user alert preferences
	 *
	 * @param int User ID
	 * @return array User alert preferences
	 */
	public function getUserAlertPrefs($userId)
	{
		// Load user preferences, falling back to database defaults
		$query = 'SELECT dap.alertType, uap.public,
				IF (uap.uapID IS NOT NULL, uap.emailAt, dap.emailAt) AS emailAt,
				IF (uap.uapID IS NOT NULL, uap.showAlert, dap.showAlert) AS showAlert
			FROM defaultAlertPrefs AS dap
				LEFT JOIN userAlertPrefs AS uap ON uap.alertType = dap.alertType AND uap.userID = '.(int)$userId;
		$this->_db->setQuery($query);
		$userPrefs = $this->_db->loadAssocList('alertType');

		// Pull in alert type titles from meta
		$prefs = array();
		foreach ($this->_alertMeta as $type => $alertMeta) {

			// Alert meta and hard-coded default preferences
			$prefs[$type] = array(
				'title' => $alertMeta['title'],
				'emailAt' => 'daily',
				'showAlert' => 1,
				'public' => 0
			);

			// Merge in user preferences if we have them
			if (array_key_exists($type, $userPrefs)) {
				$prefs[$type] = array_merge($prefs[$type], $userPrefs[$type]);
			}
		}

		// Return full complement of alert preferences
		return $prefs;
	}

	/**
	 * Save user alert preferences
	 *
	 * @param int User ID
	 * @param array Alert preferences, keyed by alert type
	 * @param array Optional array of preferences fields to update
	 * @return bool True on success, false otherwise
	 */
	public function saveUserAlertPrefs($userId, $prefs, $fields = null)
	{
		// Check we have something to do
		if (empty($prefs)) {
			return false;
		}

		// Default to updating all preference fields
		if (!$fields) {
			$fields = array('emailAt' => 'string', 'showAlert' => 'int', 'public' => 'int');
		}

		// Update preferences for every valid alert type for which we have data
		foreach ($this->_alertMeta as $type => $alertMeta) {
			if (array_key_exists($type, $prefs)) {
				$pref = $prefs[$type];

				// Update DB
				$query = 'REPLACE INTO userAlertPrefs
					SET alertType = "'.Database::escape($type).'",
						userID = '.(int)$userId;
				foreach ($fields as $field => $type) {
					$value = array_key_exists($field, $pref) ? $pref[$field] : 0;
					if ($type == 'string') {
						$query .= ', '.$field.' = "'.Database::escape($value).'"';
					} else {
						$query .= ', '.$field.' = '.(int)$value;
					}
				}
				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}

		return true;
	}

	/**
	 * Create a new alert
	 *
	 * @param int Alert type
	 * @param array Alert details
	 * @param int User ID who caused alert (eg. topic poster)
	 * @param string Alert scope (unused, don't know what it was for)
	 * @return int Alert ID
	 */
	public function createAlert($type, array $details, $alerterId = null, $scope = 'personal')
	{
		// Add alerter details
		if ($alerterId) {

			// Get user details
			$personModel = BluApplication::getModel('newperson');
			$alerter = $personModel->getPerson(array('member' => $alerterId));

			// Add to alert
			$details['userId'] = $alerter['UserID'];
			$details['username'] = $alerter['username'];
			$details['displayName'] = $alerter['name'];
		}

		// Add alert
		$query = 'INSERT INTO alerts
			SET alertType = "'.Database::escape($type).'",
				alertTime = NOW(),
				alertDetails = "'.Database::escape(serialize($details)).'",
				alertScope = "'.Database::escape($scope).'"';
		$this->_db->setQuery($query);
		$this->_db->query();

		return $this->_db->getInsertID();
	}

	/**
	 * Set up a new alert for a user
	 *
	 * @param int Alert ID
	 * @param int User ID
	 * @return bool True on success, false otherwise
	 */
	public function applyAlert($alertId, $userId)
	{
		// Get alert details
		$alert = $this->getAlert($userId, $alertId);

		// Get alert prefs
		$alertPrefs = $this->getUserAlertPrefs($userId);
		$typePrefs = $alertPrefs[$alert['alertType']];

		// Add alert mapping (flag according to prefs)
		$query = 'INSERT INTO xrefuseralert
			SET alertID = '.(int) $alertId.',
				userID = '.(int) $userId.',
				emailed = '.($typePrefs['emailAt'] == 'never' ? 1 : 0).',
				hidden = '.($typePrefs['showAlert'] ? 0 : 1);
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

}

?>