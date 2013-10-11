<?php
/**
 * @version $Id: usersync.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'usersync.php');

/**
 * Joo!CM User Synchronization Controller
 *
 * @package Joo!CM
 */
class ControllerUserSync extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joocm_usersync_view', 'showUserSync');
		$this->registerTask('joocm_usersync_perform', 'performUserSync');
	}
	
	/**
	 * shows user synchronization
	 */
	function showUserSync() {

		// initialize variables
		$db				=& JFactory::getDBO();
		$joocmConfig	=& JoocmConfig::getInstance();

		// parameter list
		$lists = array();
		
		$joomlaGroup = array();
		$joomlaGroup[] = JHTML::_('select.option', 0, JText::_('COM_JOOCM_SELECTJOOMLAGROUP'));
		$joomlaGroup[] = JHTML::_('select.option', 1, JText::_('COM_JOOCM_ALLUSERS'));
		$joomlaGroup[] = JHTML::_('select.option', 18, JText::_('COM_JOOCM_REGISTERED'));
		$joomlaGroup[] = JHTML::_('select.option', 19, JText::_('COM_JOOCM_AUTHOR'));
		$joomlaGroup[] = JHTML::_('select.option', 20, JText::_('COM_JOOCM_EDITOR'));
		$joomlaGroup[] = JHTML::_('select.option', 21, JText::_('COM_JOOCM_PUBLISHER'));
		$joomlaGroup[] = JHTML::_('select.option', 23, JText::_('COM_JOOCM_MANAGER'));
		$joomlaGroup[] = JHTML::_('select.option', 24, JText::_('COM_JOOCM_ADMINISTRATOR'));
		$joomlaGroup[] = JHTML::_('select.option', 25, JText::_('COM_JOOCM_SUPERADMINISTRATOR'));

		$lists['joomlagroup'] = JHTML::_('select.genericlist',  $joomlaGroup, 'joomlagroup', 'class="inputbox" size="1"', 'value', 'text', 0);

		// build the html radio buttons for system emails
		$lists['system_emails'] = JHTML::_('select.booleanlist', 'system_emails', '', 0);
		// build the html radio buttons for agreed terms
		$lists['agreed_terms'] = JHTML::_('select.booleanlist', 'agreed_terms', '', 0);
		// build the html radio buttons for hide
		$lists['hide'] = JHTML::_('select.booleanlist', 'hide', '', 0);
		// build the html radio buttons for show email
		$lists['show_email'] = JHTML::_('select.booleanlist', 'show_email', '', $joocmConfig->getUserSettingsDefaults('show_email'));
		// build the html radio buttons for show online state
		$lists['show_online_state'] = JHTML::_('select.booleanlist', 'show_online_state', '', $joocmConfig->getUserSettingsDefaults('show_online_state'));		
		
		// list time formats		
		$query = "SELECT f.*"
				. "\n FROM #__joocm_timeformats AS f"
				. "\n ORDER BY f.name"
				;
		$db->setQuery($query);
		$timeFormatsList = $db->loadObjectList();
		
		$timeFormats = array();
		foreach ($timeFormatsList as $timeFormat) {
			$timeFormat->name = $timeFormat->name .' ('. JoocmHelper::formatDate(time(), $timeFormat->timeformat, $joocmConfig->getConfigSettings('time_zone')) .')';
			$timeFormats[] = JHTML::_('select.option', $timeFormat->timeformat, $timeFormat->name, 'timeformat', 'name');
		}
		$lists['time_format'] = JHTML::_('select.genericlist',  $timeFormats, 'time_format', 'class="inputbox" size="1"', 'timeformat', 'name', $joocmConfig->getUserSettingsDefaults('time_format'));
		
		ViewUserSync::showUserSync($lists);	
	}
	
	/**
	 * perform user synchronization
	 */	
	function performUserSync() {
	
		// initialize variables
		$app			=& JFactory::getApplication();
		$joomlaGroup	= JRequest::getVar('joomlagroup', 0, '', 'int');
		$where = "";
		
		if (!$joomlaGroup) {
			$this->setRedirect('index.php?option=com_joocm&task=joocm_usersync_view', JText::_('COM_JOOCM_MSGSELECTJOOMLAGROUPSYNC'), 'notice'); return;
		} else if ($joomlaGroup > 1) {
			$where = "\n WHERE u.gid = $joomlaGroup";
		}
		
		// initialize some variables
		$db			=& JFactory::getDBO();
		$msgType	= '';
		
		// delete all users not more available in joomla users table
		$query = "DELETE ju.*"
				. "\n FROM #__joocm_users AS ju"
				. "\n LEFT JOIN #__users AS u ON u.id = ju.id" 
				. "\n WHERE u.id IS NULL"
				;
		$db->setQuery($query);
		
		if (!$db->query()) {
			$app->enqueueMessage($db->getErrorMsg(), 'error');
		}

		// get all existing joomla users
		$query = "SELECT u.id"
				. "\n FROM #__users AS u"
				. $where
				;
		$db->setQuery($query);
		$joomlaUsers = $db->loadObjectList();
			
		if (count($joomlaUsers)) {
		
			$systemEmails		= JRequest::getVar('system_emails', 0, '', 'int');
			$agreedTerms		= JRequest::getVar('agreed_terms', 0, '', 'int');
			$hide				= JRequest::getVar('hide', 0, '', 'int');
			$showEmail			= JRequest::getVar('show_email', 0, '', 'int');
			$showOnlineState	= JRequest::getVar('show_online_state', 1, '', 'int');
			$timeFormat			= JRequest::getVar('time_format', '');
			
			// prepare an update to existing users...
			$update = JRequest::getVar('update', array(), 'post', 'array');
			$updateCount = count($update);
			
			if ($updateCount) {
				$updateQuery = "UPDATE #__joocm_users SET";
				for ($i=0; $i < $updateCount; $i++) {
					$updateQuery .= "\n $update[$i] = ". $db->Quote(JRequest::getVar($update[$i])).(($i < $updateCount-1) ? ', ' : '');
				}
			}
			
			foreach ($joomlaUsers as $joomlaUser) {

				// check if the user already exists
				$query = "SELECT ju.id"
						. "\n FROM #__joocm_users AS ju"
						. "\n WHERE ju.id = ". $joomlaUser->id
						;
				$db->setQuery($query);
				$userExists = $db->loadResult();

				if ($userExists) {		
					if ($updateCount) {
						$query = $updateQuery
								. "\n WHERE id = ". $joomlaUser->id;
						$db->setQuery($query);
								
						if (!$db->query()) {
							$app->enqueueMessage($db->getErrorMsg(), 'error');
						}
					}	
				} else {
					$query = "INSERT INTO #__joocm_users (id, system_emails, agreed_terms, hide, show_email, show_online_state, time_format) VALUES"
							. "\n (".$joomlaUser->id.",$systemEmails,$agreedTerms,$hide,$showEmail,$showOnlineState,'$timeFormat')"
							;
					$db->setQuery($query);
					
					if (!$db->query()) {
						$app->enqueueMessage($db->getErrorMsg(), 'error');
					}
				}
			}
			$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSYNCHRONIZED', JText::_('COM_JOOCM_USERS'));
		} else {
			$msg = JText::_('COM_JOOCM_MSGNOUSERSTOSYNCHRONIZE'); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_usersync_view', $msg, $msgType);
	}
}
?>