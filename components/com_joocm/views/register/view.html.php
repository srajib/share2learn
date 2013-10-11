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
 * Joo!CM Register View
 *
 * @package Joo!CM
 */
class JoocmViewRegister extends JView
{

	function display($tpl = null) {

		if ($this->joocmUser->get('id') > 0) {
			$this->app->redirect(JoocmHelper::getLink('login'), JText::_('COM_JOOCM_MSGALREADYLOGGEDIN'), 'notice');
		}

		$session =& JFactory::getSession();
		$joocmRegisterForm = $session->get('joocmRegisterForm');
		$session->set('joocmRegisterForm', null);

		if ($this->joocmConfig->getConfigSettings('enable_terms') && !JRequest::getVar('agreed_terms', 0, '', 'int') && !$joocmRegisterForm) {
			$this->app->redirect(JoocmHelper::getLink('terms'));
		}
		
		$joocmUser = new JoocmUser(0, true);
		
		if ($joocmRegisterForm) {
			$joocmUser->bind($joocmRegisterForm);
		}

		// handle page title
		$this->document->setTitle($this->joocmConfig->getConfigSettings('community_name') .' - '. JText::_('COM_JOOCM_REGISTRATION'));

		// handle bread crumb
		$this->breadCrumbs->addItem(JText::_('COM_JOOCM_REGISTRATION'));		
		
		// handle metadata
		$this->document->setDescription(JText::sprintf('COM_JOOCM_REGISTERATCOMMUNITYNAME', $this->joocmConfig->getConfigSettings('community_name')));
		$this->document->setMetadata('keywords', JText::_('COM_JOOCM_REGISTER'). ', '. $this->joocmConfig->getConfigSettings('keywords'));

		$this->assignRef('joocmUser', $joocmUser);

		// handle CAPTCHA
		$this->assignRef('joocmCaptcha', JoocmCaptcha::getInstance());
		$this->joocmCaptcha->prepare($this->joocmConfig->getCaptchaSettings('captcha_register'));

		parent::display($tpl);
	}
}
?>