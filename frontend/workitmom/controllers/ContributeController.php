<?php


class WorkitmomContributeController extends ClientFrontendController{

	/**
	 *	Display landing page
	 */
	public function view()
	{
		/* Get data */
		$itemsModel = $this->getModel('items');
		$latestNews = $itemsModel->getLatest('news', 0, 4);

		/* Set page title */
		$this->_doc->setTitle('Contribute');
		$this->_doc->setAdPage(OpenX::PAGE_ARTICLE);

		/* Load page template */
		include (BLUPATH_TEMPLATES.'/contribute/landing.php');
	}

	/**
	 *	Can't be arsed to type out the similar methods bajillion times.
	 */
	protected function __call($method, $args)
	{
		if (preg_match('/^landing_/', $method)) {

			/* Load (static) template */
			$filename = preg_replace('/^landing_/', '', $method) . '.php';
			include(BLUPATH_TEMPLATES.'/'.strtolower($this->_controllerName).'/landing/'.$filename);

			/* Exit */
			return true;
		}
	}
	
	/**
	 *	Check if user's IP is listed as SPAM at stopforumspam.com
	 */
	public function CheckIfSpambot() {
		$ip = $_SERVER['REMOTE_ADDR'];
	    $xml_string = file_get_contents("http://www.stopforumspam.com/api?ip=" . urlencode($ip));
	    $xml = new SimpleXMLElement($xml_string);
	 
	    if ($xml->appears == "yes") {
	      return true; // Result indicates dangerous.
	    } else {
	      return false; // Result indicates safe.
	    }
	}
	

	/**
	 *	"Contribute a lifesaver" page
	 */
	public function lifesaver(){
		if ($this->CheckIfSpambot()) { return  false; }		// TRUE = SPAM IP	FALSE = GOOD IP

		/* Require user */
		if (!$user = $this->_requireUser('Please sign in, or sign up, to submit a lifesaver.')) {
			return false;
		}
		
		/* Get arguments */
		$args = $this->_args;

		/* Get task */
		if (Utility::iterable($args) && array_shift($args) == 'submit') {

			/* Content creator status */
			$personModel = $this->getModel('person');
			$personModel->ensureContentCreator($user);

			/* Get parameters */
			$lifesaver = Request::getString('form_lifesaver');
			$itemargs['articleLive'] = 0;
			$itemargs['articleAuthor'] = $user->contentcreatorid;
			$itemargs['articleTitle'] = substr($lifesaver, 0, 50);
			$itemargs['articleBody'] = $lifesaver;
			$itemargs['articleType'] = 'lifesaver';

			/* Validation */
			$validation = array();
			$validation['body'] = $this->_validateWithMessage(
				$itemargs['articleBody'],
				'required',
				'Please enter some text.'
			);
			if (in_array(false, $validation)){
				return $this->_redirect('/contribute/lifesaver/');
			}
			
			/* Add item */
			$itemsModel = $this->getModel('items');
			$itemsModel->createItem($itemargs);

			/* Exit - goes to lifesavers landing page, because lifesavers don't have detail pages */
			$this->_redirect(SITEURL . '/lifesavers/', 'Thank you for submitting your lifesaver.');

		} else {

			/* Add breadcrumb */
			$this->_uri .= '/lifesavers';
			BluApplication::getBreadcrumbs()->add('Contribute', '/contribute/');
			BluApplication::getBreadcrumbs()->add('Share a Lifesaver', $this->_uri . '/');

			/* Set page title */
			$this->_doc->setTitle('Share a Lifesaver');
			$this->_doc->setAdPage(OpenX::PAGE_LIFESAVERS);

			/* Load template */
			include(BLUPATH_TEMPLATES . '/contribute/lifesaver.php');
		}

	}

	/**
	 *	"Write an Article" page
	 */
	public function article() {
		if ($this->CheckIfSpambot()) { return  false; }		// TRUE = SPAM IP	FALSE = GOOD IP
		
		/* Require user */
		if (!$user = $this->_requireUser('Please sign in, or sign up, to post an article.')) {
			return false;
		}
		
		/* Get arguments */
		$args = $this->_args;

		/* Get task */
		if (Utility::iterable($args) && array_shift($args) == 'submit') {

			/* Content creator status */
			$ccargs['fullName'] = Request::getString('form_author');
			$ccargs['contentCreatorByLine'] = Request::getString('form_byline');
			$personModel = $this->getModel('person');
			$personModel->ensureContentCreator($user, $ccargs);

			/* Get parameters */
			$itemargs['articleType'] = 'article';
			$itemargs['articleLive'] = 0;
			$itemargs['articleAuthor'] = $user->contentcreatorid;
			$itemargs['articleTitle'] = Request::getString('form_title');
			$itemargs['articleSubTitle'] = Request::getString('form_subtitle');
			$itemargs['articleBody'] = Request::getString('form_article', null, null, true);
			$itemargs['articleEmailAuthor'] = 1;

			/* Validation */
			$validation = array();
			$validation['title'] = $this->_validateWithMessage(
				$itemargs['articleTitle'],
				'required',
				'Please enter a title.'
			);
			$validation['subtitle'] = $this->_validateWithMessage(
				$itemargs['articleSubTitle'],
				'required',
				'Please enter a subtitle.'
			);
			$validation['body'] = $this->_validateWithMessage(
				$itemargs['articleBody'],
				'required',
				'Please enter your article.'
			);
			if (in_array(false, $validation)){
				return $this->_redirect('/contribute/article/');
			}

			$itemargs['articleBody'] = preg_replace('#(\n?<script[^>]*?>.*?</script[^>]*?>)|(\n?<script[^>]*?/>)#is', '', $itemargs['articleBody']);
			
			/* Add item */
			$itemsModel = $this->getModel('items');
			$article = $itemsModel->createItem($itemargs);

			/* Other paraphernalia */
			// Tags
			if ($tags = Request::getString('form_tags')) {
				$tags = explode(', ', $tags);
				$article->applyTags($tags);
			}

			// Category
			if ($category = Request::getString('form_category')) {
				$article->applyCategory($category, 'article');
			}

			/* Exit */
			$message = 'Thank you for submitting your article.<br/>Our editors will review it and it will be published within 48 hours.';
			$this->_redirect('/articles/', $message);

		} else {

			/* Add breadcrumb */
			$this->_uri .= '/articles';
			BluApplication::getBreadcrumbs()->add('Contribute', '/contribute/');
			BluApplication::getBreadcrumbs()->add('Write an Article', $this->_uri . '/');

			/* Set page title */
			$this->_doc->setTitle('Write an Article');
			$this->_doc->setAdPage(OpenX::PAGE_ARTICLE);

			/* Load template */
			include(BLUPATH_TEMPLATES . '/contribute/article.php');
		}

	}

	/**
	 *	"Write a Blog" page
	 */
	public function blog() {
		if ($this->CheckIfSpambot()) { return  false; }		// TRUE = SPAM IP	FALSE = GOOD IP

		/* Require user */
		if (!$user = $this->_requireUser('Please sign in, or sign up, to write a blog post.')) {
			return false;
		}

		/* Get arguments */
		$args = $this->_args;

		/* Add breadcrumb */
		$this->_uri .= '/blog';
		BluApplication::getBreadcrumbs()->add('Contribute', '/contribute/');
		BluApplication::getBreadcrumbs()->add('Write a Blog', $this->_uri . '/');

		/* Redirect */
		if (Utility::is_loopable($args) && $task = array_shift($args)){
			switch(strtolower($task)){
				case 'submit':

					/* Content creator status */
					$personModel = $this->getModel('person');
					$personModel->ensureContentCreator($user);

					/* Prepare validation */
					$validation = array();
					
					// Validate title
					$title = Request::getString('form_title');
					$validation['title'] = $this->_validateWithMessage(
						$title,
						'required',
						'Please enter a title for this post.'
					);
					
					// Validate body text.
					$body = Request::getString('form_blogpost', null, null, true);
					$validation['body'] = $this->_validateWithMessage(
						$body,
						'required',
						'Please enter some text for the post.'
					);
					
					/* Failed validation? */
					if (!in_array(false, $validation)){
						
						/* Prepare validated input arguments */
						$itemargs['articleTitle'] = $title;
						$itemargs['articleBody'] = $body;
						
						/* Prepare other (fixed) input arguments.*/
						$itemargs['articleType'] = 'note';
						$itemargs['articleLive'] = 0;
						$itemargs['articleAuthor'] = $user->contentcreatorid;
						$itemargs['articleEmailAuthor'] = 1;
						$itemargs['articlePrivacy'] = Request::getString('form_privacy') == 'public' ? 0 : 1;

						/* Add the post */
						$blogsModel = $this->getModel('blogs');
						$blog = $blogsModel->getBlog('member', $user->contentcreatorid);
						$post = $blog->addPost($itemargs);

						/* Other miscellany */
						// Add tags
						if ($tags = Request::getString('form_tags')){
							$tags = explode(', ', $tags);
							$post->applyTags($tags);
						}

						// Add category
						if ($category = Request::getString('form_category')){
							$post->applyCategory($category, 'note');
						}

						// Send alerts to friends
						$alertsModel = $this->getModel('alerts');
						$alertId = $alertsModel->createAlert('friendnote', array(
							'itemId'		=>	$post->id,
							'itemTitle'		=>	$post->title
						), $user->userid);
						foreach($user->getFriends() as $friend) {
							$alertsModel->applyAlert($alertId, $friend->userid);
						}

						/* Exit - redirect straight to their new post. */
						return $this->_redirect(Uri::build($post), 'Thank you for submitting your blog post.');
						
					}
					
					// Validation failed.
					/* Prefill form using previously passed data */
					$prefillTitle = $title;
					$prefillBody = $body;						
					break;

				default:
					break;
			}
		}

		/* Set automatic topic if one is supplied */
		$prefillTitle = isset($prefillTitle) ? $prefillTitle : Request::getString('title', '');
		$prefillBody = isset($prefillBody) ? $prefillBody : '';

		/* Set page title */
		$this->_doc->setTitle('Write a Blog');
		$this->_doc->setAdPage(OpenX::PAGE_CONTRIBUTE_BLOG);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/contribute/blog.php');
	}

	/**
	 *	"Share a News Story" page
	 */
	public function news() {
		if ($this->CheckIfSpambot()) { return  false; }		// TRUE = SPAM IP	FALSE = GOOD IP

		/* Require user */
		if (!$user = $this->_requireUser('Please sign in, or sign up, to submit a news story.')) {
			return false;
		}
		
		/* Get args */
		$args = $this->_args;

		/* Get task */
		if (Utility::iterable($args) && array_shift($args) == 'submit') {

			/* Content creator status */
			$personModel = $this->getModel('person');
			$personModel->ensureContentCreator($user);

			/* Get parameters */
			$itemargs['articleLive'] = 0;
			$itemargs['articleAuthor'] = $user->contentcreatorid;
			$itemargs['articleType'] = 'news';
			$itemargs['articleTitle'] = Request::getString('form_title');
			$itemargs['articleLink'] = Request::getString('form_url');
			$itemargs['articleBody'] = Request::getString('form_description', null, null, true);

			/* Add item */
			$itemsModel = $this->getModel('items');
			$newsarticle = $itemsModel->createItem($itemargs);

			/* Add other stuff */
			// Tags
			if ($tags = Request::getString('form_tags')) {
				$tags = explode(', ', $tags);
				$newsarticle->applyTags($tags);
			}

			// Category
			if ($category = Request::getString('form_category')) {
				$newsarticle->applyCategory($category, 'news');
			}

			/* Redirect */
			$message = 'Thank you for submitting your news story.<br/>Our editors will review it and it will be published within 24 hours.';
			$this->_redirect(Uri::build($newsarticle), $message);
		}

		else {

			/* Add breadcrumb */
			$this->_uri .= '/news';
			BluApplication::getBreadcrumbs()->add('Contribute', '/contribute/');
			BluApplication::getBreadcrumbs()->add('Share a News Story', $this->_uri . '/');

			/* Set page title */
			$this->_doc->setTitle('Share a News Story');
			$this->_doc->setAdPage(OpenX::PAGE_NEWS);

			/* Load template */
			include(BLUPATH_TEMPLATES . '/contribute/news.php');
		}

	}

	/**
	 *	"Ask a Question" page
	 */
	public function question() {
		if ($this->CheckIfSpambot()) { return  false; }		// TRUE = SPAM IP	FALSE = GOOD IP

		/* Require user */
		if (!$user = $this->_requireUser('Please sign in, or sign up, to ask a question.')) {
			return false;
		}
		
		/* Get arguments */
		$args = $this->_args;
		
		/* Get task */
		if (Utility::iterable($args) && array_shift($args) == 'submit') {

			/* Content creator status */
			$personModel = $this->getModel('person');
			$personModel->ensureContentCreator($user);

			/* Prepare input arguments.*/
			$itemargs['articleType'] = 'question';
			$itemargs['articleLive'] = 0;
			$itemargs['articleAuthor'] = $user->contentcreatorid;
			$itemargs['articleTitle'] = Request::getString('form_question');
			$itemargs['articleBody'] = Request::getString('form_question');
			$itemargs['articleEmailAuthor'] = 1;
			
			/* Validation */
			$validation = array();
			$validation['body'] = $this->_validateWithMessage(
				$itemargs['articleBody'],
				'required',
				'Please enter your question.'
			);
			if (in_array(false, $validation)){
				return $this->_redirect('/contribute/question/');
			}

			/* Add item */
			$itemsModel = $this->getModel('items');
			$q = $itemsModel->createItem($itemargs);

			/* Add other stuff */
			// Tags
			if ($tags = Request::getString('form_tags')) {
				$tags = explode(', ', $tags);
				$q->applyTags($tags);
			}

			// Category
			if ($category = Request::getString('form_category')) {
				$q->applyCategory($category, 'question');
			}

			/* Redirect */
			return $this->_redirect(Uri::build($q), 'Thank you for submitting your question.');
			
		} else {

			/* Add breadcrumb */
			$this->_uri .= '/question';
			BluApplication::getBreadcrumbs()->add('Contribute', '/contribute/');
			BluApplication::getBreadcrumbs()->add('Ask a Question', $this->_uri . '/');

			/* Set page title */
			$this->_doc->setTitle('Ask a Question');
			$this->_doc->setAdPage(OpenX::PAGE_QUESTION);

			$Lq = Request::getString('landing_question') ? Request::getString('landing_question') : '';

			/* Load template */
			include(BLUPATH_TEMPLATES . '/contribute/question.php');
			
		}

	}

	/**
	 *	"Upload a photo" page
	 */
	public function photo() {
		if ($this->CheckIfSpambot()) { return  false; }		// TRUE = SPAM IP	FALSE = GOOD IP

		/* Require user */
		if (!$this->_requireUser('Please sign in, or sign up, to post a photo.')) { return false; }

		/* Add breadcrumb */
		$this->_uri .= '/photo';
		BluApplication::getBreadcrumbs()->add('Contribute', '/contribute/');
		BluApplication::getBreadcrumbs()->add('Upload a Photo', $this->_uri . '/');

		/* Set page title */
		$this->_doc->setTitle('Upload a Photo');

	}

}


?>
