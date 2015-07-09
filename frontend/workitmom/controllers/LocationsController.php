<?php

/**
 * Locations Controller
 *
 * @package BluApplication
 * @subpackage FrontendControllers
 */
class WorkitmomLocationsController extends ClientFrontendController
{
	/**
	 *	Search for users' locations.
	 *
	 *	Used by ajax autocompleter.
	 */
	public function search() {

		/* Get arguments */
		$criteria = Request::getString('criteria');

		/* Get model */
		$locationsModel = $this->getModel('locations');

		/* Get data - limited to 8 */
		$locations = $locationsModel->search($criteria, 8);

		/* Display */
		switch($this->_doc->getFormat()){
			case 'json':

				/* Format output for Autocompleter (JS class) */
				$output = array();
				while(Utility::is_loopable($locations) && $location = array_shift($locations)){
					$output[] = array(
						'id' => $location['locationID'],
						'text' => $location['locationLongName']
					);
				}

				/* Display */
				echo json_encode($output);
				break;
		}

		/* Exit */
		return true;

	}

	/**
	 *	Validate a location name.
	 *
	 *	Used by Standardform.
	 */
	public function validate(){

		/* Get arguments */
		$criteria = Request::getString('criteria');

		/* Get model */
		$locationsModel = $this->getModel('locations');

		/* Get data - limited to 5 */
		$result = $locationsModel->validate($criteria);

		/* Display */
		if ($this->_doc->getFormat() == 'json'){
			echo json_encode($result);
		}

		/* Exit */
		return $result;

	}
}
?>