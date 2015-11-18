<?php

/**
 *	Handles photo albums for Member photos.
 */
class WorkitmomPhotoalbumController extends WorkitmomNewcommentsController {
	
	/**
	 *	Member photos browse page.
	 */
	public function view(){
		// Hacked to scroll down the page
		return $this->_redirect('/connect/members/#scroll-photos');
	}
	
	/**
	 *	Another details page.
	 *
	 *	Requires photo ID as argument.
	 */
	public function photo(){
		
		/* Get photo ID */
		$args = $this->_args;
		if (!Utility::iterable($args)){
			return $this->_errorRedirect();
		}
		$photoId = (int) array_shift($args);
		
		/* Get photo */
		$photosModel = $this->getModel('newphotos');
		$photo = $photosModel->getPhoto($photoId);
		
		/* Check if current user is the author */
		$personModel = $this->getModel('person');
		$person = $personModel->getPerson(array('username' => $photo['author']['username']));

		$userModel = $this->getModel('user');
		$isSelf = $userModel->isSelf($person);
		$options = array(
			'user' => $photo['author']['userid'],
			'order' => 'date'
		);

		if(!$isSelf)
		{
			$options['status'] = 1;
			
			/*Check photo if it is alive.(Avoid change imageID directly in URL)*/
			if(!(int)$photo['status'])
			{
				return $this->_errorRedirect();
			}
		}			
		
		/* Get all photos from same author */
		$total = null;		
		$photoalbum = $photosModel->getPhotos(0, 0, $total,$options);
		
		$photoPosition = array_search($photo['id'], array_keys($photoalbum)) + 1;
		
		/* Get photos from photoalbum that are adjacent to photo */
		$adjacentPhotos = Utility::adjacent($photo['id'], array_keys($photoalbum));
		foreach($adjacentPhotos as &$adj){
			$adj = $photosModel->getPhoto($adj);
		}
		unset($adj);
		
		/* Add breadcrumbs */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		foreach(array(
			'Connect' => SITEURL . '/connect/',
			'Member Photos' => SITEURL . '/photoalbum/',
			$photo['author']['name'] => $photo['author']['url']
		) as $title => $link){
			$breadcrumbs->add($title, $link);
		}
		
		/* Set title */
		$this->_doc->setTitle($photo['title'].BluApplication::getSetting('titleSeparator').$photo['author']['name'].'\'s Photos');
		$this->_doc->setAdPage(OpenX::PAGE_ARTICLE);
		
		/* Prepare display data */
		$pagination = Pagination::simple(array(
			'limit' => 1,
			'total' => $total,
			'current' => $photoPosition,
			'url' => '?page='
		));
		
		/* Load template */
		include(BLUPATH_TEMPLATES.'/photoalbum/detail.php');
		
	}
	
	
	
	###							NewcommentsController							###
	
	/**
	 *	Overrides NewcommentsController.
	 */
	protected function comments_view(array $options = array()){
		$options = array_merge($options, array('extraCss' => 'small_list'));
		return parent::comments_view($options);
	}
	
	/**
	 *	Required by NewcommentsController.
	 */
	protected function add_comment_call_model_add(array $args){
		
		/* Append (overwrite) item ID, and comment type. */
		$photo = $this->get_commentable_object();
		$args['commentTypeObjectId'] = $photo['id'];
		$args['commentType'] = 'userphoto';

		/* Delegate to Comments model */
		$commentsModel = $this->getModel('comments');
		$commentID = $commentsModel->addComment($args);

		// Add alert
		$alertsModel = $this->getModel('alerts');
		$alertId = $alertsModel->createAlert('photocomment', array(
			'photoId' => $photo['id']
		), $args['commentOwner']);
		$alertsModel->applyAlert($alertId, $photo['author']['userid']);

		/* Return */
		return $commentID;
		
	}
	
	/**
	 *	Required by NewcommentsController
	 */
	protected function get_commentable_object(){
		
		/* Get arguments */
		$args = $this->_args;
		if (!Utility::iterable($args)){
			return null;
		}
		$photoId = (int) array_shift($args);
		
		/* Get photo */
		$photosModel = $this->getModel('newphotos');
		$photo = $photosModel->getPhoto($photoId);
		
		/* Return */
		return $photo;
		
	}
	
	/**
	 *	Required by NewcommentsController
	 */
	protected function get_redirect_page(){
		$photo = $this->get_commentable_object();
		return $photo['link'];
	}
	
	###							End NewcommentsController							###

}

?>
