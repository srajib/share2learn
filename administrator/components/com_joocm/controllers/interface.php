<?php
/**
 * @version $Id: interface.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'interface.php');

/**
 * Joo!CM Interface Controller
 *
 * @package Joo!CM
 */
class ControllerInterface extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joocm_interface_view', 'showInterfaces');
		$this->registerTask('joocm_interface_new', 'editInterface');
		$this->registerTask('joocm_interface_edit', 'editInterface');
		$this->registerTask('joocm_interface_save', 'saveInterface');
		$this->registerTask('joocm_interface_apply', 'saveInterface');
		$this->registerTask('joocm_interface_delete', 'deleteInterface');
		$this->registerTask('joocm_interface_cancel', 'cancelEditInterface');
		$this->registerTask('joocm_interface_orderup', 'orderInterface');
		$this->registerTask('joocm_interface_orderdown', 'orderInterface');
		$this->registerTask('joocm_interface_saveorder', 'saveInterfaceOrder');
		$this->registerTask('joocm_interface_publish', 'changeInterfacePublishState');
		$this->registerTask('joocm_interface_unpublish', 'changeInterfacePublishState');
	}
	
	/**
	 * compiles a list of interfaces
	 */
	function showInterfaces() {

		// initialize variables
		$app			=& JFactory::getApplication();
		$db				=& JFactory::getDBO();

		$context			= 'com_joocm.joocm_interface_view';
		$filter_order		= $app->getUserStateFromRequest("$context.filter_order", 'filter_order', 'i.client, i.ordering');
		$filter_order_Dir	= $app->getUserStateFromRequest("$context.filter_order_Dir",	'filter_order_Dir',	'');
		$filter_client		= $app->getUserStateFromRequest("$context.filter_client", 'filter_client', -1);
		$filter_type		= $app->getUserStateFromRequest("$context.filter_type", 'filter_type', -1);
		$limit				= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart			= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');
		
		$where = array();
		$orderby = "\n ORDER BY $filter_order $filter_order_Dir";
		
		if ($filter_client >= 0 && $filter_client <= 1) {
			$where[] = "i.client = $filter_client";
		}
				
		if ($filter_type >= 0 && $filter_type <= 1) {
			$where[] = "i.system = $filter_type";
		}
		
		$where = (count($where) ? "\n WHERE " . implode(' AND ', $where) : '');
		
		// get the total number of records
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joocm_interfaces AS i"
				. $where
				. $orderby
				;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		// create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
												
		$query = "SELECT i.*"
				. "\n FROM #__joocm_interfaces AS i"
				. $where
				. $orderby
				;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();
		
		// parameter list
		$lists = array();
		
		// generate client list
		$client[] = JHTML::_('select.option', '-1', '- '.JText::_('COM_JOOCM_SELECTINTERFACECLIENT').' -', 'value', 'text');
		$client[] = JHTML::_('select.option', '0', JText::_('COM_JOOCM_FRONTEND'), 'value', 'text');
		$client[] = JHTML::_('select.option', '1', JText::_('COM_JOOCM_BACKEND'), 'value', 'text');
		$lists['client'] = JHTML::_('select.genericlist',  $client, 'filter_client', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $filter_client);
		
		// generate system list
		$types[] = JHTML::_('select.option', '-1', '- '.JText::_('COM_JOOCM_SELECTINTERFACETYPE').' -', 'value', 'text');
		$types[] = JHTML::_('select.option', '0', JText::_('COM_JOOCM_NOSYSTEM'), 'value', 'text');
		$types[] = JHTML::_('select.option', '1', JText::_('COM_JOOCM_SYSTEM'), 'value', 'text');
		$lists['type'] = JHTML::_('select.genericlist',  $types, 'filter_type', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $filter_type);		
		
		// table ordering
		$lists['filter_order'] = $filter_order;
		$lists['filter_order_Dir']	= $filter_order_Dir;
		
		ViewInterface::showInterfaces($rows, $pagination, $lists);	
	}
	
	/**
	 * cancels edit interface operation
	 */
	function cancelEditInterface() {

		// check in interface so other can edit it
		$row =& JTable::getInstance('JoocmInterface');
		$row->bind(JRequest::get('post'));
		$row->checkin();

		$this->setRedirect('index.php?option=com_joocm&task=joocm_interface_view');
	}
	
	/**
	 * edit interface
	 */
	function editInterface() {

		// initialize variables
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$cid 	= JRequest::getVar('cid', array(0));
		
		if (!is_array($cid)) {
			$cid = array(0);
		}
	
		$row =& JTable::getInstance('JoocmInterface');
		$row->load($cid[0]);

		// is someone else editing this time format?
		if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
			$editingUser =& JFactory::getUser($row->checked_out);
			$this->setRedirect('index.php?option=com_joocm&task=joocm_interface_view', JText::sprintf('COM_JOOCM_MSGBEINGEDITTED', JText::_('COM_JOOCM_INTERFACE'), $row->name, $editingUser->name)); return;
		}
		
		// check out interface so nobody else can edit it
		$row->checkout($user->get('id'));
		
		// parameter list
		$lists = array();
		
		$clients = array();
		$clients[] = JHTML::_('select.option', 0, JText::_('COM_JOOCM_FRONTEND'));
		$clients[] = JHTML::_('select.option', 1, JText::_('COM_JOOCM_BACKEND'));

		$lists['client'] = JHTML::_('select.radiolist',  $clients, 'client', '', 'value', 'text', $row->client);
		
		$restrictions = array();
		$restrictions[] = JHTML::_('select.option', 0, JText::_('COM_JOOCM_BOTH'));
		$restrictions[] = JHTML::_('select.option', 1, JText::_('COM_JOOCM_OFFLINEUSERS'));
		$restrictions[] = JHTML::_('select.option', 2, JText::_('COM_JOOCM_ONLINEUSERS'));
		
		$lists['show_restriction'] = JHTML::_('select.radiolist',  $restrictions, 'show_restriction', '', 'value', 'text', $row->show_restriction);
		
		// build the html radio buttons for published
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);		
				
		ViewInterface::editInterface($row, $lists);			
	}
	
	/**
	 * save interface
	 */	
	function saveInterface() {

		// initialize variables
		$db =& JFactory::getDBO();
		$post	= JRequest::get('post');
		$row =& JTable::getInstance('JoocmInterface');

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

		switch (JRequest::getCmd('task')) {
			case 'joocm_interface_apply':
				$link = 'index.php?option=com_joocm&task=joocm_interface_edit&cid[]='. $row->id .'&hidemainmenu=1';
				break;
			case 'joocm_interface_save':
			default:
				$link = 'index.php?option=com_joocm&task=joocm_interface_view';
				break;
		}
		
		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOCM_INTERFACE'), $row->name);
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * delete interface
	 */	
	function deleteInterface() {

		// initialize variables
		$app		=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			
			$deletedItems = 0;
			foreach ($cid as $id) {
				$row =& JTable::getInstance('JoocmInterface'); $row->load($id);
				
				if ($row->system) {
					$app->enqueueMessage(JText::sprintf('COM_JOOCM_MSGSYSTEMINTERFACENOTDELETED', $row->name), 'error');
				} else {
					$row->delete();
					$deletedItems++;
				}
			}
			
			if ($deletedItems == 1) {
				$app->enqueueMessage(JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_INTERFACE')));
			} else if ($deletedItems > 1) {
				$app->enqueueMessage(JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_INTERFACES')));
			}
		} else {
			$app->enqueueMessage(JText::sprintf('COM_JOOCM_MSGNOSELECTION', JText::_('COM_JOOCM_INTERFACE'), JText::_('COM_JOOCM_DELETE')), 'notice');
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_interface_view');
	}
	
	/**
	 * moves the order of forum up or down
	 */
	function orderInterface() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$direction	= JRequest::getCmd('task', 'joocm_interface_orderup') == 'joocm_interface_orderup' ? -1 : 1;

		if (isset($cid[0])) {
			$row =& JTable::getInstance('JoocmInterface');
			$row->load((int) $cid[0]);
			$row->move($direction, 'client = ' . (int) $row->client);
		}
		
		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYREORDERED', JText::_('COM_JOOCM_INTERFACE'), JText::_($row->name));
		$this->setRedirect('index.php?option=com_joocm&task=joocm_interface_view', $msg);
	}
		
	/**
	 * save the forum order 
	 */
	function saveInterfaceOrder() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$order		= JRequest::getVar('order', array (0), 'post', 'array');
		$total		= count($cid);
		$conditions	= array ();

		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));

		// instantiate a forum table object
		$row = & JTable::getInstance('JoocmInterface');

		// update the ordering for items in the cid array
		for ($i = 0; $i < $total; $i ++) {
			$row->load( (int) $cid[$i] );
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError(500, $db->getErrorMsg());
					return false;
				}
				
				// remember to updateOrder this group
				$condition = "client = $row->client";
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

		// execute update order for each group
		foreach ($conditions as $cond) {
			$row->load($cond[0]);
			$row->reorder($cond[1]);
		}

		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSAVEDORDER', JText::_('COM_JOOCM_INTERFACE'));
		$this->setRedirect('index.php?option=com_joocm&task=joocm_interface_view', $msg);
	}
		
	/**
	 * changes the publish state of a time format
	 */
	function changeInterfacePublishState($state = 0) {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$state		= JRequest::getCmd('task', 'joocm_interface_publish') == 'joocm_interface_publish' ? 1 : 0;
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			$msgText = ($state == 1) ? 'COM_JOOCM_MSGSUCCESSFULLYPUBLISHED' : 'COM_JOOCM_MSGSUCCESSFULLYUNPUBLISHED';
			
			// are there one or more rows to publish/unpublish?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmInterface'); $row->load($cid[0]);
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_INTERFACE'), $row->name);
			} else {
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_INTERFACES'), '');
			}
	
			$query = "UPDATE #__joocm_interfaces"
					. "\n SET published = $state"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_interface_view', $msg, $msgType);
	}
}
?>