<?php
/**
 * @version $Id: view.html.php 172 2010-09-19 09:40:34Z sterob $
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
 * Joo!BB Captcha View
 *
 * @package Joo!BB
 */
class JoobbViewCaptcha extends JView
{

	function display($tpl = null) {

		// initialize variables
		JHTML::_('behavior.modal');
		
		// handle page title
		$this->document->setTitle($this->joobbConfig->getBoardSettings('board_name'));
		
		// get buttons
		$joobbButtonSet	=& JoobbButtonSet::getInstance();
		$this->assignRef('buttonSubmit', $joobbButtonSet->buttonByFunction['buttonSubmit']);
		$this->assignRef('buttonCancel', $joobbButtonSet->buttonByFunction['buttonCancel']);

		// handle CAPTCHA
		$this->assignRef('joocmCaptcha', JoocmCaptcha::getInstance());
		$this->joocmCaptcha->prepare(1);
		
		// handle captcha action
		switch (JRequest::getVar('do', '')) {
			case 'joobbdeletepost':
				$actionCaptcha = JRoute::_('index.php?option=com_joobb&task=joobbdeletepost&post='.JRequest::getVar('post', 0, '', 'int').'&Itemid='.$this->Itemid);
				break;
			case 'joobbdeletetopic':
				$actionCaptcha = JRoute::_('index.php?option=com_joobb&task=joobbdeletetopic&topic='.JRequest::getVar('topic', 0, '', 'int').'&Itemid='.$this->Itemid);
				break;
			default:
				$actionCaptcha = JRoute::_('index.php?option=com_joobb$view=board&Itemid='.$this->Itemid);
		}
		
		$this->assignRef('actionCaptcha', $actionCaptcha);

		parent::display($tpl);
	}
}
?>