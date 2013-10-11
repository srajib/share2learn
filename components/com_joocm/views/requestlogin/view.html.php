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
 * Joo!CM Request Login View
 *
 * @package Joo!CM
 */
class JoocmViewRequestLogin extends JView
{

	function display($tpl = null) {

		// handle page title
		$this->document->setTitle($this->joocmConfig->getConfigSettings('community_name') .' - '. JText::_('COM_JOOCM_REQUESTLOGIN'));
		
		// handle metadata
		$this->document->setDescription(JText::sprintf('COM_JOOCM_LOGINREQUESTFORCOMMUNITYNAME', $this->joocmConfig->getConfigSettings('community_name')));
		$this->document->setMetadata('keywords', JText::_('COM_JOOCM_REQUESTLOGIN'). ', '. $this->joocmConfig->getConfigSettings('community_name'));
				
		// handle bread crumb
		$this->breadCrumbs->addItem(JText::_('COM_JOOCM_REQUESTLOGIN'));
			
		// handle CAPTCHA
		$this->assignRef('joocmCaptcha', JoocmCaptcha::getInstance());
		$this->joocmCaptcha->prepare($this->joocmConfig->getCaptchaSettings('captcha_requestlogin'));
		
		parent::display($tpl);
	}
}
?>