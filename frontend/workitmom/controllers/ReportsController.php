<?php

/**
 *	Reporting mechanism.
 */
abstract class WorkitmomReportsController extends ClientFrontendController {

	/**
	 *	Report an object.
	 */
	final protected function report($objectType, $objectId){
		
		/* Get model */
		$reportsModel = $this->getModel('reports');
		
		/* Get report arguments */
		$reason = Request::getString('reason', null);
		
		/* Report object. */
		$reportId = $reportsModel->{'report'.ucfirst($objectType)}($objectId, $reason);
		
		/* If successful... */
		if ($reportId){
		
			/* Extra stuff. */
			$this->report_extra($reportId);
		
			/* Send an email to administrator, on success. */
			$report = $reportsModel->getReport($reportId);
			$user = BluApplication::getUser();
			$emailMsg = new Email();
			$emailMsg->quickSend(BluApplication::getSetting('abuseEmail'), 'Work It, Mom!', 'Report of Abuse', 'reportabuse', array(
				'name' => $user->name,
				'email' => $user->email,
				'objectType' => $report['objectType'],
				'link' => $report['link'],
				'reason' => $reason ? $reason : '[not given]',
				'reportId' => $report['id']
			));
			
		}
		
		/* Return */
		return $reportId;
		
	}
	
	/**
	 *	Derived controller-specific reporting task.
	 */
	protected function report_extra($reportId){}

}

?>