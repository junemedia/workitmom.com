<?php

/**
 *	This basically acts as a template for an item-type browse page. Including the sorting etc.
 */
abstract class WorkitmomItemsController extends WorkitmomCommentsController {

	/**
	 *	The type of (database) item that we are dealing with.
	 */
	protected $_itemtype;

	/**
	 *	Commonly used data, available for all item types.
	 */
	protected $_latest;
	protected $_popular;
	protected $_featured;

	/**
	 *	This is how we (should) display the type of the items in the template.
	 */
	public $itemtype_singular;		// The name of an item
	public $itemtype_plural;		// The name of several items.

	/**
	 *	Somewhere to store a single ItemObject (convenience for the detail page module/functions).
	 */
	protected $_item;

	/**
	 *	The URL of an item.
	 */
	protected function _getItemURL(ItemObject $item)
	{
		return Uri::build($item);
	}

	/**
	 * 	Get data.
	 */
	public function __construct($args)
	{
		/* Frontend controller constructor */
		parent::__construct($args);
		$this->_url = SITEURL.'/'.strtolower($this->_controllerName);

		/* Add breadcrumb */
		BluApplication::getBreadcrumbs()->add($this->itemtype_plural, $this->_url . '/');

		/* Set page title */
		$this->_doc->setTitle($this->itemtype_plural);
	}

	/**
	 *	Redirect to default browse page (see child controller -> view).
	 */
	public function browse()
	{
		return $this->_redirect($this->_url);
	}


	###							CommentsController							###

	/**
	 *	Overrides CommentsController.
	 */
	protected function comments_add(array $options = array())
	{
		/* Add option */
		$options = array_merge($options, array('type' => $this->_itemtype));

		/* Display */
		return parent::comments_add($options);
	}

	/**
	 *	Go through existing comments.
	 *	Page number of comments comes from request variable.
	 */
	public function comments_view(array $options = array())
	{
		/* Add options */
		if ($this->_itemtype != 'question'){
			$options = array_merge($options, array('extraCss' => ' small_list'));
		}

		/* Load template */
		return parent::comments_view($options);
	}

	/**
	 *	Required by CommentsController.
	 */
	public function get_commentable_object(){

		/* Get */
		if (!isset($this->_item) || !$this->_item){

			/* Get arguments */
			$args = $this->_args;
			if (empty($args)) { return null; }
			$id = (int) array_shift($args);

			/* Get item */
			$itemsModel = $this->getModel('items');
			$item = $itemsModel->getItem($id);

			/* Set */
			$this->_item = $item;

		}

		/* Return */
		return $this->_item;

	}

	###							End CommentsController							###



	/**
	 *	Place a vote on the item, then redirect back to show success message on template.
	 *
	 *	@args (optional) the rating to pass.
	 */
	public function vote()
	{
		/* Require user */
		if (!$this->_requireUser('Please sign in or register to vote.')) { return false; }
		$user = BluApplication::getUser();

		/* Get arguments */
		$args = $this->_args;
		if (!Utility::is_loopable($args)){
			return $this->_errorRedirect();
		}
		$id = (int) array_shift($args);

		/* Get parameters */
		if (func_num_args() > 0){
			$func_args = func_get_args();
			$override_rating = array_shift($func_args);
		}
		if (isset($override_rating) && $override_rating > 0 && $override_rating < 6){
			$rating = $override_rating;
		} else {
			$rating = Request::getInt('rating', null);
			if (!$rating){
				return $this->_errorRedirect();
			}
		}

		/* Update model */
		$itemsModel = $this->getModel('items');
		$item = $itemsModel->getItem($id);
		$success = $item->addRating($rating, $user);

		/* Load template for detail page */
		$message = ($success ? 'Thank you for rating this ' : 'You have already rated this ') . strtolower($this->itemtype_singular) . '.';
		$messageType = $success ? 'info' : 'error';
		return $this->_redirect('/'.$this->_controllerName.'/detail/'.$id, $message, $messageType);
	}

	/**
	 *	Get the heading for the page
	 */
	public function page_heading($extended = false)
	{
		/* Get data */
		$title = '';
		$template = '';
		switch($this->_itemtype){
			case 'landingpage':
				$title = 'Essential Guides';
				if ($extended){
					$subtitle = $this->detail_item_get()->title;
					$template = '/items/blocks/page_title_essentials_extended.php';
				} else {
					$template = '/items/blocks/page_title_essentials.php';
				}
				break;

			case 'list':
				$title = 'Checklists';
				$template = '/items/blocks/page_title_checklists.php';
				break;

			case 'interview':
				$title = 'Member Interviews';
				$template = '/items/blocks/page_title_interviews.php';
				break;

			case 'question':
				$title = 'Member Questions';
				$template = '/items/blocks/page_title_questions.php';
				break;

			case 'quicktip':
				$title = 'Quick Tips';
				$template = '/items/blocks/page_title_quicktips.php';
				break;

			case 'dailydeal':
				$title = 'Daily Deals';
				$template = '/items/blocks/page_title_dailydeals.php';
				break;

			case 'slideshow':
				$title = 'Slideshows';
				if ($extended){
					$subtitle = 'Quick tips, great products, and useful resources to make your daily juggle easier and more fun!';
					$template = '/items/blocks/page_title_slideshows_extended.php';
				} else {
					$template = '/items/blocks/page_title_slideshows.php';
				}
				break;

			default:
				$title = 'Member Articles';
				$template = '/items/blocks/page_title_article.php';
				break;
		}

		/* Load template */
		include(BLUPATH_TEMPLATES . $template);
	}



	###							BROWSE PAGE + LISTING MODULES							###

	/**
	 *	Listing / browse area for a specific type of item.
	 *	Usually goes on an item browse page.
	 */
	public function listing()
	{
		/* Get request variables */
		$category = Request::getString('category', null);
		$sort = Request::getString('sort', BluApplication::getSetting('listingSort', 'date'));
		$page = Request::getInt('page', 1);

		/* Get parameters. */
		$total = true;
		$limit = BluApplication::getSetting('listingLength', 9);
		switch($sort){
			case 'owner':
				$function = 'Owned';
				break;

			case 'title':
				$function = 'Alphabetical';
				break;

			case 'comments':
				$function = 'MostCommented';
				break;

			case 'votes':
				$function = 'MostVoted';
				break;

			case 'views':
				$function = 'MostViewed';
				break;

			case 'date':
			default:
				$sort = 'date';
				$function = 'Latest';
				break;
		}

		/* Get data. */
		$itemsModel = $this->getModel('items')->set('category', $category);
		$items = $itemsModel->{'get'.$function}($this->_itemtype, ($page - 1) * $limit, $limit, $total);

		/* Prepare pagination */
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '?sort=' . urlencode($sort) . '&amp;category=' . urlencode($category) . '&amp;page='
		));

		/* Load template */
		include($this->listing_template());
	}

	/**
	 *	Default listing (box) template.
	 */
	protected function listing_template(){
		return BLUPATH_TEMPLATES . '/items/landing/box.php';
	}

	/**
	 *	Count string.
	 */
	protected function listing_countstring($pagination)
	{
		/* Pagination values */
		$showing_start = $pagination->get('start');
		$showing_end = $pagination->get('end');
		$totalitems = $pagination->get('total');

		/* Display settings */
		$singular = $this->itemtype_singular;
		$plural = $this->itemtype_plural;

		/* Load template */
		include(BLUPATH_TEMPLATES . '/site/landing/countstring.php');
	}

	/**
	 *	Categories bar.
	 */
	protected function listing_categories($category, $sort)
	{
		// Get user details
		$user = BluApplication::getUser();

		/* Get possible categories */
		$categoryTabs = array(
			'Balancing Act'			=>	'Balancing Act',
			'Career & Money'		=>	'Career &amp; Money',
			'Pregnancy & Parenting'	=>	'Pregnancy &amp; Parenting',
			'Your Business'			=>	'Your Business',
			'Just For You'			=>	'Just For You'
		);
		$this->listing_categories_customise_categories($categoryTabs);

		/* Get parameters */
		$category = Request::getString('category', null);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/items/landing/categories.php');
	}

	/**
	 *	Customise the categories. Override using child class.
	 */
	protected function listing_categories_customise_categories(array &$categories){}

	/**
	 *	Sorter.
	 *
	 *	@args (string) category: the category fetched from $this->listing.
	 *	@args (string) fetchedSort: the sorting option fetched from $this->listing.
	 */
	final protected function listing_sorter($category, $fetchedSort)
	{
		// Get user details
		$user = BluApplication::getUser();

		/* Get possible sort values */
		$sorts = array(
			'title'			=>		'A-Z',
			'date'			=>		'Most Recent',
			'comments'		=>		'Most Comments',
			'views'			=>		'Most Views'
		);
		if ($user && ($this->_itemtype == 'article')) {
			$sorts['owner'] = 'My Articles';
		}
		$this->listing_sorter_customise_sorts($sorts);

		/* Get parameters */
		$defaultSort = BluApplication::getSetting('listingSort', 'date');
		$sort = $fetchedSort ? $fetchedSort : Request::getString('sort', $defaultSort);	// Use previously fetched sort option, or if it doesn't exist fetch it again.
		$on = in_array($sort, array_keys($sorts));

		/* Load template */
		include(BLUPATH_TEMPLATES . '/site/landing/sorter.php');
	}

	/**
	 *	Customise the sorting possibilities. Override using child class.
	 *
	 *	Obviously, the array keys need to correspond to one of the switch case blocks in $this->listing for it to go through.
	 */
	protected function listing_sorter_customise_sorts(array &$sorts){}
	
	/**
	 *	Allow space for an ad to sit under browse/categories bar.
	 */
	protected function listing_ad() {
		include(BLUPATH_TEMPLATES . '/site/ads/WEBSITE_LEFT_BANNER_2.php');
	}

	/**
	 *	Listing.
	 */
	final protected function listing_list(array $things)
	{
		/* Prepare template */
		$css = array('item_list');
		$this->listing_list_customise_css($css);
		$css = Utility::is_loopable($css) ? implode(' ', $css) : null;

		/* Load template */
		if (Utility::is_loopable($things)){
			include(BLUPATH_TEMPLATES . '/site/landing/listing.php');
		} else {
			// No items - A separate template? Or just show nothing?
			echo '';
		}
	}

	/**
	 *	Custom listing CSS classes. Override using child class.
	 */
	protected function listing_list_customise_css(array &$css){}

	/**
	 *	Individual row of the box listing.
	 */
	protected function listing_individual(ItemObject $thing)
	{
		/* Alternating colours */
		static $alt = false;
		$alt = !$alt;

		/* Other data */
		$link = $this->_getItemURL($thing);
		$commentCount = $thing->getCommentCount();

		/* Load template */
		include($this->listing_individual_template());
	}

	/**
	 *	Default template for the individual item in a listing.
	 */
	protected function listing_individual_template()
	{
		return BLUPATH_TEMPLATES . '/items/landing/listing_individual.php';
	}



	###							ITEM DETAIL PAGE + MODULES							###

	/**
	 *	Default DETAIL page.
	 */
	public function detail()
	{
		// Parse arguments
		$args = $this->_args;
		if (empty($args)) {
			$this->_errorRedirect();
		}
		$id = (int) array_shift($args);
		$page = Utility::iterable($args) && is_numeric(Utility::getLast($args)) ? (int) Utility::getLast($args) : 1;

		// Get (and set) the single item
		$itemsModel = $this->getModel('items');
		$item = $this->_item = $itemsModel->getItem($id);

		// Add breadcrumbs
		$this->detail_breadcrumbs();

		/* Set page title */
		$this->_doc->setTitle(Text::trim($item->title, 50));
		$this->_doc->setAdPage(OpenX::PAGE_ARTICLE);

		/* Increment view count */
		$item->increaseViews();

		/* Load template */
		$template = '/items/details.php';
		$cssClass = 'articles';
		$this->detail_template($template, $cssClass);	// Last chance to change template/css class...
		include(BLUPATH_TEMPLATES . $template);
	}

	/**
	 *	For the child controller to add custom breadcrumbs.
	 */
	protected function detail_breadcrumbs(){

		/* Get data */
		$item =& $this->_item;

		/* Add breadcrumbs */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add(Text::trim($item->title, 50), $this->_getItemURL($item));

	}

	/**
	 *	For the child controller to change the detail page template/css class to use.
	 */
	protected function detail_template(&$template, &$cssClass){}

	/**
	 *	Get the individual item.
	 */
	protected function detail_item_get()
	{
		return $this->_item;
	}

	/**
	 *	Get the title block.
	 */
	protected function detail_title()
	{
		/* Format data */
		$item = $this->detail_item_get();
		$commentCount = $item->getCommentCount();

		/* Image - item image, falls back to user images. */
		$imageSize = 75;
		$image = ASSETURL . '/';
		$image .= $item->image ? 'item' : 'user';
		$image .= 'images/' . $imageSize . '/' . $imageSize . '/1/';
		$image .= $item->image ? $item->image : (isset($item->author->image) ? $item->author->image : '');

		/* Image - if you only want to go with user image */
		/*
		$imageSize = 75;
		$image = ASSETURL . '/';
		$image .= 'user';
		$image .= 'images/' . $imageSize . '/' . $imageSize . '/1/';
		$image .= $item->author->image;
		*/

		/* Load template */
		include($this->detail_title_template());
	}

	/**
	 *	Default template for the title of a detail page.
	 */
	protected function detail_title_template()
	{
		return BLUPATH_TEMPLATES . '/items/details/title.php';
	}

	/**
	 *	Get the post block.
	 *	The template also makes calls to retrieve the pullquote and the 'also like' block.
	 *
	 *	@args (int) page: the page to display.
	 */
	public function detail_body($page = 1)
	{
		/* Load data */
		$item = $this->detail_item_get();
		$content = $this->detail_body_content();
		$type = $this->_itemtype;
		$limit = BluApplication::getSetting('articleLength', 450);

		/* Prepare pagination */
		$pagination = Pagination::text(array(
			'limit' => $limit,
			'content' => $content,
			'current' => (int) $page,
			'url' => Uri::build($item).'/'
		));

		/* Load template */
		include($this->detail_body_template());
	}

	/**
	 *	Default content to show as the item body.
	 */
	protected function detail_body_content()
	{
		return $this->detail_item_get()->body;
	}

	/**
	 *	Default body text template.
	 */
	protected function detail_body_template()
	{
		return BLUPATH_TEMPLATES . '/items/details/body.php';
	}

	/**
	 *	Get the 'You may also like' block.
	 *
	 *	@param int Number of items to list.
	 */
	protected function detail_pullquote($num_req = 5)
	{
		/* Get data */
		$item = $this->detail_item_get();
		$relatedItems = $item->getRelated(0, (int) $num_req);

		/* Nothing to show? */
		if (!Utility::iterable($relatedItems)){
			return false;
		}

		/* Load template */
		include($this->detail_pullquote_template());
	}

	/**
	 *	Default pullquote template.
	 */
	protected function detail_pullquote_template()
	{
		return BLUPATH_TEMPLATES . '/items/details/pullquote.php';
	}

	/**
	 *	Get the author block
	 */
	protected function detail_author()
	{
		/* Load data */
		$author = $this->detail_item_get()->author;

		/* Load template */
		include(BLUPATH_TEMPLATES . '/items/details/about_author.php');
	}

	/**
	 *	"Share this article" block.
	 */
	protected function detail_share()
	{
		/* Get data */
		$item = $this->detail_item_get();
		$share_id = $item->id;
		$share_link = $this->_getItemURL($item);
		$share_title = $item->title;
		$rating = $item->rating;

		/* Has been bookmarked? */
		if($user = BluApplication::getUser()) {
			$itemsModel = $this->getModel('items');
			$isBookmarked = $itemsModel->isBookmarked($item->id, $user->userid);
		} else $isBookmarked = false;

		/* Load template */
		include(BLUPATH_TEMPLATES . '/site/details/share.php');
	}

	/**
	 *	Sidebar.
	 *
	 *	Default sidebar for all detail pages.
	 *	Override as necessary.
	 */
	protected function detail_sidebar(){
		$this->sidebar(array(
'slideshow_featured', 'marketplace', 'catch_your_breath'
		));
	}

	/**
	 *	Share this: 'Save'
	 */
	public function bookmark()
	{
		/* Require user */
		if (!$this->_requireUser('Please sign in or register to save '.strtolower($this->itemtype_plural).'.')) { return false; }
		$user = BluApplication::getUser();

		/* Get arguments */
		$args = $this->_args;
		if (!Utility::is_loopable($args)){ $this->_errorRedirect(); }
		$id = (int) array_shift($args);

		$itemsModel = $this->getModel('items');

		if(Request::getString('remove') == 1) { /* Delete bookmark */
			if($itemsModel->isBookmarked($id, $user->userid)) {
				$itemsModel->unbookmarkItem($id, $user->userid);
				return $this->_redirect($this->_url."/detail/".$id, ucfirst($this->itemtype_singular)." removed from saved list.");
			} else return;
		} else {
			if($itemsModel->isBookmarked($id, $user->userid)) { /* Check it's not already bookmarked */
				return $this->_redirect($this->_url."/detail/".$id, "You have already saved this ".$this->itemtype_singular);
			} else if($itemsModel->bookmarkItem($id, $user->userid)) { /* Go on, then */
				return $this->_redirect($this->_url."/detail/".$id, ucfirst($this->itemtype_singular)." saved.");
			} else return;
		}
	}

	/**
	 *	RSS feed for item type.
	 */
	public function rss()
	{
		$this->_doc->setFormat('xml');
		$this->_doc->setMimeType('application/rss+xml');
		
		$total = 0;
		$format = Request::getString('format', 'rss');
		
		/* Get items */
		$itemsModel = $this->getModel('items');
		switch($format)
		{
			case "xml":
				$latestitems = $itemsModel->getLatest($this->_itemtype, 0, 25);
				break;
			
			case "rss":
				$latestitems = $itemsModel->getRssFeed(0, 25, $total);
				break;
		}

		ob_start();

		include (BLUPATH_TEMPLATES . '/items/rss/XML_header.php');
		foreach((array)$latestitems as $li) {
			if(is_array($li))
			{
				$li_title = $li['title'];
				$li_author = $li['author'];
				$li_link = $li['url'];
				$li_description = Text::trim($li['description'])."...";
				$li_date = date("r", strtotime($li['livedate']));
			}
			else
			{
				$li_title = $li->title;
				$li_author = $li->author->name;
				$li_link = SITEINSECUREURL . $this->_getItemURL($li);
				$li_description = Text::trim($li->body);
				$li_date = date("r", strtotime($li->getRawDate()));
			}
			include (BLUPATH_TEMPLATES . '/items/rss/XML_item.php');
		}
		include (BLUPATH_TEMPLATES . '/items/rss/XML_footer.php');

		echo ob_get_clean();
	}

}

?>
