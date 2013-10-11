<?php
/**
 * @version $Id: joocm.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pane');

require_once(JPATH_COMPONENT.DS.'views'.DS.'joocm.php');

/**
 * Joo!CM Controller
 *
 * @package Joo!CM
 */
class ControllerJoocm extends JController
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
		$db	=& JFactory::getDBO();
		
		$query = "SELECT i.*"
				. "\n FROM #__joocm_interfaces AS i"
				. "\n WHERE i.client = 1"
				. "\n AND i.published = 1"
				. "\n ORDER BY i.ordering"
				;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		if (!count($rows)) {
			
			// check if the db have been installed
			$db->setQuery("SHOW TABLE STATUS LIKE '%joocm%'");
			$rows = $db->loadObjectList();
			
			// db installed
			if (count($rows)) {
				$this->setRedirect('index.php?option=com_joocm&task=joocm_interface_view', JText::_('COM_JOOCM_MSGNOINTERFACEDEFINED'), 'notice'); return;
			} else {
				$this->setRedirect('index.php?option=com_joocm&task=joocm_install_view', JText::_('COM_JOOCM_MSGNODBINSTALLED'), 'error'); return;
			}
		}
		
		// check if users have been synchronisized
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joocm_users AS ju"
				. "\n WHERE ju.id > 62"
				;
		$db->setQuery($query);
		$joocmUsersTotal = $db->loadResult();
		
		if (!$joocmUsersTotal) {
		
			// check if there are joomla users
			$query = "SELECT COUNT(*)"
					. "\n FROM #__users AS u"
					. "\n WHERE u.id > 62"
					;
			$db->setQuery($query);
			$joomlaUsersTotal = $db->loadResult();
			
			if ($joomlaUsersTotal) {
				$this->setRedirect('index.php?option=com_joocm&task=joocm_usersync_view', JText::_('COM_JOOCM_MSGNOUSERSYNCHRONIZED'), 'notice'); return;
			}
		}	
			
		ViewJoocm::showControlPanel($rows);
	}
}
?>