<?php
/**
 * @version $Id: toolbar.joobb.html.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
 // no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Toolbar Html
 *
 * @package Joo!BB
 */
class TOOLBAR_joobb {

	function _JOOBB_FORUM() {
		JToolBarHelper::title(JText::_('COM_JOOBB_FORUMMANAGER'), 'forum.png');
		JToolBarHelper::custom('joobb_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOBB_JOOBB', false);
		JToolBarHelper::custom('joobb_forum_new', 'new.png', 'new_f2.png', 'COM_JOOBB_NEW', false);
		JToolBarHelper::custom('joobb_forum_edit', 'edit.png', 'edit_f2.png', 'COM_JOOBB_EDIT', false);
		JToolBarHelper::custom('joobb_forum_delete', 'delete.png', 'delete_f2.png', 'COM_JOOBB_DELETE', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/forum_manager');
	}
		
	function _JOOBB_FORUM_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOBB_EDIT') : JText::_('COM_JOOBB_ADD');
	
		JToolBarHelper::title(JText::_('COM_JOOBB_FORUMMANAGER') .' - <span>'. $text.'</span>', 'forum.png');
		JToolBarHelper::custom('joobb_forum_apply', 'apply.png', 'apply_f2.png', 'COM_JOOBB_APPLY', false);
		JToolBarHelper::custom('joobb_forum_save', 'save.png', 'save_f2.png', 'COM_JOOBB_SAVE', false);
		JToolBarHelper::custom('joobb_forum_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOBB_CANCEL', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/forum_manager#forum_manager_edit');
	}
	
	function _JOOBB_CATEGORY() {
		JToolBarHelper::title(JText::_('COM_JOOBB_CATEGORYMANAGER'), 'category.png');
		JToolBarHelper::custom('joobb_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOBB_JOOBB', false);
		JToolBarHelper::custom('joobb_category_new', 'new.png', 'new_f2.png', 'COM_JOOBB_NEW', false);
		JToolBarHelper::custom('joobb_category_edit', 'edit.png', 'edit_f2.png', 'COM_JOOBB_EDIT', false);
		JToolBarHelper::custom('joobb_category_delete', 'delete.png', 'delete_f2.png', 'COM_JOOBB_DELETE', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/category_manager');
	}
	
	function _JOOBB_CATEGORY_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOBB_EDIT') : JText::_('COM_JOOBB_ADD');
	
		JToolBarHelper::title(JText::_('COM_JOOBB_CATEGORYMANAGER') .' - <span>'. $text.'</span>', 'category.png');
		JToolBarHelper::custom('joobb_category_apply', 'apply.png', 'apply_f2.png', 'COM_JOOBB_APPLY', false);
		JToolBarHelper::custom('joobb_category_save', 'save.png', 'save_f2.png', 'COM_JOOBB_SAVE', false);
		JToolBarHelper::custom('joobb_category_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOBB_CANCEL', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/category_manager#category_manager_edit');
	}
		
	function _JOOBB_CONFIG() {
		JToolBarHelper::title(JText::_('COM_JOOBB_CONFIGMANAGER'), 'configuration.png');
		JToolBarHelper::custom('joobb_config_apply', 'apply.png', 'apply_f2.png', 'COM_JOOBB_APPLY', false);
		JToolBarHelper::custom('joobb_config_save', 'save.png', 'save_f2.png', 'COM_JOOBB_SAVE', false);
		JToolBarHelper::custom('joobb_config_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOBB_CANCEL', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/configuration');
	}

	function _JOOBB_TEMPLATE() {
		JToolBarHelper::title(JText::_('COM_JOOBB_TEMPLATEMANAGER'), 'template.png');
		JToolBarHelper::custom('joobb_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOBB_JOOBB', false);
		JToolBarHelper::custom('joobb_template_default', 'default.png', 'default_f2.png', 'COM_JOOBB_DEFAULT', false);
		JToolBarHelper::custom('joobb_template_edit', 'edit.png', 'edit_f2.png', 'COM_JOOBB_EDIT', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/template_manager');
	}
	
	function _JOOBB_TEMPLATE_EDIT() {
		JToolBarHelper::title(JText::_('COM_JOOBB_TEMPLATEMANAGER') .' - <span>'. JText::_('COM_JOOBB_EDIT') .'</span>', 'template.png');
		JToolBarHelper::custom('joobb_template_apply', 'apply.png', 'apply_f2.png', 'COM_JOOBB_APPLY', false);				
		JToolBarHelper::custom('joobb_template_save', 'save.png', 'save_f2.png', 'COM_JOOBB_SAVE', false);		
		JToolBarHelper::custom('joobb_template_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOBB_CANCEL', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/template_manager#template_manager_edit');
	}
	
	function _JOOBB_EMOTIONSET() {
		JToolBarHelper::title(JText::_('COM_JOOBB_EMOTIONSETMANAGER'), 'emotionset.png');
		JToolBarHelper::custom('joobb_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOBB_JOOBB', false);
		JToolBarHelper::custom('joobb_emotionset_default', 'default.png', 'default_f2.png', 'COM_JOOBB_DEFAULT', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/emotion-set_manager');
	}

	function _JOOBB_ICONSET() {
		JToolBarHelper::title(JText::_('COM_JOOBB_ICONSETMANAGER'), 'iconset.png');
		JToolBarHelper::custom('joobb_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOBB_JOOBB', false);
		JToolBarHelper::custom('joobb_iconset_default', 'default.png', 'default_f2.png', 'COM_JOOBB_DEFAULT', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/icon-set_manager');
	}
				
	function _JOOBB_USER() {
		JToolBarHelper::title(JText::_('COM_JOOBB_USERMANAGER'), 'joobbuser.png');	
		JToolBarHelper::custom('joobb_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOBB_JOOBB', false);
		JToolBarHelper::custom('joobb_user_new', 'new.png', 'new_f2.png', 'COM_JOOBB_NEW', false);
		JToolBarHelper::custom('joobb_user_edit', 'edit.png', 'edit_f2.png', 'COM_JOOBB_EDIT', false);
		JToolBarHelper::custom('joobb_user_delete', 'delete.png', 'delete_f2.png', 'COM_JOOBB_DELETE', false);
		JToolBarHelper::custom('joobb_user_logout', 'cancel.png', 'cancel_f2.png', 'COM_JOOBB_LOGOUT', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/user_manager');
	}
	
	function _JOOBB_USER_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOBB_EDIT') : JText::_('COM_JOOBB_ADD');

		JToolBarHelper::title(JText::_('COM_JOOBB_USERMANAGER') .' - <span>'. $text.'</span>', 'joobbuser.png');
		JToolBarHelper::custom('joobb_user_apply', 'apply.png', 'apply_f2.png', 'COM_JOOBB_APPLY', false);				
		JToolBarHelper::custom('joobb_user_save', 'save.png', 'save_f2.png', 'COM_JOOBB_SAVE', false);		
		JToolBarHelper::custom('joobb_user_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOBB_CANCEL', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/user_manager#user_manager_edit');
	}

	function _JOOBB_GROUP() {
		JToolBarHelper::title(JText::_('COM_JOOBB_GROUPMANAGER'), 'group.png');
		JToolBarHelper::custom('joobb_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOBB_JOOBB', false);
		JToolBarHelper::custom('joobb_group_new', 'new.png', 'new_f2.png', 'COM_JOOBB_NEW', false);
		JToolBarHelper::custom('joobb_group_edit', 'edit.png', 'edit_f2.png', 'COM_JOOBB_EDIT', false);
		JToolBarHelper::custom('joobb_group_delete', 'delete.png', 'delete_f2.png', 'COM_JOOBB_DELETE', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/group_manager');
	}
	
	function _JOOBB_GROUP_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOBB_EDIT') : JText::_('COM_JOOBB_ADD');
		
		JToolBarHelper::title(JText::_('COM_JOOBB_GROUPMANAGER') .' - <span>'. $text.'</span>', 'group.png');
		JToolBarHelper::custom('joobb_group_apply', 'apply.png', 'apply_f2.png', 'COM_JOOBB_APPLY', false);				
		JToolBarHelper::custom('joobb_group_save', 'save.png', 'save_f2.png', 'COM_JOOBB_SAVE', false);		
		JToolBarHelper::custom('joobb_group_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOBB_CANCEL', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/group_manager#group_manager_edit');
	}

	function _JOOBB_RANK() {
		JToolBarHelper::title(JText::_('COM_JOOBB_RANKMANAGER'), 'rank.png');
		JToolBarHelper::custom('joobb_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOBB_JOOBB', false);
		JToolBarHelper::custom('joobb_rank_new', 'new.png', 'new_f2.png', 'COM_JOOBB_NEW', false);
		JToolBarHelper::custom('joobb_rank_edit', 'edit.png', 'edit_f2.png', 'COM_JOOBB_EDIT', false);
		JToolBarHelper::custom('joobb_rank_delete', 'delete.png', 'delete_f2.png', 'COM_JOOBB_DELETE', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/rank_manager');
	}
	
	function _JOOBB_RANK_EDIT() {
		$cid = JRequest::getVar('cid', array(0) );
		$text = intval($cid[0]) ? JText::_('COM_JOOBB_EDIT') : JText::_('COM_JOOBB_ADD');
		
		JToolBarHelper::title(JText::_('COM_JOOBB_RANKMANAGER') .' - <span>'. $text.'</span>', 'rank.png');
		JToolBarHelper::custom('joobb_rank_apply', 'apply.png', 'apply_f2.png', 'COM_JOOBB_APPLY', false);				
		JToolBarHelper::custom('joobb_rank_save', 'save.png', 'save_f2.png', 'COM_JOOBB_SAVE', false);		
		JToolBarHelper::custom('joobb_rank_cancel', 'cancel.png', 'cancel_f2.png', 'COM_JOOBB_CANCEL', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/rank_manager#rank_manager_edit');
	}
										
	function _JOOBB_USERSYNC() {
		JToolBarHelper::title(JText::_('COM_JOOBB_USERSYNCMANAGER'), 'usersync.png');
		JToolBarHelper::custom('joobb_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOBB_JOOBB', false);
		JToolBarHelper::custom('joobb_usersync_perform', 'apply.png', 'apply_f2.png', 'COM_JOOBB_PERFORM', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/user_synchronization_manager');
	}

	function _JOOBB_INSTALL() {
		JToolBarHelper::title(JText::_('COM_JOOBB_INSTALLATIONMANAGER'), 'installation.png');
		JToolBarHelper::custom('joocm_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOBB_JOOBB', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/installation_manager');
	}
										
	function _JOOBB_SITEMAP() {
		JToolBarHelper::title(JText::_('COM_JOOBB_SITEMAPMANAGER'), 'sitemap.png');
		JToolBarHelper::custom('joobb_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOBB_JOOBB', false);
		JToolBarHelper::custom('joobb_sitemap_perform', 'apply.png', 'apply_f2.png', 'COM_JOOBB_PERFORM', false);
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/sitemap_manager');
	}

	function _JOOBB_CREDITS() {
		JToolBarHelper::title(JText::_('COM_JOOBB_JOOBBCREDITS'), 'credits.png');
		JToolBarHelper::custom('joobb_controlpanel', 'back.png', 'back_f2.png', 'COM_JOOBB_JOOBB', false);
	}
	
	function _DEFAULT() {
		JToolBarHelper::title(JText::_('COM_JOOBB_JOOBB'), 'joobb.png');
		TOOLBAR_joobb::_appendHelpButton('http://www.joobb.org/documentation/joobb/control_panel');
	}
	
	function _appendHelpButton($url) {
		$toolBar = &JToolBar::getInstance('toolbar');
		
		$text	= JText::_('COM_JOOBB_HELP');

		$html	= "<a href=\"$url\" class=\"toolbar\" target=\"_blank\">\n";
		$html  .= "<span class=\"icon-32-help\" title=\"$text\"></span>\n";
 		$html  .= "$text\n";
		$html  .= "</a>\n";

		$toolBar->appendButton('Custom', $html);
	}
}
?>