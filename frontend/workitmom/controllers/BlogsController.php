<?php

/**
 *	Primarily serving Member blogs, although bung together with a couple of Featured blog listings.
 */
class WorkitmomBlogsController extends WorkitmomCommentsController {

	/**
	 *	The blog.
	 */
	protected $_blog;

	/**
	 *	The blog post.
	 */
	protected $_post;

	/**
	 *	Browse page.
	 */
	public function view(){

		/* Add breadcrumb */
		BluApplication::getBreadcrumbs()->add('Blogs', '/blogs/');

		/* Get models */
		$blogsModel = $this->getModel('blogs');
		$bloggersModel = $this->getModel('bloggers');

		/* Get featured blogs */
		$popularPosts = $bloggersModel->getMostCommentedPosts(0, 8, array('within_days' => 7));

		/* Get member blogs */
		$spotlightBlog = $blogsModel->getSpotlight();

		/* Set page title */
		$this->_doc->setTitle('Blogs');
		$this->_doc->setAdPage(OpenX::PAGE_ARTICLE);

		/* Load page template */
		include(BLUPATH_TEMPLATES . '/blogs/landing.php');
	}

	/**
	 *	Landing: featured blogs module.
	 */
	public function landing_featured(){

		/* Get parameters */
		$limit = 5;
		$total = null;
		$page = Request::getInt('featured_blogs_page', 1);

		/* Get blogs */
		$blogsModel = $this->getModel('blogs');
		$featuredBlogs = $blogsModel->getFeatured(($page - 1) * $limit, $limit, $total);

		/* Set pagination */
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '?featured_blogs_page='
		));

		/* Load template */
		include(BLUPATH_TEMPLATES . '/blogs/landing/featured.php');

	}

	/**
	 *	Landing: member blogs.
	 */
	protected function landing_members(){

		/* Get data */
		$blogsModel = $this->getModel('blogs');
		$latestBlogs = $blogsModel->getLatest(0, 5);
		$latestPosts = array();
		foreach($latestBlogs as $blog){
			$latestPosts[] = $blog->getLatestPost();
		}
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/blogs/landing/latest.php');

	}

	/**
	 *	Featured blogs browse page.
	 */
	public function featured(){

		/* Get parameters */
		$page = Request::getInt('page', 1);
		$limit = null;
		$total = null;

		/* Add breadcrumbs - "Featured Blogs" */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Blogs', '/blogs/');
		$breadcrumbs->add('Featured Blogs', '/blogs/featured/');

		/* Get models */
		$blogsModel = $this->getModel('blogs');

		/* Get data */
		$blogs = $blogsModel->getFeatured(0, $limit, $total);

		/* Set page title */
		$this->_doc->setTitle('Featured Blogs');
		$this->_doc->setAdPage(OpenX::PAGE_ARTICLE);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/blogs/featured_blogs.php');

	}

	public function featured_individual($blog){

		/* Alternating colours */
		static $alt = false;
		$alt = !$alt;

		$post = $blog->getLatestPost();
		$blogUrl = $blog->url;
		$postUrl = $post->url;

		/* Load template */
		include(BLUPATH_TEMPLATES . '/blogs/featured/featured_individual.php');
	}

	/**
	 *	Members blogs browse page.
	 */
	public function members(){

		/* Get arguments */
		
		/* Add breadcrumbs - "Member Blogs" */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Blogs', '/blogs/');
		$breadcrumbs->add('Member Blogs', '/blogs/members/');

		/* Set page title */
		$this->_doc->setTitle('Member Blogs');
		$this->_doc->setAdPage(OpenX::PAGE_ARTICLE);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/blogs/member_blogs.php');
	}
	
	/**
	 *	Member blog listing - box.
	 */
	public function members_listing(){
		
		/* Get parameters */
		$page = Request::getInt('page', 1);
		$limit = BluApplication::getSetting('listingLength', 9);
		$sort = Request::getString('sort');
		if (!in_array($sort, array('date', 'comments'))){
			$sort = BluApplication::getSetting('listingSort');
		}
		$total = null;

		/* Get models */
		$blogsModel = $this->getModel('newblogs');
		
		/* Get data */
		$options = array();
		switch($sort){
			case 'comments':
				$options['order'] = 'comments';
				$options['direction'] = 'desc';
				break;
				
			case 'date':
			default:
				$options['order'] = 'date';
				$options['direction'] = 'desc';
				break;
		}
		$posts = $blogsModel->getPosts(($page - 1)*$limit, $limit, $total, $options);
		
		/* Set up pagination */
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '?sort='.$sort.'&amp;page='
		));
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/blogs/members_listing.php');

	}
	
	/**
	 *	Member blog listing - sorter.
	 */
	protected function members_listing_sorter($fetchedSort){

		/* Get possible sort values */
		$sorts = array(
			'date'			=>		'Most Recent',
			'comments'		=>		'Most Comments',
		);

		/* Get parameters */
		$defaultSort = BluApplication::getSetting('listingSort', 'date');
		$sort = $fetchedSort ? $fetchedSort : Request::getString('sort', $defaultSort);	// Use previously fetched sort option, or if it doesn't exist fetch it again.
		$on = in_array($sort, array_keys($sorts));

		/* Load template */
		include(BLUPATH_TEMPLATES . '/site/landing/sorter.php');
	}
	
	/**
	 *	Member blog listing - countstring.
	 */
	protected function members_listing_countstring($pagination){
		
		/* Pagination values */
		$showing_start = $pagination->get('start');
		$showing_end = $pagination->get('end');
		$totalitems = $pagination->get('total');

		/* Display settings */
		$singular = 'Member blog';
		$plural = 'Member blogs';

		/* Load template */
		include(BLUPATH_TEMPLATES . '/site/landing/countstring.php');
	}

	/**
	 *	Member blog listing - individual post.
	 */
	public function members_listing_individual($post){

		/* Alternating colours */
		static $alt = false;
		$alt = !$alt;

		/* Load template */
		include(BLUPATH_TEMPLATES . '/blogs/members/members_individual.php');
	}
	
	/**
	 *	Create a space for an ad underneath the listing sorter.
	 */
	protected function members_listing_ad() {
		echo Template::makeAd(OpenX::WEBSITE_LEFT_BANNER_1, OpenX::PAGE_BLOGS);
	}

	/**
	 *	A member's blog.
	 */
	public function member_blog()
	{
		/* Get arguments */
		$args = $this->_args;

		/* Parse arguments */
		$username = Utility::is_loopable($args) ? array_shift($args) : null;
		if (!$username) {
			return $this->members();
		}

		/* Get blogger */
		$personModel = $this->getModel('person');
		$blogger = $personModel->getPerson(array('username' => $username));
		if (!$blogger){ return $this->_errorRedirect(); }

		/* Get blog */
		$blogsModel = $this->getModel('blogs');
		$this->_blog = $blogsModel->getBlog('member', $blogger->contentcreatorid);
		if (!$this->_blog){ return $this->_errorRedirect(); }

		/* Add breadcrumbs */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Blogs', '/blogs/');
		$breadcrumbs->add('Member Blogs', '/blogs/members/');
		$breadcrumbs->add($this->_blog->title, '/blogs/members/' . $this->_blog->author->username);

		/* Set page title */
		$this->_doc->setTitle($this->_blog->title);
		
		/* Some display data */
		$author = $this->_blog->author;

		/* Load page template */
		include(BLUPATH_TEMPLATES . '/blogs/member_blog/details.php');
	}
	
	/**
	 *	Member blog browse page - preview posts.
	 */
	public function member_blog_posts(){
	
		/* Get arguments */
		$args = $this->_args;
		$page = Request::getInt('page', 1);
		$limit = BluApplication::getSetting('bigListingLength');
		$offset = ($page - 1) * $limit;

		/* Parse arguments */
		$username = Utility::is_loopable($args) ? array_shift($args) : null;
		if (!$username) {
			return $this->members();
		}
		
		/* Get blogger */
		$personModel = $this->getModel('newperson');
		$blogger = $personModel->getPerson(array('username' => $username));
		if (!$blogger){ return $this->_errorRedirect(); }
		
		/* Get data */
		$newblogsModel = $this->getModel('newblogs');
		$total = null;
		$posts = $newblogsModel->getLatestUserPosts($blogger['UserID'], $offset, $limit, $total);
		
		/* Output results */
		if (Utility::iterable($posts)){
		
			/* Get pagination */
			$pagination = Pagination::simple(array(
				'limit' => $limit,
				'total' => $total,
				'current' => $page,
				'url' => '?page='
			));
			
			/* Output */
			foreach($posts as $post){
				$this->member_blog_post_preview($post);
			}
			echo $pagination->get('buttons');
			
		} else {
			
			/* Get user */
			$author = $this->_blog->author;
			
			/* Details */
			$userModel = $this->getModel('user');
			$isSelf = $userModel->isSelf($author);
			
			/* Load 'no posts' template */
			include(BLUPATH_TEMPLATES.'/blogs/member_blog/empty.php');
			
		}
		
	}

	/**
	 *	Fetch the relevant member blog.
	 */
	protected function member_blog_get(){
		return $this->_blog;
	}

	/**
	 *	Member blog browse page - preview blog block.
	 */
	public function member_blog_post_preview($post){

		/* Load template */
		include (BLUPATH_TEMPLATES . '/blogs/member_blog/preview.php');

	}

	/**
	 *	Blog heading block.
	 */
	protected function member_blog_heading(){

		/* Get blog */
		$blog = $this->member_blog_get();

		// Is user subscribed?
		$user = BluApplication::getUser();
		if ($user) {
			$itemsModel = $this->getModel('newitems');
			$isSubscribed = $itemsModel->isSubscribed($user->userid, $blog->author->contentcreatorid);
		} else {
			$isSubscribed = false;
		}

		/* Load template */
		include(BLUPATH_TEMPLATES . '/blogs/blocks/title.php');

	}

	/**
	 *	Display individual member blog POST page.
	 */
	public function member_blog_post(){

		/* Get arguments */
		$args = $this->_args;

		/* Parse arguments */
		$postid = Utility::is_loopable($args) ? (int) array_shift($args) : null;
		if (!$postid){ return $this->member_blog(); }
		$page = Utility::iterable($args) && is_numeric(Utility::getLast($args)) ? (int) Utility::getLast($args) : 1;

		/* Get blog post */
		$blogsModel = $this->getModel('blogs');
		$this->_post = $blogsModel->getMemberBlogPost($postid);
		$this->_blog = $this->_post->getBlog();

		/* Add breadcrumbs */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Blogs', '/blogs/');
		$breadcrumbs->add('Member Blogs', '/blogs/members/');
		$breadcrumbs->add($this->_blog->title, '/blogs/member_blog/' . $this->_post->author->username);
		$breadcrumbs->add($this->_post->title, '/blogs/members/' . $this->_post->author->username . '/' . $this->_post->id);

		/* Update view count. */
		$this->_post->increaseViews();

		/* Set page title */
		$this->_doc->setTitle($this->_post->title . BluApplication::getSetting('titleSeparator') . $this->_blog->title);
		$this->_doc->setAdPage(OpenX::PAGE_MEMBERS);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/blogs/member_blog_post/details.php');

	}

	/**
	 *	Return the blog post.
	 */
	protected function member_blog_post_get(){
		return $this->_post;
	}

	/**
	 *	Blog post page - content
	 */
	public function member_blog_post_body($page = 1){

		/* Load data */
		$post = $this->member_blog_post_get();
		$limit = BluApplication::getSetting('articleLength', 450);

		/* Prepare pagination */
		$pagination = Pagination::text(array(
			'limit' => $limit,
			'content' => $post->body,
			'current' => (int) $page,
			'url' => '/blogs/member_blog_post/'.$post->id.'/'
		));
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/blogs/member_blog_post/details/body.php');

	}

	/**
	 *	Blog post page - Share block
	 */
	protected function member_blog_post_share(){

		/* Get data */
		$post = $this->member_blog_post_get();
		$share_id = $post->id;
		$share_link = Uri::build($post);
		$share_title = $post->title;
		$rating = $post->rating;

		/* Has been bookmarked? */
		if($user = BluApplication::getUser()) {
			$itemsModel = $this->getModel('items');
			$isBookmarked = $itemsModel->isBookmarked($post->id, $user->userid);
		} else $isBookmarked = false;

		/* Load template */
		include(BLUPATH_TEMPLATES . '/site/details/share.php');

	}




	###							CommentsController							###

	/**
	 *	Required by CommentsController.
	 */
	protected function get_commentable_object(){

		/* Get post */
		if (!isset($this->_post) || !$this->_post){

			/* Get arguments */
			$args = $this->_args;

			/* Parse arguments */
			$postid = Utility::is_loopable($args) ? (int) array_shift($args) : null;
			if (!$postid){ return null; }

			/* Get blog post */
			$blogsModel = $this->getModel('blogs');
			$post = $blogsModel->getMemberBlogPost($postid);

			/* Set */
			$this->_post = $post;

		}

		/* Return */
		return $this->_post;

	}

	###							End CommentsController							###

	public function vote(){
		//echo 'vote';
	}




	/**
	 * Subscribe to a blog
	 */
	public function subscribe()
	{
		if (!$user = $this->_requireUser('Please log in or sign up to subscribe to a member blog.')) {
			return false;
		}

		// Get blogger details
		$username = $this->_args[0];
		$personModel = $this->getModel('person');
		$blogger = $personModel->getPerson(array('username' => $username));

		// Add subscription
		$itemsModel = $this->getModel('newitems');
		$itemsModel->subscribe($user->userid, $blogger->contentcreatorid, 'note');

		// Redirect to blog with message
		$this->_redirect('/account/blogs?tab=subscribed', 'Your subscription has been added.');
	}

	/**
	 * Unsubscribe from a blog
	 */
	public function unsubscribe()
	{
		if (!$user = $this->_requireUser('Please log in to remove a blog subscription.')) {
			return false;
		}

		// Get blogger details
		$username = $this->_args[0];
		$personModel = $this->getModel('person');
		$blogger = $personModel->getPerson(array('username' => $username));

		// Remove subscription
		$itemsModel = $this->getModel('newitems');
		$itemsModel->unsubscribe($user->userid, $blogger->contentcreatorid, 'note');

		// Redirect to blog with message
		$this->_redirect('/account/blogs?tab=subscribed', 'Your subscription has been removed.');
	}

}

?>
