<?php
/**
 * @version $Id: emotionset.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'emotionset.php');

/**
 * Joo!BB Emotion Set Controller
 *
 * @package Joo!BB
 */
class ControllerEmotionSet extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joobb_emotionset_view', 'showEmotionSets');
		$this->registerTask('joobb_emotionset_default', 'defaultEmotionSet');
	}
	
	/**
	 * compiles a list of emotion sets
	 */
	function showEmotionSets() {

		// initialize variables
		$app		=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		
		$context		= 'com_joobb.joobb_emotionset_view';
		$limit			= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart		= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');

		// get default template
		$query = "SELECT c.emotion_set"
				. "\n FROM #__joobb_configs AS c"
				;
		$db->setQuery($query);
		$defaultEmotionSet = $db->loadResult();
		
		// list templates
		jimport('joomla.filesystem.folder');
		$emotionSets = JFolder::folders(JOOBB_EMOTIONS);
				
		$rows = array();
		foreach ($emotionSets as $emotionSetFolder) {
			$fileList = JFolder::files(JOOBB_EMOTIONS.DS.$emotionSetFolder, '.xml');
			foreach ($fileList as $emotionSetFile) {
				if(!$data = JoobbHelper::parseXMLFile(JOOBB_EMOTIONS.DS.$emotionSetFolder, $emotionSetFile, 'emotionset')){
					continue;
				} else {
					$data->default_emotion_set = ($data->file_name == $defaultEmotionSet);
					$rows[] = $data;
				}
			}
		}
		
		jimport('joomla.html.pagination');
		$pagination = new JPagination(count($rows), $limitstart, $limit);
		$rows = array_slice($rows, $pagination->limitstart, $pagination->limit);
				
		ViewEmotionSet::showEmotionSets($rows, $pagination);	
	}
	
	/**
	 * set emotion set as default
	 */		
	function defaultEmotionSet() {

		// Initialize variables
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msg		= '';
		$msgType	= '';

		if (count($cid)) {

			// set first selected emotion set as default
			JoobbEmotionSet::setDefault($cid[0]);
		} else {
			$msg = JText::sprintf('COM_JOOBB_MSGNOSELECTION', JText::_('COM_JOOBB_EMOTIONSET'), JText::_('COM_JOOBB_DEFAULT')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joobb&task=joobb_emotionset_view', $msg, $msgType);
	}
} 
?>