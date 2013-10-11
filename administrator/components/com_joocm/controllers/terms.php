<?php
/**
 * @version $Id: terms.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'terms.php');

/**
 * Joo!CM Terms Controller
 *
 * @package Joo!CM
 */
class ControllerTerms extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joocm_terms_view', 'showTerms');
		$this->registerTask('joocm_terms_new', 'editTerms');
		$this->registerTask('joocm_terms_edit', 'editTerms');
		$this->registerTask('joocm_terms_save', 'saveTerms');
		$this->registerTask('joocm_terms_apply', 'saveTerms');
		$this->registerTask('joocm_terms_delete', 'deleteTerms');
		$this->registerTask('joocm_terms_cancel', 'cancelEditTerms');
		$this->registerTask('joocm_terms_publish', 'changeTermsPublishState');
		$this->registerTask('joocm_terms_unpublish', 'changeTermsPublishState');
	}
	
	/**
	 * compiles a list of terms
	 */
	function showTerms() {

		// initialize variables
		$app		=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		
		$context			= 'com_joocm.joocm_terms_view';
		$filter_order		= $app->getUserStateFromRequest("$context.filter_order", 'filter_order', 't.terms');
		$filter_order_Dir	= $app->getUserStateFromRequest("$context.filter_order_Dir",	'filter_order_Dir',	'');
		$limit				= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart			= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');
		
		$orderby = "\n ORDER BY $filter_order $filter_order_Dir";
		
		// get the total number of records
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joocm_terms"
				. $orderby
				;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		// create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
												
		$query = "SELECT t.*"
				. "\n FROM #__joocm_terms AS t"
				. $orderby
				;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();
		
		// parameter list
		$lists = array();
		
		// table ordering
		$lists['filter_order'] = $filter_order;
		$lists['filter_order_Dir']	= $filter_order_Dir;
		
		ViewTerms::showTerms($rows, $pagination, $lists);	
	}
	
	/**
	 * cancels edit terms operation
	 */
	function cancelEditTerms() {

		// check in category so other can edit it
		$row =& JTable::getInstance('JoocmTerms');
		$row->bind(JRequest::get('post'));
		$row->checkin();

		$this->setRedirect('index.php?option=com_joocm&task=joocm_terms_view');
	}
	
	/**
	 * edit the terms
	 */
	function editTerms() {
	
		// initialize variables
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$cid 	= JRequest::getVar('cid', array(0));
		
		if (!is_array($cid)) {
			$cid = array(0);
		}
	
		$row =& JTable::getInstance('JoocmTerms');
		$row->load($cid[0]);

		// is someone else editing this terms?
		if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
			$editingUser =& JFactory::getUser($row->checked_out);
			$this->setRedirect('index.php?option=com_joocm&task=joocm_terms_view', JText::sprintf('COM_JOOCM_MSGBEINGEDITTED', JText::_('COM_JOOCM_TERMS'), $row->terms, $editingUser->name), 'notice'); return;
		}
		
		// check out category so nobody else can edit it
		$row->checkout($user->get('id'));
		
		// parameter list
		$lists = array();
		
		// build the html radio buttons for state
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);		
				
		ViewTerms::editTerms($row, $lists);			
	}
	
	/**
	 * save the terms
	 */	
	function saveTerms() {

		// initialize variables
		$db		=& JFactory::getDBO();
		$post	= JRequest::get('post');
		$row	=& JTable::getInstance('JoocmTerms');

		if (!$row->bind($post)) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if (!$row->check()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		
		// we need to request the text separetly, otherwise html tags will be not saved
		$termstext = JRequest::getVar('termstext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		// clean text for xhtml transitional compliance
		$row->termstext = str_replace('<br>', '<br />', $termstext);
		
		// we need to request the text separetly, otherwise html tags will be not saved
		$agreementtext = JRequest::getVar('agreementtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		// clean text for xhtml transitional compliance
		$row->agreementtext = str_replace('<br>', '<br />', $agreementtext);
				
		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$row->checkin();

		switch (JRequest::getCmd('task')) {
			case 'joocm_terms_apply':
				$link = 'index.php?option=com_joocm&task=joocm_terms_edit&cid[]='. $row->id .'&hidemainmenu=1'. 'text: '.$post['text'];
				break;
			case 'joocm_terms_save':
			default:
				$link = 'index.php?option=com_joocm&task=joocm_terms_view';
				break;
		}

		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOCM_TERMS'), $row->terms);
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * delete the terms
	 */	
	function deleteTerms() {

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			
			// how many terms are there to delete?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmTerms'); $row->load($cid[0]);
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_TERMS'), $row->terms);
			} else {
				$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYDELETED', JText::_('COM_JOOCM_TERMS'), '');
			}
		
			$query = "DELETE FROM #__joocm_terms"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
					
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		} else {
			$msg = JText::sprintf('COM_JOOCM_MSGNOSELECTION', JText::_('COM_JOOCM_TERMS'), JText::_('COM_JOOCM_DELETE')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_terms_view', $msg, $msgType);
	}
	
	/**
	 * Changes the publish state of a terms
	 */
	function changeTermsPublishState() {

		// initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$state		= JRequest::getCmd('task', 'joocm_terms_publish') == 'joocm_terms_publish' ? 1 : 0;
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {
			$msgText = ($state == 1) ? 'COM_JOOCM_MSGSUCCESSFULLYPUBLISHED' : 'COM_JOOCM_MSGSUCCESSFULLYUNPUBLISHED';
			
			// are there one or more rows to publish/unpublish?
			if (count($cid) == 1) {
				$row =& JTable::getInstance('JoocmTerms'); $row->load($cid[0]);
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_TERMS'), $row->terms);
			} else {
				$msg = JText::sprintf($msgText, JText::_('COM_JOOCM_TERMS'), '');
			}
			
			$query = "UPDATE #__joocm_terms"
					. "\n SET published = $state"
					. "\n WHERE id = " . implode(' OR id = ', $cid)
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg(); $msgType = 'error';
			}
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_terms_view', $msg, $msgType);
	}
}
?>