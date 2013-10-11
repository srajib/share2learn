<?php
/**
 * @version $Id: profilefield.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'profilefield.php');

/**
 * Joo!CM Profile Field Controller
 *
 * @package Joo!CM
 */
class ControllerProfileField extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joocm_profilefield_view', 'showProfileFields');
		$this->registerTask('joocm_profilefield_new', 'editProfileField');
		$this->registerTask('joocm_profilefield_edit', 'editProfileField');
		$this->registerTask('joocm_profilefield_save', 'saveProfileField');
		$this->registerTask('joocm_profilefield_apply', 'saveProfileField');
		$this->registerTask('joocm_profilefield_delete', 'deleteProfileField');
		$this->registerTask('joocm_profilefield_cancel', 'cancelEditProfileField');
		$this->registerTask('joocm_profilefield_orderup', 'orderProfileField');
		$this->registerTask('joocm_profilefield_orderdown', 'orderProfileField');
		$this->registerTask('joocm_profilefield_saveorder', 'saveProfileFieldOrder');
		$this->registerTask('joocm_profilefield_publish', 'changeProfileFieldPublishState');
		$this->registerTask('joocm_profilefield_unpublish', 'changeProfileFieldPublishState');
	}
	
	/**
	 * compiles a list of profile fields
	 */
	function showProfileFields() {

		// initialize variables
		$app		=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		
		$context			= 'com_joocm.joocm_profilefield_view';
		$filter_order		= $app->getUserStateFromRequest("$context.filter_order", 'filter_order', 's.ordering, p.ordering');
		$filter_order_Dir	= $app->getUserStateFromRequest("$context.filter_order_Dir",	'filter_order_Dir',	'');
		$limit				= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart			= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');
		
		$orderby = "\n ORDER BY $filter_order $filter_order_Dir";
		
		// get the total number of records
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joocm_profiles_fields AS p"
				. "\n INNER JOIN #__joocm_profiles_fields_sets AS s ON s.id = p.id_profile_field_set"
				. $orderby
				;		
		$db->setQuery($query);
		$total = $db->loadResult();
		
		// create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
												
		$query = "SELECT p.*, s.name AS profile_field_set_name"
				. "\n FROM #__joocm_profiles_fields AS p"
				. "\n INNER JOIN #__joocm_profiles_fields_sets AS s ON s.id = p.id_profile_field_set"
				. $orderby
				;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();
		
		// parameter list
		$lists = array();
		
		// table ordering
		$lists['filter_order'] = $filter_order;
		$lists['filter_order_Dir']	= $filter_order_Dir;
		
		ViewProfileField::showProfileFields($rows, $pagination, $lists);	
	}
	
	/**
	 * cancels edit profile field operation
	 */
	function cancelEditProfileField() {

		// check in profile field so other can edit it
		$row =& JTable::getInstance('JoocmProfileField');
		$row->bind(JRequest::get('post'));
		$row->checkin();

		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefield_view');
	}
	
	/**
	 * edit the profile field
	 */
	function editProfileField() {

		// initialize variables
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$cid 	= JRequest::getVar('cid', array(0));
		
		if (!is_array($cid)) {
			$cid = array(0);
		}

		$row =& JTable::getInstance('JoocmProfileField');
		$row->load($cid[0]);

		// is someone else editing this profile field?
		if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
			$editingUser =& JFactory::getUser($row->checked_out);
			$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefield_view', JText::sprintf('COM_JOOCM_MSGBEINGEDITTED', JText::_('COM_JOOCM_PROFILEFIELD'), $row->name, $editingUser->name), 'notice'); return;
		}
		
		// check out profile field so nobody else can edit it
		$row->checkout($user->get('id'));
		
		// parameter list
		$lists = array();

		// build the html radio buttons for published
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);
		// build the html radio buttons for required
		$lists['required'] = JHTML::_('select.booleanlist', 'required', '', $row->required);
		// build the html radio buttons for disabled
		$lists['disabled'] = JHTML::_('select.booleanlist', 'disabled', '', $row->disabled);

		// list profile field sets		
		$query = "SELECT p.*"
				. "\n FROM #__joocm_profiles_fields_sets AS p"
				. "\n ORDER BY p.ordering"
				;
		$db->setQuery($query);
		$lists['profilefieldsets'] = JHTML::_('select.genericlist', $db->loadObjectList(), 'id_profile_field_set', 'class="inputbox" size="1"', 'id', 'name', intval($row->id_profile_field_set));
	
		// list profile field lists		
		$query = "SELECT l.*"
				. "\n FROM #__joocm_profiles_fields_lists AS l"
				. "\n ORDER BY l.name"
				;
		$db->setQuery($query);
		$lists['profilefieldlists'] = JHTML::_('select.genericlist', $db->loadObjectList(), 'id_profile_field_list', 'class="inputbox" size="1"', 'id', 'name', intval($row->id_profile_field_list));
		
		// list GUI elements
		$elements = array();
		$elements[] = JHTML::_('select.option', 0, JText::_('TextBox'));
		$elements[] = JHTML::_('select.option', 1, JText::_('TextArea'));
//		$elements[] = JHTML::makeOption(2, JText::_('CheckBox'));
		$elements[] = JHTML::_('select.option', 3, JText::_('RadioButton'));
		$elements[] = JHTML::_('select.option', 4, JText::_('ListBox'));
		$elements[] = JHTML::_('select.option', 5, JText::_('ComboBox'));
		$lists['elements'] = JHTML::_('select.genericlist', $elements, 'element', 'class="inputbox" size="1" onchange="selElement(this.options[this.selectedIndex].value);"', 'value', 'text', $row->element);
		
		$types = array();
		$types[] = JHTML::_('select.option', 'varchar', JText::_('Text'));
		$types[] = JHTML::_('select.option', 'integer', JText::_('Integer'));
		$types[] = JHTML::_('select.option', 'date', JText::_('Date'));
		$types[] = JHTML::_('select.option', 'time', JText::_('Time'));
		$types[] = JHTML::_('select.option', 'datetime', JText::_('Datetime'));
		$lists['types'] = JHTML::_('select.genericlist', $types, 'type', 'class="inputbox" size="1"', 'value', 'text', $row->type);
		
		ViewProfileField::editProfileField($row, $lists);			
	}
	
	/**
	 * save the profile field
	 */	
	function saveProfileField() {

		// initialize variables
		$db =& JFactory::getDBO();
		$cid 	= JRequest::getVar('id', 0, 'post', 'int');
		$post	= JRequest::get('post');

		$row =& JTable::getInstance('JoocmProfileField');
		
		if (!$row->bind($post)) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		
		// must be called before storing current record!
		JoocmHelper::changeField($row, $cid);
		
		if ($row->id == 0) {	
			$query = "SELECT Max(ordering) AS ordering"
					. "\n FROM #__joocm_profiles_fields"
					. "\n WHERE id_profile_field_set = $row->id_profile_field_set"
					;
			$db->setQuery($query);
			$max = $db->loadObject();
			$row->ordering = $max->ordering + 1;
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

		switch (JRequest::getCmd('task')) {
			case 'joocm_profilefield_apply':
				$link = 'index.php?option=com_joocm&task=joocm_profilefield_edit&cid[]='. $row->id .'&hidemainmenu=1';
				break;

			case 'joocm_profilefield_save':
			default:
				$link = 'index.php?option=com_joocm&task=joocm_profilefield_view';
				break;
		}

		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOCM_PROFILEFIELD'), $row->name);
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * delete the profile field
	 */	
	function deleteProfileField() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			
			// how many categories are there to delete?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmProfileField'); $row->load($cid[0]);
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_PROFILEFIELD'), $row->name);
			} else {
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_PROFILEFIELDS'), '');
			}

			// delete all fields
			foreach ($cid as $id) {
				$row =& JTable::getInstance('JoocmProfileField'); $row->load($id);
				
				// must be called before storing actual record!
				JoocmHelper::dropField($row);
			}
				
			$query = "DELETE FROM #__joocm_profiles_fields"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		} else {
			$msg = JText::sprintf('COM_JOOCM_MSGNOSELECTION', JText::_('COM_JOOCM_PROFILEFIELD'), JText::_('COM_JOOCM_DELETE')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefield_view', $msg, $msgType);
	}
	
	/**
	 * moves the order of profile field up or down
	 */
	function orderProfileField() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$direction	= JRequest::getCmd('task', 'joocm_profilefield_orderup') == 'joocm_profilefield_orderup' ? -1 : 1;

		if (isset($cid[0])) {
			$row =& JTable::getInstance('JoocmProfileField');
			$row->load((int) $cid[0]);
			$row->move($direction, 'id_profile_field_set = ' . (int) $row->id_profile_field_set);
		}
		
		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYREORDERED', JText::_('COM_JOOCM_PROFILEFIELD'), $row->name);
		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefield_view', $msg);
	}
	
	/**
	 * save the profile field order 
	 */
	function saveProfileFieldOrder() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$order		= JRequest::getVar('order', array (0), 'post', 'array');
		$total		= count($cid);
		$conditions	= array();
		
		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));
		
		// instantiate a profile field table object
		$row = & JTable::getInstance('JoocmProfileField');

		// update the ordering for items in the cid array
		for ($i = 0; $i < $total; $i ++) {
			$row->load((int) $cid[$i]);
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError(500, $db->getErrorMsg()); return;
				}
				
				// remember to update order this group
				$condition = "id_profile_field_set = $row->id_profile_field_set";
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

		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSAVEDORDER', JText::_('COM_JOOCM_PROFILEFIELD'));
		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefield_view', $msg);
	}
	
	/**
	 * changes the publish state of a profile field
	 */
	function changeProfileFieldPublishState() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$state		= JRequest::getCmd('task', 'joocm_profilefield_publish') == 'joocm_profilefield_publish' ? 1 : 0;
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			$msgText = ($state == 1) ? 'COM_JOOCM_MSGSUCCESSFULLYPUBLISHED' : 'COM_JOOCM_MSGSUCCESSFULLYUNPUBLISHED';

			// are there one or more rows to publish/unpublish?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmProfileField'); $row->load($cid[0]);
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_PROFILEFIELD'), $row->name);
			} else {
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_PROFILEFIELDS'), '');
			}

			$query = "UPDATE #__joocm_profiles_fields"
					. "\n SET published = $state"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);

			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefield_view', $msg, $msgType);
	}		
}
?>