<?php

/**
 * Email Object
 *
 * @package BluApplication
 * @subpackage SharedLib
 */
class Email
{
	/**
	 * Message subject
	 *
	 * @var string
	 */
	private $_subject = '';

	/**
	 * Message body
	 *
	 * @var string
	 */
	private $_body = '';

	/**
	 * Friendly sender name
	 *
	 * @var string
	 */
	private $_fromName;

	/**
	 * Sender e-mail address
	 *
	 * @var string
	 */
	private $_fromEmail;

	/**
	 * Friendly recipient name
	 *
	 * @var string
	 */
	private $_toName;

	/**
	 * Recipient e-mail address
	 *
	 * @var string
	 */
	private $_toEmail;

	/**
	 * Contructor
	 */
	public function __construct()
	{
		// Set default sender name and e-mail
		$this->_fromEmail = BluApplication::getSetting('adminEmail');
		$this->_fromName = BluApplication::getSetting('storeName');
	}

	/**
	 * Test for valid email address
	 *
	 * @param string Email address
	 * @return bool True if valid, false otherwise
	 */
	public static function isEmailAddress($email)
	{
		// Check it looks valid
		if (preg_match('/\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,4}$/', $email)) {

			// Check DNS record to try and pick out typos
			$host = explode('@', $email);
            if(checkdnsrr($host[1].'.', 'MX')) {
				return true;
			}
            if(checkdnsrr($host[1].'.', 'A')) {
				return true;
			}
            if(checkdnsrr($host[1].'.', 'CNAME')) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Set recipient name and address
	 *
	 * @param string Friendly name
	 * @param string E-mail address
	 */
	public function setRecipient($email, $name)
	{
		$this->_toEmail = $email;
		$this->_toName = $name;
	}

	/**
	 * Set sender name and address
	 *
	 * @param string Friendly name
	 * @param string E-mail address
	 */
	public function setSender($email, $name)
	{
		$this->_fromEmail = $email;
		$this->_fromName = $name;
	}

	/**
	 * Set message subject
	 *
	 * @param string Subject
	 */
	public function setSubject($subject, $prefix = true)
	{
		// Add store name prefix
		if ($prefix) {
			$subject = BluApplication::getSetting('storeName').': '.$subject;
		}
		$this->_subject = $subject;
	}

	/**
	 * Get template file contents
	 *
	 * @param string Template name
	 * @param array Replacement variables
	 */
	public function getTemplate($file)
	{
		// Load template contents
		$template = file_get_contents(BLUPATH_LANGUAGE.'/email/'.$file.'.htm');
		return $template;
	}

	/**
	 * Replace all placeholders in a message
	 *
	 * @param string Message text
	 * @param array Replacement variables
	 */
	public function replacePlacholders($text, $vars = null)
	{
		// Add common replacements
		$vars['siteurl'] = 'http://'.$_SERVER['SERVER_NAME'].SITEURL;
		$vars['friendlysiteurl'] = $_SERVER['SERVER_NAME'].SITEURL;
		$vars['asseturl'] = 'http://'.$_SERVER['SERVER_NAME'].ASSETURL;
		$vars['siteasseturl'] = 'http://'.$_SERVER['SERVER_NAME'].SITEASSETURL;
		$vars['storeName'] = BluApplication::getSetting('storeName');
		$vars['storeEmail'] = BluApplication::getSetting('contactEmail');

		// Replace all placeholders
		foreach($vars as $k => $v) {
			$text = str_replace('['.$k.']', $v, $text);
		}
		return $text;
	}

	/**
	 * Load a template and perform replacements
	 *
	 * @param string Template name
	 * @param array Replacement varaiables
	 */
	public function loadTemplate($file, $vars = null)
	{
		$text = $this->getTemplate($file);
		$text = $this->replacePlacholders($text, $vars);
		return $text;
	}

	/**
	 * Set the message body text
	 *
	 * @param string Body text
	 */
	public function setBody($text)
	{
		$this->_body = $text;
	}

	/**
	 * Load message body from e-mail template
	 *
	 * @param string Template name
	 * @param array Replacement variables
	 */
	public function setBodyFromTemplate($file, $vars = null)
	{
		$text = $this->loadTemplate($file, $vars);
		$this->setBody($text);
	}

	/**
	 * Send the e-mail message
	 *
	 * @param bool Whether to wrap the message in the standard header and footer
	 * @return bool True on success, false otherwise
	 */
	public function send($useHeaderFooter = true)
	{
		// Build headers
		$headers = 'From: "'.$this->_fromName.'" <'.$this->_fromEmail.">\n";
		$headers.= 'Reply-To: '.$this->_fromEmail."\n";
		$headers.= 'Content-type: text/html; charset=utf-8';

		// Get message body
		$msg = $this->_body;

		// Add header and footer?
		if ($useHeaderFooter) {
			$header = $this->loadTemplate('header');
			$footer = $this->loadTemplate('footer');
			$msg = $header."\n".$msg."\n".$footer;
		}

		// Save file to disk and return
		$name = $this->_toEmail."-".microtime();
		$saved = file_put_contents(BLUPATH_BASE.'/logs/email/'.$name.'.htm', $msg);
		if (DEBUG) {
			return $saved;
		}

		// Send mail
		return mail('"'.$this->_toName.'" <'.$this->_toEmail.'>', $this->_subject, $msg, $headers);
	}

	/**
	 * Qucikly send an e-mail to a recipient using a given template
	 *
	 * @param string Recipient e-mail
	 * @param string Recipient name
	 * @param string Message subject
	 * @param string Template name
	 * @param array Replacement variables
	 */
	public function quickSend($email, $name, $subject, $template, $vars = null)
	{
		$this->setRecipient($email, $name);
		$this->setSubject($subject);
		$this->setBodyFromTemplate($template, $vars);
		return $this->send();
	}
}
?>
