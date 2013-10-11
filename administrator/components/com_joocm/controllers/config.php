<?php
/**
 * @version $Id: config.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'config.php');

/**
 * Joo!CM Config Controller
 *
 * @package Joo!CM
 */
class ControllerConfig extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joocm_config_view', 'showConfig');
		$this->registerTask('joocm_config_save', 'saveConfig');
		$this->registerTask('joocm_config_apply', 'saveConfig');
		$this->registerTask('joocm_config_cancel', 'cancelEditConfig');
	}

	/**
	 * shows the configuration in editing mode
	 */
	function showConfig() {

		// initialize variables
		$db				=& JFactory::getDBO();
		$user			=& JFactory::getUser();

		$row =& JTable::getInstance('JoocmConfig');
		$row->load(1);

		// is someone else editing this configuration?
		if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
			$editingUser =& JFactory::getUser($row->checked_out);
			$this->setRedirect('index.php?option=com_joocm', JText::sprintf('COM_JOOCM_MSGBEINGEDITTED', JText::_('COM_JOOCM_CONFIG'), $row->name, $editingUser->name)); return;
		}
		
		// check out configuration so nobody else can edit it
		$row->checkout($user->get('id'));

		// parameter list
		$lists = array();

		// config settings params definitions
		$file = JOOCM_ADMINPARAMS.DS.'config_settings.xml';
		$lists['config_settings'] = new JParameter($row->config_settings, $file);
		
		// user setting default params definitions
		$file = JOOCM_ADMINPARAMS.DS.'config_user_settings_defaults.xml';
		$lists['user_settings_defaults'] = new JParameter($row->user_settings_defaults, $file);

		// avatar settings params definitions
		$file = JOOCM_ADMINPARAMS.DS.'config_avatar_settings.xml';
		$lists['avatar_settings'] = new JParameter($row->avatar_settings, $file);
			
		// captcha settings params definitions
		$file = JOOCM_ADMINPARAMS.DS.'config_captcha_settings.xml';
		$lists['captcha_settings'] = new JParameter($row->captcha_settings, $file);
				
		ViewConfig::showConfig($row, $lists);			
	}
	
	/**
	 * cancels edit configuration operation
	 */
	function cancelEditConfig() {

		// check in configuration so other can edit it
		$row =& JTable::getInstance('JoocmConfig');
		$row->bind(JRequest::get('post'));
		$row->checkin();
		
		$this->setRedirect('index.php?option=com_joocm');
	}

	/**
	 * save the configuration
	 */	
	function saveConfig() {

		// initialize variables
		$db 	=& JFactory::getDBO();
		$post	= JRequest::get('post');
		$row 	=& JTable::getInstance('JoocmConfig');

		if (!$row->bind($post)) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		// save params
		$config_settings = JRequest::getVar('config_settings', array(), 'post', 'array');
		if (is_array($config_settings)) {
			$txt = array();
			foreach ($config_settings as $k=>$v) {
				$txt[] = "$k=$v";
			}
			$row->config_settings = implode("\n", $txt);
		}
		
		// save params
		$user_settings_defaults = JRequest::getVar('user_settings_defaults', array(), 'post', 'array');
		if (is_array($user_settings_defaults)) {
			$txt = array();
			foreach ($user_settings_defaults as $k=>$v) {
				$txt[] = "$k=$v";
			}
			$row->user_settings_defaults = implode("\n", $txt);
		}

		// save params
		$avatar_settings = JRequest::getVar('avatar_settings', array(), 'post', 'array');
		if (is_array($avatar_settings)) {
			$txt = array();
			foreach ($avatar_settings as $k=>$v) {
				$txt[] = "$k=$v";
			}
			$row->avatar_settings = implode("\n", $txt);
		}
		
		// save params
		$captcha_settings = JRequest::getVar('captcha_settings', array(), 'post', 'array');
		if (is_array($captcha_settings)) {
			$txt = array();
			foreach ($captcha_settings as $k=>$v) {
				$txt[] = "$k=$v";
			}
			$row->captcha_settings = implode("\n", $txt);
		}
				
		if (!$row->check()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$row->checkin();

		switch (JRequest::getCmd('task')) {
			case 'joocm_config_apply':
				$link = 'index.php?option=com_joocm&task=joocm_config_view';
				break;
			case 'joocm_config_save':
			default:
				$link = 'index.php?option=com_joocm&task=joocm_controlpanel';
				break;
		}
		
		$msg = JText::sprintf('COM_JOOCM_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOCM_CONFIG'), $row->name);
		$this->setRedirect($link, $msg);
	}
}
?>