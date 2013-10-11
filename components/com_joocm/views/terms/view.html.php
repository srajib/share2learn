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
 * Joo!CM Terms View
 *
 * @package Joo!CM
 */
class JoocmViewTerms extends JView
{

	function display($tpl = null) {

		$terms = $this->get('terms');
		if (!$terms->id) {
			$this->app->redirect(JoocmHelper::getLink('main'), JText::_('COM_JOOCM_MSGNOTERMS'), 'notice');
		}
		$this->assignRef('terms', $terms);

		// handle page title
		$this->document->setTitle($this->joocmConfig->getConfigSettings('community_name') .' - '. $terms->terms);
		
		// handle metadata
		$this->document->setDescription($terms->description);
		$this->document->setMetadata('keywords', $terms->keywords);
		
		// handle bread crumb
		$this->breadCrumbs->addItem(JText::_($terms->terms));
		
		// get users config
		$usersConfig = &JComponentHelper::getParams('com_users');
		
		$showAgreement = 1;
		if ($this->joocmUser->get('id') && $this->joocmUser->get('agreed_terms') || !$usersConfig->get('allowUserRegistration')) {
			$showAgreement = 0;
		}
		$this->assign('showAgreement', $showAgreement);
		
		if ($showAgreement && !$this->joocmUser->get('id')) {
			$action = JRoute::_('index.php?option=com_joocm&view=register&Itemid='. $this->Itemid);
		} else {
			$action = JRoute::_('index.php?option=com_joocm&task=joocmagreedterms&Itemid='. $this->Itemid);
		}
		$this->assignRef('action', $action);
			
		parent::display($tpl);
	}
}
?>