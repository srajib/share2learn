<?php
/**
 * @version $Id: view.html.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * Joo!CM Information View
 *
 * @package Joo!CM
 */
class JoocmViewInformation extends JView
{

	function display($tpl = null) {

		// initialize variables
		$joocmUser = JoocmUser::getInstance(JRequest::getVar('user', 0, '', 'int'));
		$this->assignRef('joocmUser', $joocmUser);
		
		$info = JRequest::getVar('info');
		
		$infoFilePath = JOOCM_BASEPATH.DS.'views'.DS.'information'.DS.'tmpl'.DS;
		
		switch($info) {
			case 'account_activation':
				switch($this->joocmConfig->getConfigSettings('account_activation')) {
					case 0:		// no activation needed
						$info = $info.'_no';
						break;
					case 1:		// activation by user
						$info = $info.'_user';
						break;
					case 2:		// activation by admin
						$info = $info.'_admin';
						break;				
				}			
				break;
			default:
				break;				 
		}
	
		$this->assignRef('info', $info);

		// handle page title
		$this->document->setTitle(JText::_('COM_JOOCM_INFORMATION'));
		
		// handle metadata
		$this->document->setDescription($this->joocmConfig->getConfigSettings('description'));
		$this->document->setMetadata('keywords', $this->joocmConfig->getConfigSettings('keywords'));
		
		// handle bread crumb
		$this->breadCrumbs->addItem($this->joocmConfig->getConfigSettings('community_name') .' - '. JText::_('COM_JOOCM_INFORMATION'));
		
		$this->assignRef('linkMainPage', JRoute::_('index.php?option=com_joocm&view=main&Itemid='.$this->Itemid));
		$this->assignRef('linkLogin', JRoute::_('index.php?option=com_joocm&view=login&Itemid='.$this->Itemid));
		$this->assignRef('linkRequestLogin', JRoute::_('index.php?option=com_joocm&view=requestlogin&Itemid='.$this->Itemid));		

		parent::display($tpl);
	}
}
?>