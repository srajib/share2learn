<?php
/**
 * @version $Id: joobbtemplate.php 208 2012-02-20 07:04:33Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Template
 *
 * @package Joo!BB
 */
class JoobbTemplate 
{
	
	/**
	 * template file
	 *
	 * @var string
	 */
	var $templateFile;
		
	/**
	 * template name
	 *
	 * @var string
	 */
	var $templateName;
		
	/**
	 * template path
	 *
	 * @var string
	 */
	var $templatePath;
	
	/**
	 * template path live
	 *
	 * @var string
	 */
	var $templatePathLive;
	
	/**
	 * theme file
	 *
	 * @var string
	 */
	var $themeFile;
	
	/**
	 * theme path
	 *
	 * @var string
	 */
	var $themePath;
	
	/**
	 * theme path live
	 *
	 * @var string
	 */
	var $themePathLive;
	
	/**
	 * style sheet
	 *
	 * @var string
	 */
	var $styleSheet;
	
	/**
	 * style sheet live
	 *
	 * @var string
	 */
	var $styleSheetLive;
				
	/**
	 * joobb template
	 */		
	function JoobbTemplate() {
		$this->initialize();
	}
	
	/**
	 * get joobb template object
	 *
	 * @access public
	 * @return object of JoobbTemplate
	 */
	function &getInstance() {
		static $joobbTemplate;

		if (!is_object($joobbTemplate)) {
			$joobbTemplate= new JoobbTemplate();
		}

		return $joobbTemplate;
	}
		
	function initialize() {
		$joobbConfig =& JoobbConfig::getInstance();

		$this->templateFile = $joobbConfig->getTemplateFile();
		$this->templateName = basename($this->templateFile, ".xml");
		$this->templatePath = JOOBB_TEMPLATES.DS.$this->templateName;
		$this->templatePathLive = JOOBB_TEMPLATES_LIVE.DL.$this->templateName;
		
		$this->themeFile = $joobbConfig->getThemeFile();
		$this->themeName = basename($this->themeFile, ".xml");
		$this->themePath = $this->templatePath.DS.'themes'.DS.$this->themeName;
		$this->themePathLive = $this->templatePathLive.DL.'themes'.DL.$this->themeName;
		
		$this->styleSheet = $this->themePath.DS.$this->themeName.'.css';
		$this->styleSheetLive = $this->themePathLive.DL.$this->themeName.'.css';

		// load template language
		$this->loadLanguage();
	}
		
	/**
	 * load language
	 *
	 * @access 	public
	 */
	function loadLanguage() {
	
		// try to get language file on current locale set up
		$langFile = $this->templatePath.DS.'language'.DS.JoobbHelper::getLocale().DS.$this->templateName.'.ini';
		
		// try to fall back to default language
		if (!is_file($langFile)) {
			$langFile = $this->templatePath.DS.'language'.DS.'en-GB'.DS.$this->templateName.'.ini';	
		}
		
		if (is_file($langFile)) {
			$lang =& JFactory::getLanguage();
			$lang->_load($langFile);
		}
	}

	function setDefault($templateFile) {
	
		// initialize variables
		$db			=& JFactory::getDBO();
		$themeFile	= JoobbTemplate::findFirstTheme($templateFile);
		
		// set default design for to true
		$query = "UPDATE #__joobb_configs"
				. "\n SET template = '$templateFile', theme = '$themeFile'"
				;
		$db->setQuery($query);
		
		if (!$db->query()) {
			JError::raiseError(1001, $db->getErrorMsg());
		}
	}
	
	function setTheme($themeFile) {
	
		// initialize variables
		$db			=& JFactory::getDBO();

		// set default design for to true
		$query = "UPDATE #__joobb_configs"
				. "\n SET theme = '$themeFile'"
				;
		$db->setQuery($query);
		
		if (!$db->query()) {
			JError::raiseError(1001, $db->getErrorMsg());
		}
	}
	
	function getTemplatePath($live = false) {
		if ($live) {
			return $this->templatePathLive;
		} else {
			return $this->templatePath;
		}
	}

	function getThemePath($live = false) {
		if ($live) {
			return $this->themePathLive;
		} else {
			return $this->themePath;
		}
	}
	
	function getStyleSheet($live = false) {
		if ($live) {
			return $this->styleSheetLive;
		} else {
			return $this->styleSheet;
		}
	}
	
	function findFirstTheme($templateFile) {
	
		// initialize variables
		$themePath = JOOBB_TEMPLATES.DS.basename($templateFile, ".xml").DS.'themes';
		$themeFile = '';
		
		// list themes
		$themeList = JFolder::folders($themePath);
		foreach ($themeList as $themeFolder) {
			$fileList = JFolder::files($themePath.DS.$themeFolder, '.xml');
			foreach ($fileList as $fileName) {
				if($data = JoobbHelper::parseXMLFile($themePath.DS.$themeFolder, $fileName, 'theme')) {
					$themeFile = $data->file_name; break;
				}
			}
			
			// if we found a file we can leave
			if ($themeFile != '') {
				break;
			}
		}
		
		return $themeFile;
	}
}
?>