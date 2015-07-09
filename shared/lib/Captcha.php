<?php

/**
 * Captcha Object
 *
 * @package BluApplication
 * @subpackage SharedLib
 */
class Captcha
{
	/**
	 * Characters to include in code
	 *
	 * @var string
	 */
	private $_chars = 'abcdefghjkmnprstuvwxyzABCDEFGHKLMNPQRSTUVWXYZ2456789';

	/**
	 * Colours to use for text and lines
	 *
	 * @var array
	 */
	private $_cols = Array(
		'normal' => Array(
			Array(0, 0, 0),
			Array(244, 164, 27),
			Array(35, 83, 130)),
		'line' => Array(
			Array(35, 83, 130),
			Array(120, 120, 120),
			Array(0xa4, 0xc8, 0xe1)
		));

	/**
	 * Current captcha code
	 *
	 * @var string
	 */
	private $_code = null;

	/**
	 * Current captcha image
	 *
	 * @var resource
	 */
	private $_im = null;

	/**
	 * Generate a random code and store in session for later checking
	 *
	 * @param int Code length
	 * @return array The generated code characters
	 */
	/*public function generateCode($length = 5)
	{
		$left = $length;
		while ($left) {
			$code[] = $this->_chars[mt_rand(0, (strlen($this->_chars) - 1)) ];
			$left--;
		}
		$this->_code = $code;

		// Store code in session
		Session::set('captcha', implode('', $this->_code));
		return $this->_code;
	}*/
	
	public function generateCode($characters = 5) {
		//list all possible characters, similar looking characters and vowels have been removed 
		$possible = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		$this->_code = $code;
		
		// Store code in session
		Session::set('captcha', trim($this->_code));
		return $this->_code;
	}

	/**
	 * Generate captcha image
	 *
	 * @param int Image width
	 * @param int Image height
	 * @param int Image padding
	 * @param int Font size
	 * @param string Font name
	 * @return resource The generated image
	 */
	/*public function generateImage($width = 120, $height = 40, $padding = 15, $size = 16, $font = 'captcha.ttf')
	{
		// Get font location
		$font = BLUPATH_BASE.'/shared/fonts/'.$font;

		// Create image
		$this->_im = imagecreate($width, $height);
		imagecolorallocate($this->_im, 255, 255, 255);
		$i = 0;
		foreach($this->_code as $char) {
			$col = $this->_getColour();
			if (function_exists('imagettftext')) {
				imagettftext($this->_im, $size, // Size
				mt_rand(-20, 20), // Angle
				($i * ($size + 3) + $padding), // x pos
				($height - $padding), // y pos
				$col, // colour
				$font, // Font
				$char // Text
				);
			} else {
				imagestring($this->_im, 5, // Font
				($i * ($size + 3) + $padding), // x pos
				($padding), // y pos
				$char, // Text
				$col // colour
				);
			}
			++$i;
		}

		// Add lines
		$this->_addLines(5, $width, $height);
		return $this->_im;
	}*/
	public function generateImage($width = 120, $height = 40, $padding = 15, $size = 30, $font = 'monofont.ttf'){
	
		// Get font location
		$font = BLUPATH_BASE.'/shared/fonts/'.$font;
		
		//$this->generateCode();
		
		//font size will be 75% of the image height
		$font_size = $size;
		$this->_im = imagecreate($width, $height) or die('Cannot initialize new GD image stream');
		//set the colours
		$background_color = imagecolorallocate($this->_im, 255, 255, 255);
		$text_color = imagecolorallocate($this->_im, 20, 40, 100);
		$noise_color = imagecolorallocate($this->_im, 100, 120, 180);
		//generate random dots in background 
		for( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($this->_im, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}
		//generate random lines in background
		for( $i=0; $i<($width*$height)/150; $i++ ) {
			imageline($this->_im, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}
		// create textbox and add text
		$textbox = imagettfbbox($font_size, mt_rand(-7, 7), $font, $this->_code) or die('Error in imagettfbbox function');

		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		imagettftext($this->_im, $font_size, mt_rand(-7, 7), $x, $y, $text_color, $font , $this->_code) or die('Error in imagettftext function');
		//print_r($this->_code);exit;
		///output captcha image to browser
		//header('Content-Type: image/jpeg');
		//imagejpeg($this->_im);
		//imagedestroy($this->_im);
		//var_dump($this->_code);exit;
		// Add lines
		$this->_addLines(5, $width, $height);
		return $this->_im;
	}

	/**
	 * Get a random colour from the selection available
	 *
	 * @return resource Colour resource
	 */
	private function _getColour($type = '')
	{
		if ($type == 'line') $t = 'line';
		else $t = 'normal';
		$col = mt_rand(0, (count($this->_cols[$t]) - 1));
		if (!isset($this->_cols[$t][$col]['resource'])) {
			$this->_cols[$t][$col]['resource'] = imagecolorallocate($this->_im, $this->_cols[$t][$col][0], $this->_cols[$t][$col][1], $this->_cols[$t][$col][2]);
		}
		return $this->_cols[$t][$col]['resource'];
	}

	/**
	 * Add funky lines to image
	 */
	private function _addLines($num = 5, $width, $height)
	{
		while ($num) {
			$col = $this->_getColour('line');
			imageline($this->_im, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $col);
			$num--;
		}
	}

	/**
	 * Check an entered code against that stored in the session
	 *
	 * @param string Code to check
	 * @return bool True if matches, false otherwise
	 */
	public static function checkCode($checkCode)
	{
	        $code = Session::get('captcha');
		//return false;
if(strlen($checkCode) && trim(strtolower($checkCode)) == strtolower($code))
		{
			self::clearCode();
			return true;
		}
		else
		{
			return false;
		}
		//return (strlen($checkCode) && trim(strtolower($checkCode)) == strtolower($code));
	}

	/**
	 * Clear session code
	 */
	public static function clearCode()
	{
		Session::delete('captcha');
	}
}
?>
