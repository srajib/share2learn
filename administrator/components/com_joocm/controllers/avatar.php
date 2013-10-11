<?php
/**
 * @version $Id: avatar.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'avatar.php');

/**
 * Joo!CM Avatar Controller
 *
 * @package Joo!CM
 */
class ControllerAvatar extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joocm_avatar_view', 'showAvatars');
		$this->registerTask('joocm_avatar_new', 'editAvatar');
		$this->registerTask('joocm_avatar_edit', 'editAvatar');
		$this->registerTask('joocm_avatar_save', 'saveAvatar');
		$this->registerTask('joocm_avatar_apply', 'saveAvatar');
		$this->registerTask('joocm_avatar_delete', 'deleteAvatar');
		$this->registerTask('joocm_avatar_cancel', 'cancelEditAvatar');
		$this->registerTask('joocm_avatar_publish', 'changeAvatarPublishState');
		$this->registerTask('joocm_avatar_unpublish', 'changeAvatarPublishState');
	}

	/**
	 * compiles a list of avatars
	 */
	function showAvatars() {

		// initialize variables
		$app			=& JFactory::getApplication();
		$db				=& JFactory::getDBO();
		$joocmConfig	=& JoocmConfig::getInstance();
		
		$context			= 'com_joocm.joocm_avatar_view';
		$filter_order		= $app->getUserStateFromRequest("$context.filter_order", 'filter_order', 'a.avatar_file');
		$filter_order_Dir	= $app->getUserStateFromRequest("$context.filter_order_Dir",	'filter_order_Dir',	'');
		$limit				= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart			= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');
		
		$orderby = "\n ORDER BY $filter_order $filter_order_Dir";
		
		// get the total number of records
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joocm_avatars AS a"
				. $orderby
				;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		// create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
		
		$query = "SELECT a.*, u.name"
				. "\n FROM #__joocm_avatars AS a"
				. "\n LEFT JOIN #__users AS u ON u.id = a.id_user"
				. $orderby
				;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();
		
		// parameter list
		$lists = array();

		$lists['avatar_path'] = $joocmConfig->getAvatarSettings('avatar_path');
		
		// table ordering
		$lists['filter_order'] = $filter_order;
		$lists['filter_order_Dir']	= $filter_order_Dir;
		
		ViewAvatar::showAvatars($rows, $pagination, $lists);	
	}
	
	/**
	 * cancels edit terms operation
	 */
	function cancelEditAvatar() {

		// check in category so other can edit it
		$row =& JTable::getInstance('JoocmAvatar');
		$row->bind(JRequest::get('post'));
		$row->checkin();

		$this->setRedirect('index.php?option=com_joocm&task=joocm_avatar_view');
	}
	
	/**
	 * edit avatar
	 */
	function editAvatar() {

		// initialize variables
		$db				=& JFactory::getDBO();
		$user			=& JFactory::getUser();
		$joocmConfig	=& JoocmConfig::getInstance();
		$cid			= JRequest::getVar('cid', array(0));
		
		if (!is_array($cid)) {
			$cid = array(0);
		}
	
		$row =& JTable::getInstance('JoocmAvatar');
		$row->load($cid[0]);

		// is someone else editing this avatar?
		if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
			$editingUser =& JFactory::getUser($row->checked_out);
			$this->setRedirect('index.php?option=com_joocm&task=joocm_avatar_view', JText::sprintf('COM_JOOCM_MSGBEINGEDITTED', JText::_('COM_JOOCM_AVATAR'), $row->terms, $editingUser->name)); return;
		}
		
		// check out category so nobody else can edit it
		$row->checkout($user->get('id'));
		
		// parameter list
		$lists = array();
		
		$lists['avatar_path'] = $joocmConfig->getAvatarSettings('avatar_path');
		
		// build the html radio buttons for state
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);		
				
		ViewAvatar::editAvatar($row, $lists);			
	}
	
	/**
	 * save avatar
	 */	
	function saveAvatar() {

		// initialize variables
		$db		=& JFactory::getDBO();
		$post	= JRequest::get('post');
		$row	=& JTable::getInstance('JoocmAvatar');

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
			case 'joocm_avatar_apply':
				$link = 'index.php?option=com_joocm&task=joocm_avatar_edit&cid[]='. $row->id .'&hidemainmenu=1'. 'text: '.$post['text'];
				break;
			case 'joocm_avatar_save':
			default:
				$link = 'index.php?option=com_joocm&task=joocm_avatar_view';
				break;
		}

		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOCM_AVATAR'), $row->terms);
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * delete avatar
	 */	
	function deleteAvatar() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			
			// how many avatars are there to delete?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmAvatar'); $row->load($cid[0]);
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_AVATARS'), $row->terms);
			} else {
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_AVATAR'), '');
			}
		
			$query = "DELETE FROM #__joocm_avatars"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		} else {
			$msg = JText::sprintf('COM_JOOCM_MSGNOSELECTION', JText::_('COM_JOOCM_AVATAR'), JText::_('COM_JOOCM_DELETE')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_avatar_view', $msg, $msgType);
	}
	
	/**
	 * changes the publish state of an avatar
	 */
	function changeAvatarPublishState() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$state		= JRequest::getCmd('task', 'joocm_avatar_publish') == 'joocm_avatar_publish' ? 1 : 0;
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			$msgText = ($state == 1) ? 'COM_JOOCM_MSGSUCCESSFULLYPUBLISHED' : 'COM_JOOCM_MSGSUCCESSFULLYUNPUBLISHED';
			
			// are there one or more rows to publish/unpublish?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmAvatar'); $row->load($cid[0]);
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_AVATARS'), $row->terms);
			} else {
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_AVATAR'), '');
			}
			
			$query = "UPDATE #__joocm_avatars"
					. "\n SET published = $state"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_avatar_view', $msg, $msgType);
	}
}
?>