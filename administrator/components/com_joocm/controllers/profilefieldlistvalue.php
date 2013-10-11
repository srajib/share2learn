<?php
/**
 * @version $Id: profilefieldlistvalue.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'profilefieldlistvalue.php');

/**
 * Joo!CM Profile Field List Value Controller
 *
 * @package Joo!CM
 */
class ControllerProfileFieldListValue extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joocm_profilefieldlistvalue_view', 'showProfileFieldListValues');
		$this->registerTask('joocm_profilefieldlistvalue_new', 'editProfileFieldListValue');
		$this->registerTask('joocm_profilefieldlistvalue_edit', 'editProfileFieldListValue');
		$this->registerTask('joocm_profilefieldlistvalue_save', 'saveProfileFieldListValue');
		$this->registerTask('joocm_profilefieldlistvalue_apply', 'saveProfileFieldListValue');
		$this->registerTask('joocm_profilefieldlistvalue_delete', 'deleteProfileFieldListValue');
		$this->registerTask('joocm_profilefieldlistvalue_cancel', 'cancelEditProfileFieldListValue');
		$this->registerTask('joocm_profilefieldlistvalue_orderup', 'orderProfileFieldListValue');
		$this->registerTask('joocm_profilefieldlistvalue_orderdown', 'orderProfileFieldListValue');
		$this->registerTask('joocm_profilefieldlistvalue_saveorder', 'saveProfileFieldListValueOrder');
		$this->registerTask('joocm_profilefieldlistvalue_publish', 'changeProfileFieldListValueState');
		$this->registerTask('joocm_profilefieldlistvalue_unpublish', 'changeProfileFieldListValueState');
	}
	
	/**
	 * compiles a list of profile field list values
	 */
	function showProfileFieldListValues() {

		// initialize variables
		$app		=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		
		$context			= 'com_joocm.joocm_profilefieldlistvalue_view';
		$filter_fieldlist	= $app->getUserStateFromRequest("$context.filter_fieldlist", 'filter_fieldlist', 0);
		$filter_order		= $app->getUserStateFromRequest("$context.filter_order", 'filter_order', 'v.id_profile_field_list, v.ordering');
		$filter_order_Dir	= $app->getUserStateFromRequest("$context.filter_order_Dir",	'filter_order_Dir',	'');
		$limit				= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart			= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');

		$where = array();
		if ($filter_fieldlist > 0) {
			$where[] = "v.id_profile_field_list = $filter_fieldlist";
		}
		$where = (count($where) ? "\n WHERE " . implode(' AND ', $where) : '');
		
		$orderby = "\n ORDER BY $filter_order $filter_order_Dir";
		
		// get the total number of records
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joocm_profiles_fields_lists_values AS v"
				. $where
				. $orderby
				;		
		$db->setQuery($query);
		$total = $db->loadResult();
		
		// create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
												
		$query = "SELECT v.*, l.name AS profile_field_list_name"
				. "\n FROM #__joocm_profiles_fields_lists_values AS v"
				. "\n LEFT JOIN #__joocm_profiles_fields_lists AS l ON l.id = v.id_profile_field_list"
				. $where
				. $orderby
				;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();
		
		// parameter list
		$lists = array();
		
		// get profile field lists
		$query = "SELECT l.*"
				. "\n FROM #__joocm_profiles_fields_lists AS l"
				. "\n ORDER BY l.name"
				;
		$db->setQuery($query);
		$profileFieldLists[] = JHTML::_('select.option', '0', '- '.JText::_('COM_JOOCM_SELECTPROFILEFIELDLIST').' -', 'id', 'name');
		$profileFieldLists = array_merge($profileFieldLists, $db->loadObjectList());
		$lists['profilefieldlists'] = JHTML::_('select.genericlist',  $profileFieldLists, 'filter_fieldlist', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'id', 'name', $filter_fieldlist);

		// table ordering
		$lists['filter_order'] = $filter_order;
		$lists['filter_order_Dir']	= $filter_order_Dir;
									
		ViewProfileFieldListValue::showProfileFieldListValues($rows, $pagination, $lists);	
	}
	
	/**
	 * cancels edit profile field list value operation
	 */
	function cancelEditProfileFieldListValue() {

		// check in category so other can edit it
		$row =& JTable::getInstance('JoocmProfileFieldListValue');
		$row->bind(JRequest::get('post'));
		$row->checkin();

		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldlistvalue_view');
	}
	
	/**
	 * edit the profile field list value
	 */
	function editProfileFieldListValue() {

		// initialize variables
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$cid 	= JRequest::getVar('cid', array(0));
		
		if (!is_array($cid)) {
			$cid = array(0);
		}

		$row =& JTable::getInstance('JoocmProfileFieldListValue');
		$row->load($cid[0]);
		
		if (!$row->id) {
			$row->published = 1;
		}

		// is someone else editing this profile field list value?
		if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
			$editingUser =& JFactory::getUser($row->checked_out);
			$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldlistvalue_view', JText::sprintf('COM_JOOCM_MSGBEINGEDITTED', JText::_('COM_JOOCM_PROFILEFIELDLISTVALUE'), $row->name, $editingUser->name), 'notice'); return;
		}
		
		// check out profile field list value so nobody else can edit it
		$row->checkout($user->get('id'));
		
		// parameter list
		$lists = array();

		// list profile field lists		
		$query = "SELECT l.*"
				. "\n FROM #__joocm_profiles_fields_lists AS l"
				. "\n ORDER BY l.name"
				;
		$db->setQuery($query);	
		$lists['profilefieldlists'] = JHTML::_('select.genericlist',  $db->loadObjectList(), 'id_profile_field_list', 'class="inputbox" size="1"', 'id', 'name', intval($row->id_profile_field_list));

		// build the html radio buttons for state
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);
						
		ViewProfileFieldListValue::editProfileFieldListValue($row, $lists);			
	}
	
	/**
	 * save the profile field list value
	 */	
	function saveProfileFieldListValue() {

		// initialize variables
		$db =& JFactory::getDBO();
		$post	= JRequest::get('post');
		$row =& JTable::getInstance('JoocmProfileFieldListValue');

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
			case 'joocm_profilefieldlistvalue_apply':
				$link = 'index.php?option=com_joocm&task=joocm_profilefieldlistvalue_edit&cid[]='. $row->id .'&hidemainmenu=1';
				break;
			case 'joocm_profilefieldlistvalue_save':
			default:
				$link = 'index.php?option=com_joocm&task=joocm_profilefieldlistvalue_view';
				break;
		}
		
		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOCM_PROFILEFIELDLISTVALUE'), $row->name);
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * delete the profile field list value
	 */	
	function deleteProfileFieldListValue() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			
			// how many categories are there to delete?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmProfileFieldListValue'); $row->load($cid[0]);
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_PROFILEFIELDLISTVALUE'), $row->name);
			} else {
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_PROFILEFIELDLISTVALUES'), '');
			}
		
			$query = "DELETE FROM #__joocm_profiles_fields_lists_values"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		} else {
			$msg = JText::sprintf('COM_JOOCM_MSGNOSELECTION', JText::_('COM_JOOCM_PROFILEFIELDLISTVALUE'), JText::_('COM_JOOCM_DELETE')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldlistvalue_view', $msg, $msgType);
	}

	/**
	 * moves the order of profile field list value up or down
	 */
	function orderProfileFieldListValue() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$direction	= JRequest::getCmd('task', 'joocm_profilefieldlistvalue_orderup') == 'joocm_profilefieldlistvalue_orderup' ? -1 : 1;

		if (isset($cid[0])) {
			$row =& JTable::getInstance('JoocmProfileFieldListValue');
			$row->load((int) $cid[0]);
			$row->move($direction);
		}
		
		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYREORDERED', JText::_('COM_JOOCM_PROFILEFIELDLISTVALUE'), $row->name);
		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldlistvalue_view', $msg);
	}

	/**
	 * save the profile field list value order 
	 */
	function saveProfileFieldListValueOrder() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$order		= JRequest::getVar('order', array (0), 'post', 'array');
		$total		= count($cid);
		$conditions	= array();

		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));

		// instantiate a profile field list value table object
		$row = & JTable::getInstance('JoocmProfileFieldListValue');

		// update the ordering for items in the cid array
		for ($i = 0; $i < $total; $i ++) {
			$row->load((int) $cid[$i]);
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError(500, $db->getErrorMsg()); return;
				}
				
				// remember to updateOrder this group
				$condition = "id_profile_field_list = $row->id_profile_field_list";
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
		
		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSAVEDORDER', JText::_('COM_JOOCM_PROFILEFIELDLISTVALUE'));
		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldlistvalue_view', $msg);
	}

	/**
	 * changes the publish state of a profile field list value
	 */
	function changeProfileFieldListValueState() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$state		= JRequest::getCmd('task', 'joocm_profilefieldlistvalue_publish') == 'joocm_profilefieldlistvalue_publish' ? 1 : 0;
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			$msgText = ($state == 1) ? 'COM_JOOCM_MSGSUCCESSFULLYPUBLISHED' : 'COM_JOOCM_MSGSUCCESSFULLYUNPUBLISHED';
			
			// are there one or more rows to publish/unpublish?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmProfileFieldListValue'); $row->load($cid[0]);
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_PROFILEFIELDLISTVALUE'), $row->name);
			} else {
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_PROFILEFIELDLISTVALUES'), '');
			}
			
			$query = "UPDATE #__joocm_profiles_fields_lists_values"
					. "\n SET published = $state"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
		
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldlistvalue_view', $msg, $msgType);
	}	
}
?>