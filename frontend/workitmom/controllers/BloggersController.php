<?php

class WorkitmomBloggersController extends ClientFrontendController {

	/**
	 *	Browse page.
	 */
	public function view()
	{
		if (!isset($this->_args[0])) {
			return $this->_redirect('/');
		}
		
		// Build streaming context with cookies
		$cookies = array();
		if (!empty($_COOKIE)) {
			foreach ($_COOKIE as $name => $cookie) {
				$cookies[] = $name.'='.$cookie;
			}
		}
		$opts = array('http' => array('header' => 'Cookie: '.implode(';', $cookies)));
		$context = stream_context_create($opts);

		// Build url
		$url = SITEINSECUREURL.$_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'&':'?').'wppt=1';

		// Get wordpress blog contet
		$blogContent = file_get_contents($url, false, $context);
		$blogContentArray = explode('WP_DIVIDER', $blogContent);
		$mainBlogContent = $blogContentArray[4];

		// Set up document
		$x_title_array = explode('&raquo;', $blogContentArray[0]);
		$y_title_array = array();
		if(isset($x_title_array[2])) array_push($y_title_array, Text::trim(trim($x_title_array[2]), 50));
		if(isset($x_title_array[0])) array_push($y_title_array, Text::trim(trim($x_title_array[0]), 50));
		$z_title = implode(BluApplication::getSetting('titleSeparator'), $y_title_array);
		$this->_doc->setTitle($z_title);
		$this->_doc->setGenericHeader($blogContentArray[1]);

		// Get blog info
		$blogsModel = $this->getModel('blogs');
		$blogInfo = $blogsModel->getBlogBySlug($this->_args[0]);
		
		$bloggersModel = BluApplication::getModel('bloggers');
		$blogID = $bloggersModel->getWIMFromWordpress($blogInfo['blog_id']);
		
		// Get post info.
		$post = array();
		if (preg_match('/<wppost( (id)="([0-9]+)")? \/>/i', $mainBlogContent, $post) && Utility::iterable($post)){
			$postId = $post[3];
			$post = $bloggersModel->getPost($blogID, $postId);
		}
		
		// Set top nav when we know the blog ID
		//$this->_doc->setAdPage(OpenX::PAGE_ARTICLE);
		$this->_doc->setAdPage(OpenX::PAGE_BLOGS.($blogID ? ':'.$blogID : ''));
		
		// Set breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Blogs', '/blogs/');
		$breadcrumbs->add($blogContentArray[3], $blogContentArray[2]);
		if (isset($post)){
			$breadcrumbs->add($post['title'], $post['url']);
		}

		// Get include: blog header
		ob_start();
		$this->_blogHeaderImage($blogInfo);
		$blogHeaderLump = ob_get_contents();
		ob_end_clean();
		
		// Get include: sidebar - mini ad - EDIT: DEPRECATED - ad now runs off Wordpress OpenX plugin
//		$miniAdLump = Template::makeAd(OpenX::WEBSITE_INLINE_1, OpenX::PAGE_BLOGS.($blogID ? ':'.$blogID : ''));

		// Get include: sidebar - newsletter.
		ob_start();
		$this->sidebar(array('newsletter'), false);
		$newsletterLump = ob_get_clean();

		// Get include: sidebar - featured slideshow.
		ob_start();
		$this->sidebar(array('slideshow_featured'),false);
		$slideShowLump = ob_get_contents();
		ob_end_clean();

		// Get include: sidebar - marketplace listings.
		ob_start();
		$this->sidebar(array('marketplace'),false);
		$marketPlaceLump = ob_get_contents();
		ob_end_clean();

		// Get include: sidebar - most popular posts.
		ob_start();
		$this->sidebar(array(
			array('popular_featured_posts', array('wordpress' => $blogInfo['blog_id']))
		), false);
		$popularFeaturedPostsLump = ob_get_clean();

		// Get include: footer (above comments module) - most recent posts.
		ob_start();
		$args = array();
		$args['wordpress'] = $blogInfo['blog_id'];
		if (isset($post)){
			$args['post'] = $post['id'];
		}
		$this->sidebar(array(
			array('recent_featured_posts', $args)
		), false);
		$recentPostsLump = ob_get_clean();
		
		// Get include: bottom blocks
		ob_start();
		BluApplication::getModules('site')->bottom_blocks();
		$bottomBlocksLump = ob_get_clean();

		// Inject header into content
		$mainBlogContent = str_replace('WP_NEWSLETTER',$newsletterLump, $mainBlogContent);
		$mainBlogContent = str_replace('WP_BLOGIMAGE',$blogHeaderLump, $mainBlogContent);
		$mainBlogContent = str_replace('WP_SLIDESHOW',$slideShowLump, $mainBlogContent);
		//$mainBlogContent = str_replace('WP_MARKETPLACE',$marketPlaceLump, $mainBlogContent);
		$mainBlogContent = str_replace('WP_POPULARPOSTS',$popularFeaturedPostsLump, $mainBlogContent);
		$mainBlogContent = str_replace('WP_RECENTPOSTS',$recentPostsLump, $mainBlogContent);		
		$mainBlogContent = str_replace('WP_BOTTOMBLOCKS',$bottomBlocksLump, $mainBlogContent);		
		$mainBlogContent = str_replace('WP_MINI_AD', $miniAdLump, $mainBlogContent);
		
		// Pull out "sidebar chunk"
		$mainBlogContentFinalExplode = explode('WP_SIDEBARCHUNK',$mainBlogContent);
		$mainBlogContent = $mainBlogContentFinalExplode[0].$mainBlogContentFinalExplode[2];
		$mainBlogSideBarContent = $mainBlogContentFinalExplode[1];

		// Do "sidebar chunk" replacement
		$mainBlogContent = str_replace('SIDEBAR__CHUNK',$mainBlogSideBarContent,$mainBlogContent);
		$mainBlogContent = str_replace('SIDEBAR_','',$mainBlogContent);
		$mainBlogContent = str_replace('_CHUNK','',$mainBlogContent);

		// Output content
		echo $mainBlogContent;
	}

	/**
	 * Old main WIM feed
	 */
	public function feed()
	{
		$url = SITEINSECUREURL.'/bloggers/workitmom/feed?wppt=1';
		echo file_get_contents($url);
	}
	
	/**
	 *	Blog header image.
	 */
	protected function _blogHeaderImage($blogInfo){
		
		/* Format data */
		// Images
		$imageUrl = $blogInfo['blogImage'];
		$imagemapUrl = $blogInfo['blogImageMap'];
		if ($imageUrl){
			$headImage = array(
				'image' => $imageUrl,
				'imageType' => 'blogheader'
			);
		} else {
			$headImage = array(
				'image' => $blogInfo['userImage'],
				'imageType' => 'user'
			);
		}
		
		// Author
		$author = $blogInfo['firstname'].' '.$blogInfo['lastname'];
		
		// Other data
		$title = $blogInfo['blogTitle'];
		$link = $blogInfo['path'];
		$description = $blogInfo['blogDescription'];
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/blogs/mainblogparts/header_image.php');
	}

}

?>
