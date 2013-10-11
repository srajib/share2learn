<?php
/**
 * @version $Id: joobbconfig.php 209 2012-02-20 16:06:14Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Configuration
 *
 * @package Joo!BB
 */
class JoobbConfig
{
	/**
	 * config data
	 *
	 * @var array
	 */
	var $_config = null;
	
	/**
	 * board settings data
	 *
	 * @var array
	 */
	var $_board_settings = null;
	
	/**
	 * latest posts settings data
	 *
	 * @var array
	 */
	var $_latestpost_settings = null;
	
	/**
	 * feed settings data
	 *
	 * @var array
	 */
	var $_feed_settings = null;
	
	/**
	 * view settings data
	 *
	 * @var array
	 */
	var $_view_settings = null;
	
	/**
	 * user settings defaults data
	 *
	 * @var array
	 */
	var $_user_settings_defaults = null;
	
	/**
	 * attachment settings
	 *
	 * @var array
	 */
	var $_attachment_settings = null;
	
	/**
	 * image settings
	 *
	 * @var array
	 */
	var $_image_settings = null;
	
	/**
	 * captcha settings
	 *
	 * @var array
	 */
	var $_captcha_settings = null;
	
	/**
	 * parse settings
	 *
	 * @var array
	 */
	var $_parse_settings = null;
	
	/**
	 * joobb config
	 */					
	function JoobbConfig() {
	
		// initialize variables
		$db			=& JFactory::getDBO();
			
		$query = "SELECT c.editor AS config_editor, c.emotion_set AS emotion_set_file, c.icon_set AS icon_set_file, c.board_settings,"
				. "\n c.latestpost_settings, c.view_settings, c.user_settings_defaults, c.attachment_settings, c.image_settings,"
				. "\n c.feed_settings, c.captcha_settings, c.parse_settings, c.topic_icon_function, c.post_icon_function,"
				. "\n c.template AS template_file, c.theme AS theme_file"
				. "\n FROM #__joobb_configs AS c"
				. "\n WHERE c.id = 1"
				;
		$db->setQuery($query);
		$this->_config = $db->loadObject();
		$this->_board_settings = new JParameter($this->_config->board_settings);
		$this->_latestpost_settings = new JParameter($this->_config->latestpost_settings);
		$this->_feed_settings = new JParameter($this->_config->feed_settings);
		$this->_view_settings = new JParameter($this->_config->view_settings);
		$this->_user_settings_defaults = new JParameter($this->_config->user_settings_defaults);
		$this->_attachment_settings = new JParameter($this->_config->attachment_settings);
		$this->_image_settings = new JParameter($this->_config->image_settings);
		$this->_captcha_settings = new JParameter($this->_config->captcha_settings);
		$this->_parse_settings = new JParameter($this->_config->parse_settings);
	}
	
	/**
	 * get instance
	 *
	 * @access 	public
	 * @return object
	 */
	function &getInstance() {
	
		static $joobbConfig;

		if (!is_object($joobbConfig)) {
			$joobbConfig = new JoobbConfig();
		}

		return $joobbConfig;
	}

	/**
	 * get template file
	 * 
	 * @access public
	 * @return string
	 */
	function getTemplateFile() {
		return $this->_config->template_file;
	}
	
	/**
	 * get theme file
	 * 
	 * @access public
	 * @return string
	 */
	function getThemeFile() {
		return $this->_config->theme_file;
	}

	/**
	 * get emotion set image source
	 * 
	 * @access public
	 * @return string
	 */
	function getEmotionSetFile() {
		return $this->_config->emotion_set_file;
	}

	/**
	 * get icon set image source
	 * 
	 * @access public
	 * @return string
	 */
	function getIconSetFile() {
		return $this->_config->icon_set_file;
	}
		
	/**
	 * get default topic icon
	 * 
	 * @access public
	 * @return string
	 */
	function getDefaultTopicIcon() {
		return $this->_config->topic_icon_function;
	}
		
	/**
	 * get default post icon
	 * 
	 * @access public
	 * @return string
	 */
	function getDefaultPostIcon() {
		return $this->_config->post_icon_function;
	}

	/**
	 * get time zone name
	 * 
	 * @access public
	 * @return string
	 */
	function getTimeZoneName() {
		return $this->_config->time_zone_name;
	}
				
	/**
	 * get time zone offset
	 * 
	 * @access public
	 * @return string
	 */
	function getTimeZoneOffset() {
		return $this->_config->time_zone_offset;
	}
		
	/**
	 * get time format
	 * 
	 * @access public
	 * @return string
	 */
	function getTimeFormat() {
		return $this->_config->time_format;
	}
	
	/**
	 * get editor
	 * 
	 * @access public
	 * @return string
	 */
	function getEditor() {
		return $this->_config->config_editor;
	}
	
	/**
	 * get board settings
	 * 
	 * @access public
	 * @return string
	 */
	function getBoardSettings($value) {
		return $this->_board_settings->get($value);
	}
	
	/**
	 * get latest posts settings
	 * 
	 * @access public
	 * @return string
	 */
	function getLatestPostSettings($value) {
		return $this->_latestpost_settings->get($value);
	}
	
	/**
	 * get feed settings
	 * 
	 * @access public
	 * @return string
	 */
	function getFeedSettings($value) {
		return $this->_feed_settings->get($value);
	}
		
	/**
	 * get view settings
	 * 
	 * @access public
	 * @return string
	 */
	function getViewSettings($value) {
		return $this->_view_settings->get($value);
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
	 * get attachment settings
	 * 
	 * @access public
	 * @return string
	 */
	function getAttachmentSettings($value) {
		return $this->_attachment_settings->get($value);
	}

	/**
	 * get image settings
	 * 
	 * @access public
	 * @return string
	 */
	function getImageSettings($value) {
		return $this->_image_settings->get($value);
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

	/**
	 * get parse settings
	 * 
	 * @access public
	 * @return string
	 */
	function getParseSettings($value) {
		return $this->_parse_settings->get($value);
	}								
}
?>