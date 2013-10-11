<?php
/**
 * @version $Id: forum.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'forum.php');

/**
 * Joo!BB Forum Controller
 *
 * @package Joo!BB
 */
class ControllerForum extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joobb_forum_view', 'showForums');
		$this->registerTask('joobb_forum_new', 'editForum');
		$this->registerTask('joobb_forum_edit', 'editForum');
		$this->registerTask('joobb_forum_save', 'saveForum');
		$this->registerTask('joobb_forum_apply', 'saveForum');
		$this->registerTask('joobb_forum_delete', 'deleteForum');
		$this->registerTask('joobb_forum_cancel', 'cancelEditForum');
		$this->registerTask('joobb_forum_orderup', 'orderForum');
		$this->registerTask('joobb_forum_orderdown', 'orderForum');
		$this->registerTask('joobb_forum_saveorder', 'saveForumOrder');
		$this->registerTask('joobb_forum_publish', 'changeForumPublishState');
		$this->registerTask('joobb_forum_unpublish', 'changeForumPublishState');
	}
	
	/**
	 * compiles a list of forums
	 */
	function showForums() {

		// initialize variables
		$app		=& JFactory::getApplication();
		$db			=& JFactory::getDBO();

		$context			= 'com_joobb.joobb_forum_view';
		$filter_order		= $app->getUserStateFromRequest("$context.filter_order", 'filter_order', 'c.ordering, f.ordering');
		$filter_order_Dir	= $app->getUserStateFromRequest("$context.filter_order_Dir", 'filter_order_Dir', '');
		$limit				= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart			= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');
		
		$orderby = "\n ORDER BY $filter_order $filter_order_Dir";
		
		// get the total number of records
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joobb_forums AS f"
				. "\n INNER JOIN #__joobb_categories AS c ON f.id_cat = c.id"
				. $orderby
				;
		$db->setQuery($query);
		$total = $db->loadResult();
	
		// create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);		
		
		$query = "SELECT f.*, c.name AS category"
				. "\n FROM #__joobb_forums AS f"
				. "\n INNER JOIN #__joobb_categories AS c ON f.id_cat = c.id"
				. $orderby
				;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();
		
		// parameter list
		$lists = array();
		
		// table ordering
		$lists['filter_order'] = $filter_order;
		$lists['filter_order_Dir']	= $filter_order_Dir;
		
		ViewForum::showForums($rows, $pagination, $lists);	
	}
	
	/**
	 * cancels edit forum operation
	 */
	function cancelEditForum() {

		// check in forum so other can edit it
		$row =& JTable::getInstance('JoobbForum');
		$row->bind(JRequest::get('post'));
		$row->checkin();

		$this->setRedirect('index.php?option=com_joobb&task=joobb_forum_view');
	}
	
	/**
	 * edit the forum
	 */
	function editForum() {
		
		// initialize variables
		$db 	=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$cid 	= JRequest::getVar('cid', array(0));

		if (!is_array($cid)) {
			$cid = array(0);
		}

		$row =& JTable::getInstance('JoobbForum');
		$row->load($cid[0]);

		// is someone else editing this forum?
		if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
			$editingUser =& JFactory::getUser($row->checked_out);
			$this->setRedirect('index.php?option=com_joobb&task=joobb_forum_view', JText::sprintf('COM_JOOBB_MSGBEINGEDITTED', JText::_('COM_JOOBB_FORUM'), $row->name, $editingUser->name), 'notice'); return;
		}
		
		// check out forum so nobody else can edit it
		$row->checkout($user->get('id'));
		
		// initialize fields of a new forum record
		if ($cid[0] < 1) {
			$row->status = 1;	// enabled
			$row->locked = 0;	// not locked
			
			$row->auth_view = 0;		// all
			$row->auth_read = 0;		// all
			$row->auth_post = 1;		// registered
			$row->auth_post_all = 4;	// administrators
			$row->auth_reply = 1;		// registered
			$row->auth_reply_all = 4;	// registered
			$row->auth_edit = 1;		// registered
			$row->auth_edit_all = 1;	// administrators
			$row->auth_delete = 1;		// registered
			$row->auth_delete_all = 4;	// administrators
			$row->auth_move = 3;		// moderators
			$row->auth_reportpost = 1;	// registered
			$row->auth_sticky = 3;		// moderators
			$row->auth_lock = 3;		// moderators
			$row->auth_lock_all = 4;	// administrators
			$row->auth_announce = 3;	// moderators
			$row->auth_attachments = 1;	// registered
		}

		// parameter list
		$lists = array();
		
		// list categories		
		$query = "SELECT c.*"
				. "\n FROM #__joobb_categories AS c"
				. "\n ORDER BY c.ordering"
				;
		$db->setQuery( $query );
		$lists['categories'] = JHTML::_('select.genericlist',  $db->loadObjectList(), 'id_cat', 'class="inputbox" size="1"', 'id', 'name', intval($row->id_cat));
				
		// build the html radio buttons for status
		$lists['status'] = JHTML::_('select.booleanlist', 'status', '', $row->status);
		// build the html radio buttons for locked
		$lists['locked'] = JHTML::_('select.booleanlist', 'locked', '', $row->locked);
		
		// get authentification option list
		$joobbAuth =& JoobbAuth::getInstance();
		$authOptionList = $joobbAuth->getAuthOptionList();

		// get last post
		$lastPost =& JTable::getInstance('JoobbPost');
		$lastPost->load($row->id_last_post);
		$query = "SELECT MAX(id)"
				. "\n FROM #__menu AS m"
				. "\n WHERE m.link LIKE 'index.php?option=com_joobb&view=board%'"
				;
		$db->setQuery($query);
		$Itemid = $db->loadResult();
		$itemLink = JRoute::_(JURI::root().'index.php?option=com_joobb&view=topic&topic='.$lastPost->id_topic.'&Itemid='.$Itemid.'#p'.$lastPost->id);
		$row->last_post_href ='<a href="'.$itemLink.'" target="_blank">'.$lastPost->subject.'</a>';
		
		$lists['auth_view'] = JHTML::_('select.genericlist', $authOptionList, 'auth_view', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_view));
		$lists['auth_read'] = JHTML::_('select.genericlist', $authOptionList, 'auth_read', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_read));
		$lists['auth_post'] = JHTML::_('select.genericlist', $authOptionList, 'auth_post', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_post));
		$lists['auth_post_all'] = JHTML::_('select.genericlist', $authOptionList, 'auth_post_all', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_post_all));
		$lists['auth_reply'] = JHTML::_('select.genericlist', $authOptionList, 'auth_reply', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_reply));
		$lists['auth_reply_all'] = JHTML::_('select.genericlist', $authOptionList, 'auth_reply_all', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_reply_all));
		$lists['auth_edit'] = JHTML::_('select.genericlist', $authOptionList, 'auth_edit', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_edit));
		$lists['auth_edit_all'] = JHTML::_('select.genericlist', $authOptionList, 'auth_edit_all', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_edit_all));
		$lists['auth_delete'] = JHTML::_('select.genericlist', $authOptionList, 'auth_delete', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_delete));
		$lists['auth_delete_all'] = JHTML::_('select.genericlist', $authOptionList, 'auth_delete_all', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_delete_all));
		$lists['auth_move'] = JHTML::_('select.genericlist', $authOptionList, 'auth_move', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_move));
		$lists['auth_reportpost'] = JHTML::_('select.genericlist', $authOptionList, 'auth_reportpost', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_reportpost));
		$lists['auth_sticky'] = JHTML::_('select.genericlist', $authOptionList, 'auth_sticky', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_sticky));
		$lists['auth_lock'] = JHTML::_('select.genericlist', $authOptionList, 'auth_lock', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_lock));
		$lists['auth_lock_all'] = JHTML::_('select.genericlist', $authOptionList, 'auth_lock_all', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_lock_all));
		$lists['auth_announce'] = JHTML::_('select.genericlist', $authOptionList, 'auth_announce', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_announce));
		$lists['auth_attachments'] = JHTML::_('select.genericlist', $authOptionList, 'auth_attachments', 'class="inputbox" size="1"', 'value', 'text', intval($row->auth_attachments));

		ViewForum::editForum($row, $lists);	
	}
	
	/**
	 * save the forum
	 */	
	function saveForum() {

		// initialize variables
		$db 	=& JFactory::getDBO();
		$post	= JRequest::get('post');
		$row 	=& JTable::getInstance('JoobbForum');

		if (!$row->bind($post)) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if (!$row->check()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		
		// we need to request the text separetly, otherwise html tags will be not saved
		$description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		// clean text for xhtml transitional compliance
		$row->description = str_replace('<br>', '<br />', $description);
		
		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$row->checkin();
		$row->reorder('id_cat = '.$row->id_cat);
		
		switch (JRequest::getCmd('task')) {
			case 'joobb_forum_apply':
				$link = 'index.php?option=com_joobb&task=joobb_forum_edit&cid[]='. $row->id .'&hidemainmenu=1';
				break;
			case 'joobb_forum_save':
			default:
				$link = 'index.php?option=com_joobb&task=joobb_forum_view';
				break;
		}

		$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOBB_FORUM'), $row->name);
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * delete the forum
	 */	
	function deleteForum() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);

		if (count($cid)) {
			
			// how many forums are there to delete?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoobbForum'); $row->load($cid[0]);
				$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOBB_FORUM'), $row->name);
			} else {
				$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOBB_FORUMS'), '');
			}
			
			// delete all posts
			$query = "SELECT p.id"
					. "\n FROM #__joobb_posts AS p"
					. "\n INNER JOIN #__joobb_topics AS t ON t.id = p.id_topic"
					. "\n INNER JOIN #__joobb_forums AS f ON f.id = t.id_forum"
					. "\n INNER JOIN #__joobb_categories AS c ON c.id = f.id_cat"
					. "\n WHERE c.id = " . implode(' OR c.id = ', $cid)
					;
			$db->setQuery($query);
			$rows = $db->loadResultArray();
			
			if (count($rows)) {
				$query = "DELETE FROM #__joobb_posts"
						. "\n WHERE id = " . implode(' OR id = ', $rows)
						;
				$db->setQuery($query);
				
				if (!$db->query()) {
					$msg = $db->getErrorMsg(); $msgType = 'error';
				}	
			}

			// delete all topics
			$query = "SELECT t.id"
					. "\n FROM #__joobb_topics` AS t"
					. "\n INNER JOIN #__joobb_forums AS f ON f.id = t.id_forum"
					. "\n INNER JOIN #__joobb_categories AS c ON c.id = f.id_cat"
					. "\n WHERE c.id = " . implode(' OR c.id = ', $cid)
					;
			$db->setQuery($query);
			$rows = $db->loadResultArray();

			if (count($rows)) {
				$query = "DELETE FROM #__joobb_topics"
						. "\n WHERE id = " . implode(' OR id = ', $rows)
						;
				$db->setQuery($query);
				
				if (!$db->query()) {
					$msg = $db->getErrorMsg(); $msgType = 'error';
				}
			}
			
			$query = "DELETE FROM #__joobb_forums"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		} else {
			$msg = JText::sprintf('COM_JOOBB_MSGNOSELECTION', JText::_('COM_JOOBB_FORUM'), JText::_('COM_JOOBB_DELETE')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joobb&task=joobb_forum_view', $msg, $msgType);
	}
	
	/**
	 * moves the order of forum up or down
	 */
	function orderForum() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$direction	= JRequest::getCmd('task', 'joobb_forum_orderup') == 'joobb_forum_orderup' ? -1 : 1;

		if (isset($cid[0])) {
			$row =& JTable::getInstance('JoobbForum');
			$row->load((int) $cid[0]);
			$row->move($direction, 'id_cat = ' . (int) $row->id_cat);
		}
		
		$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYREORDERED', JText::_('COM_JOOBB_FORUM'), $row->name);
		$this->setRedirect('index.php?option=com_joobb&task=joobb_forum_view', $msg);
	}
	
	/**
	 * save the forum order 
	 */
	function saveForumOrder() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$order		= JRequest::getVar('order', array (0), 'post', 'array');
		$total		= count($cid);
		$conditions	= array ();

		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));

		// instantiate a forum table object
		$row = &JTable::getInstance('JoobbForum');

		// update the ordering for items in the cid array
		for ($i = 0; $i < $total; $i ++) {
			$row->load((int) $cid[$i]);
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError( 500, $db->getErrorMsg() ); return;
				}
				
				// remember to updateOrder this group
				$condition = "id_cat = $row->id_cat";
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

		$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYSAVEDORDER', JText::_('COM_JOOBB_FORUM'));
		$this->setRedirect('index.php?option=com_joobb&task=joobb_forum_view', $msg);
	}
	
	/**
	 * changes the publish state of a forum
	 */
	function changeForumPublishState() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$state		= JRequest::getCmd('task', 'joobb_forum_publish') == 'joobb_forum_publish' ? 1 : 0;
		$msgType	= '';

		JArrayHelper::toInteger($cid);

		if (count($cid)) {
			$msgText = ($state == 1) ? 'COM_JOOBB_MSGSUCCESSFULLYPUBLISHED' : 'COM_JOOBB_MSGSUCCESSFULLYUNPUBLISHED';
			
			// are there one or more rows to publish/unpublish?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoobbForum'); $row->load($cid[0]);
				$msg = JText::sprintf($msgText, JText::_('COM_JOOBB_FORUM'), $row->name);
			} else {
				$msg = JText::sprintf($msgText, JText::_('COM_JOOBB_FORUMS'), '');
			}

			$query = "UPDATE #__joobb_forums"
					. "\n SET status = $state"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
	
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		}
		
		$this->setRedirect('index.php?option=com_joobb&task=joobb_forum_view', $msg, $msgType);
	}
}
?>