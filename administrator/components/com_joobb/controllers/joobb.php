<?php
/**
 * @version $Id: joobb.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pane');

require_once(JPATH_COMPONENT.DS.'views'.DS.'joobb.php');

/**
 * Joo!BB Controller
 *
 * @package Joo!BB
 */
class ControllerJoobb extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * shows the control panel
	 */
	function display() {

		// initialize variables
		$db =& JFactory::getDBO();
		
		// get forum size
		$query = "SHOW TABLE STATUS LIKE '%joobb%'"
				 ;
		$db->setQuery($query);
		$tableRows = $db->loadObjectList();
		
		// db installed
		if (!count($tableRows)) {
			$this->setRedirect('index.php?option=com_joobb&task=joobb_install_view', JText::_('COM_JOOBB_MSGNODBINSTALLED'), 'error'); return;
		}
		
		// get number of topics
		$query = "SELECT '". JText::_('COM_JOOBB_BOARDTOPICS') ."' AS description, SUM(topics) AS value" .
				 "\n FROM #__joobb_forums"
				 ;
		$db->setQuery($query);	
		$rows = $db->loadObjectList();

		if (!is_array($rows)) {
			$object = new stdClass(); $object->description = JText::_('COM_JOOBB_BOARDTOPICS'); $object->value = JText::_('COM_JOOBB_NOTAVAILABLE');
			$rows[] = $object;
		}
						
		// get number of posts
		$query = "SELECT '". JText::_('COM_JOOBB_BOARDPOSTS') ."' AS description, SUM(posts) AS value" .
				 "\n FROM #__joobb_forums"
				 ;
		$db->setQuery($query);
		$rusult = $db->loadObjectList();

		if (!is_array($rusult)) {
			$object = new stdClass(); $object->description = JText::_('COM_JOOBB_BOARDPOSTS'); $object->value = JText::_('COM_JOOBB_NOTAVAILABLE');
			$rusult[] = $object;
		}
				
		$rows = array_merge($rows, $rusult);

		// get number of users
		$query = "SELECT  '". JText::_('COM_JOOBB_BOARDUSERS') ."' AS description , COUNT(*) AS value" .
				 "\n FROM #__users"
				 ;
		$db->setQuery($query);
		$rusult = $db->loadObjectList();

		if (!is_array($rusult)) {
			$object = new stdClass(); $object->description = JText::_('COM_JOOBB_BOARDUSERS'); $object->value = JText::_('COM_JOOBB_NOTAVAILABLE');
			$rusult[] = $object;
		}
					
		$rows = array_merge($rows, $rusult);

		// get board size
		$size = 0;
		foreach($tableRows as $tableRow) {
			$size += $tableRow->Data_length;
		}
		
        if ($size < 964) { 
			$size = round($size) ." Bytes"; 
		} else if ($size < 1000000) { 
			$size = round($size/1024,2) ." KB" ; 
		} else { 
			$size = round($size/1048576,2) ." MB"; 
		}
		
		$object = new stdClass(); $object->description = JText::_('COM_JOOBB_BOARDSIZE'); $object->value = $size;
		$rows[] = $object;
					
		ViewJoobb::showControlPanel($rows);
	}

}
?>