<?php

/**
 *	This controller is for all things that might be used site-wide. Nothing else, please.
 *	NO BODGING ALLOWED.
 *
 * 	No point of duplicating code, so just stick it in here.
 */

class WorkitmomSiteModules extends ClientFrontendController {

	/**
	 *	Full category name.
	 */
	private $_category;

	/**
	 *	(Empty) constructor needed for ReflectionClass.
	 */
	public function __construct(){}

	/**
	 *	Basic mutator method..
	 */
	public function set($key, $value){
		switch (strtolower($key)){
			case 'category':
				$this->_category = $value;
				break;

			default:
				break;
		}
		return $this;
	}

	/**
	 *	Basic accessor method.
	 */
	public function get($key){
		switch(strtolower($key)){
			case 'category':
				return isset($this->_category) ? $this->_category : null;
				break;

			default:
				return null;
				break;
		}
	}

	/**
	 *	"Useful resources" - takes one featured item of every item type.
	 */
	public function useful_resources(){
		
		/* Get model */
		$itemsModel = $this->getModel('items');
		$itemsModel->set('category', $this->get('category'));
		
		/* Get data */
		$resources = $itemsModel->getResources();
		
		/* No data? */
		if (!DEBUG && !Utility::iterable($resources)){
			return false; 
		}
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/site/modules/useful_resources.php');
		
	}

	/**
	 *	"From our Bloggers" module.
	 */
	public function from_our_bloggers(){
		
		/* Load model */
		$blogsModel = $this->getModel('blogs');
		$blogsModel->set('category', $this->get('category'));
		
		/* Get data */
		$featuredBlogs = $blogsModel->getFeatured(0, 7);
		
		/* No data */
		if (!DEBUG && !Utility::iterable($featuredBlogs)){
			return false;
		}
		
		/* Load template */
		include (BLUPATH_TEMPLATES.'/site/modules/from_our_bloggers.php');
		
	}

	/**
	 *	"Featured Question" module.
	 */
	public function featured_question($trimQuestionEvenMore = false, $trimAnswer = false){
		
		/* Get model */
		$itemsModel = $this->getModel('items');
		$itemsModel->set('category', $this->get('category'));
		
		/* Get data */
		$featuredQuestion = $itemsModel->getLatest('question');
		$featuredComments = $featuredQuestion->getComments();
		$featuredAnswer = array_shift($featuredComments);
		
		/* No data? */
		if (!DEBUG && !$featuredQuestion){
			return false;
		}
		
		/* Prepare and load template */
		$link = Uri::build($featuredQuestion);
		include(BLUPATH_TEMPLATES.'/site/modules/featured_question.php');
		
	}

	/**
	 *	"Featured Lifesaver" module. (rotating - i.e. random)
	 *
	 *	@args (bool) useButton: shows the 'see all' button in the top corner.
	 */
	public function featured_lifesaver($useButton = true){
		// Prepare models and parameters
		$itemsModel = $this->getModel('items');
		$itemsModel->set('category', $this->get('category'));
		$pool = 10;			// the haystack size

		// get a handful of featured lifesavers
		$featuredLifesavers = $itemsModel->getLatest('lifesaver', 0, $pool);
		if (!DEBUG && !Utility::iterable($featuredLifesavers)){ return false; }

		// pick a random one out of them
		$featuredLifesaver = $featuredLifesavers[array_rand($featuredLifesavers)];

		// Exit
		include(BLUPATH_TEMPLATES.'/site/modules/featured_lifesaver.php');
	}

	/**
	 *	"Featured Slideshow" module.
	 */
	public function featured_slideshow(){
		/* Get model */
		$itemsModel = $this->getModel('items');
		$itemsModel->set('category', $this->get('category'));
		
		/* Get data */
		$featuredSlideshow = $itemsModel->getIndexFeatured('slideshow');
		if (!DEBUG && !$featuredSlideshow){
			return false;
		}
		
		/* Display data */
		$link = Uri::build($featuredSlideshow);
		include(BLUPATH_TEMPLATES.'/site/modules/featured_slideshow.php');
		
	}
	
	/**
	 *	NTENT tags
	 */
	public function ad_ntent_right()
	{
		// Load template
		//include(BLUPATH_TEMPLATES . '/site/modules/ad_ntent_right.php');
	}

	/**
	 *	"Newest Members" module.
	 */
	public function newest_members(){
		
		/* Get model */
		$personModel = $this->getModel('person');
		
		/* Get data */
		$newestMembers = $personModel->getLatest(0, 5);
		if (!DEBUG && !Utility::iterable($newestMembers)){
			return false;
		}
		
		/* Display data */
		include(BLUPATH_TEMPLATES.'/site/modules/newest_members.php');
	}

	/**
	 *	"Working Mom Interviews"
	 */
	public function working_mom_interviews(){
		
		/* Get model */
		$itemsModel = $this->getModel('items');
		$itemsModel->set('category', $this->get('category'));
		
		/* Get data */
		$featuredInterview = $itemsModel->getLatest('interview');
		if (!DEBUG && !$featuredInterview){
			return false;
		}
		
		/* Display data */
		$link = Uri::build($featuredInterview);
		include(BLUPATH_TEMPLATES . '/site/modules/mom_interview.php');
		
	}

	/**
	 * Sound off (group topics)
	 */
	public function sound_off($limit = 3)
	{
		/* Get parameters */
		$category = $this->get('category');
		
		/* Get model */
		$groupsModel = $this->getModel('groups');
		
		/* Get discussion topics */
		//$featuredTopics = $groupsModel->getRecentlyActiveCategoryTopics($category, $limit);
		$total = null;
		$featuredTopics = $groupsModel->getTopics(0, (int) $limit, $total, array(
			'category_name' => $category,
			'exclude_groups' => $groupsModel->getDislikedGroups(),
			'order' => 'latest_post'
		));

		/* Load template */
		include(BLUPATH_TEMPLATES.'/site/modules/sound_off.php');
	}

	/**
	 *	Search box
	 */
	public function search($type, $header = null, $label = true)
	{
		// Load template
		include(BLUPATH_TEMPLATES . '/site/modules/search.php');
	}
	
	
	
	
	
	
	###							SIDEBAR MODULES							###

	/**
	 *	Daily Inspiration
	 */
	public function daily_inspiration(){
		// $itemsModel = $this->getModel('items')->set('category', $this->get('category'));
		// $resources = $itemsModel->getResources();
		// if (!DEBUG && !Utility::iterable($resources)){ return false; }
		
		$dailyinspirationModel = $this->getModel('dailyinspiration');
		$dailyInspiration = $dailyinspirationModel->getDailyInspiration();
		
		include(BLUPATH_TEMPLATES.'/site/modules/daily_inspiration.php');
	}

	/**
	 *	"Working Mom News" block
	 */
	public function working_mom_news(){
		
		/* Get model */
		$itemsModel = $this->getModel('items');
		$itemsModel->set('category', $this->get('category'));
		
		/* Get data */
		$latestNews = $itemsModel->getLatest('news', 0, 4);
		
		/* Display data */
		include(BLUPATH_TEMPLATES . '/site/modules/working_mom_news.php');
		
	}

	/**
	 *	Newsletter
	 */
	public function newsletter(){
		/* Load template */
		include(BLUPATH_TEMPLATES.'/site/modules/newsletter.php');
	}

	/**
	 *	300x250 ad
	 */
	public function ad_mini($adLocation)
	{
		// Load template
		include(BLUPATH_TEMPLATES . '/site/modules/ad_mini.php');
	}
	
	/**
	 *	300x250 ad
	 */
	public function ad_zedo($adLocation)
	{
		// Load template
		include(BLUPATH_TEMPLATES . '/site/modules/ad_zedo.php');
	}

	/**
	 *	Catch your breath
	 */
	public function catch_your_breath()
	{
		// Redirect to 'Write a blog'
		$this->write_a_blog();
	}
	
	/**
	 *	Write a blog.
	 */
	public function write_a_blog(){
		// Load template
		include(BLUPATH_TEMPLATES . '/site/modules/write_a_blog.php');
	}

	/**
	 *	Two skyscraper ads
	 */
	public function ad_skyscraper($adLocation)
	{
		// Load template */
		include(BLUPATH_TEMPLATES . '/site/modules/ad_skyscraper.php');

	}

	/**
	 *	Marketplace
	 */
	public function marketplace()
	{
		// Get data
		$marketplaceModel = $this->getModel('marketplace');
		$total = null;
		$listings = $marketplaceModel->getListings(0, 4, $total, 'featured');

		// Load template
		include(BLUPATH_TEMPLATES . '/site/modules/marketplace.php');
	}

	/**
	 *	Indulge yourself
	 */
	public function indulge_yourself()
	{
		// Get data here
		$bloggersModel = $this->getModel('bloggers');
		$affordable = $bloggersModel->getBlog(23);
		$posts = $bloggersModel->getLatestBlogPosts(23, 0, 3);
		
		// Load template
	 	include(BLUPATH_TEMPLATES.'/site/modules/indulge_yourself.php');
	}

	/**
	 *	Write an Article module
	 */
	public function write_article()
	{
		// Load template
		include(BLUPATH_TEMPLATES.'/site/modules/write_article.php');
	}

	/**
	 *	Create a Group module

	 */
	public function create_group()
	{
		// Load template
		include(BLUPATH_TEMPLATES .'/site/modules/create_group.php');
	}

	/**
	 *	Promote your Business module
	 */
	public function promote_your_business()
	{
		// Load template
		include(BLUPATH_TEMPLATES.'/site/modules/promote_your_business.php');
	}

	/**
	 *	Module that gets the most recent	NOT DONE YET
	 */
	public function recent($num)
	{
		?>&clubs; "<?= $num; ?> Most Recent..." Module<?
	}

	/**
	 *	About contributor					NOT DONE YET
	 */
	public function about_contributor()
	{
		?>&clubs; "About Contributor" Module<?
	}
	
	/**
	 *	Tag cloud.
	 */
	public function tag_cloud(array $tags = array()){
		
		/* Load template */
		include(BLUPATH_TEMPLATES . '/site/modules/tag_cloud.php');
		
	}
	
	/**
	 *	Popular featured posts.
	 *
	 *	Goes on Wordpress RHS as a module.
	 */
	public function popular_featured_posts(array $args){
		
		/* Get parameters */
		$num_req = 5;
		
		/* Build criteria */
		$criteria = array();
		
		/* Get data */
		$bloggersModel = $this->getModel('bloggers');
		if (Utility::iterable($args)){
			
			// Get blog identifier
			$blog_id = array_shift($args);
			$blogID = $bloggersModel->getWimFromWordpress((int) $blog_id);
			
			// Exclude "Nov. 9th 2007 Casual Friday" post from 'WIM blog.'.
			if ($blogID == 7){
				$criteria['exclude'] = array(221);
			}
			
			// Get from a single blog.
			$popularPosts = $bloggersModel->getMostCommentedBlogPosts($blogID, 0, $num_req, $criteria);
			
		} else {
			// Get from all blogs.
			$popularPosts = $bloggersModel->getMostCommentedPosts(0, $num_req, $criteria);
		}
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/site/modules/popular_featured_posts.php');
		
	}
	
	/**
	 *	Recent featured posts.
	 *
	 *	Goes on Wordpress blog post, just above comments module (at bottom of page).
	 */
	public function recent_featured_posts(array $args){
		
		/* Get parameters */
		$num_req = 3;
		
		/* Get blog id */
		if (!Utility::iterable($args)){
			// Something has gone wrong?
			return false;
		}
		$blog_id = array_shift($args);
		
		/* Get data - given wordpress blog ID. */
		$bloggersModel = $this->getModel('bloggers');
		$blogID = $bloggersModel->getWimFromWordpress((int) $blog_id);
		$postId = array_shift($args);
		$recentPosts = $bloggersModel->getLatestBlogPosts($blogID, 0, $num_req, array('exclude' => array($postId)));
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/site/modules/recent_featured_posts.php');
		
	}
	
	/**
	 *	Bottom blocks
	 */
	public function bottom_blocks() {
	
		$blocksModel = $this->getModel('bottomblocks');
		$blocks = $blocksModel->getBlocks();
		include(BLUPATH_TEMPLATES.'/site/modules/bottom_blocks.php');
	}
	
	/**
	 *	This week's topic
	 */
	public function this_weeks_topic() {
		
		$weeklyTopic = $this->getModel('blogs')->getWeeklyTheme();
		include(BLUPATH_TEMPLATES.'/site/modules/this_weeks_topic.php');
		
	}

}


?>
