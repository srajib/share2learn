<?php
/**
 * @version $Id: joobbemotionset.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Emotion Set
 *
 * @package Joo!BB
 */
class JoobbEmotionSet
{
	/**
	 * The manifest XML object
	 * @var object
	 */
	var $_xml = null;
	
	var $name;
	
	var $xmlFile;
	
	var $emotions;
	
	var $codesList;

	function JoobbEmotionSet($xmlFile) {
		
		// initialize variables
		$messageQueue	=& JoobbMessageQueue::getInstance();
		
		$this->name = basename($xmlFile, ".xml");
		$this->xmlFile = $xmlFile;
		$this->emotions = array();
		$this->codesList = array();
		
		if (file_exists(JOOBB_EMOTIONS.DS.$this->name.DS.$xmlFile)) {
			$this->_xml =& JFactory::getXMLParser('Simple');
			$this->_xml->loadFile(JOOBB_EMOTIONS.DS.$this->name.DS.$xmlFile);
			
			$root = & $this->_xml->document;
			$emotionSet = $root->getElementByPath('emotionset');
			
			if ($emotionSet) {
				
				// load language file
				if ($emotionSet->attributes('translateable')) {
					$this->loadLanguage($xmlFile);
				}

				$elements = $emotionSet->children();
		
				foreach ($elements as $element) {
					$emotion = new StdClass();
					$emotion->fileName = JOOBB_EMOTIONS_LIVE.DL.$this->name.DL.$element->attributes('filename');
					$emotion->emotion = $element->attributes('emotion');
					$emotion->codes = explode(',', $element->attributes('codes'));
					$emotion->hidden = $element->attributes('hidden');
					$this->emotions[] = $emotion;
					foreach ($emotion->codes as $emotionCode) {
						$this->codesList[] = array($emotionCode, $emotion);
					}
				}
				
				// sort the code list, so the engine can interpret them
				$this->bubbleSortDescending($this->codesList);
			} else {
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGXMLPARSINGERROR', JText::_('COM_JOOBB_EMOTIONSET'), JOOBB_EMOTIONS.DS.$this->name.DS.$xmlFile));
			}
		} else {
			$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGRESSOURCENOTFOUND', JText::_('COM_JOOBB_EMOTIONSET'), JOOBB_EMOTIONS.DS.$this->name.DS.$xmlFile));
		}

	}

	/**
	 * get instance
	 *
	 * @access 	public
	 * @return object
	 */
	function &getInstance($xmlFile) {
	
		static $joobbEmotionSet;

		if (!is_object($joobbEmotionSet)) {
			$joobbEmotionSet = new JoobbEmotionSet($xmlFile);
		}

		return $joobbEmotionSet;
	}
	
	/**
	 * load language
	 *
	 * @access 	public
	 */
	function loadLanguage($xmlFile) {
	
		// initialize variables
		$messageQueue	=& JoobbMessageQueue::getInstance();
		
		// load language file for emotions
		$langFile = JOOBB_EMOTIONS.DS.$this->name.DS.JoobbHelper::getLocale().DS.$this->name.'.ini';
		
		if (!is_file($langFile)) {
			$langFile = JOOBB_EMOTIONS.DS.$this->name.DS.'en-GB'.DS.$this->name.'.ini';	
		}
			
		if (is_file($langFile)) {
			$lang =& JFactory::getLanguage();
			$lang->_load($langFile); // sorry for calling private function, but there is no other solution at the moment!	
		} else {
			$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGNOLANGUAGEFILE', JText::_('COM_JOOBB_EMOTIONSET')));
		}
	}

	function setDefault($emotionSetFile) {
	
		// initialize variables
		$db =& JFactory::getDBO();
			
		// set default emotion set to true
		$query = "UPDATE #__joobb_configs"
				. "\n SET emotion_set = '$emotionSetFile'"
				;
		$db->setQuery($query);

		if (!$db->query()) {
			JError::raiseError(1001, $db->getErrorMsg());
		}		
	}
		
	/**
	 * bubble sort descending
	 *
	 * @access 	public
	 */
	function bubbleSortDescending(&$a) {
		$length = count($a);

		for ($i=1; $i < $length; $i++) {
			$flag = 0;
			for ($j=0; $j < $length-$i; $j++) {
				if (strlen($a[$j][0]) < strlen($a[$j+1][0])) {
					$h = $a[$j];
					$a[$j] = $a[$j+1];
					$a[$j+1] = $h;
				} 
			} 
		}
	}				
}
?>