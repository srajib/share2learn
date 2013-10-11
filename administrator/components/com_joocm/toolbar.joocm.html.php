<?php
/**
 * @version $Id: toolbar.joocm.html.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
 // no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Toolbar Html
 *
 * @package Joo!CM
 */
class TOOLBAR_joocm {

	function _JOOCM_CONFIG() {
		JToolBarHelper::title(JText::_('COM_JOOCM_CONFIGMANAGER'), 'config.png');
		JToolBarHelper::custom('joocm_config_apply', 'apply.png', 'apply_f2.png', 'COM_JOOCM_APPLY', false);				
		JToolBarHelper::custom('joocm_config_save', 'save.png', 'save_f2.png', 'COM_JOOCM_SAVE', false);		
		JToolBarHelper::custom('joocm_config_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOCM_CANCEL', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/configuration');	
	}

	function _JOOCM_TIMEFORMAT() {
		JToolBarHelper::title(JText::_('COM_JOOCM_TIMEFORMATMANAGER'), 'timeformat.png');
		JToolBarHelper::custom('joocm_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOCM_JOOCM', false);
		JToolBarHelper::custom('joocm_timeformat_default', 'default.png', 'default_f2.png', 'COM_JOOCM_DEFAULT', false);
		JToolBarHelper::custom('joocm_timeformat_new', 'new.png', 'new_f2.png', 'COM_JOOCM_NEW', false);
		JToolBarHelper::custom('joocm_timeformat_edit', 'edit.png', 'edit_f2.png', 'COM_JOOCM_EDIT', false);
		JToolBarHelper::custom('joocm_timeformat_delete', 'delete.png', 'delete_f2.png', 'COM_JOOCM_DELETE', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/time_format_manager');
	}

	function _JOOCM_TIMEFORMAT_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOCM_EDIT') : JText::_('COM_JOOCM_ADD');
	
		JToolBarHelper::title(JText::_('COM_JOOCM_TIMEFORMATMANAGER') .' - <span>'. $text.'</span>', 'timeformat.png');
		JToolBarHelper::custom('joocm_timeformat_apply', 'apply.png', 'apply_f2.png', 'COM_JOOCM_APPLY', false);				
		JToolBarHelper::custom('joocm_timeformat_save', 'save.png', 'save_f2.png', 'COM_JOOCM_SAVE', false);		
		JToolBarHelper::custom('joocm_timeformat_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOCM_CANCEL', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/time_format_manager#time_format_edit');
	}
		
	function _JOOCM_USER() {
		JToolBarHelper::title(JText::_('COM_JOOCM_USERMANAGER'), 'joocmuser.png');	
		JToolBarHelper::custom('joocm_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOCM_JOOCM', false);
		JToolBarHelper::custom('joocm_user_new', 'new.png', 'new_f2.png', 'COM_JOOCM_NEW', false);
		JToolBarHelper::custom('joocm_user_edit', 'edit.png', 'edit_f2.png', 'COM_JOOCM_EDIT', false);
		JToolBarHelper::custom('joocm_user_delete', 'delete.png', 'delete_f2.png', 'COM_JOOCM_DELETE', false);
		JToolBarHelper::custom('joocm_user_logout', 'cancel.png', 'cancel_f2.png', 'COM_JOOCM_LOGOUT', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/user_manager');
	}
	
	function _JOOCM_USER_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOCM_EDIT') : JText::_('COM_JOOCM_ADD');
			
		JToolBarHelper::title(JText::_('COM_JOOCM_USERMANAGER') .' - <span>'. $text.'</span>', 'joocmuser.png');
		JToolBarHelper::custom('joocm_user_apply', 'apply.png', 'apply_f2.png', 'COM_JOOCM_APPLY', false);				
		JToolBarHelper::custom('joocm_user_save', 'save.png', 'save_f2.png', 'COM_JOOCM_SAVE', false);		
		JToolBarHelper::custom('joocm_user_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOCM_CANCEL', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/user_manager#user_manager_edit');
	}

	function _JOOCM_PROFILEFIELD() {
		JToolBarHelper::title(JText::_('COM_JOOCM_PROFILEFIELDMANAGER'), 'profilefield.png');
		JToolBarHelper::custom('joocm_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOCM_JOOCM', false);
		JToolBarHelper::custom('joocm_profilefield_new', 'new.png', 'new_f2.png', 'COM_JOOCM_NEW', false);
		JToolBarHelper::custom('joocm_profilefield_edit', 'edit.png', 'edit_f2.png', 'COM_JOOCM_EDIT', false);
		JToolBarHelper::custom('joocm_profilefield_delete', 'delete.png', 'delete_f2.png', 'COM_JOOCM_DELETE', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/profile_manager#profile_fields_list');
	}
	
	function _JOOCM_PROFILEFIELD_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOCM_EDIT') : JText::_('COM_JOOCM_ADD');
			
		JToolBarHelper::title(JText::_('COM_JOOCM_PROFILEFIELDMANAGER') .' - <span>'. $text.'</span>', 'profilefield.png');
		JToolBarHelper::custom('joocm_profilefield_apply', 'apply.png', 'apply_f2.png', 'COM_JOOCM_APPLY', false);				
		JToolBarHelper::custom('joocm_profilefield_save', 'save.png', 'save_f2.png', 'COM_JOOCM_SAVE', false);		
		JToolBarHelper::custom('joocm_profilefield_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOCM_CANCEL', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/profile_manager#profile_fields_edit');
	}

	function _JOOCM_PROFILEFIELDSET() {
		JToolBarHelper::title(JText::_('COM_JOOCM_PROFILEFIELDSETMANAGER'), 'profilefieldset.png');
		JToolBarHelper::custom('joocm_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOCM_JOOCM', false);
		JToolBarHelper::custom('joocm_profilefieldset_new', 'new.png', 'new_f2.png', 'COM_JOOCM_NEW', false);
		JToolBarHelper::custom('joocm_profilefieldset_edit', 'edit.png', 'edit_f2.png', 'COM_JOOCM_EDIT', false);
		JToolBarHelper::custom('joocm_profilefieldset_delete', 'delete.png', 'delete_f2.png', 'COM_JOOCM_DELETE', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/profile_manager#profile_field-set_list');
	}
	
	function _JOOCM_PROFILEFIELDSET_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOCM_EDIT') : JText::_('COM_JOOCM_ADD');
			
		JToolBarHelper::title(JText::_('COM_JOOCM_PROFILEFIELDSETMANAGER') .' - <span>'. $text.'</span>', 'profilefieldset.png');
		JToolBarHelper::custom('joocm_profilefieldset_apply', 'apply.png', 'apply_f2.png', 'COM_JOOCM_APPLY', false);				
		JToolBarHelper::custom('joocm_profilefieldset_save', 'save.png', 'save_f2.png', 'COM_JOOCM_SAVE', false);		
		JToolBarHelper::custom('joocm_profilefieldset_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOCM_CANCEL', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/profile_manager#profile_field-set_edit');
	}

	function _JOOCM_PROFILEFIELDLIST() {
		JToolBarHelper::title(JText::_('COM_JOOCM_PROFILEFIELDLISTMANAGER'), 'profilefield.png');
		JToolBarHelper::custom('joocm_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOCM_JOOCM', false);
		JToolBarHelper::custom('joocm_profilefieldlist_new', 'new.png', 'new_f2.png', 'COM_JOOCM_NEW', false);
		JToolBarHelper::custom('joocm_profilefieldlist_edit', 'edit.png', 'edit_f2.png', 'COM_JOOCM_EDIT', false);
		JToolBarHelper::custom('joocm_profilefieldlist_delete', 'delete.png', 'delete_f2.png', 'COM_JOOCM_DELETE', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/profile_manager#profile_field-lists_list');
	}
	
	function _JOOCM_PROFILEFIELDLIST_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOCM_EDIT') : JText::_('COM_JOOCM_ADD');
			
		JToolBarHelper::title(JText::_('COM_JOOCM_PROFILEFIELDLISTMANAGER') .' - <span>'. $text.'</span>', 'profilefield.png');
		JToolBarHelper::custom('joocm_profilefieldlist_apply', 'apply.png', 'apply_f2.png', 'COM_JOOCM_APPLY', false);				
		JToolBarHelper::custom('joocm_profilefieldlist_save', 'save.png', 'save_f2.png', 'COM_JOOCM_SAVE', false);		
		JToolBarHelper::custom('joocm_profilefieldlist_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOCM_CANCEL', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/profile_manager#profile_field-lists_edit');
	}

	function _JOOCM_PROFILEFIELDLISTVALUE() {
		JToolBarHelper::title(JText::_('COM_JOOCM_PROFILEFIELDLISTVALUEMANAGER'), 'profilefield.png');
		JToolBarHelper::custom('joocm_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOCM_JOOCM', false);
		JToolBarHelper::custom('joocm_profilefieldlistvalue_new', 'new.png', 'new_f2.png', 'COM_JOOCM_NEW', false);
		JToolBarHelper::custom('joocm_profilefieldlistvalue_edit', 'edit.png', 'edit_f2.png', 'COM_JOOCM_EDIT', false);
		JToolBarHelper::custom('joocm_profilefieldlistvalue_delete', 'delete.png', 'delete_f2.png', 'COM_JOOCM_DELETE', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/profile_manager#profile_field-list-values_list');
	}
	
	function _JOOCM_PROFILEFIELDLISTVALUE_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOCM_EDIT') : JText::_('COM_JOOCM_ADD');
			
		JToolBarHelper::title(JText::_('COM_JOOCM_PROFILEFIELDLISTVALUEMANAGER') .' - <span>'. $text.'</span>', 'profilefield.png');
		JToolBarHelper::custom('joocm_profilefieldlistvalue_apply', 'apply.png', 'apply_f2.png', 'COM_JOOCM_APPLY', false);				
		JToolBarHelper::custom('joocm_profilefieldlistvalue_save', 'save.png', 'save_f2.png', 'COM_JOOCM_SAVE', false);		
		JToolBarHelper::custom('joocm_profilefieldlistvalue_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOCM_CANCEL', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/profile_manager#profile_field-list-values_edit');
	}

	function _JOOCM_TERMS() {
		JToolBarHelper::title(JText::_('COM_JOOCM_TERMSMANAGER'), 'terms.png');
		JToolBarHelper::custom('joocm_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOCM_JOOCM', false);
		JToolBarHelper::custom('joocm_terms_new', 'new.png', 'new_f2.png', 'COM_JOOCM_NEW', false);
		JToolBarHelper::custom('joocm_terms_edit', 'edit.png', 'edit_f2.png', 'COM_JOOCM_EDIT', false);
		JToolBarHelper::custom('joocm_terms_delete', 'delete.png', 'delete_f2.png', 'COM_JOOCM_DELETE', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/terms_manager');
	}
	
	function _JOOCM_TERMS_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOCM_EDIT') : JText::_('COM_JOOCM_ADD');
			
		JToolBarHelper::title(JText::_('COM_JOOCM_TERMSMANAGER') .' - <span>'. $text.'</span>', 'terms.png');
		JToolBarHelper::custom('joocm_terms_apply', 'apply.png', 'apply_f2.png', 'COM_JOOCM_APPLY', false);				
		JToolBarHelper::custom('joocm_terms_save', 'save.png', 'save_f2.png', 'COM_JOOCM_SAVE', false);		
		JToolBarHelper::custom('joocm_terms_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOCM_CANCEL', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/terms_manager#terms_manager_edit');
	}

	function _JOOCM_AVATAR() {
		JToolBarHelper::title(JText::_('COM_JOOCM_AVATARMANAGER'), 'avatar.png');
		JToolBarHelper::custom('joocm_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOCM_JOOCM', false);
		JToolBarHelper::custom('joocm_avatar_new', 'new.png', 'new_f2.png', 'COM_JOOCM_NEW', false);
		JToolBarHelper::custom('joocm_avatar_edit', 'edit.png', 'edit_f2.png', 'COM_JOOCM_EDIT', false);
		JToolBarHelper::custom('joocm_avatar_delete', 'delete.png', 'delete_f2.png', 'COM_JOOCM_DELETE', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/avatar_manager');
	}
	
	function _JOOCM_AVATAR_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOCM_EDIT') : JText::_('COM_JOOCM_ADD');
			
		JToolBarHelper::title(JText::_('COM_JOOCM_AVATARMANAGER') .' - <span>'. $text.'</span>', 'avatar.png');
		JToolBarHelper::custom('joocm_avatar_apply', 'apply.png', 'apply_f2.png', 'COM_JOOCM_APPLY', false);				
		JToolBarHelper::custom('joocm_avatar_save', 'save.png', 'save_f2.png', 'COM_JOOCM_SAVE', false);		
		JToolBarHelper::custom('joocm_avatar_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOCM_CANCEL', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/avatar_manager#avatar_manager_edit');
	}

	function _JOOCM_INTERFACE() {
		JToolBarHelper::title(JText::_('COM_JOOCM_INTERFACEMANAGER'), 'interface.png');
		JToolBarHelper::custom('joocm_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOCM_JOOCM', false);
		JToolBarHelper::custom('joocm_interface_new', 'new.png', 'new_f2.png', 'COM_JOOCM_NEW', false);
		JToolBarHelper::custom('joocm_interface_edit', 'edit.png', 'edit_f2.png', 'COM_JOOCM_EDIT', false);
		JToolBarHelper::custom('joocm_interface_delete', 'delete.png', 'delete_f2.png', 'COM_JOOCM_DELETE', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/interface_manager');
	}
	
	function _JOOCM_INTERFACE_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOCM_EDIT') : JText::_('COM_JOOCM_ADD');
	
		JToolBarHelper::title(JText::_('COM_JOOCM_INTERFACEMANAGER') .' - <span>'. $text.'</span>', 'interface.png');
		JToolBarHelper::custom('joocm_interface_apply', 'apply.png', 'apply_f2.png', 'COM_JOOCM_APPLY', false);
		JToolBarHelper::custom('joocm_interface_save', 'save.png', 'save_f2.png', 'COM_JOOCM_SAVE', false);
		JToolBarHelper::custom('joocm_interface_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOCM_CANCEL', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/interface_manager#interface_manager_edit');
	}

	function _JOOCM_LINK() {
		JToolBarHelper::title(JText::_('COM_JOOCM_LINKMANAGER'), 'link.png');
		JToolBarHelper::custom('joocm_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOCM_JOOCM', false);
		JToolBarHelper::custom('joocm_link_new', 'new.png', 'new_f2.png', 'COM_JOOCM_NEW', false);
		JToolBarHelper::custom('joocm_link_edit', 'edit.png', 'edit_f2.png', 'COM_JOOCM_EDIT', false);
		JToolBarHelper::custom('joocm_link_delete', 'delete.png', 'delete_f2.png', 'COM_JOOCM_DELETE', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/link_manager');
	}

	function _JOOCM_LINK_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOCM_EDIT') : JText::_('COM_JOOCM_ADD');
	
		JToolBarHelper::title(JText::_('COM_JOOCM_LINKMANAGER') .' - <span>'. $text.'</span>', 'link.png');
		JToolBarHelper::custom('joocm_link_apply', 'apply.png', 'apply_f2.png', 'COM_JOOCM_APPLY', false);				
		JToolBarHelper::custom('joocm_link_save', 'save.png', 'save_f2.png', 'COM_JOOCM_SAVE', false);		
		JToolBarHelper::custom('joocm_link_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOCM_CANCEL', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/link_manager#link_manager_edit');		
	}

	function _JOOCM_USERSYNC() {
		JToolBarHelper::title(JText::_('COM_JOOCM_USERSYNCMANAGER'), 'usersync.png');
		JToolBarHelper::custom('joocm_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOCM_JOOCM', false);
		JToolBarHelper::custom('joocm_usersync_perform', 'apply.png', 'apply_f2.png', 'COM_JOOCM_PERFORM', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/user_synchronization_manager');
	}

	function _JOOCM_INSTALL() {
		JToolBarHelper::title(JText::_('COM_JOOCM_INSTALLATIONMANAGER'), 'installation.png');
		JToolBarHelper::custom('joocm_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOCM_JOOCM', false);
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/installation_manager');
	}

	function _JOOCM_CREDITS() {
		JToolBarHelper::title(JText::_('COM_JOOCM_JOOCMCREDITS'), 'credits.png');
		JToolBarHelper::custom('joocm_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOCM_JOOCM', false);
	}

	function _DEFAULT() {
		JToolBarHelper::title(JText::_('COM_JOOCM_JOOCM'), 'joocm.png');
		TOOLBAR_joocm::_appendHelpButton('http://www.joobb.org/documentation/joocm/control_panel');
	}
	
	function _appendHelpButton($url) {
		$toolBar = &JToolBar::getInstance('toolbar');
		
		$text	= JText::_('COM_JOOCM_HELP');

		$html	= "<a href=\"$url\" class=\"toolbar\" target=\"_blank\">\n";
		$html  .= "<span class=\"icon-32-help\" title=\"$text\"></span>\n";
 		$html  .= "$text\n";
		$html  .= "</a>\n";

		$toolBar->appendButton('Custom', $html);
	}
}
?>