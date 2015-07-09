<?php

/**
 * Captcha Controller
 *
 * @package BluApplication
 * @subpackage FrontendControllers
 */
class CaptchaController extends ClientFrontendController
{
	/**
	 * Display a captcha
	 */
	public function view()
	{
		$captcha = new Captcha();
		$captcha->generateCode();
		$im = $captcha->generateImage();
		header('Content-type: image/jpg');
		imagejpeg($im, '', 100);
	}

	/**
	 * Check a captcha
	 */
	public function check()
	{
		$code = Request::getString('captcha');
		echo json_encode(Captcha::checkCode($code));
	}
}
?>
