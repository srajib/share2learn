<?php
/**
 * @version $Id: profilefieldlist.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'profilefieldlist.php');

/**
 * Joo!BB Profile Field List Controller
 *
 * @package Joo!BB
 */
class ControllerProfileFieldList extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joocm_profilefieldlist_view', 'showProfileFieldLists');
		$this->registerTask('joocm_profilefieldlist_new', 'editProfileFieldList');
		$this->registerTask('joocm_profilefieldlist_edit', 'editProfileFieldList');
		$this->registerTask('joocm_profilefieldlist_save', 'saveProfileFieldList');
		$this->registerTask('joocm_profilefieldlist_apply', 'saveProfileFieldList');
		$this->registerTask('joocm_profilefieldlist_delete', 'deleteProfileFieldList');
		$this->registerTask('joocm_profilefieldlist_cancel', 'cancelEditProfileFieldList');
		$this->registerTask('joocm_profilefieldlist_publish', 'changeProfileFieldListPublishState');
		$this->registerTask('joocm_profilefieldlist_unpublish', 'changeProfileFieldListPublishState');
	}
	
	/**
	 * compiles a list of profile field lists
	 */
	function showProfileFieldLists() {

		// initialize variables
		$app		=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		
		$context			= 'com_joocm.joocm_profilefieldlist_view';
		$filter_order		= $app->getUserStateFromRequest("$context.filter_order", 'filter_order', 'l.name');
		$filter_order_Dir	= $app->getUserStateFromRequest("$context.filter_order_Dir",	'filter_order_Dir',	'');
		$limit				= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart			= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');
		
		$orderby = "\n ORDER BY $filter_order $filter_order_Dir";
		
		// get the total number of records
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joocm_profiles_fields_lists AS l"
				. $orderby
				;		
		$db->setQuery($query);
		$total = $db->loadResult();
		
		// create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
												
		$query = "SELECT l.*"
				. "\n FROM #__joocm_profiles_fields_lists AS l"
				. $orderby
				;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();
		
		// parameter list
		$lists = array();
		
		// table ordering
		$lists['filter_order'] = $filter_order;
		$lists['filter_order_Dir']	= $filter_order_Dir;
		
		ViewProfileFieldList::showProfileFieldLists($rows, $pagination, $lists);	
	}
	
	/**
	 * cancels edit profile field list operation
	 */
	function cancelEditProfileFieldList() {

		// check in category so other can edit it
		$row =& JTable::getInstance('JoocmProfileFieldList');
		$row->bind(JRequest::get('post'));
		$row->checkin();

		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldlist_view');
	}
	
	/**
	 * edit the profile field list
	 */
	function editProfileFieldList() {

		// initialize variables
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$cid 	= JRequest::getVar('cid', array(0));
		
		if (!is_array($cid)) {
			$cid = array(0);
		}

		$row =& JTable::getInstance('JoocmProfileFieldList');
		$row->load($cid[0]);

		// is someone else editing this profile field list?
		if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
			$editingUser =& JFactory::getUser($row->checked_out);
			$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldlist_view', JText::sprintf('COM_JOOCM_MSGBEINGEDITTED', JText::_('COM_JOOCM_PROFILEFIELDLIST'), $row->name, $editingUser->name), 'notice'); return;
		}
		
		// check out profile field list so nobody else can edit it
		$row->checkout($user->get('id'));
		
		// parameter list
		$lists = array();
		
		// build the html radio buttons for published
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);
				
		ViewProfileFieldList::editProfileFieldList($row, $lists);			
	}
	
	/**
	 * save the profile field list
	 */	
	function saveProfileFieldList() {

		// initialize variables
		$db 	=& JFactory::getDBO();
		$post	= JRequest::get('post');
		$row 	=& JTable::getInstance('JoocmProfileFieldList');

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
			case 'joocm_profilefieldlist_apply':
				$link = 'index.php?option=com_joocm&task=joocm_profilefieldlist_edit&cid[]='. $row->id .'&hidemainmenu=1';
				break;
			case 'joocm_profilefieldlist_save':
			default:
				$link = 'index.php?option=com_joocm&task=joocm_profilefieldlist_view';
				break;
		}
		
		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOCM_PROFILEFIELDLIST'), $row->name);
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * delete the profile field list
	 */	
	function deleteProfileFieldList() {

		// initialize variables
		$db		=& JFactory::getDBO();
		$cid	= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);

		if (count($cid)) {
			
			// how many profile field list are there to delete?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmProfileFieldList'); $row->load($cid[0]);
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_PROFILEFIELDLIST'), $row->name);
			} else {
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_PROFILEFIELDLISTS'), '');
			}
	
			$query = "DELETE FROM #__joocm_profiles_fields_lists"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		} else {
			$msg = JText::sprintf('COM_JOOCM_MSGNOSELECTION', JText::_('COM_JOOCM_PROFILEFIELDLIST'), JText::_('COM_JOOCM_DELETE')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldlist_view', $msg, $msgType);
	}
	
	/**
	 * Changes the publish state of a profile field list
	 */
	function changeProfileFieldListPublishState() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$state		= JRequest::getCmd('task', 'joocm_profilefieldlist_publish') == 'joocm_profilefieldlist_publish' ? 1 : 0;
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);

		if (count($cid)) {
			$msgText = ($state == 1) ? 'COM_JOOCM_MSGSUCCESSFULLYPUBLISHED' : 'COM_JOOCM_MSGSUCCESSFULLYUNPUBLISHED';
			
			// are there one or more rows to publish/unpublish?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmProfileFieldList'); $row->load($cid[0]);
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_PROFILEFIELDLIST'), $row->name);
			} else {
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_PROFILEFIELDLISTS'), '');
			}
			
			$query = "UPDATE #__joocm_profiles_fields_lists"
					. "\n SET published = $state"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);	
	
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		}
		
		$this->setRedirect('index.php?option=com_joocm&task=joocm_profilefieldlist_view', $msg, $msgType);
	}		
}
?>