<?php
/**
 * @version $Id: joobbbuttonset.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Button Set
 *
 * @package Joo!BB
 */
class JoobbButtonSet
{
	/**
	 * The manifest XML object
	 * @var object
	 */
	var $_xml = null;
	
	var $baseName;
	
	var $xmlFile;
	
	var $buttons;
	
	var $buttonByFunction;
	
	var $onlyButtonSet;

	var $sampleImage;

	function JoobbButtonSet($xmlFile, $buttonSetOnly = true) {
	
		// initialize variables
		$joobbTemplate	=& JoobbTemplate::getInstance();
		$this->buttonsPath = $joobbTemplate->getThemePath().DS.'buttons';
		$this->buttonsPathLive = $joobbTemplate->getThemePath(true).DL.'buttons';

		$this->baseName = basename($xmlFile, ".xml");
		$this->xmlFile = $xmlFile;
		$this->buttonSetOnly = $buttonSetOnly;
		$this->buttons = array();
		$this->buttonByFunction = array();
		
		// load the content from xml
		$this->loadXML();
	}
	
	/**
	 * get instance
	 *
	 * @access	public
	 * @return	object
	 */
	function &getInstance() {
	
		static $joobbButtonSet;

		if (!is_object($joobbButtonSet)) {
			$joobbConfig	=& JoobbConfig::getInstance();
			$joobbButtonSet = new JoobbButtonSet($joobbConfig->getThemeFile());
		}

		return $joobbButtonSet;
	}
	
	/**
	 * load xml
	 *
	 * @access 	public
	 */
	function loadXML() {
	
		// initialize variables
		$messageQueue =& JoobbMessageQueue::getInstance();
		
		if (file_exists($this->buttonsPath.DS.$this->xmlFile)) {
			$this->_xml =& JFactory::getXMLParser('Simple');
			$this->_xml->loadFile($this->buttonsPath.DS.$this->xmlFile);
	
			$root = & $this->_xml->document;
			
			if (!$this->buttonSetOnly) {
				$element =& $root->name[0];
				$this->name = $element ? $element->data() : '';
		
				$element = $root->creationDate[0];
				$this->creationDate = $element ? $element->data() : JText::_('Unknown');
		
				$element =& $root->author[0];
				$this->author = $element ? $element->data() : JText::_('Unknown');
		
				$element =& $root->copyright[0];
				$this->copyright = $element ? $element->data() : '';
		
				$element =& $root->authorEmail[0];
				$this->authorEmail = $element ? $element->data() : '';
		
				$element =& $root->authorUrl[0];
				$this->authorUrl = $element ? $element->data() : '';
		
				$element =& $root->version[0];
				$this->version = $element ? $element->data() : '';
		
				$element =& $root->description[0];
				$this->description = $element ? $element->data() : '';

				$element =& $root->sampleImage[0];
				$this->sampleImage = $element ? $element->data() : '';
			}
			
			// get the buttonset
			$buttonSet = $root->getElementByPath('buttonset');
			
			if ($buttonSet) {
				
				// load language file
				if ($buttonSet->attributes('translateable')) {
					$this->loadLanguage();
				}
				
				// load buttons style sheet
				$this->loadStyleSheet();
				
				$elements = $buttonSet->children();
				foreach ($elements as $element) {
					$button = new StdClass();
					
					$attributes = $element->attributes();
					foreach ($attributes as $attribute => $value) {
						$button->$attribute = JText::_($value);
					}
					$this->buttons[] = $button;
					$this->buttonByFunction[$button->function] = $button;
				}
			} else {
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGXMLPARSINGERROR', JText::_('COM_JOOBB_BUTTONSET'), $this->buttonsPath.DS.$this->xmlFile));
			}
		} else {
			$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGRESSOURCENOTFOUND', JText::_('COM_JOOBB_BUTTONSET'), $this->buttonsPath.DS.$this->xmlFile));
		}
	}
		
	/**
	 * load language
	 *
	 * @access 	public
	 */
	function loadLanguage() {
	
		// initialize variables
		$messageQueue	=& JoobbMessageQueue::getInstance();
	
		// load language file for buttons
		$langFile = $this->buttonsPath.DS.JoobbHelper::getLocale().DS.$this->baseName.'.ini';
		
		if (!is_file($langFile)) {
			$langFile = $this->buttonsPath.DS.'en-GB'.DS.$this->baseName.'.ini';	
		}	
				
		if (is_file($langFile)) {
			$lang =& JFactory::getLanguage();
			$lang->_load($langFile);
		} else {
			$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGNOLANGUAGEFILE', JText::_('COM_JOOBB_BUTTONSET')));
		}
	}
	
	/**
	 * load style sheet
	 *
	 * @access 	public
	 */
	function loadStyleSheet() {
	
		// initialize variables
		$document =& JFactory::getDocument();
		
		// load buttons style sheet file located in language folder
		$locale = JoobbHelper::getLocale();
		if (is_file($this->buttonsPath.DS.JoobbHelper::getLocale().DS.$this->baseName.'.css')) {
			$document->addStyleSheet($this->buttonsPathLive.DL.JoobbHelper::getLocale().DL.$this->baseName.'.css');	
		} elseif (is_file($this->buttonsPath.DS.'en-GB'.DS.$this->baseName.'.css')) {
			$document->addStyleSheet($this->buttonsPathLive.DL.'en-GB'.DL.$this->baseName.'.css');	
		}
	
		// load buttons style sheet file located in buttons root folder
		if (is_file($this->buttonsPath.DS.$this->baseName.'.css')) {
			$document->addStyleSheet($this->buttonsPathLive.DL.$this->baseName.'.css');
		}
	}
}
?>