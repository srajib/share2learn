<?php
/**
 * @version $Id: category.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'category.php');

/**
 * Joo!BB Category Controller
 *
 * @package Joo!BB
 */
class ControllerCategory extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joobb_category_view', 'showCategories');
		$this->registerTask('joobb_category_new', 'editCategory');
		$this->registerTask('joobb_category_edit', 'editCategory');
		$this->registerTask('joobb_category_save', 'saveCategory');
		$this->registerTask('joobb_category_apply', 'saveCategory');
		$this->registerTask('joobb_category_delete', 'deleteCategory');
		$this->registerTask('joobb_category_cancel', 'cancelEditCategory');
		$this->registerTask('joobb_category_orderup', 'orderCategory');
		$this->registerTask('joobb_category_orderdown', 'orderCategory');
		$this->registerTask('joobb_category_saveorder', 'saveCategoryOrder');
		$this->registerTask('joobb_category_publish', 'changeCategoryPublishState');
		$this->registerTask('joobb_category_unpublish', 'changeCategoryPublishState');
	}
	
	/**
	 * compiles a list of categories
	 */
	function showCategories() {

		// initialize variables
		$app		=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		
		$context			= 'com_joobb.joobb_category_view';
		$filter_order		= $app->getUserStateFromRequest("$context.filter_order", 'filter_order', 'c.ordering');
		$filter_order_Dir	= $app->getUserStateFromRequest("$context.filter_order_Dir", 'filter_order_Dir', '');
		$limit				= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart			= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');
		
		$orderby = "\n ORDER BY $filter_order $filter_order_Dir";
		
		// get the total number of records
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joobb_categories AS c"
				. $orderby
				;		
		$db->setQuery($query);
		$total = $db->loadResult();
		
		// create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
												
		$query = "SELECT c.*"
				. "\n FROM #__joobb_categories AS c"
				. $orderby
				;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();
		
		// parameter list
		$lists = array();
		
		// table ordering
		$lists['filter_order'] = $filter_order;
		$lists['filter_order_Dir']	= $filter_order_Dir;
		
		ViewCategory::showCategories($rows, $pagination, $lists);	
	}
	
	/**
	 * cancels edit category operation
	 */
	function cancelEditCategory() {

		// check in category so other can edit it
		$row =& JTable::getInstance('JoobbCategory');
		$row->bind(JRequest::get('post'));
		$row->checkin();

		$this->setRedirect('index.php?option=com_joobb&task=joobb_category_view');
	}
	
	/**
	 * edit the category
	 */
	function editCategory() {

		// initialize variables
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$cid 	= JRequest::getVar('cid', array(0));
		
		if (!is_array($cid)) {
			$cid = array(0);
		}

		$row =& JTable::getInstance('JoobbCategory');
		$row->load($cid[0]);

		// is someone else editing this category?
		if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
			$editingUser =& JFactory::getUser($row->checked_out);
			$this->setRedirect('index.php?option=com_joobb&task=joobb_category_view', JText::sprintf('COM_JOOBB_MSGBEINGEDITTED', JText::_('COM_JOOBB_CATEGORY'), $row->name, $editingUser->name), 'notice'); return;
		}
		
		// check out category so nobody else can edit it
		$row->checkout($user->get('id'));
		
		// parameter list
		$lists = array();

		// build the html radio buttons for published
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);		
				
		ViewCategory::editCategory($row, $lists);			
	}
	
	/**
	 * save the category
	 */	
	function saveCategory() {

		// initialize variables
		$db		=& JFactory::getDBO();
		$post	= JRequest::get('post');
		$row	=& JTable::getInstance('JoobbCategory');

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
			case 'joobb_category_apply':
				$link = 'index.php?option=com_joobb&task=joobb_category_edit&cid[]='. $row->id .'&hidemainmenu=1';
				break;
			case 'joobb_category_save':
			default:
				$link = 'index.php?option=com_joobb&task=joobb_category_view';
				break;
		}
		
		$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOBB_CATEGORY'), $row->name);
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * delete the category
	 */
	function deleteCategory() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {

			// how many categories are there to delete?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoobbCategory'); $row->load($cid[0]);
				$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOBB_CATEGORY'), $row->name);
			} else {
				$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOBB_CATEGORIES'), '');
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
			
			// delete all forums
			$query = "SELECT f.id"
					. "\n FROM #__joobb_forums AS f"
					. "\n INNER JOIN #__joobb_categories AS c ON c.id = f.id_cat"
					. "\n WHERE c.id = " . implode(' OR c.id = ', $cid)
					;
			$db->setQuery($query);
			$rows = $db->loadResultArray();

			if (count($rows)) {
				$query = "DELETE FROM #__joobb_forums"
						. "\n WHERE id = " . implode(' OR id = ', $rows)
						;
				$db->setQuery($query);
				
				if (!$db->query()) {
					$msg = $db->getErrorMsg(); $msgType = 'error';
				}
			}

			// delete all categories at least
			$query = "DELETE FROM #__joobb_categories"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		} else {
			$msg = JText::sprintf('COM_JOOBB_MSGNOSELECTION', JText::_('COM_JOOBB_CATEGORY'), JText::_('COM_JOOBB_DELETE')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joobb&task=joobb_category_view', $msg, $msgType);
	}
	
	/**
	 * moves the order of category up or down
	 */
	function orderCategory() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$direction	= JRequest::getCmd('task', 'joobb_category_orderup') == 'joobb_category_orderup' ? -1 : 1;

		if (isset($cid[0])) {
			$row =& JTable::getInstance('JoobbCategory');
			$row->load((int) $cid[0]);
			$row->move($direction);
		}
		
		$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYREORDERED', JText::_('COM_JOOBB_CATEGORY'), $row->name);
		$this->setRedirect('index.php?option=com_joobb&task=joobb_category_view', $msg);
	}
	
	/**
	 * save the category order 
	 */
	function saveCategoryOrder() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$order		= JRequest::getVar('order', array (0), 'post', 'array');
		$total		= count($cid);
		$conditions	= array();

		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));

		// instantiate a category table object
		$row = & JTable::getInstance('JoobbCategory');

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

		$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYSAVEDORDER', JText::_('COM_JOOBB_CATEGORY'));
		$this->setRedirect('index.php?option=com_joobb&task=joobb_category_view', $msg);
	}
	
	/**
	 * Changes the publish state of a category
	 */
	function changeCategoryPublishState() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$state		= JRequest::getCmd('task', 'joobb_category_publish') == 'joobb_category_publish' ? 1 : 0;
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

			$query = "UPDATE #__joobb_categories"
					. "\n SET published = $state"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
	
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		}

		$this->setRedirect('index.php?option=com_joobb&task=joobb_category_view', $msg, $msgType);
	}		
}
?>