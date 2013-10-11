<?php
/**
 * @version $Id: user.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'user.php');

/**
 * Joo!CM User Controller
 *
 * @package Joo!CM
 */
class ControllerUser extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joocm_user_view', 'showUsers');
		$this->registerTask('joocm_user_new', 'editUser');
		$this->registerTask('joocm_user_edit', 'editUser');
		$this->registerTask('joocm_user_save', 'saveUser');
		$this->registerTask('joocm_user_apply', 'saveUser');
		$this->registerTask('joocm_user_delete', 'deleteUser');
		$this->registerTask('joocm_user_cancel', 'cancelEditUser');
		$this->registerTask('joocm_user_block', 'changeUserBlock');
		$this->registerTask('joocm_user_unblock', 'changeUserBlock');
		$this->registerTask('joocm_user_logout', 'logoutUser');
	}
	
	/**
	 * compiles a list of users
	 */
	function showUsers() {
		global $option;

		// initialize variables
		$app			=& JFactory::getApplication();
		$db				=& JFactory::getDBO();
		$currentUser	=& JFactory::getUser();
		$acl			=& JFactory::getACL();
		
		$context			= 'com_joocm.joocm_user_view';
		$filter_order		= $app->getUserStateFromRequest("$context.filter_order",		'filter_order', 	'a.name');
		$filter_order_Dir	= $app->getUserStateFromRequest("$context.filter_order_Dir",	'filter_order_Dir',	'');
		$filter_type		= $app->getUserStateFromRequest("$context.filter_type", 		'filter_type', 		0);
		$filter_logged		= $app->getUserStateFromRequest("$context.filter_logged", 	'filter_logged', 	0);
		$search 			= $app->getUserStateFromRequest("$context.search", 			'search', 			'');
		$search 			= $db->getEscaped(trim(JString::strtolower($search)));
		$where 				= array();

		$limit			= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart		= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');
	
		if (isset($search) && $search!= '') {
			$where[] = "(a.username LIKE '%$search%' OR a.email LIKE '%$search%' OR a.name LIKE '%$search%')";
		}
		if ($filter_type) {
			if ($filter_type == 'Public Frontend') {
				$where[] = "a.usertype = 'Registered' OR a.usertype = 'Author' OR a.usertype = 'Editor' OR a.usertype = 'Publisher'";
			} else if ($filter_type == 'Public Backend') {
				$where[] = "a.usertype = 'Manager' OR a.usertype = 'Administrator' OR a.usertype = 'Super Administrator'";
			} else {
				$where[] = "a.usertype = LOWER('$filter_type')";
			}
		}
		if ($filter_logged == 1) {
			$where[] = "s.userid = a.id";
		} else if ($filter_logged == 2) {
			$where[] = "s.userid IS NULL";
		}
	
		// exclude any child group id's for this user
		$pgids = $acl->get_group_children($currentUser->get('gid'), 'ARO', 'RECURSE');
	
		if (is_array($pgids) && count($pgids) > 0) {
			$where[] = "(a.gid NOT IN (" . implode(',', $pgids) . "))";
		}
		$filter = '';
		if ($filter_logged == 1 || $filter_logged == 2) {
			$filter = "\n INNER JOIN #__session AS s ON s.userid = a.id";
		}

		$orderby = "\n ORDER BY $filter_order $filter_order_Dir";
		$where = (count($where) ? "\n WHERE " . implode(' AND ', $where) : '');
	
		$query = "SELECT COUNT(a.id)"
				. "\n FROM #__users AS a"
				. $filter
				. $where
				;
		$db->setQuery($query);
		$total = $db->loadResult();
	
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);

		$query = "SELECT a.*, g.name AS groupname"
				. "\n FROM #__users AS a"
				. "\n INNER JOIN #__core_acl_aro AS aro ON aro.value = a.id"
				. "\n INNER JOIN #__core_acl_groups_aro_map AS gm ON gm.aro_id = aro.id"
				. "\n INNER JOIN #__core_acl_aro_groups AS g ON g.id = gm.group_id"
				. $filter
				. $where
				. "\n GROUP BY a.id"
				. $orderby
				;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();
			
		$n = count($rows);
		$template = "SELECT COUNT(s.userid)"
				. "\n FROM #__session AS s"
				. "\n WHERE s.userid = %d"
				;
		for ($i = 0; $i < $n; $i++) {
			$row = &$rows[$i];
			$query = sprintf($template, intval($row->id));
			$db->setQuery($query);
			$row->loggedin = $db->loadResult();
		}

		// get list of Groups for dropdown filter
		$query = "SELECT name AS value, name AS text"
				. "\n FROM #__core_acl_aro_groups"
				. "\n WHERE name != 'ROOT'"
				. "\n AND name != 'USERS'"
				;
		$db->setQuery($query);
		$types[] = JHTML::_('select.option', '0', '- '.JText::_('COM_JOOCM_SELECTGROUP').' -', 'value', 'text');
		$types = array_merge($types, $db->loadObjectList());
		$lists['type'] = JHTML::_('select.genericlist',  $types, 'filter_type', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $filter_type);		
		
		// get list of Log Status for dropdown filter
		$logged[] = JHTML::_('select.option', 0, '- '.JText::_('COM_JOOCM_SELECTLOGSTATUS').' -', 'value', 'text');
		$logged[] = JHTML::_('select.option', 1, '- '.JText::_('COM_JOOCM_LOGGEDIN').' -', 'value', 'text');
		$lists['logged'] = JHTML::_('select.genericlist',  $logged, 'filter_logged', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $filter_logged);		
	
		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search'] = $search;
			
		ViewUser::showUsers($rows, $pagination, $lists);				

	}

	/**
	 * cancels edit user operation
	 */
	function cancelEditUser() {
		$this->setRedirect('index.php?option=com_joocm&task=joocm_user_view');
	}

	/**
	 * edit the user
	 */
	function editUser() {

		// initialize variables
		$db				=& JFactory::getDBO();
		$joocmConfig	=& JoocmConfig::getInstance();

		$cid 	= JRequest::getVar('cid', array(0));
		$option = JRequest::getVar('option');
		
		if (!is_array($cid)) {
			$cid = array(0);
		}
		
		if ($cid[0]) {
			$joocmUser =& JoocmUser::getInstance($cid[0]);
		} else {
			$joocmUser = new JoocmUser(0, true);
		}
		
		// lists
		$lists = array();

		// build the html radio buttons for block
		$lists['block'] = JHTML::_('select.booleanlist', 'block', '', $joocmUser->get('block'));				
		// build the html radio buttons for sendEmail
		$lists['sendEmail'] = JHTML::_('select.booleanlist', 'sendEmail', '', $joocmUser->get('sendEmail'));
		// build the html radio buttons for agreed terms
		$lists['agreed_terms'] = JHTML::_('select.booleanlist', 'agreed_terms', '', $joocmUser->get('agreed_terms'));
		// build the html radio buttons for show email
		$lists['show_email'] = JHTML::_('select.booleanlist', 'show_email', '', $joocmUser->get('show_email'));
		// build the html radio buttons for show online state
		$lists['show_online_state'] = JHTML::_('select.booleanlist', 'show_online_state', '', $joocmUser->get('show_online_state'));
		// build the html radio buttons for system emails
		$lists['system_emails'] = JHTML::_('select.booleanlist', 'system_emails', '', $joocmUser->get('system_emails'));
		// build the html radio buttons for hide
		$lists['hide'] = JHTML::_('select.booleanlist', 'hide', '', $joocmUser->get('hide'));
		
		// list time formats		
		$query = "SELECT f.*"
				. "\n FROM #__joocm_timeformats AS f"
				. "\n ORDER BY f.name"
				;
		$db->setQuery($query);
		$lists['timeformats'] = JHTML::_('select.genericlist',  $db->loadObjectList(), 'time_format', 'class="inputbox" size="1"', 'timeformat', 'name', $joocmUser->get('time_format'));
		
		if ($joocmConfig->getConfigSettings('enable_profiles')) {
		
			// get profile data
			$query = "SELECT s.*"
					. "\n FROM #__joocm_profiles_fields_sets AS s"
					. "\n WHERE s.published = 1"
					. "\n ORDER BY s.ordering"
					;
			$db->setQuery($query);
			$lists['fieldsets'] = $db->loadObjectList();
			
			$query = "SELECT f.*"
					. "\n FROM #__joocm_profiles_fields AS f"
					. "\n WHERE f.published = 1"
					. "\n ORDER BY f.ordering"
					;
			$db->setQuery($query);
			$fieldrows = $db->loadObjectList();
			
			$fields = array();
			foreach($fieldrows as $fieldrow) {
				$fields[] = JoocmHelper::createElement($fieldrow, $joocmUser->get($fieldrow->name));
			}
			$lists['fields'] = $fields;
		}
		
		ViewUser::editUser($joocmUser, $lists);
	}
	
	/**
	 * save the user
	 */	
	function saveUser() {

		// initialize variables
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar('id', 0, 'post', 'int');
		
		if ($cid) {
			$joocmUser =& JoocmUser::getInstance($cid);
		} else {
			$joocmUser = new JoocmUser(0, true);
		}
		
		$isNewUser = ($joocmUser->get('id') < 1);
		
		if (!$joocmUser->bind($post)) {
			$this->setRedirect('index.php?option=com_joocm&task=joocm_user_edit&cid[]='. $cid .'&hidemainmenu=1', $joocmUser->getError(), 'error'); return false;
		}
		
		// we need to request the text separetly, otherwise html tags will be not saved
		$signature = JRequest::getVar('signature', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		// clean text for xhtml transitional compliance
		$joocmUser->set('signature', str_replace('<br>', '<br />', $signature));
		
		// lets save the user
		if (!$joocmUser->save()) {
			$this->setRedirect('index.php?option=com_joocm&task=joocm_user_edit&cid[]='. $cid .'&hidemainmenu=1', $joocmUser->getError(), 'error'); return;
		}

		// handle email sending
		if ($isNewUser) {
			JRequest::setVar('password', $joocmUser->password_clear);
			
			// send registration mail
			$joocmMail =& JoocmMail::getInstance();
			$joocmMail->sendRegistrationMail($joocmUser);
		}
				
		switch (JRequest::getCmd('task')) {
			case 'joocm_user_apply':
				$link = 'index.php?option=com_joocm&task=joocm_user_edit&cid[]='. $joocmUser->get('id') .'&hidemainmenu=1';
				break;
			case 'joocm_user_save':
			default:
				$link = 'index.php?option=com_joocm&task=joocm_user_view';
				break;
		}
		
		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOCM_USER'), $joocmUser->get('name'));
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * delete the user
	 */	
	function deleteUser() {

		// initialize variables
		$db 			=& JFactory::getDBO();
		$currentUser 	=& JFactory::getUser();
		$acl			=& JFactory::getACL();
		$cid 			= JRequest::getVar('cid', array(), '', 'array');
		$msgType		= '';

		JArrayHelper::toInteger($cid);

		if (count($cid)) {
			
			// how many users are there to delete?
			if (count($cid) == 1) {
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_USER'), '');
			} else {
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_USERS'), '');
			}
			
			// ToDo: original joomla code => rewrite for joocm needs
			foreach ($cid as $id) {
			
				// check for a super admin ... can't delete them
				$objectID 	= $acl->get_object_id('users', $id, 'ARO');
				$groups 	= $acl->get_object_groups($objectID, 'ARO');
				$this_group = strtolower($acl->get_group_name($groups[0], 'ARO'));
	
				if ($this_group == 'super administrator') {
					$msg = JText::_('You cannot delete a Super Administrator');
				} else if ($id == $currentUser->get('id')) {
					$msg = JText::_('You cannot delete Yourself!');
				} else if (($this_group == 'administrator') && ($currentUser->get('gid') == 24)) {
					$msg = JText::_('WARNDELETE');
				} else {
					$joocmUser =& JoocmUser::getInstance((int)$id);
					$count = 2;
	
					if ($joocmUser->get('gid') == 25) {
					
						// count number of active super admins
						$query = "SELECT COUNT(id)"
								. "\n FROM #__users"
								. "\n WHERE gid = 25"
								. "\n AND block = 0"
						;
						$db->setQuery($query);
						$count = $db->loadResult();
					}
	
					if ($count <= 1 && $joocmUser->get('gid') == 25) {
					
						// cannot delete Super Admin where it is the only one that exists
						$msg = "You cannot delete this Super Administrator as it is the only active Super Administrator for your site";
					} else {
	
						// delete users active session
						ControllerUser::logoutUser();
						
						// delete user
						$joocmUser->delete();
					}
				}
			}
		} else {
			$msg = JText::sprintf('COM_JOOCM_MSGNOSELECTION', JText::_('COM_JOOCM_USER'), JText::_('COM_JOOCM_DELETE')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_user_view', $msg, $msgType);
	}
	
	/**
	 * blocks or unblocks user
	 */
	function changeUserBlock() {

		// initialize variables
		$db 	= JFactory::getDBO();
		$cid	= JRequest::getVar('cid', array(), 'post', 'array');
		$block	= JRequest::getCmd('task', 'joocm_user_block') == 'joocm_user_block' ? 1 : 0;
	
		JArrayHelper::toInteger($cid);

		$cids = implode(',', $cid);
		$query = "UPDATE #__users"
				. "\n SET block = $block"
				. "\n WHERE id IN ($cids)"
				;
		$db->setQuery($query);
	
		if (!$db->query()) {
			$this->setRedirect('index.php?option=com_joocm&task=joocm_user_view', $db->getErrorMsg(), 'error'); return;
		}
	
		// if action is to block a user
		if ($block == 1) {
			foreach($cid as $id) {
				JRequest::setVar('cid', $id);
					
				// delete users active session
				ControllerUser::logoutUser();
			}
		}
		
		$this->setRedirect('index.php?option=com_joocm&task=joocm_user_view');
	}
	
	/**
	 * logout user
	 */
	function logoutUser() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$task		= JRequest::getVar('task');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);

		if (count($cid)) {
			$query = "DELETE FROM #__session"
					. "\n WHERE userid = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if ($db->query()) {
			
				// we are done when calling from blog or delete function
				if ($task == 'joocm_user_delete' || $task == 'joocm_user_block') {
					return;
				}
				
				// how many users are there to logout?
				if (count($cid) == 1) {
					$joocmUser =& JoocmUser::getInstance($cid[0]);
					$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYLOGGEDOUT', JText::_('COM_JOOCM_USER'), $joocmUser->get('name'));
				} else {
					$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYLOGGEDOUT', JText::_('COM_JOOCM_USERS'), '');
				}
			} else {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		} else {
			$msg = JText::sprintf('COM_JOOCM_MSGNOSELECTION', JText::_('COM_JOOCM_USER'), JText::_('COM_JOOCM_LOGOUT')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_user_view', $msg, $msgType);
	}
}
?>