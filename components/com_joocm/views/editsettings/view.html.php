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
 * Joo!CM Edit Settings View
 *
 * @package Joo!CM
 */
class JoocmViewEditSettings extends JView
{

	function display($tpl = null) {

		if ($this->joocmUser->get('id') < 1) {
			$this->app->redirect(JoocmHelper::getLink('login'), JText::_('COM_JOOCM_MSGNOPERMISSIONEDITSETTINGS'), 'notice');
		}

		// handle page title
		$this->document->setTitle($this->joocmConfig->getConfigSettings('community_name') .' - '. JText::_('COM_JOOCM_MYSETTINGS'));

		// handle bread crumb
		$this->breadCrumbs->addItem(JText::_('COM_JOOCM_MYSETTINGS'));
		
		// handle metadata
		$this->document->setDescription($this->joocmConfig->getConfigSettings('description'));
		$this->document->setMetadata('keywords', $this->joocmConfig->getConfigSettings('keywords'));

		$lists = array();

		// check to see if Frontend User Params have been enabled
		$usersConfig = &JComponentHelper::getParams('com_users');
		$check = $usersConfig->get('frontend_userparams');

		if ($check == '1' || $check == 1 || $check == NULL) {
			$lists['params'] = $this->joocmUser->getParameters(true);
		}
		
		// build the html radio buttons for show email
		$lists['show_email'] = JHTML::_('select.booleanlist', 'show_email', '', $this->joocmUser->get('show_email'), JText::_('COM_JOOCM_YES'), JText::_('COM_JOOCM_NO'));
		// build the html radio buttons for show online state
		$lists['show_online_state'] = JHTML::_('select.booleanlist', 'show_online_state', '', $this->joocmUser->get('show_online_state'), JText::_('COM_JOOCM_YES'), JText::_('COM_JOOCM_NO'));		

		$timeFormatsList = $this->get('timeformats');
		
		$timeformats = array();
		foreach ($timeFormatsList as $timeFormat) {
			$timeFormat->name = $timeFormat->name .' ('. JoocmHelper::formatDate(time(), $timeFormat->timeformat, $this->joocmConfig->getConfigSettings('time_zone')) .')';
			$timeformats[] = JHTML::_('select.option', $timeFormat->timeformat, $timeFormat->name, 'timeformat', 'name');
		}
		$lists['timeformats'] = JHTML::_('select.genericlist',  $timeformats, 'time_format', 'class="inputbox" size="1"', 'timeformat', 'name', $this->joocmUser->get('time_format'));
		
		$this->assignRef('lists', $lists);
					
		parent::display($tpl);
	}
}
?>