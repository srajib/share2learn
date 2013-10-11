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
 * Joo!CM Profile View
 *
 * @package Joo!CM
 */
class JoocmViewProfile extends JView
{

	function display($tpl = null) {

		if ($this->joocmConfig->getConfigSettings('allow_show_users') && !$this->joocmUser->get('id')) {
			$this->app->redirect(JoocmHelper::getLink('login'), JText::_('COM_JOOCM_MSGNOPERMISSIONVIEWMEMBERSPROFILE'));
		}	
		
		// initialize variables
		$joocmUserView	=& JoocmUser::getInstance(JRequest::getVar('id', $this->joocmUser->get('id')));
		
		if ($joocmUserView->get('id') != $this->joocmUser->get('id')) {
			$joocmUserView->incrementViewsCount();
		}
				
		$model				=& $this->getModel();
		$profilefieldsets	= $model->getProfileFieldSets();
		$profilefields		= $model->getProfileFields($joocmUserView);
		
		// handle page title
		$this->document->setTitle($this->joocmConfig->getConfigSettings('community_name') .' - '. $joocmUserView->get('name'));
		
		// handle metadata
		$this->document->setDescription(JText::sprintf('COM_JOOCM_PROFILEOFDESC', $joocmUserView->get('name'), $this->joocmConfig->getConfigSettings('community_name')));
		$this->document->setMetadata('keywords', JText::_($joocmUserView->get('name')) .', '. JText::_($this->joocmConfig->getConfigSettings('community_name')));
		
		// handle bread crumb
		$this->breadCrumbs->addItem($joocmUserView->get('name'));
		
		// show email?
		if ($joocmUserView->get('show_email')) {
			$joocmUserView->set('email', JHTML::_('email.cloak', $joocmUserView->get('email')));
		} else {
			$joocmUserView->set('email', JText::_('COM_JOOCM_EMAILISNOTAVAILABLE'));
		}

		$this->assignRef('joocmUserView', $joocmUserView);
		$this->assignRef('profilefieldsets', $profilefieldsets);
		$this->assignRef('profilefields', $profilefields);
		
		// handle avatar
		$this->assignRef('enableAvatars', $this->joocmConfig->getConfigSettings('enable_avatars'));
		
		if ($this->enableAvatars) {
			$joocmAvatar =& JoocmAvatar::getInstance();
			$this->assignRef('joocmAvatar', $joocmAvatar);
		}	
					
		parent::display($tpl);
	}
}
?>