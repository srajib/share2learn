<?php
/**
 * @version $Id: iconset.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'iconset.php');

/**
 * Joo!BB Icon Set Controller
 *
 * @package Joo!BB
 */
class ControllerIconSet extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joobb_iconset_view', 'showIconSets');
		$this->registerTask('joobb_iconset_default', 'defaultIconSet');
	}
	
	/**
	 * compiles a list of icon sets
	 */
	function showIconSets() {

		// initialize variables
		$app		=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		
		$context		= 'com_joobb.joobb_iconset_view';
		$limit			= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart		= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');

		// get default button set
		$query = "SELECT c.icon_set"
				. "\n FROM #__joobb_configs AS c"
				;
		$db->setQuery($query);
		$defaultIconSet = $db->loadResult();
		
		// list templates
		jimport('joomla.filesystem.folder');
		$iconSets = JFolder::folders(JOOBB_ICONS);
				
		$rows = array();
		foreach($iconSets as $iconSetFolder) {
			$fileList = JFolder::files(JOOBB_ICONS.DS.$iconSetFolder, '.xml');
			foreach($fileList as $iconSetFile) {
				if(!$data = JoobbHelper::parseXMLFile(JOOBB_ICONS.DS.$iconSetFolder, $iconSetFile, 'iconset')){
					continue;
				} else {
					$data->default_icon_set =($data->file_name == $defaultIconSet);
					$rows[] = $data;
				}
			}
		}
		
		jimport('joomla.html.pagination');
		$pagination = new JPagination(count($rows), $limitstart, $limit);
		$rows = array_slice($rows, $pagination->limitstart, $pagination->limit);

		ViewIconSet::showIconSets($rows, $pagination);	
	}

	/**
	 * set icon set as default
	 */	
	function defaultIconSet() {

		// initialize variables
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		$msg		= '';
		$msgType	= '';
		
		if(count($cid)) {

			// set first selected icon set as default
			JoobbIconSet::setDefault($cid[0]);
		} else {
			$msg = JText::sprintf('COM_JOOBB_MSGNOSELECTION', JText::_('COM_JOOBB_ICONSET'), JText::_('COM_JOOBB_DEFAULT')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joobb&task=joobb_iconset_view', $msg, $msgType);
	}	
}
?>