<?php
/**
 * @version $Id: usersync.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'usersync.php');

/**
 * Joo!BB User Synchronization Controller
 *
 * @package Joo!BB
 */
class ControllerUserSync extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joobb_usersync_view', 'showUserSync');
		$this->registerTask('joobb_usersync_perform', 'performUserSync');
	}
	
	/**
	 * shows user synchronization
	 */
	function showUserSync() {

		// initialize variables
		$db				=& JFactory::getDBO();
		$joobbConfig	=& JoobbConfig::getInstance();
		
		// parameter list
		$lists = array();
		
		$joomlaGroup = array();
		$joomlaGroup[] = JHTML::_('select.option', 0, JText::_('COM_JOOBB_SELECTJOOMLAGROUP'));
		$joomlaGroup[] = JHTML::_('select.option', 1, JText::_('COM_JOOBB_ALLUSERS'));
		$joomlaGroup[] = JHTML::_('select.option', 18, JText::_('COM_JOOBB_REGISTERED'));
		$joomlaGroup[] = JHTML::_('select.option', 19, JText::_('COM_JOOBB_AUTHOR'));
		$joomlaGroup[] = JHTML::_('select.option', 20, JText::_('COM_JOOBB_EDITOR'));
		$joomlaGroup[] = JHTML::_('select.option', 21, JText::_('COM_JOOBB_PUBLISHER'));
		$joomlaGroup[] = JHTML::_('select.option', 23, JText::_('COM_JOOBB_MANAGER'));
		$joomlaGroup[] = JHTML::_('select.option', 24, JText::_('COM_JOOBB_ADMINISTRATOR'));
		$joomlaGroup[] = JHTML::_('select.option', 25, JText::_('COM_JOOBB_SUPERADMINISTRATOR'));

		$lists['joomlagroup'] = JHTML::_('select.genericlist',  $joomlaGroup, 'joomlagroup', 'class="inputbox" size="1"', 'value', 'text', 0);
		
		// build the html radio buttons for enable bbcode
		$lists['enable_bbcode'] = JHTML::_('select.booleanlist', 'enable_bbcode', '', $joobbConfig->getUserSettingsDefaults('enable_bbcode'));
		
		// build the html radio buttons for enable emotions
		$lists['enable_emotions'] = JHTML::_('select.booleanlist', 'enable_emotions', '', $joobbConfig->getUserSettingsDefaults('enable_emotions'));
		
		// build the html radio buttons for enable emotions
		$lists['auto_subscription'] = JHTML::_('select.booleanlist', 'auto_subscription', '', $joobbConfig->getUserSettingsDefaults('auto_subscription'));
		
		$joobbAuth =& JoobbAuth::getInstance();
		$lists['roles'] = JHTML::_('select.genericlist', $joobbAuth->getUserRoleOptionList(), 'role', 'class="inputbox" size="1"', 'value', 'text', $joobbConfig->getUserSettingsDefaults('role'));
				
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
			$this->setRedirect('index.php?option=com_joobb&task=joobb_usersync_view', JText::_('COM_JOOBB_MSGSELECTJOOMLAGROUP'), 'notice'); return;
		} else if ($joomlaGroup > 1) {
			$where = "\n WHERE u.gid = $joomlaGroup";
		}
		
		// initialize some variables
		$db			=& JFactory::getDBO();
		$msgType	= '';
		
		// delete all users not more available in joomla users table
		$query = "DELETE ju.*"
				. "\n FROM #__joobb_users AS ju"
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
		
			$role			= JRequest::getVar('role', 0, '', 'int');
			$enableBBCode	= JRequest::getVar('enable_bbcode', 1, '', 'int');
			$enableEmotions	= JRequest::getVar('enable_emotions', 1, '', 'int');
			
			// prepare an update to existing users...
			$update = JRequest::getVar('update', array(), 'post', 'array');
			$updateCount = count($update);
			
			if ($updateCount) {
				$updateQuery = "UPDATE #__joobb_users SET";
				for ($i=0; $i < $updateCount; $i++) {
					$updateQuery .= "\n $update[$i] = ". $db->Quote(JRequest::getVar($update[$i])).(($i < $updateCount-1) ? ', ' : '');
				}
			}
			
			foreach ($joomlaUsers as $joomlaUser) {

				// check if the user already exists
				$query = "SELECT ju.id"
						. "\n FROM #__joobb_users AS ju"
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
					$query = "INSERT INTO #__joobb_users (id, role, enable_bbcode, enable_emotions) VALUES"
							. "\n (".$joomlaUser->id.",$role,$enableBBCode,$enableEmotions)"
							;
					$db->setQuery($query);
					
					if (!$db->query()) {
						$app->enqueueMessage($db->getErrorMsg(), 'error');
					}
				}		
			}
			$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYSYNCHRONIZED', JText::_('COM_JOOBB_USERS'));
		} else {
			$msg = JText::_('COM_JOOBB_MSGNOUSERSTOSYNCHRONIZE'); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joobb&task=joobb_usersync_view', $msg, $msgType);
	}
}
?>