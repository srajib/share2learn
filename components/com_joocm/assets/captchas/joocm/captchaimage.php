<?php
/**
 * @version $Id: captchaimage.php 22 2009-12-25 20:07:22Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

define('DS', DIRECTORY_SEPARATOR);

define('JOOCM_CAPTCHABASE', dirname(__FILE__));
define('JOOCM_CAPTCHAFONTS', JOOCM_CAPTCHABASE.DS.'fonts');
define('JOOCM_CAPTCHAIMAGES', JOOCM_CAPTCHABASE.DS.'images');

/**
 * Joo!CM Captcha
 *
 * @package Joo!CM
 */
class JoocmCaptchaImage
{
	/**
	 * image file list
	 *
	 * @var array
	 */
	var $_imageFileList;

	/**
	 * font file list
	 *
	 * @var array
	 */
	var $_fontFileList;

	/**
	 * font size min
	 *
	 * @var integer
	 */
	var $_fontSizeMin = 25;

	/**
	 * font size max
	 *
	 * @var integer
	 */
	var $_fontSizeMax = 30;

	/**
	 * font color
	 *
	 * @var array
	 */
	var $_fontColor = array(125, 125, 125);
	
	function JoocmCaptchaImage() {
		$this->setImageList();
		$this->setFontList();
	}
	
	/**
	 * get a joocm captcha object
	 *
	 * @access public
	 * @return object of JoocmCaptcha
	 */
	function &getInstance() {
	
		static $joocmCaptchaImage;

		if (!is_object($joocmCaptchaImage)) {
			$joocmCaptchaImage = new JoocmCaptchaImage();
		}

		return $joocmCaptchaImage;
	}

	function createImage($codeText) {
	
		header('Content-type: image/png'); 
	
		// create a background image
		$captchaImage = ImageCreateFromPNG($this->getRandomImage());

		$codeTextLength = strlen($codeText);
		$this->segmentSize = (int)(ImageSX($captchaImage) / $codeTextLength);
		for ($i=0; $i < $codeTextLength; $i++) {
			$this->drawCharacter($captchaImage, $codeText[$i], $i);
		}
		
		ImagePNG($captchaImage);
		ImageDestroy($captchaImage);
	}

	function drawCharacter($captchaImage, $character, $characterPos) {
	
		// get random font
		$characterFont = JOOCM_CAPTCHAFONTS.DS.$this->_fontFileList[array_rand($this->_fontFileList)];
		
		// get random color
		$textColor = ImageColorAllocate($captchaImage, $this->_fontColor[0], $this->_fontColor[1], $this->_fontColor[2]);

		// get random font size
		$fontSize = rand($this->_fontSizeMin, $this->_fontSizeMax);
	
		// get random angle
		$angle = rand(-30, 30);
		
		// get the points of the bounding box of the character
		$characterDetails = ImageTTFBBox($fontSize, $angle, $characterFont, $character);
		
		/**
			0 lower left corner, X position 
			1 lower left corner, Y position 
			2 lower right corner, X position 
			3 lower right corner, Y position 
			4 upper right corner, X position 
			5 upper right corner, Y position 
			6 upper left corner, X position 
			7 upper left corner, Y position
		*/		

		// calculate character starting coordinates
		$posX = $characterPos * $this->segmentSize+10;
		$posY = rand(ImageSY($captchaImage) - ($characterDetails[1] - $characterDetails[7]), ImageSY($captchaImage)-10);
		
		// draw the character
		ImageTTFText($captchaImage, $fontSize, $angle, $posX, $posY, $textColor, $characterFont, $character);
	}
	
	function getRandomImage() {
		return JOOCM_CAPTCHAIMAGES.DS.$this->_imageFileList[array_rand($this->_imageFileList)];
	}
			
	function setImageList() {
		if (empty($this->_imageFileList)) {
			$this->_imageFileList = array();
			$this->_imageFileList = $this->getFileList(JOOCM_CAPTCHAIMAGES, '.png');
		}
	}
	
	function setFontList() {
		if (empty($this->_fontFileList)) {
			$this->_fontFileList = array();
			$this->_fontFileList = $this->getFileList(JOOCM_CAPTCHAFONTS, '.ttf');
		}
	}
	
	function getFileList($path, $filter = '.') { 
	
		// initialize variables
		$files = array();

		if (is_dir($path)) {
			$handle = opendir($path);
			while (($file = readdir($handle)) !== false) {
				if (($file != '.') && ($file != '..')) {
					if (preg_match("/$filter/", strtolower($file))) {
						$files[] = $file;
					}
				}
			}
			closedir($handle);
			asort($files);
		} else {
			$files = NULL;
		}
		return $files;
	}
	
	function setFontSizeRange($fontSizeRange) {
		$fontSize = explode(':', $fontSizeRange);
		
		// set the min font size value
		if (isset($fontSize[0]) && $fontSize[0] != '' && ($fontSize[0] >= 10)) {
			$this->_fontSizeMin = $fontSize[0];
		}
		
		// set the max font size value
		if (isset($fontSize[1]) && $fontSize[1] != '' && $fontSize[1] >= 15) {
			$this->_fontSizeMax = $fontSize[1];
		}
	}
	
	function setFontColor($fontColor) {
		
		// do we have a valid color?
		if (preg_match('/[0-9a-fA-F]{6}/', substr($fontColor, 0, 6))) {
			$this->_fontColor = sscanf($fontColor, '%2x%2x%2x');
		}
	}
}
?>