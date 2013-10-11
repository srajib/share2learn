<?php
/**
 * @version $Id: joocmconfig.php 90 2010-05-02 17:07:07Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Configuration
 *
 * @package Joo!CM
 */
class JoocmConfig
{
	/**
	 * config data
	 *
	 * @var array
	 */
	var $_config = null;
	
	/**
	 * config settings data
	 *
	 * @var array
	 */
	var $_config_settings = null;

	/**
	 * user settings defaults data
	 *
	 * @var array
	 */
	var $_user_settings_defaults = null;

	/**
	 * avatar settings
	 *
	 * @var array
	 */
	var $_avatar_settings = null;
	
	/**
	 * captcha settings
	 *
	 * @var array
	 */
	var $_captcha_settings = null;
					
	function JoocmConfig() {
	
		// initialize variables
		$db			=& JFactory::getDBO();
			
		$query = "SELECT c.*"
				. "\n FROM #__joocm_configs AS c"
				. "\n WHERE c.id = 1"
				;		
		$db->setQuery($query);
		$this->_config = $db->loadObject();
		$this->_config_settings = new JParameter($this->_config->config_settings);
		$this->_user_settings_defaults = new JParameter($this->_config->user_settings_defaults);
		$this->_avatar_settings = new JParameter($this->_config->avatar_settings);
		$this->_captcha_settings = new JParameter($this->_config->captcha_settings);
	}
	
	/**
	 * get instance
	 *
	 * @access 	public
	 * @return object
	 */
	function &getInstance() {
	
		static $joocmConfig;

		if (!is_object($joocmConfig)) {
			$joocmConfig = new JoocmConfig();
		}

		return $joocmConfig;
	}

	/**
	 * get config settings
	 * 
	 * @access public
	 * @return string
	 */
	function getConfigSettings($value) {
		return $this->_config_settings->get($value);
	}
	
	/**
	 * get user settings defaults
	 * 
	 * @access public
	 * @return string
	 */
	function getUserSettingsDefaults($value) {
		return $this->_user_settings_defaults->get($value);
	}
	
	/**
	 * get avatar settings
	 * 
	 * @access public
	 * @return string
	 */
	function getAvatarSettings($value) {
		return $this->_avatar_settings->get($value);
	}
		
	/**
	 * get captcha settings
	 * 
	 * @access public
	 * @return string
	 */
	function getCaptchaSettings($value) {
		return $this->_captcha_settings->get($value);
	}						
}
?>