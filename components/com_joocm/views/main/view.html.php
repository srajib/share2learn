<?php
/**
 * @version $Id: view.html.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * Joo!CM Main View
 *
 * @package Joo!CM
 */
class JoocmViewMain extends JView
{

	function display($tpl = null) {

		// get session
		$session =& JFactory::getSession();

		// set data model
		$model =& $this->getModel();
		$profilefieldsets	= $model->getProfileFieldSets();
		$profilefields		= $model->getProfileFields($this->joocmUser);
		
		// handle page title
		$this->document->setTitle($this->joocmConfig->getConfigSettings('community_name'));

		// handle metadata
		$this->document->setDescription($this->joocmConfig->getConfigSettings('description'));
		$this->document->setMetadata('keywords', $this->joocmConfig->getConfigSettings('keywords'));
				
		// assign the interfaces
		$this->assignRef('interfaces', $this->get('interfaces'));

		$this->assignRef('profilefieldsets', $profilefieldsets);
		$this->assignRef('profilefields', $profilefields);

		// allow show user list?
		$showUserList = true;
		if ($this->joocmConfig->getConfigSettings('allow_show_users') && !$this->joocmUser->get('id')) {
			 $showUserList = false;
		}
		$this->assignRef('showUserList', $showUserList);
		
		// handle avatar
		$this->assignRef('enableAvatars', $this->joocmConfig->getConfigSettings('enable_avatars'));
		
		if ($this->enableAvatars) {
			$joocmAvatar =& JoocmAvatar::getInstance();
			$this->assignRef('joocmAvatar', $joocmAvatar);
		}

		// get the max users at main page
		$usersMainPage = $this->joocmConfig->getConfigSettings('users_main_page');
		
		$members = JRequest::getVar('members', $session->get('joocmMembers', 'recentonline'));
		$session->set('joocmMembers', $members);
		$this->assignRef('members', $members);

		switch ($this->members) {
			case 'latest':
			
				// get online users
				$latestMembers =& $model->getLatestMembers(1, 0, $usersMainPage);
				$this->assignRef('latestMembers', $latestMembers);
				break;
			case 'online':
			
				// get online users
				$onlineUsers =& $model->getOnlineUsers(1, 0, $usersMainPage);
				$this->assignRef('onlineUsers', $onlineUsers);
				break;
			case 'recentonline':
			
				// get online users
				$recentOnlineMembers =& $model->getRecentOnlineMembers(1, 0, $usersMainPage);
				$this->assignRef('recentOnlineMembers', $recentOnlineMembers);
				break;
			default:			
				break;
		}
	
		// handle CAPTCHA
		$this->assignRef('joocmCaptcha', JoocmCaptcha::getInstance());
		$this->joocmCaptcha->prepare($this->joocmConfig->getCaptchaSettings('captcha_login'));
			
		parent::display($tpl);
	}
	
	function &getOnlineUser($index = 0) {

		$onlineUser =& $this->onlineUsers[$index];
						
		$onlineUser->userLink = '';
		if ($onlineUser->name) {
			$onlineUser->userLink = JoocmHelper::getLink('profile', '&id='.$onlineUser->userid);
		} else {
			$onlineUser->name = JText::_('COM_JOOCM_GUEST');
		}

		return $onlineUser;
	}
	
	function &getLatestMember($index = 0) {

		$latestMember =& $this->latestMembers[$index];
						
		$latestMember->userLink = '';
		if ($latestMember->name) {
			$latestMember->userLink = JoocmHelper::getLink('profile', '&id='.$latestMember->id);
		} else {
			$latestMember->name = JText::_('COM_JOOCM_GUEST');
		}

		return $latestMember;
	}
	
	function &getRecentOnlineMember($index = 0) {

		$recentOnlineMember =& $this->recentOnlineMembers[$index];
						
		$recentOnlineMember->userLink = '';
		if ($recentOnlineMember->name) {
			$recentOnlineMember->userLink = JoocmHelper::getLink('profile', '&id='.$recentOnlineMember->id);
		} else {
			$recentOnlineMember->name = JText::_('COM_JOOCM_GUEST');
		}

		return $recentOnlineMember;
	}
}
?>