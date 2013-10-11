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
 * Joo!CM Avatar View
 *
 * @package Joo!CM
 */
class JoocmViewAvatar extends JView
{

	function display($tpl = null) {

		if ($this->joocmUser->get('id') < 1) {
			$this->app->redirect(JoocmHelper::getLink('login'), JText::_('COM_JOOCM_MSGNOPERMISSIONMANAGEAVATARS'), 'notice');
		}

		if (!$this->joocmConfig->getConfigSettings('enable_avatars')) {
			$this->app->redirect(JoocmHelper::getLink('main'), JText::_('COM_JOOCM_MSGAVATARSNOTACTIVE'), 'notice');
		} else if ($this->joocmConfig->getConfigSettings('avatar_source') != 1) {
			$this->app->redirect(JoocmHelper::getLink('main'), JText::_('COM_JOOCM_MSGJOOCMAVATARSNOTACTIVE'), 'notice');
		}

		// handle page title
		$this->document->setTitle($this->joocmConfig->getConfigSettings('community_name') .' - '. JText::_('COM_JOOCM_MYAVATAR'));
		
		// handle bread crumb
		$this->breadCrumbs->addItem(JText::_('COM_JOOCM_MYAVATAR'));
		
		// handle metadata
		$this->document->setDescription($this->joocmConfig->getConfigSettings('description'));
		$this->document->setMetadata('keywords', $this->joocmConfig->getConfigSettings('keywords'));

		// handle avatar
		$this->assignRef('enableAvatars', $this->joocmConfig->getConfigSettings('enable_avatars'));
		
		if ($this->enableAvatars) {
			$joocmAvatar =& JoocmAvatar::getInstance();
			$this->assignRef('joocmAvatar', $joocmAvatar);
			$this->assignRef('standardAvatars', $joocmAvatar->getStandardAvatars());
			$this->assignRef('userAvatars', $joocmAvatar->getUserAvatars($this->joocmUser->get('id')));
		}
			
		parent::display($tpl);
	}
}
?>