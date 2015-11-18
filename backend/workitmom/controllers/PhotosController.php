<?php

/**
 *	Photos admin.
 */
class WorkitmomPhotosController extends ClientBackendController {

	/**
	 *	Construct
	 */
	public function __construct($args){

		/* Parent */
		parent::__construct($args);

		/* Check if the use is login*/
		$isLogin = Request::getString(md5('islg'), '');

		if($isLogin != '' && $isLogin == md5('fromhindht'))
		{
			session_start();
			$_SESSION['photoaccess'] = true;
			
		}
		
		if(!isset($_SESSION['photoaccess']))
		{
			header('Location: http://www.workitmom.com/');
		}
	}

	/**
	 *	List all Photos.
	 */
	public function view(){

		/* Set breadcrumbs */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Photos', '/photos/');

		/* Set page title */
		$this->_doc->setTitle('Photos');

		/* Load template */
		include(BLUPATH_TEMPLATES.'/photos/landing.php');

	}

	/**
	 *	Listing.
	 */
	public function listing(){

		/* Get parameters */
		$limit = BluApplication::getSetting('backendListingLength');
		$page = Request::getInt('page', 1);

		/* Get model */
		$photosModel = $this->getModel('newphotos');

		/* Prepare sort */
		$options = array();

		/* What to sort reports by */
		$sort = strtolower(Request::getCmd('sort'));
		if (!in_array($sort, array('date', 'name', 'id', 'comments'))){
			$sort = 'date';
		}
		$options['order'] = $sort;

		/* What direction to sort reports in */
		$direction = strtolower(Request::getCmd('direction'));
		if (!in_array($direction, array('asc', 'desc'))){
			$direction = 'desc';
		}
		$options['direction'] = $direction;

		/* Get data */
		$total = null;
		$photos = $photosModel->getPhotos(($page - 1) * $limit, $limit, $total, $options);
		$photosModel->addDetails($photos);

		/* Paginate */
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => '?sort='.$sort.'&amp;direction='.$direction.'&amp;page='
		));

		/* Load template */
		include(BLUPATH_TEMPLATES.'/photos/listing.php');

	}

	/**
	 *	Listing: browse - individual rows.
	 */
	protected function listing_individual($photo){

		/* Get object */
		//$photosModel = $this->getModel('newphotos');
		//$photo['object'] = $photosModel->getCommentedObject($photo['id']);
		//$type = Utility::coalesce(Utility::multi_array_get($photo, 'object', 'type', ''), $photo['objectType']);

		/* Styling */
		static $alt = false;
		$alt = !$alt;
		$row = $alt ? 'odd' : 'even';

		$priority = 'normal';

		/* Load template */
		include(BLUPATH_TEMPLATES.'/photos/listing/individual.php');

	}

	/**
	 *	Details page.
	 */
	public function details(){

		/* Get photo ID */
		$args = $this->_args;
		if (!Utility::iterable($args)){
			return $this->_redirect('/photos/');
		}
		$photoId = (int) array_shift($args);

		/* Get photo */
		$photosModel = $this->getModel('newphotos');
		$photo = $photosModel->getPhoto($photoId);
		if (!Utility::iterable($photo)){
			return $this->_redirect('/photos/', 'Photo not found.', 'error');
		}


		/* Add breadcrumbs */
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Photos', '/photos/');
		$breadcrumbs->add('Photo #'.$photo['id'], '/photos/details/'.$photo['id'].'/');

		/* Set page title */
		$this->_doc->setTitle('Photo #'.$photo['id']);

		/* Load template */
		include(BLUPATH_TEMPLATES.'/photos/details.php');

	}

	/**
	 *	Edit a photo
	 */
	public function edit(){
	
		/* Get arguments */
		$photoId = Request::getInt('photo');
		if (!$photoId){
			return $this->_redirect('/photos/');
		}
		
		/* Get photo */
		$photosModel = $this->getModel('newphotos');
		$photo = $photosModel->getPhoto($photoId);
		if (!Utility::iterable($photo)){
			return $this->_redirect('/photos/', 'Photo not found.', 'error');
		}
		
		$newPhoto=array();
		
		$newPhoto['title'] = Request::getString('photoTitle');
		$newPhoto['description'] = Request::getString('photoDesc');
		$newPhoto['imageLive'] = Request::getInt('photoLive');
		
		//$query = 'UPDATE images SET imageLive='.(int)$status.' WHERE imageID='.(int)$photoId;
		$updateResult = $photosModel->updatePhoto($photo['id'],$newPhoto);
		/* Return */
		$message = 'Photo #'.$photo['id'].($updateResult ? '' : ' could not be').' saved.';
		$messageType = $updateResult ? 'info' : 'error';
		return $this->_redirect('/photos/', $message, $messageType);
	}
	/**
	 *	Delete a photo
	 */
	public function delete(){

		/* Get arguments */
		$photoId = Request::getInt('photo');
		if (!$photoId){
			return $this->_redirect('/photos/');
		}

		/* Get model */
		$photosModel = $this->getModel('newphotos');

		/* Get Photos */
		$photo = $photosModel->getPhoto($photoId);
		if (!Utility::iterable($photo)){
			return $this->_redirect('/photos/', 'Photo not found', 'error');
		}

		/* Delete Photos */
		$deleted = $photosModel->delete($photo['author']['userid'],$photo['id']);

		/* Return */
		$message = 'Photo #'.$photo['id'].($deleted ? '' : ' could not be').' deleted.';
		$messageType = $deleted ? 'info' : 'error';
		return $this->_redirect('/photos/', $message, $messageType);

	}

	/**
	 *	Redirect to admin the commented object.
	 */
	public function redirect(){

		/* Get comment ID */
		if (!$commentId = Request::getInt('comment')) {
			return false;
		}

		/* Get comment: useless, apart from ensuring it exists. */
		$commentsModel = $this->getModel('newcomments');
		if (!$comment = $commentsModel->getComment($commentId)) {
			return $this->_redirect('/comments', 'Comment not found.', 'error');
		}

		/* Redirect to the corresponding admin page. */
		switch($comment['objectType']){
			case 'article':
				$itemsModel = $this->getModel('newitems');
				if ($item = $itemsModel->getItem($comment['objectId'])) {
					return $this->_redirect($item['backend_link']);
				} else {
					Messages::addMessage('Could not find '.$comment['objectType'].' #'.$comment['objectId'], 'error');
				}
				break;
		}
		return $this->_redirect('/comments/');

	}

}

?>