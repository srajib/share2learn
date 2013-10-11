<?php
/**
 * @version $Id: rank.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'rank.php');

/**
 * Joo!BB Rank Controller
 *
 * @package Joo!BB
 */
class ControllerRank extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joobb_rank_view', 'showRanks');
		$this->registerTask('joobb_rank_new', 'editRank');
		$this->registerTask('joobb_rank_edit', 'editRank');
		$this->registerTask('joobb_rank_save', 'saveRank');
		$this->registerTask('joobb_rank_apply', 'saveRank');
		$this->registerTask('joobb_rank_delete', 'deleteRank');
		$this->registerTask('joobb_rank_cancel', 'cancelEditRank');
		$this->registerTask('joobb_rank_publish', 'changeRankPublishState');
		$this->registerTask('joobb_rank_unpublish', 'changeRankPublishState');
	}
	
	/**
	 * compiles a list of rank
	 */
	function showRanks() {

		// initialize variables
		$app		=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		
		$context			= 'com_joobb.joobb_rank_view';
		$filter_order		= $app->getUserStateFromRequest("$context.filter_order", 'filter_order', 'r.min_posts');
		$filter_order_Dir	= $app->getUserStateFromRequest("$context.filter_order_Dir", 'filter_order_Dir', '');
		$limit				= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart			= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');
		
		$orderby = "\n ORDER BY $filter_order $filter_order_Dir";
		
		// get the total number of records
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joobb_ranks AS r"
				. $orderby
				;		
		$db->setQuery($query);
		$total = $db->loadResult();
		
		// create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
												
		$query = "SELECT r.*"
				. "\n FROM #__joobb_ranks AS r"
				. $orderby
				;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();
		
		// parameter list
		$lists = array();
		
		// table ordering
		$lists['filter_order'] = $filter_order;
		$lists['filter_order_Dir']	= $filter_order_Dir;
			
		ViewRank::showRanks($rows, $pagination, $lists);	
	}
	
	/**
	 * cancels edit rank operation
	 */
	function cancelEditRank() {

		// check in category so other can edit it
		$row =& JTable::getInstance('JoobbRank');
		$row->bind(JRequest::get('post'));
		$row->checkin();

		$this->setRedirect('index.php?option=com_joobb&task=joobb_rank_view');
	}
	
	/**
	 * edit the rank
	 */
	function editRank() {

		// initialize variables
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$cid 	= JRequest::getVar('cid', array(0));
		
		if (!is_array($cid)) {
			$cid = array(0);
		}
		
		$row =& JTable::getInstance('JoobbRank');
		$row->load($cid[0]);

		// is someone else editing this rank?
		if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
			$editingUser =& JFactory::getUser($row->checked_out);
			$this->setRedirect('index.php?option=com_joobb&task=joobb_rank_view', JText::sprintf('COM_JOOBB_MSGBEINGEDITTED', JText::_('COM_JOOBB_RANK'), $row->name, $editingUser->name)); return;
		}
		
		// parameter list
		$lists = array();

		// build the html radio buttons for state
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);		
				
		ViewRank::editRank($row, $lists);			
	}
	
	/**
	 * save the rank
	 */	
	function saveRank() {

		// initialize variables
		$db =& JFactory::getDBO();
		$post = JRequest::get('post');
		$row =& JTable::getInstance('JoobbRank');

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
			case 'joobb_rank_apply':
				$link = 'index.php?option=com_joobb&task=joobb_rank_edit&cid[]='. $row->id .'&hidemainmenu=1';
				break;
			case 'joobb_rank_save':
			default:
				$link = 'index.php?option=com_joobb&task=joobb_rank_view';
				break;
		}

		$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOBB_RANK'), $row->name);
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * delete the rank
	 */	
	function deleteRank() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			
			// how many categories are there to delete?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoobbRank'); $row->load($cid[0]);
				$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOBB_RANK'), $row->name);
			} else {
				$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOBB_RANKS'), '');
			}
		
			$query = "DELETE FROM #__joobb_ranks"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		} else {
			$msg = JText::sprintf('COM_JOOBB_MSGNOSELECTION', JText::_('COM_JOOBB_RANK'), JText::_('COM_JOOBB_DELETE')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joobb&task=joobb_rank_view', $msg, $msgType);
	}
	
	/**
	 * changes the publish state of a rank
	 */
	function changeRankPublishState() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$state		= JRequest::getCmd('task', 'joobb_rank_publish') == 'joobb_rank_publish' ? 1 : 0;
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			$msgText = ($state == 1) ? 'COM_JOOBB_MSGSUCCESSFULLYPUBLISHED' : 'COM_JOOBB_MSGSUCCESSFULLYUNPUBLISHED';
			
			// are there one or more rows to publish/unpublish?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoobbCategory'); $row->load($cid[0]);
				$msg = JText::sprintf($msgText, JText::_('COM_JOOBB_CATEGORY'), $row->name);
			} else {
				$msg = JText::sprintf($msgText, JText::_('COM_JOOBB_CATEGORIES'), '');
			}
			
			$query = "UPDATE #__joobb_ranks"
					. "\n SET published = $state"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
	
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		}

		$this->setRedirect('index.php?option=com_joobb&task=joobb_rank_view', $msg, $msgType);
	}
}
?>