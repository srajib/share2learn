<?php
/**
 * @version $Id: group.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'group.php');

/**
 * Joo!BB Group Controller
 *
 * @package Joo!BB
 */
class ControllerGroup extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joobb_group_view', 'showGroups');
		$this->registerTask('joobb_group_new', 'editGroup');
		$this->registerTask('joobb_group_edit', 'editGroup');
		$this->registerTask('joobb_group_save', 'saveGroup');
		$this->registerTask('joobb_group_apply', 'saveGroup');
		$this->registerTask('joobb_group_delete', 'deleteGroup');
		$this->registerTask('joobb_group_cancel', 'cancelEditGroup');
		$this->registerTask('joobb_group_publish', 'changeGroupPublishState');
		$this->registerTask('joobb_group_unpublish', 'changeGroupPublishState');
	}
	
	/**
	 * compiles a list of groups
	 */
	function showGroups() {

		// initialize variables
		$app		=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		
		$context			= 'com_joobb.joobb_group_view';
		$filter_order		= $app->getUserStateFromRequest("$context.filter_order", 'filter_order', 'g.name');
		$filter_order_Dir	= $app->getUserStateFromRequest("$context.filter_order_Dir", 'filter_order_Dir', '');
		$limit				= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart			= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');

		$orderby = "\n ORDER BY $filter_order $filter_order_Dir";
		
		// get the total number of records
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joobb_groups AS g"
				. $orderby
				;		
		$db->setQuery($query);
		$total = $db->loadResult();
		
		// create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
												
		$query = "SELECT g.*"
				. "\n FROM #__joobb_groups AS g"
				. $orderby
				;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();
		
		// parameter list
		$lists = array();
		
		// joobb user roles
		$joobbAuth =& JoobbAuth::getInstance();
		$lists['roles'] = $joobbAuth->getUserRoleList();
				
		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order'] = $filter_order;
		
		ViewGroup::showGroups($rows, $pagination, $lists);	
	}
	
	/**
	 * cancels edit group operation
	 */
	function cancelEditGroup() {

		// check in category so other can edit it
		$row =& JTable::getInstance('JoobbGroup');
		$row->bind(JRequest::get('post'));
		$row->checkin();

		$this->setRedirect('index.php?option=com_joobb&task=joobb_group_view');
	}
	
	/**
	 * edit the group
	 */
	function editGroup() {

		// initialize variables
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$cid 	= JRequest::getVar('cid', array(0));
		
		if (!is_array($cid)) {
			$cid = array(0);
		}

		$row =& JTable::getInstance('JoobbGroup');
		$row->load($cid[0]);

		// is someone else editing this group?
		if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
			$editingUser =& JFactory::getUser($row->checked_out);
			$this->setRedirect('index.php?option=com_joobb&task=joobb_group_view', JText::sprintf('COM_JOOBB_MSGBEINGEDITTED', JText::_('COM_JOOBB_GROUP'), $row->name, $editingUser->name)); return;
		}
		
		// check out group so nobody else can edit it
		$row->checkout($user->get('id'));
		
		// parameter list
		$lists = array();
		
		// get authentification option list
		$joobbAuth =& JoobbAuth::getInstance();
		$authOptionList = $joobbAuth->getAuthOptionList();

		$lists['roles'] = JHTML::_('select.genericlist',  $authOptionList, 'role', 'class="inputbox" size="1"', 'value', 'text', $row->role);		
		
		// build the html radio buttons for published
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);
	
		// list group users
		$query = "SELECT g.*, u.name" 
				. "\n FROM #__joobb_groups_users AS g"
				. "\n LEFT JOIN #__users AS u ON g.id_user= u.id"
				. "\n WHERE g.id_group = ". $row->id	
				. "\n ORDER BY u.name"
				;
		$db->setQuery($query);
		$lists['groupusers'] = JHTML::_('select.genericlist',  $db->loadObjectList(), 'groupusers', 'class="inputbox" size="10" multiple="multiple"', 'id', 'name');

		// list forums
		$query = "SELECT f.*" 
				. "\n FROM #__joobb_forums AS f"
				. "\n WHERE f.status = 1"	
				. "\n ORDER BY f.name"
				;
		$db->setQuery($query);
		$forumrows = $db->loadObjectList();
			
		$forums = array();
		foreach($forumrows as $forumsrow) {
			$forums[] = JHTML::_('select.option', $forumsrow->id, $forumsrow->name);
		}
		
		// get administrated forums by the user
		$query = "SELECT fa.id_forum AS value" 
				. "\n FROM #__joobb_forums_auth AS fa"
				. "\n WHERE fa.role = 4"
				. "\n AND fa.id_group = ".$row->id
				;
		$db->setQuery($query);			
		$lists['administratedforums'] = JHTML::_('select.genericlist', $forums, 'administratedforums[]', 'class="inputbox" size="10" multiple="multiple"', 'value', 'text', $db->loadObjectList());
				
		// get moderated forums by the user
		$query = "SELECT fa.id_forum AS value" 
				. "\n FROM #__joobb_forums_auth AS fa"
				. "\n WHERE fa.role = 3"
				. "\n AND fa.id_group = ".$row->id
				;
		$db->setQuery($query);		
		$lists['moderatedforums'] = JHTML::_('select.genericlist', $forums, 'moderatedforums[]', 'class="inputbox" size="10" multiple="multiple"', 'value', 'text', $db->loadObjectList());
				
		// get forums with private access for the user
		$query = "SELECT fa.id_forum AS value" 
				. "\n FROM #__joobb_forums_auth AS fa"
				. "\n WHERE fa.role = 2"
				. "\n AND fa.id_group = ".$row->id
				;
		$db->setQuery($query);		
		$lists['privateforums'] = JHTML::_('select.genericlist', $forums, 'privateforums[]', 'class="inputbox" size="10" multiple="multiple"', 'value', 'text', $db->loadObjectList());
		
		ViewGroup::editGroup($row, $lists);			
	}
	
	/**
	 * save the group
	 */	
	function saveGroup() {

		// initialize variables
		$db =& JFactory::getDBO();
		$task = JRequest::getVar('task');

		$row =& JTable::getInstance('JoobbGroup');

		if (!$row->bind(JRequest::get('post'))) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if (!$row->check()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$row->checkin();
		
		// save forums administrated by the user
		$administratedforums = JRequest::getVar('administratedforums', array(), 'post', 'array');
		if (is_array($administratedforums)) {
		
			// first delete all moderating authentifications
			$query = "DELETE FROM #__joobb_forums_auth"
					. "\n WHERE id_group = ". $row->id
					. "\n AND role = 4"
					;
			$db->setQuery($query);
			if (!$db->query()) {
				$this->setRedirect('index.php?option=com_joobb&task=joobb_user_edit&cid[]='. $cid .'&hidemainmenu=1', $db->getErrorMsg()); return;
			}
			
			// add all selected forums which the user should modarate		
			foreach ($administratedforums as $administratedforum) {
				$query = "INSERT INTO #__joobb_forums_auth"
						. "\n SET id_forum = $administratedforum, role = 4, id_group = ". $row->id
						;
				$db->setQuery($query);
				if (!$db->query()) {
					$this->setRedirect('index.php?option=com_joobb&task=joobb_user_edit&cid[]='. $cid .'&hidemainmenu=1', $db->getErrorMsg()); return;	
				}
			}
		}	

		// save forums moderated by the user
		$moderatedforums = JRequest::getVar('moderatedforums', array(), 'post', 'array');
		if (is_array($moderatedforums)) {
		
			// first delete all moderating authentifications
			$query = "DELETE FROM #__joobb_forums_auth"
					. "\n WHERE id_group = ". $row->id
					. "\n AND role = 3"
					;
			$db->setQuery($query);
			if (!$db->query()) {
				$this->setRedirect('index.php?option=com_joobb&task=joobb_user_edit&cid[]='. $cid .'&hidemainmenu=1', $db->getErrorMsg()); return;
			}
			
			// add all selected forums which the user should modarate		
			foreach ($moderatedforums as $moderatedforum) {
				$query = "INSERT INTO #__joobb_forums_auth"
						. "\n SET id_forum = $moderatedforum, role = 3, id_group = ". $row->id
						;
				$db->setQuery($query);
				if (!$db->query()) {
					$this->setRedirect('index.php?option=com_joobb&task=joobb_user_edit&cid[]='. $cid .'&hidemainmenu=1', $db->getErrorMsg()); return;
				}
			}
		}

		// save forums with private access for the user
		$privateforums = JRequest::getVar('privateforums', array(), 'post', 'array');
		if (is_array($privateforums)) {
		
			// first delete all moderating authentifications
			$query = "DELETE FROM #__joobb_forums_auth"
					. "\n WHERE id_group = ". $row->id
					. "\n AND role = 2"
					;
			$db->setQuery($query);
			if (!$db->query()) {
				$this->setRedirect('index.php?option=com_joobb&task=joobb_user_edit&cid[]='. $cid .'&hidemainmenu=1', $db->getErrorMsg()); return;
			}
			
			// add all selected forums which the user should modarate		
			foreach ($privateforums as $privateforum) {
						$query = "INSERT INTO #__joobb_forums_auth"
						. "\n SET id_forum = $privateforum, role = 2, id_group = ". $row->id
						;
				$db->setQuery($query);
				if (!$db->query()) {
					$this->setRedirect('index.php?option=com_joobb&task=joobb_user_edit&cid[]='. $cid .'&hidemainmenu=1', $db->getErrorMsg()); return;
				}
			}
		}
		
		switch (JRequest::getCmd('task')) {
			case 'joobb_group_apply':
				$link = 'index.php?option=com_joobb&task=joobb_group_edit&cid[]='. $row->id .'&hidemainmenu=1';
				break;
			case 'joobb_group_save':
			default:
				$link = 'index.php?option=com_joobb&task=joobb_group_view';
				break;
		}
		
		$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOBB_GROUP'), $row->name);
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * delete the group
	 */	
	function deleteGroup() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
	
		if (count($cid)) {
			
			// how many groups are there to delete?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoobbGroup'); $row->load($cid[0]);
				$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOBB_GROUP'), $row->name);
			} else {
				$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOBB_GROUPS'), '');
			}
		
			$query = "DELETE FROM #__joobb_groups"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		} else {
			$msg = JText::sprintf('COM_JOOBB_MSGNOSELECTION', JText::_('COM_JOOBB_GROUP'), JText::_('COM_JOOBB_DELETE')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joobb&task=joobb_group_view', $msg, $msgType);
	}
	
	/**
	 * changes the publish state of a group
	 */
	function changeGroupPublishState() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$state		= JRequest::getCmd('task', 'joobb_group_publish') == 'joobb_group_publish' ? 1 : 0;
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			$msgText = ($state == 1) ? 'COM_JOOBB_MSGSUCCESSFULLYPUBLISHED' : 'COM_JOOBB_MSGSUCCESSFULLYUNPUBLISHED';
			
			// are there one or more rows to publish/unpublish?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoobbGroup'); $row->load($cid[0]);
				$msg = JText::sprintf($msgText, JText::_('COM_JOOBB_GROUP'), $row->name);
			} else {
				$msg = JText::sprintf($msgText, JText::_('COM_JOOBB_GROUPS'), '');
			}
			
			$query = "UPDATE #__joobb_groups"
					. "\n SET published = $state"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
	
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}		
		}

		$this->setRedirect('index2.php?option=com_joobb&task=joobb_group_view', $msg, $msgType);
	}		
}
?>