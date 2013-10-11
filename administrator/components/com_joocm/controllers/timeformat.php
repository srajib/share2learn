<?php
/**
 * @version $Id: timeformat.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'timeformat.php');

/**
 * Joo!CM Time Format Controller
 *
 * @package Joo!CM
 */
class ControllerTimeFormat extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joocm_timeformat_view', 'showTimeFormats');
		$this->registerTask('joocm_timeformat_new', 'editTimeFormat');
		$this->registerTask('joocm_timeformat_edit', 'editTimeFormat');
		$this->registerTask('joocm_timeformat_save', 'saveTimeFormat');
		$this->registerTask('joocm_timeformat_apply', 'saveTimeFormat');
		$this->registerTask('joocm_timeformat_delete', 'deleteTimeFormat');
		$this->registerTask('joocm_timeformat_cancel', 'cancelEditTimeFormat');
		$this->registerTask('joocm_timeformat_default', 'defaultTimeFormat');
		$this->registerTask('joocm_timeformat_publish', 'changeTimeFormatPublishState');
		$this->registerTask('joocm_timeformat_unpublish', 'changeTimeFormatPublishState');
	}
	
	/**
	 * compiles a list of time formats
	 */
	function showTimeFormats() {

		// initialize variables
		$app			=& JFactory::getApplication();
		$db				=& JFactory::getDBO();
		$joocmConfig	=& JoocmConfig::getInstance();
		
		$context			= 'com_joocm.joocm_timeformat_view';
		$filter_order		= $app->getUserStateFromRequest("$context.filter_order", 'filter_order', 'f.name');
		$filter_order_Dir	= $app->getUserStateFromRequest("$context.filter_order_Dir",	'filter_order_Dir',	'');
		$limit				= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart			= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');
		
		$orderby = "\n ORDER BY $filter_order $filter_order_Dir";
		
		// get the total number of records
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joocm_timeformats AS f"
				. $orderby
				;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		// create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
												
		$query = "SELECT f.*"
				. "\n FROM #__joocm_timeformats AS f"
				. $orderby
				;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();
		
		// parameter list
		$lists = array();
		
		// default time format
		$lists['default_timeformat'] = $joocmConfig->getConfigSettings('time_format');
		
		// table ordering
		$lists['filter_order'] = $filter_order;
		$lists['filter_order_Dir']	= $filter_order_Dir;
		
		ViewTimeFormat::showTimeFormats($rows, $pagination, $lists);	
	}
	
	/**
	 * cancels edit time format operation
	 */
	function cancelEditTimeFormat() {

		// check in category so other can edit it
		$row =& JTable::getInstance('JoocmTimeFormat');
		$row->bind(JRequest::get('post'));
		$row->checkin();

		$this->setRedirect('index.php?option=com_joocm&task=joocm_timeformat_view');
	}
	
	/**
	 * edit the time format
	 */
	function editTimeFormat() {
	
		// initialize variables
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$cid 	= JRequest::getVar('cid', array(0));
		
		if (!is_array($cid)) {
			$cid = array(0);
		}
	
		$row =& JTable::getInstance('JoocmTimeFormat');
		$row->load($cid[0]);

		// is someone else editing this time format?
		if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
			$editingUser =& JFactory::getUser($row->checked_out);
			$this->setRedirect('index.php?option=com_joocm&task=joocm_timeformat_view', JText::sprintf('COM_JOOCM_MSGBEINGEDITTED', JText::_('COM_JOOCM_TIMEFORMAT'), $row->name, $editingUser->name)); return;
		}
		
		// check out category so nobody else can edit it
		$row->checkout($user->get('id'));
		
		// parameter list
		$lists = array();
		
		// build the html radio buttons for state
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);		
				
		ViewTimeFormat::editTimeFormat($row, $lists);			
	}
	
	/**
	 * save the time format
	 */	
	function saveTimeFormat() {

		// initialize variables
		$db =& JFactory::getDBO();
		$post	= JRequest::get('post');
		$row =& JTable::getInstance('JoocmTimeFormat');

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
			case 'joocm_timeformat_apply':
				$link = 'index.php?option=com_joocm&task=joocm_timeformat_edit&cid[]='. $row->id .'&hidemainmenu=1';
				break;
			case 'joocm_timeformat_save':
			default:
				$link = 'index.php?option=com_joocm&task=joocm_timeformat_view';
				break;
		}
		
		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOCM_TIMEFORMAT'), $row->name);
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * delete the time format
	 */	
	function deleteTimeFormat() {
		global $mainframe;

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			
			// how many categories are there to delete?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmTimeFormat'); $row->load($cid[0]);
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_TIMEFORMAT'), $row->name);
			} else {
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_TIMEFORMATS'), '');
			}
		
			$query = "DELETE FROM #__joocm_timeformats"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		} else {
			$msg = JText::sprintf('COM_JOOCM_MSGNOSELECTION', JText::_('COM_JOOCM_TIMEFORMAT'), JText::_('COM_JOOCM_DELETE')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_timeformat_view', $msg, $msgType);
	}
	
	/**
	 * changes the publish state of a time format
	 */
	function changeTimeFormatPublishState($state = 0) {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$state		= JRequest::getCmd('task', 'joocm_timeformat_publish') == 'joocm_timeformat_publish' ? 1 : 0;
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			$msgText = ($state == 1) ? 'COM_JOOCM_MSGSUCCESSFULLYPUBLISHED' : 'COM_JOOCM_MSGSUCCESSFULLYUNPUBLISHED';
			
			// are there one or more rows to publish/unpublish?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmTimeFormat'); $row->load($cid[0]);
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_TIMEFORMAT'), $row->name);
			} else {
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_TIMEFORMATS'), '');
			}
	
			$query = "UPDATE #__joocm_timeformats"
					. "\n SET published = $state"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_timeformat_view', $msg, $msgType);
	}
	
	/**
	 * set time format as default
	 */	
	function defaultTimeFormat() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			
			// set the default time format to the default config 
			$row =& JTable::getInstance('JoocmTimeFormat');
			
			if ($row->load($cid[0])) {
			
				// get the default config
				$query = "SELECT c.id"
						. "\n FROM #__joocm_configs AS c"
						. "\n WHERE default_config = 1"
						;
				$db->setQuery($query);
				$configId = $db->loadResult();
				
				if ($configId) {	
					$config =& JTable::getInstance('JoocmConfig');
					if ($config->load($configId)) {
						$configSettings = new JParameter($config->config_settings);
						$configSettings->set('time_format', $row->timeformat);	
						$config->config_settings = $configSettings->toString();
						$config->store();
					}
				}
			}
		} else {
			$msg = JText::sprintf('COM_JOOCM_MSGNOSELECTION', JText::_('COM_JOOCM_CONFIG'), JText::_('COM_JOOCM_DEFAULT')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_timeformat_view', $msg, $msgType);
	}
}
?>