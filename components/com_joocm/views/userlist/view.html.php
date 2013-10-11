<?php
/**
 * @version $Id: view.html.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * Joo!CM User List View
 *
 * @package Joo!CM
 */
class JoocmViewUserList extends JView
{

	function display($tpl = null) {

		if ($this->joocmConfig->getConfigSettings('allow_show_users') && !$this->joocmUser->get('id')) {
			$this->app->redirect(JoocmHelper::getLink('login'), JText::_('COM_JOOCM_MSGNOPERMISSIONVIEWMEMBERS'), 'notice');
		}
		
		// get session
		$session =& JFactory::getSession();

		// request variables
		$limit = JRequest::getVar('limit', $this->joocmConfig->getConfigSettings('items_per_page'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		
		$filter	= JRequest::getVar('filter', $session->get('joocmFilter', 'all'));
		$session->set('joocmFilter', $filter);
		$this->assignRef('filter', $filter);
		
		$searchUser = JRequest::getVar('searchUser', $session->get('joocmSearchUser', ''));
		$session->set('joocmSearchUser', $searchUser);
		$this->assignRef('searchUser', $searchUser);
		
		$orderBy = JRequest::getVar('orderby', $session->get('joocmOrderBy', 'name'));
		$session->set('joocmOrderBy', $orderBy);
		$this->assignRef('orderBy', $orderBy);
		
		$orderByDir = JRequest::getVar('orderbydir', $session->get('joocmOrderByDir', 'ASC'));
		$session->set('joocmOrderByDir', $orderByDir);
		$this->assignRef('orderByDir', $orderByDir);
				
		$model	=& $this->getModel();
		$joocmUsers =& $model->getJoocmUsers($limitstart, $limit, $filter, $searchUser, $orderBy, $orderByDir);
		$this->assignRef('joocmUsers', $joocmUsers);

		$total = $model->getTotalUsers();
		$this->assignRef('total', $total);
		
		$showPagination = false;
		if ($total > $limit) {
			$showPagination = true;
		}
		$this->assign('showPagination', $showPagination);
		
		jimport('joomla.html.pagination');
		$this->pagination = new JPagination($total, $limitstart, $limit);

		// handle page title
		$this->document->setTitle($this->joocmConfig->getConfigSettings('community_name') .' - '. JText::_('COM_JOOCM_MEMBERS'));
		
		// handle metadata
		$this->document->setDescription(JText::sprintf('COM_JOOCM_MEMBERSAT', $this->joocmConfig->getConfigSettings('community_name')));
		$this->document->setMetadata('keywords', JText::_('COM_JOOCM_MEMBERS'). ', '. $this->joocmConfig->getConfigSettings('keywords'));
		
		// handle bread crumb
		$this->breadCrumbs->addItem(JText::_('COM_JOOCM_MEMBERS'));
		
		// handle avatar
		$this->assignRef('enableAvatars', $this->joocmConfig->getConfigSettings('enable_avatars'));
		
		if ($this->enableAvatars) {
			$joocmAvatar =& JoocmAvatar::getInstance();
			$this->assignRef('joocmAvatar', $joocmAvatar);
		}	

		parent::display($tpl);
	}
	
	function &getJoocmUser($index) {
		
		// initialize variables
		$db =& JFactory::getDBO();
	
		$joocmUser =& $this->joocmUsers[$index];
		$joocmUser->registerDate = JoocmHelper::Date($joocmUser->registerDate);
		$joocmUser->lastvisitDate = JoocmHelper::Date($joocmUser->lastvisitDate);
		$joocmUser->userLink = JoocmHelper::getLink('profile', '&id='.$joocmUser->id);
		
		// get online state
		$joocmUser->onlineState = false;
		if ($joocmUser->userid) {
			$joocmUser->onlineState = true;
		}	

		return $joocmUser;
	}
}
?>