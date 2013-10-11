<?php
/**
 * @version $Id: toolbar.joocm.php 90 2010-05-02 17:07:07Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JApplicationHelper::getPath('toolbar_html'));

/**
 * Joo!CM Toolbar
 *
 * @package Joo!CM
 */

switch ($task) {				
	case 'joocm_config_view':
		TOOLBAR_joocm::_JOOCM_CONFIG();
		break;
		
	case 'joocm_user_view':
		TOOLBAR_joocm::_JOOCM_USER();
		break;	
	case 'joocm_user_new':
	case 'joocm_user_edit':
		TOOLBAR_joocm::_JOOCM_USER_EDIT();
		break;

	case 'joocm_profilefieldset_view':
		TOOLBAR_joocm::_JOOCM_PROFILEFIELDSET();
		break;
	case 'joocm_profilefieldset_new':
	case 'joocm_profilefieldset_edit':
		TOOLBAR_joocm::_JOOCM_PROFILEFIELDSET_EDIT();
		break;		

	case 'joocm_profilefield_view':
		TOOLBAR_joocm::_JOOCM_PROFILEFIELD();
		break;
	case 'joocm_profilefield_new':
	case 'joocm_profilefield_edit':
		TOOLBAR_joocm::_JOOCM_PROFILEFIELD_EDIT();
		break;		

	case 'joocm_profilefieldlist_view':
		TOOLBAR_joocm::_JOOCM_PROFILEFIELDLIST();
		break;
	case 'joocm_profilefieldlist_new':
	case 'joocm_profilefieldlist_edit':
		TOOLBAR_joocm::_JOOCM_PROFILEFIELDLIST_EDIT();
		break;		

	case 'joocm_profilefieldlistvalue_view':
		TOOLBAR_joocm::_JOOCM_PROFILEFIELDLISTVALUE();
		break;
	case 'joocm_profilefieldlistvalue_new':
	case 'joocm_profilefieldlistvalue_edit':
		TOOLBAR_joocm::_JOOCM_PROFILEFIELDLISTVALUE_EDIT();
		break;

	case 'joocm_terms_view':
		TOOLBAR_joocm::_JOOCM_TERMS();
		break;
	case 'joocm_terms_new':
	case 'joocm_terms_edit':
		TOOLBAR_joocm::_JOOCM_TERMS_EDIT();
		break;

	case 'joocm_timeformat_view':
		TOOLBAR_joocm::_JOOCM_TIMEFORMAT();
		break;
	case 'joocm_timeformat_new':
	case 'joocm_timeformat_edit':
		TOOLBAR_joocm::_JOOCM_TIMEFORMAT_EDIT();
		break;

	case 'joocm_interface_view':
		TOOLBAR_joocm::_JOOCM_INTERFACE();
		break;
	case 'joocm_interface_new':
	case 'joocm_interface_edit':
		TOOLBAR_joocm::_JOOCM_INTERFACE_EDIT();
		break;

	case 'joocm_avatar_view':
		TOOLBAR_joocm::_JOOCM_AVATAR();
		break;
	case 'joocm_avatar_new':
	case 'joocm_avatar_edit':
		TOOLBAR_joocm::_JOOCM_AVATAR_EDIT();
		break;

	case 'joocm_link_view':
		TOOLBAR_joocm::_JOOCM_LINK();
		break;
	case 'joocm_link_new':
	case 'joocm_link_edit':
		TOOLBAR_joocm::_JOOCM_LINK_EDIT();
		break;

	case 'joocm_usersync_view':
		TOOLBAR_joocm::_JOOCM_USERSYNC();
		break;

	case 'joocm_install_view':
		TOOLBAR_joocm::_JOOCM_INSTALL();
		break;

	case 'joocm_credits_view':
		TOOLBAR_joocm::_JOOCM_CREDITS();
		break;

	default:
		TOOLBAR_joocm::_DEFAULT();
		break;
}
?>