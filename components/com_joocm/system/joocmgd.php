<?php
/**
 * @version $Id: joocmgd.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM GD
 *
 * @package Joo!CM
 */
class JoocmGD
{
	var $_quality;
	var $_tmpImage;
	
	function JoobbGD($quality = 100) {
		$this->_quality = $quality;
	}
	
	/**
	 * get instance
	 *
	 * @access public
	 * @return object
	 */
	function &getInstance() {
	
		static $joocmGD;

		if (!is_object($joocmGD)) {
			$joocmConfig =& JoocmConfig::getInstance();
			$joocmGD = new JoocmGD($joocmConfig->getAvatarSettings('image_resize_quality'));
		}

		return $joocmGD;
	}
	
	/**
	 * is enabled
	 *
	 * @access public
	 * @return boolean
	 */
	function isEnabled() { 
		if (extension_loaded('gd') || extension_loaded('gd2')) {
			return true;
		}
		return false;
	}
	
	/**
	 * resize image
	 *
	 * @access public
	 * @return boolean
	 */
	function resizeImage($fileName, $maxWidth=100, $maxHeight=100) {
	
		// initialize variables
		$app =& JFactory::getApplication();
		
		if (!$this->isEnabled()) {
			return false;
		}
		
		$imageSize = @getimagesize($fileName);
		$resizeFactor = min($maxWidth/$imageSize[0], $maxHeight/$imageSize[1], 1);
	
		if ($resizeFactor > 0 && $resizeFactor < 1) {
			$width = (int) ($imageSize[0] * $resizeFactor);
			$height = (int) ($imageSize[1] * $resizeFactor);
			
			$this->_tmpImage = @imagecreatetruecolor($width, $height);
	
			$image = $this->createImage($fileName, $imageSize[2]);
			if (!$image) {
				$app->enqueueMessage(JText::_('COM_JOOCM_MSGFAILEDCRATEIMAGE'), 'notice'); return false;
			}

			// set transparency if we are handling gif or png
			if(($imageSize[2] == 1) || ($imageSize[2] == 3)) {
				imagealphablending($this->_tmpImage, false);
				imagesavealpha($this->_tmpImage, true);
				$transparent = imagecolorallocatealpha($this->_tmpImage, 255, 255, 255, 127);
				imagefilledrectangle($this->_tmpImage, 0, 0, $width, $height, $transparent);
			}

    		imagecopyresampled($this->_tmpImage, $image, 0,0, 0,0, $width, $height, $imageSize[0], $imageSize[1]);
    		
    		$this->putImage($fileName, $imageSize[2]);
    		
    		imagedestroy($this->_tmpImage);
		}
		return true;
	}
	
	/**
	 * create image
	 *
	 * @access public
	 * @return resource
	 */
	function createImage($fileName, $imageType) {

		switch ($imageType) {
			case 1:
				$resource = imagecreatefromgif($fileName);
				break;
			case 2:
				$resource = imagecreatefromjpeg($fileName);
				break;
			case 3:
				$resource = imagecreatefrompng($fileName);
				break;
			case 4:
				$resource = imagecreatefromwbmp($fileName);
				break;
			default: 
				$resource = false;
		}
		
		return $resource;
	}
	
	/**
	 * put image
	 *
	 * @access public
	 * @return boolean
	 */	
	function putImage($fileName, $imageType) {
		$result = true;
		
		switch ($imageType) {
			case 1:
				imagegif($this->_tmpImage, $fileName);
				break;
			case 2:
				imagejpeg($this->_tmpImage, $fileName, $this->_quality);
				break;
			case 3:
				imagepng($this->_tmpImage, $fileName);
				break;
			case 4:
				imagewbmp($this->_tmpImage, $fileName);
				break;
			default: 
				$result = false;
		}
		
		return $result;
	}
	
	/**
	 * get scaled image dimension
	 *
	 * @access public
	 * @return boolean
	 */
	function getScaledImageDim($fileName, $maxWidth=100, $maxHeight=100) {
		$scaledDim = null;
		
		if ($imageSize = @getimagesize($fileName)) {
			$scaleFactor = min($maxWidth/$imageSize[0], $maxHeight/$imageSize[1], 1);
			$scaledDim = array((int)($scaleFactor * $imageSize[0]), (int)($scaleFactor * $imageSize[1]));
		}
		
		return $scaledDim;
	}
}
?>