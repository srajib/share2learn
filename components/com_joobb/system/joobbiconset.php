<?php
/**
 * @version $Id: joobbiconset.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Icon Set
 *
 * @package Joo!BB
 */
class JoobbIconSet
{
	/**
	 * The manifest XML object
	 * @var object
	 */
	var $_xml = null;
	
	var $name;
	
	var $xmlFile;
	
	var $icons;
	
	var $iconByFunction;
	
	function JoobbIconSet($xmlFile) {
		
		// initialize variables
		$messageQueue	=& JoobbMessageQueue::getInstance();
		
		$this->name = basename($xmlFile, ".xml");
		$this->xmlFile = $xmlFile;
		$this->icons = array();
		
		if (file_exists(JOOBB_ICONS.DS.$this->name.DS.$xmlFile)) {
			$this->_xml =& JFactory::getXMLParser('Simple');
			$this->_xml->loadFile(JOOBB_ICONS.DS.$this->name.DS.$xmlFile);
	
			$root = & $this->_xml->document;
			$iconSet = $root->getElementByPath('iconset');

			if ($iconSet) {
				
				// load language file
				if ($iconSet->attributes('translateable')) {
					$this->loadLanguage($xmlFile);
				}

				$elements = $iconSet->children();
	
				foreach ($elements as $element) {
					$icon = new StdClass();
					$icon->fileName = JOOBB_ICONS_LIVE.DL.$this->name.DL.$element->attributes('filename');
					$icon->group = $element->attributes('group');
					$icon->function = $element->attributes('function');
					$icon->title = JText::_($element->attributes('title'));
					$this->icons[] = $icon;
					$this->iconByFunction[$icon->function] = $icon;
				}
			} else {
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGXMLPARSINGERROR', JText::_('COM_JOOBB_ICONSET'), JOOBB_ICONS.DS.$this->name.DS.$xmlFile));
			}
		} else {
			$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGRESSOURCENOTFOUND', JText::_('COM_JOOBB_ICONSET'), JOOBB_ICONS.DS.$this->name.DS.$xmlFile));
		}		
	}
	
	/**
	 * get instance
	 *
	 * @access 	public
	 * @return object
	 */
	function &getInstance() {
	
		static $joobbIconSet;

		if (!is_object($joobbIconSet)) {
			$joobbConfig	=& JoobbConfig::getInstance();
			$joobbIconSet = new JoobbIconSet($joobbConfig->getIconSetFile());
		}

		return $joobbIconSet;
	}
	
	/**
	 * get icons by group
	 *
	 * @access 	public
	 * @return array
	 */
	function getIconsByGroup($iconGroup) {

		$icons = array();
		foreach ($this->icons as $icon) {
			if ($icon->group == $iconGroup) {
				$icons[] = $icon;
			}
		}
		return $icons;
	}
	
	/**
	 * load language
	 *
	 * @access 	public
	 */		
	function loadLanguage($xmlFile) {
	
		// initialize variables
		$messageQueue	=& JoobbMessageQueue::getInstance();
		
		// load language file for icon set
		$langFile = JOOBB_ICONS.DS.$this->name.DS.JoobbHelper::getLocale().DS.$this->name.'.ini';
		
		if (!is_file($langFile)) {
			$langFile = JOOBB_ICONS.DS.$this->name.DS.'en-GB'.DS.$this->name.'.ini';	
		}	
			
		if (is_file($langFile)) {		
			$lang =& JFactory::getLanguage();
			$lang->_load($langFile); // sorry for calling private function, but there is no other solution at the moment!
		} else {
			$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGNOLANGUAGEFILE', JText::_('COM_JOOBB_ICONSET')));
		}
	}
	
	/**
	 * get default
	 *
	 * @access 	public
	 */
	function getDefault() {
	
		// initialize variables
		$db =& JFactory::getDBO();
	
		// get default icon set
		$query = "SELECT c.icon_set"
				. "\n FROM #__joobb_configs AS c"
				;
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	function setDefault($xmlFile) {
	
		// initialize variables
		$db =& JFactory::getDBO();
			
		// set default button set to true
		$query = "UPDATE #__joobb_configs AS c"
				. "\n SET c.icon_set = '$xmlFile'"
				;
		$db->setQuery($query);

		if (!$db->query()) {
			JError::raiseError(1001, $db->getErrorMsg());
		}		
	}
		
	/**
	 * load sample image
	 *
	 * @access 	public
	 */
	function getSampleImage() {
	
		// initialize variables
		$sampleImage = '';
		
		// load sample image
		$locale = JoobbHelper::getLocale();
		if (is_file(JOOBB_BUTTONS.DS.$this->baseName.DS.JoobbHelper::getLocale().DS.$this->sampleImage)) {
			$sampleImage = JOOBB_BUTTONS_LIVE.DL.$this->baseName.DL.JoobbHelper::getLocale().DL.$this->sampleImage;	
		} elseif (is_file(JOOBB_BUTTONS.DS.$this->baseName.DS.'en-GB'.DS.$this->sampleImage)) {
			$sampleImage = JOOBB_BUTTONS_LIVE.DL.$this->baseName.DL.'en-GB'.DL.$this->sampleImage;	
		}
		
		return $sampleImage;
	}
}
?>