<?php
/**
 * @version $Id: joocmcaptcha.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Captcha
 *
 * @package Joo!CM
 */
class JoocmCaptcha
{

	/**
	 * character set
	 *
	 * @var string
	 */
	var $_characterSet;

	/**
	 * character count
	 *
	 * @var int
	 */
	var $_characterCount;

	/**
	 * font size range
	 *
	 * @var string
	 */
	var $_fontSizeRange;

	/**
	 * session Id
	 *
	 * @var int
	 */
	var $sessionId;	

	/**
	 * enabled
	 *
	 * @var bool
	 */
	var $enabled;	
	
	function JoocmCaptcha() {
		$joocmConfig =& JoocmConfig::getInstance();
		
		$this->_characterSet = $joocmConfig->getCaptchaSettings('character_set');
		if ($this->_characterSet == '') {
			$this->_characterSet = "abcdefghjkmnpqrstuvwxyz23456789";
		}
		
		$this->_characterCount = $joocmConfig->getCaptchaSettings('character_count');
		$this->_fontSizeRange = $joocmConfig->getCaptchaSettings('font_size_range');
		$this->_fontColor = $joocmConfig->getCaptchaSettings('font_color');
	}
	
	/**
	 * get a joocm captcha object
	 *
	 * @access public
	 * @return object of JoocmCaptcha
	 */
	function &getInstance() {
	
		static $joocmCaptcha;

		if (!is_object($joocmCaptcha)) {
			$joocmCaptcha = new JoocmCaptcha();
		}

		return $joocmCaptcha;
	}

	function prepare($enabled = 0) { 

		// check if GD is enabled...
		$joocmGD =& JoocmGD::getInstance();
		if ($joocmGD->isEnabled()) {
			$this->enabled = $enabled;
			
			if ($this->enabled) {
			
				// set session id
				$session =& JFactory::getSession();
				$this->sessionId = $session->getId();
			} else {
				return false;
			}
		} else {
		
			// enqueue a GD error and a CAPTCHA notice it's disabled
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_JOOCM_MSGGDNOTAVAILABLE'), 'error'); $app->enqueueMessage(JText::_('COM_JOOCM_MSGCAPTCHADISABLED'), 'notice');
			
			// disable CAPTCHA
			$this->enabled = 0;
			
			return false;
		}
		
		$codeString = $this->getCodeString($this->_characterCount);
		
		$session =& JFactory::getSession();
		
//		echo ' letzer wert: ' .$session->set('captcha_code_blank', $codeString);
				
		$session->set('captcha_code', md5($codeString));
				
//echo 'code string: '.$codeString.' ';
//echo 'code crypted: '.$session->get('captcha_code');
		$savedSession = $_SESSION; 
		session_write_close();
		ini_set('session.save_handler', 'files'); 
		session_start();

		$_SESSION['captcha_code'] = $codeString;
		$_SESSION['font_size_range'] = $this->_fontSizeRange;
		$_SESSION['font_color'] = $this->_fontColor;
//echo 'session: '.$_SESSION['captcha_code'].' ';
		session_write_close();
//echo 'session: '.$_SESSION['captcha_code'].' ';
		ini_set('session.save_handler', 'user'); 
		new JSessionStorageDatabase();
		session_start(); 
		$_SESSION = $savedSession;
//echo ' code : '.$session->get('captcha_code_blank');

		return true;
	}
	
	function getCodeString($lengh) { 
		srand($this->makeSeed());
		
		$codeString = '';
		while(strlen($codeString) < $lengh) {
			$codeString .= substr($this->_characterSet, (rand() % (strlen($this->_characterSet))), 1); 
		}

		return($codeString); 
	}
	
	function makeSeed() { 
		list($usec, $sec) = explode (' ', microtime()); 
		return (float) $sec + ((float) $usec * 100000); 
	}
	
	function getImageSource() { 
		return JOOCM_CAPTCHAS_LIVE.DL.'joocm'.DL.'image.php?sid='.$this->sessionId;
	}
	
	function getCharacterCount() { 
		return $this->_characterCount;
	}
}
?>