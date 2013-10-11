<?php
/**
 * @version $Id: profilefieldset.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'profilefieldset.php');

/**
 * Joo!CM Profile Field Set Controller
 *
 * @package Joo!CM
 */
class ControllerProfileFieldSet extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joocm_profilefieldset_view', 'showProfileFieldSets');
		$this->registerTask('joocm_profilefieldset_new', 'editProfileFieldSet');
		$this->registerTask('joocm_profilefieldset_edit', 'editProfileFieldSet');
		$this->registerTask('joocm_profilefieldset_save', 'saveProfileFieldSet');
		$this->registerTask('joocm_profilefieldset_apply', 'saveProfileFieldSet');
		$this->registerTask('joocm_profilefieldset_delete', 'deleteProfileFieldSet');
		$this->registerTask('joocm_profilefieldset_cancel', 'cancelEditProfileFieldSet');
		$this->registerTask('joocm_profilefieldset_orderup', 'orderProfileFieldSet');
		$this->registerTask('joocm_profilefieldset_orderdown', 'orderProfileFieldSet');
		$this->registerTask('joocm_profilefieldset_saveorder', 'saveProfileFieldSetOrder');
		$this->registerTask('joocm_profilefieldset_publish', 'changeProfileFieldSetPublishState');
		$this->registerTask('joocm_profilefieldset_unpublish', 'changeProfileFieldSetPublishState');
	}
	
	/**
	 * compiles a list of profile field sets
	 */
	function showProfileFieldSets() {

		// initialize variables
		$app		=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		
		$context			= 'com_joocm.joocm_profilefieldset_view';
		$filter_order		= $app->getUserStateFromRequest("$context.filter_order", 'filter_order', 'p.ordering');
		$filter_order_Dir	= $app->getUserStateFromRequest("$context.filter_order_Dir",	'filter_order_Dir',	'');
		$limit				= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart			= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');
		
		$orderby = "\n ORDER BY $filter_order $filter_order_Dir";
		
		// get the total number of records
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joocm_profiles_fields_sets AS p"
				. $orderby
				;		
		$db->setQuery($query);
		$total = $db->loadResult();
		
		// create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
												
		$query = "SELECT p.*"
				. "\n FROM #__joocm_profiles_fields_sets AS p"
				. $orderby
				;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();
		
		// parameter list
		$lists = array();
		
		// table ordering
		$lists['filter_order'] = $filter_order;
		$lists['filter_order_Dir']	= $filter_order_Dir;
		
		ViewProfileFieldSet::showProfileFieldSets($rows, $pagination, $lists);	
	}
	
	/**
	 * cancels edit profile field set operation
	 */
	function cancelEditProfileFieldSet() {

		// check in category so other can edit it
		$row =& JTable::getInstance('JoocmProfileFieldSet');
		$row->bind(JRequest::get('post'));
		$row->checkin();

		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldset_view');
	}
	
	/**
	 * edit the profile field set
	 */
	function editProfileFieldSet() {
		
		// initialize variables
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$cid 	= JRequest::getVar('cid', array(0));
		
		if (!is_array($cid)) {
			$cid = array(0);
		}

		$row =& JTable::getInstance('JoocmProfileFieldSet');
		$row->load($cid[0]);

		// is someone else editing this profile field set?
		if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
			$editingUser =& JFactory::getUser($row->checked_out);
			$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldset_view', JText::sprintf('COM_JOOCM_MSGBEINGEDITTED', JText::_('COM_JOOCM_PROFILEFIELDSET'), $row->name, $editingUser->name), 'notice'); return;
		}
		
		// check out profile field set so nobody else can edit it
		$row->checkout($user->get('id'));
		
		// parameter list
		$lists = array();

		// build the html radio buttons for state
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);		
				
		ViewProfileFieldSet::editProfileFieldSet($row, $lists);			
	}
	
	/**
	 * save the profile field set
	 */	
	function saveProfileFieldSet() {

		// initialize variables
		$db		=& JFactory::getDBO();
		$post	= JRequest::get('post');
		$row 	=& JTable::getInstance('JoocmProfileFieldSet');

		if (!$row->bind($post)) {
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
		$row->reorder();

		switch (JRequest::getCmd('task')) {
			case 'joocm_profilefieldset_apply':
				$link = 'index.php?option=com_joocm&task=joocm_profilefieldset_edit&cid[]='. $row->id .'&hidemainmenu=1';
				break;
			case 'joocm_profilefieldset_save':
			default:
				$link = 'index.php?option=com_joocm&task=joocm_profilefieldset_view';
				break;
		}
		
		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOCM_PROFILEFIELDSET'), $row->name);
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * delete the profile field set
	 */	
	function deleteProfileFieldSet() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			
			// how many profile field sets are there to delete?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmProfileFieldSet'); $row->load($cid[0]);
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_PROFILEFIELDSET'), $row->name);
			} else {
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_PROFILEFIELDSETS'), '');
			}
		
			$query = "DELETE FROM #__joocm_profiles_fields_sets"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		} else {
			$msg = JText::sprintf('COM_JOOCM_MSGNOSELECTION', JText::_('COM_JOOCM_PROFILEFIELDSET'), JText::_('COM_JOOCM_DELETE')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldset_view', $msg, $msgType);
	}
	
	/**
	 * moves the order of profile field set up or down
	 */
	function orderProfileFieldSet() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$direction	= JRequest::getCmd('task', 'joocm_profilefieldset_orderup') == 'joocm_profilefieldset_orderup' ? -1 : 1;

		if (isset($cid[0])) {
			$row =& JTable::getInstance('JoocmProfileFieldSet');
			$row->load((int) $cid[0]);
			$row->move($direction);
		}
		
		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYREORDERED', JText::_('COM_JOOCM_PROFILEFIELDSET'), $row->name);
		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldset_view', $msg);
	}
	
	/**
	 * save the profile field set order 
	 */
	function saveProfileFieldSetOrder() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$order		= JRequest::getVar('order', array (0), 'post', 'array');
		$total		= count($cid);
		$conditions	= array();
		
		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));

		// instantiate an profile field set table object
		$row = & JTable::getInstance('JoocmProfileFieldSet');

		// update the ordering for items in the cid array
		for ($i = 0; $i < $total; $i ++) {
			$row->load((int) $cid[$i]);
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError(500, $db->getErrorMsg()); return;
				}
				
				// remember to update order this group
				$condition = "";
				$found = false;
				foreach ($conditions as $cond)
					if ($cond[1] == $condition) {
						$found = true;
						break;
					}
				if (!$found)
					$conditions[] = array($row->id, $condition);
			}
		}

		// execute updateOrder for each group
		foreach ($conditions as $cond) {
			$row->load($cond[0]);
			$row->reorder($cond[1]);
		}

		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSAVEDORDER', JText::_('COM_JOOCM_PROFILEFIELDSET'));
		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldset_view', $msg);
	}
	
	/**
	 * Changes the publish state of a profile field set
	 */
	function changeProfileFieldSetPublishState() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$state		= JRequest::getCmd('task', 'joocm_profilefieldset_publish') == 'joocm_profilefieldset_publish' ? 1 : 0;
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			$msgText = ($state == 1) ? 'COM_JOOCM_MSGSUCCESSFULLYPUBLISHED' : 'COM_JOOCM_MSGSUCCESSFULLYUNPUBLISHED';
			
			// are there one or more rows to publish/unpublish?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmProfileFieldSet'); $row->load($cid[0]);
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_PROFILEFIELDSET'), $row->name);
			} else {
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_PROFILEFIELDSETS'), '');
			}

			$query = "UPDATE #__joocm_profiles_fields_sets"
					. "\n SET published = $state"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
	
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldset_view', $msg, $msgType);
	}
}
?>