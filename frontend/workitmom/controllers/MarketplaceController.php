<?php

/**
 * Marketplace controller
 */
class WorkitmomMarketplaceController extends ClientFrontendController {

	/**
	 * Create stages
	 *
	 * @var array
	 */
	private $_stages;

	/**
	 * Current create stage
	 *
	 * @var int
	 */
	private $_stage;

	/**
	 * Constructor
	 */
	public function __construct($args)
	{
		parent::__construct($args);

		// Get stage to view
		$this->_stage = Session::get('marketplaceStage', 1);

		// Build stages
		$this->_stages = array(
			array('id' => 's1_details',
				'title' => 'Fill In Listing Details',
				'edit' => true),
			array('id' => 's2_photos',
				'title' => 'Upload Photos',
				'edit' => true),
			array('id' => 's3_type',
				'title' => 'Choose Listing Type',
				'edit' => true),
			array('id' => 's4_confirm',
				'title' => 'Preview &amp; Submit',
				'edit' => true));

		// Allow skip to edit previous stages
		if (isset($this->_args[0]) && is_numeric($this->_args[0]) && ($this->_args[0] > 0) && ($this->_args[0] < $this->_stage)) {
			$this->_stage = (int)$this->_args[0];
		}
	}

	/**
	 * Landing page
	 */
	public function view()
	{
		// Get featured listings
		$marketplaceModel = $this->getModel('marketplace');
		$total = true;
		$featuredListings = $marketplaceModel->getListings(null, 4, $total, 'featured');

		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Shop', '/shop');
		$breadcrumbs->add('Marketplace', '/marketplace');

		// Set page title
		$this->_doc->setTitle('Marketplace'.BluApplication::getSetting('titleSeparator').'Shop');
		$this->_doc->setAdPage(OpenX::PAGE_MARKETPLACE);

		// Load template
		include(BLUPATH_TEMPLATES . '/marketplace/landing.php');
	}

	/**
	 *	Marketplace listing box.
	 */
	public function listing()
	{
		// Get data from request
		$categorySlug = Request::getString('category');
		$sort = Request::getString('sort', BluApplication::getSetting('listingSort', 'date'));
		$page = Request::getInt('page', 1);
		$limit = BluApplication::getSetting('listingLength', 9);
		$offset = ($page - 1) * $limit;

		// Get user details
		$user = BluApplication::getUser();

		// Get model
		$marketplaceModel = $this->getModel('marketplace');

		// Get available categories
		$categories = $marketplaceModel->getCategories();

		// Get current category from slug
		$category = null;
		$categoryId = null;
		if ($categorySlug) {
			if ($categorySlug == 'owner') {
				$categoryId = 'owner';
			} else {
				foreach ($categories as $slugCategory) {
					if ($slugCategory['categoryShortName'] == $categorySlug) {
						$category = $slugCategory;
						$categoryId = $category['mpcID'];
						break;
					}
				}
			}
		}

		// Get listings
		$total = true;
		$listings = $marketplaceModel->getListings($offset, $limit, $total, $sort, $categoryId);

		/* Load template */
		include(BLUPATH_TEMPLATES . '/marketplace/landing/box.php');
	}

	/**
	 *	Marketplace listing box sorter.
	 */
	protected function listing_sorter($sort, $page)
	{
		// Get data
		$sorts = array(
			'title' => 'A-Z',
			'date' => 'Most Recent'
		);
		$defaultSort = BluApplication::getSetting('listingSort', 'date');
		$sort = Request::getString('sort', $defaultSort);
		$on = in_array($sort, array_keys($sorts));

		// There is never a category for marketplace listings, but needed for template.
		$category = null;

		/* Load template */
		include(BLUPATH_TEMPLATES . '/site/landing/sorter.php');
	}

	/**
	 *	Pagination for the listing.
	 */
	protected function listing_pagination($sort, $page, $total)
	{
		// Get limit and number of pages
		$limit = BluApplication::getSetting('listingLength', 9);

		// Build pagination
		$pagination = Pagination::simple(array(
			'limit' => $limit,
			'total' => $total,
			'current' => $page,
			'url' => $this->_url . '?sort=' . urlencode($sort) . '&amp;page='
		));

		// Display pagination
		echo $pagination->get('buttons');
	}

	/**
	 * Listing detail page
	 */
	public function detail()
	{
		// Get listing id from args
		$listingId = @$this->_args[0];

		// Get listing
		$marketplaceModel = $this->getModel('marketplace');
		$listing = $marketplaceModel->getListing($listingId);
		if (!$listing) {
			return $this->_errorRedirect();
		}

		// Get related listings
		$relatedListings = $marketplaceModel->getRelated($listing['mCategory'], $listing['marketID']);

		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Shop', '/shop');
		$breadcrumbs->add('Marketplace', '/marketplace');
		$breadcrumbs->add($listing['mTitle'], '/marketplace/detail/'.$listingId);

		// Set page title
		$this->_doc->setTitle($listing['mTitle'].BluApplication::getSetting('titleSeparator').'Marketplace'.BluApplication::getSetting('titleSeparator').'Shop');
		$this->_doc->setAdPage(OpenX::PAGE_MARKETPLACE);

		// Increment views
		$marketplaceModel->increaseViews($listingId);

		// Load template
		include(BLUPATH_TEMPLATES.'/marketplace/detail.php');
	}

	/**
	 * Create a listing
	 */
	public function create()
	{
		if (!$user = BluApplication::getUser()) {

			// Save the current page request for after login
			$this->_setReferer();
			Request::takeSnapshot();
			
			/* Set header ad */
			$this->_doc->setAdPage(OpenX::PAGE_PRESS);

			// Show landing page from which user can choose to login or register
			include(BLUPATH_TEMPLATES.'/marketplace/create_landing.php');
			return false;
		}

		// Current stage details
		$createStages = $this->_stages;
		$currentStageNum = $this->_stage;

		// Add breadcrumbs
		$breadcrumbs = BluApplication::getBreadcrumbs();
		$breadcrumbs->add('Shop', '/shop');
		$breadcrumbs->add('Marketplace', '/marketplace');
		$breadcrumbs->add('Listing Submission', '/marketplace/create');

		// Set page title
		$this->_doc->setTitle('Listing Submission'.BluApplication::getSetting('titleSeparator').'Marketplace'.BluApplication::getSetting('titleSeparator').'Shop');
		$this->_doc->setAdPage(OpenX::PAGE_MARKETPLACE);

		// Load template
		include(BLUPATH_TEMPLATES.'/marketplace/create.php');
	}

	/**
	 * Create stage
	 */
	public function create_stage()
	{
		$stageName = $this->_stages[$this->_stage - 1]['id'];
		if ($this->_doc->getFormat() == 'json') {

			// Render stage
			ob_start();
			$this->$stageName();
			$content = ob_get_clean();

			// Build response
			$response = array('stageDetails' => $this->_stages, 'stageNum' => $this->_stage, 'content' => $content);
			echo json_encode($response);
		} else {
			$this->$stageName();
		}
	}

	/**
	 * Stage 1: Details
	 */
	public function s1_details()
	{
		// Get user details
		$user = BluApplication::getUser();
		if (!$user) {
			$this->_redirect('/marketplace/create');
		}

		// Get model
		$marketplaceModel = $this->getModel('marketplace');
		
		// Get requested listing, and check credentials
		$listingId = empty($this->_args[0]) ? null : $this->_args[0];
		if ($listingId) {
			$listing = $marketplaceModel->getListing($listingId);
			if ($listing['mUserID'] == $user->userid) {
				Session::set('listingId', $listingId);

			// Not allowed, reset listing ID.
			} else {
				$listingId = null;
			}
		}
		
		// Get listing to display
		$listingId = Session::get('listingId');
		$listing = $marketplaceModel->getListing($listingId, false);

		// Get details from request/listing
		$categoryId = Request::getInt('category', $listing ? $listing['mCategory'] : null);
		$title = Request::getString('title', $listing ? $listing['mTitle'] : null);
		$shorttitle = Request::getString('shorttitle', $listing ? $listing['mShortTitle'] : null);
		$description = Request::getString('description', $listing ? $listing['mDescription'] : null);
		$website = Request::getString('website', $listing ? $listing['link'] : null);
		$discounts = Request::getString('discounts', $listing ? $listing['mDiscounts'] : null);
		$contact = array();
		$contact['name'] = Request::getString('contactname', $listing ? $listing['mContactName'] : $user->name);
		$contact['email'] = Request::getString('contactemail', $listing ? $listing['mContactEmail'] : $user->email);
		$contact['showEmail'] = Request::getString('contactshowemail', $listing ? $listing['mContactShowEmail'] : false);
		$contact['phone'] = Request::getString('contactphone', $listing ? $listing['mContactPhone'] : null);
		$contact['showPhone'] = Request::getString('contactshowphone', $listing ? $listing['mContactShowPhone'] : false);
		$contact['location'] = Request::getString('contactlocation');
		if (!$contact['location'] && $listing) {
			$locationsModel = $this->getModel('locations');
			$location = $locationsModel->getLocation($listing['mContactLocation']);
			$contact['location'] = $location['locationLongName'];
		}
		$contact['showLocation'] = Request::getString('contactshowlocation', $listing ? $listing['mContactShowLocation'] : false);
		$linkToProfile = Request::getString('linktoprofile', $listing ? $listing['mShowOnProfile'] : false);
		$receiveAlert = Request::getBool('receivealert', $listing ? $listing['mReceiveAlert'] : true);

		// Get categories
		$categories = $marketplaceModel->getCategories();

		// Load template
		include(BLUPATH_TEMPLATES.'/marketplace/create/s1_details.php');
	}

	/**
	 * Stage 1: Details Save
	 */
	public function s1_details_save()
	{
		// Get data from request
		$categoryId = Request::getInt('category');
		$title = Request::getString('title');
		$shorttitle = Request::getString('shorttitle');
		$description = Request::getString('description');
		$website = Request::getString('website');
		$discounts = Request::getString('discounts');
		$linkToProfile = Request::getString('linktoprofile');
		$receiveAlert = Request::getBool('receivealert');

		// Get contact details
		$contact = array();
		$contact['name'] = Request::getString('contactname');
		$contact['showName'] = Request::getString('contactshowname');
		$contact['email'] = Request::getString('contactemail');
		$contact['showEmail'] = Request::getString('contactshowemail');
		$contact['phone'] = Request::getString('contactphone');
		$contact['showPhone'] = Request::getString('contactshowphone');
		$contact['showLocation'] = Request::getString('contactshowlocation');

		// Get location
		$location = Request::getString('contactlocation');
		$locationsModel = $this->getModel('locations');
		$location = $locationsModel->getLocationByName($location);
		$contact['location'] = $location ? $location['locationID'] : 0;

		// Validate
		$errors = false;

		if (!$categoryId || !$title || !$shorttitle || ! $description ||
			!$contact['name'] || !$contact['email']) {

			Messages::addMessage('Please complete all required fields.', 'error');
			$errors = true;
		}

		// Show errors
		if ($errors) {
			return $this->_showMessages('create_stage', 'create');
		}

		// Get model
		$marketplaceModel = $this->getModel('marketplace');

		// Add listing
		$listingId = Session::get('listingId');
		if (!$listingId) {
			$listingId = $marketplaceModel->addListing();
			Session::set('listingId', $listingId);
		}

		// Update listing details
		$marketplaceModel->updateListing($listingId, $categoryId, $title, $shorttitle, $description,
			$website, $discounts, $contact, $linkToProfile, $receiveAlert);

		// Move to next stage
		$this->_gotoStage(2);
	}

	/**
	 * Stage 2: Photos
	 */
	public function s2_photos()
	{
		$listingId = Session::get('listingId');
		if (!$listingId) {
			return $this->_gotoStage(1);
		}

		// Get data from request
		$queueId = Request::getString('queueid', md5(uniqid()));

		// Get listing details
		$marketplaceModel = $this->getModel('marketplace');
		$listing = $marketplaceModel->getListing($listingId);

		// Load template
		include(BLUPATH_TEMPLATES.'/marketplace/create/s2_photos.php');
	}

	/**
	 * Stage 2: Photos Upload
	 */
	public function s2_photos_save()
	{
		// Get listing ID
		$listingId = Session::get('listingId');
		if (!$listingId) {
			return $this->_gotoStage(1);
		}

		// Get listing details
		$marketplaceModel = $this->getModel('marketplace');
		$listing = $marketplaceModel->getListing($listingId);

		// Get data from request
		$queueId = Request::getString('queueid');

		// Validate
		$errors = false;

		// Add photos
		for ($i = 1; $i <= 3; $i ++) {

			// Delete file?
			if ($imageId = Request::getInt('filedelete'.$i)) {
				$marketplaceModel->removeImage($imageId);
			}

			// Upload new file (if any)
			$result = $this->_saveUpload($queueId, 'fileupload'.$i, false, array('png', 'jpg', 'jpeg', 'gif', 'bmp'), array('order' => $i));
			if (isset($result['error'])) {
				Messages::addMessage($result['error'], 'error');
				$errors = true;
			}
		}

		// Check for at least one existing photo or new upload
		$assets = Upload::getQueue($queueId);
		if (empty($assets) && empty($listing['images']) && ($errors == false)) {
			Messages::addMessage('Please select a photo to upload.', 'error');
			$errors = true;
		}

		// Show errors
		if ($errors) {
			return $this->_gotoStage(2);
		}

		// Move uploaded files to their correct location
		if (!empty($assets)) {
			foreach ($assets as $uploadId => $file) {
				$marketplaceModel->addImage($listingId, $uploadId, $file, $file['order']);
			}
			Upload::clearQueue($queueId);
		}

		// Show uploaded photos
		return $this->_gotoStage(2);
	}

	/**
	 * Stage 2: Photos Continue
	 */
	public function s2_photos_continue()
	{
		// Get listing ID
		$listingId = Session::get('listingId');
		if (!$listingId) {
			return $this->_gotoStage(1);
		}

		// Get listing details
		$marketplaceModel = $this->getModel('marketplace');
		$listing = $marketplaceModel->getListing($listingId);

		// Check we have at least one photo
		if (empty($listing['images'])) {
			Messages::addMessage('You must upload at least one photo.', 'error');
			return $this->_showMessages('create_stage', 'create');
		}

		// Go to next stage
		$this->_gotoStage(3);
	}

	/**
	 * Stage 3: Type
	 */
	public function s3_type()
	{
		$listingId = Session::get('listingId');
		if (!$listingId) {
			return $this->_gotoStage(1);
		}

		// Get model
		$marketplaceModel = $this->getModel('marketplace');

		// Get availability and prices
		$totalFeat = $marketplaceModel->getTotalFeatured();
		$featAvail = $totalFeat < 24;

		// Get listing and upgrade prices
		$listingPrices = $marketplaceModel->getListingPrices();
		if (1 == 2) {
			$renewalPrices = $market->getUpgradePrices($listingId);
			$renew = true;
		} else {
			$renew = false;
		}
		
		// Calculate remaining time
		$listing = $marketplaceModel->getListing($listingId);
		$expires = strtotime($listing['endDate']) - time();

		// Load template
		include(BLUPATH_TEMPLATES.'/marketplace/create/s3_type.php');
	}

	/**
	 * Stage 3: Type Save
	 */
	public function s3_type_save()
	{
		$listingId = Session::get('listingId');
		if (!$listingId) {
			return $this->_gotoStage(1);
		}

		// Get data from request
		$paymentOptions = Request::getString('paymentoption');
		if (!$paymentOptions) {
			Messages::addMessage('Please select a listing type.', 'error');
			return $this->_showMessages('create_stage', 'create');
		}
		$paymentOptions = explode('|', $paymentOptions);

		$discountCode = strtolower(Request::getString('coupon'));
		$coupon = BluApplication::getModel('marketplace')->getDiscount($discountCode);
		if($discountCode) {
			if(!isset($coupon['code'])) {
				Messages::addMessage('You have entered an invalid discount code.', 'error');
				return $this->_showMessages('create_stage', 'create');
			} else {
				Session::set('listingDiscountCode', $coupon['code']);
			}
		}

		// Get model
		$marketplaceModel = $this->getModel('marketplace');

		// Get listing details
		$listing = $marketplaceModel->getListing($listingId, false);

		// Parse payment option
		$paymentType = $paymentOptions[0];
		$paymentDuration = $paymentOptions[1];
		Session::set('listingPaymentType', $paymentType);
		Session::set('listingPaymentDuration', $paymentDuration);

		// Get amount for chosen payment type and duration
		$listingPrices = $marketplaceModel->getListingPrices();
		$price = $listingPrices[$paymentType]['prices'][$paymentDuration];
		Session::set('listingPaymentPrice', $price);

		// Move to next stage
		$this->_gotoStage(4);
	}

	/**
	 * Stage 4: Confirm
	 */
	public function s4_confirm()
	{
		// Get model
		$marketplaceModel = $this->getModel('marketplace');

		// Get listing details
		$listingId = Session::get('listingId');
		$listing = $marketplaceModel->getListing($listingId, false);

		$paymentType = Session::get('listingPaymentType');
		$paymentDuration = Session::get('listingPaymentDuration');
		$paymentPrice = Session::get('listingPaymentPrice');
		$paymentDiscount = BluApplication::getModel('marketplace')->getDiscount(Session::get('listingDiscountCode'));

		// Get title for chosen payment type
		$listingPrices = $marketplaceModel->getListingPrices();
		$paymentTypeTitle = $listingPrices[$paymentType]['title'];

		// Load template
		include(BLUPATH_TEMPLATES.'/marketplace/create/s4_confirm.php');
	}

	/**
	 * Stage 4: Confirm Save
	 */
	public function s4_confirm_save()
	{
		$listingId = Session::get('listingId');
		if (!$listingId) {
			return $this->_gotoStage(1);
		}

		// Get model
		$marketplaceModel = $this->getModel('marketplace');

		// Get listing details
		$listingId = Session::get('listingId');
		$listing = $marketplaceModel->getListing($listingId, false);

		$paymentType = Session::get('listingPaymentType');
		$paymentDuration = Session::get('listingPaymentDuration');
		$paymentDiscount = $marketplaceModel->getDiscount(Session::get('listingDiscountCode'));
		if(isset($paymentDiscount['code'])) {
			$paymentPrice = $marketplaceModel->getDiscountedPrice(Session::get('listingPaymentPrice'), $paymentDiscount['percentage']);
		} else {
			$paymentPrice = Session::get('listingPaymentPrice');
		}

		// Get title for chosen payment type
		$listingPrices = $marketplaceModel->getListingPrices();
		$paymentTypeTitle = $listingPrices[$paymentType]['title'];

		// Get payment provider
		$paymentProvider = BluApplication::getPlugin('payment', 'paypal', '/marketplace/payment_callback');

		// Store payment details
		$details['description'] = 'Work It, Mom! Marketplace Listing';
		$details['customerName'] = $listing['mContactName'];
		$details['customerEmail'] = $listing['mContactEmail'];
		$details['amount'] = $paymentPrice;
		$itemTitle = $paymentTypeTitle.' for '.$paymentDuration.' '.(($paymentDuration == 1) ? 'month' : 'months');
		$details['items'] = array(
			$itemTitle => array(
				'netAmount' => $paymentPrice,
				'taxAmount' => 0,
				'quantity' => 1
			)
		);
		$details['currency'] = 'USD';
		$paymentProvider->storeDetails($details);

		// Check details
		if (!$paymentProvider->checkDetails()) {
			return $this->_showMessage('create_stage', 'create');
		}

		// Process payment
		if (!$token = $paymentProvider->processPayment()) {
			if (!Messages::countMessages()) {
				Messages::addMessage('Sorry, there was a problem processing your payment. Please try again.', 'error');
			}
			return $this->_showMessages('create_stage', 'create');
		}

		// Redirect if requested
		if ($redirect = $paymentProvider->getRedirect()) {
			$url = $redirect['url'];
			$reason = $redirect['reason'];
			$res = $redirect['res'];

			// Set document title
			$this->_doc->setTitle($reason);

			// Load page template
			if ($redirect['type'] == '3dsecure') {
				return include(BLUPATH_TEMPLATES.'/payment/3dsecure.php');
			} else {
				return include(BLUPATH_TEMPLATES.'/payment/redirect.php');
			}
		}

		// Complete order
		$this->_paymentComplete($token);
	}

	/**
	 * Payment callback
	 */
	public function payment_callback()
	{
		// Get payment provider
		$paymentProvider = BluApplication::getPlugin('payment', 'paypal', '/marketplace/payment_callback');

		// Handle callback
		if (!$token = $paymentProvider->handleCallback()) {
			if (!Messages::countMessages()) {
				Messages::addMessage('Sorry, there was a problem processing your payment. Please try again.', 'error');
			}
			return $this->_showMessages('create_stage', 'create');
		}

		// Complete order
		$this->_paymentComplete($token);
	}

	/**
	 * Payment complete
	 *
	 * @param string Payment token
	 */
	private function _paymentComplete($token)
	{
		// Get listing details
		$listingId = Session::get('listingId');
		$paymentType = Session::get('listingPaymentType');
		$paymentDuration = Session::get('listingPaymentDuration');
		$marketplaceModel = BluApplication::getModel('marketplace');
		$paymentDiscount = $marketplaceModel->getDiscount(Session::get('listingDiscountCode'));
		if (isset($paymentDiscount['code'])) {
			$price = $marketplaceModel->getDiscountedPrice(Session::get('listingPaymentPrice'), $paymentDiscount['percentage']);
			$discount = $paymentDiscount['code'];
		} else {
			$price = Session::get('listingPaymentPrice');
			$discount = '';
		}

		// Add payment to listing
		$marketplaceModel = $this->getModel('marketplace');
		$marketplaceModel->addPayment($listingId, $paymentType, $paymentDuration, $price, $token, $discount);
		//$marketplaceModel->setLive($listingId);

		// Finish listing
		Session::delete('marketplaceStage');
		Session::delete('listingId');
		Session::delete('listingPaymentType');
		Session::delete('listingPaymentDuration');
		Session::delete('listingPaymentPrice');
		Session::delete('listingDiscountCode');

		// All ok, redirect to live listing
		$this->_redirect('/marketplace/detail/'.$listingId, 'Thank you for submitting your marketplace listing.<br/><br/>We will review and publish your listing live within 24 hours. Below is what your listing will look live once live.<br/><br/>Once your listing is live on Work It, Mom! you can edit it at any time by going to My Account/My Marketplace Listings.<br/><br/><a href="http://www.workitmom.com/">Click here</a> to go to Work It, Mom! homepage.');
	}

	/**
	 * Create process sidebar
	 */
	public function create_sidebar()
	{
		// Load template
		include(BLUPATH_TEMPLATES.'/marketplace/create/sidebar.php');
	}

	/**
	 * Moves the user to a different stage of the checkout
	 *
	 * @param string New stage name
	 */
	private function _gotoStage($stage)
	{
		// Clear submission status
		unset($_REQUEST['submit']);

		// Store stage
		$this->_stage = $stage;
		Session::set('marketplaceStage', $stage);

		// Display stage/checkout depending on format
		if ($this->_doc->getFormat() == 'site') {
			$this->create();
		} else {
			$this->create_stage();
		}
	}

}

?>
