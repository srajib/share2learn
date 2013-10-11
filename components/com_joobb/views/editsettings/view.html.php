<?php
/**
 * @version $Id: view.html.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * Joo!BB Edit Settings View
 *
 * @package Joo!BB
 */
class JoobbViewEditSettings extends JView
{

	function display($tpl = null) {

		if ($this->joobbUser->get('id') < 1) {
			$this->messageQueue->addMessage(JText::_('COM_JOOBB_MSGNOPERMISSIONEDITSETTINGS'));
			$this->app->redirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $this->Itemid, false));
		}

		// handle page title
		$this->document->setTitle(JText::_('COM_JOOBB_MYBOARDSETTINGS'));
		
		// handle bread crumb
		$this->breadCrumbs->addBreadCrumb(JText::_('COM_JOOBB_MYBOARDSETTINGS'));
		
		$lists = array();
		
		$lists['enable_bbcode'] = JHTML::_('select.booleanlist', 'enable_bbcode', '', $this->joobbUser->get('enable_bbcode'), JText::_('COM_JOOBB_YES'), JText::_('COM_JOOBB_NO'));
		$lists['enable_emotions'] = JHTML::_('select.booleanlist', 'enable_emotions', '', $this->joobbUser->get('enable_emotions'), JText::_('COM_JOOBB_YES'), JText::_('COM_JOOBB_NO'));
		$lists['auto_subscription'] = JHTML::_('select.booleanlist', 'auto_subscription', '', $this->joobbUser->get('auto_subscription'), JText::_('COM_JOOBB_YES'), JText::_('COM_JOOBB_NO'));
		
		$this->assignRef('lists', $lists);
		
		$action = JRoute::_('index.php?option=com_joobb');
		$this->assignRef('action', $action);
		
		// get buttons
		$joobbButtonSet	=& JoobbButtonSet::getInstance();
		$this->assignRef('buttonSubmit', $joobbButtonSet->buttonByFunction['buttonSubmit']);
		$this->assignRef('buttonCancel', $joobbButtonSet->buttonByFunction['buttonCancel']);
	
		parent::display($tpl);
	}
}
?>