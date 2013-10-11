<?php
/**
 * @version $Id: toolbar.joobb.php 190 2010-10-21 17:56:57Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JApplicationHelper::getPath('toolbar_html'));

/**
 * Joo!BB Toolbar
 *
 * @package Joo!BB
 */

switch ($task) {
	case 'joobb_forum_view':
		TOOLBAR_joobb::_JOOBB_FORUM();
		break;		
	case 'joobb_forum_new':
	case 'joobb_forum_edit':
		TOOLBAR_joobb::_JOOBB_FORUM_EDIT();
		break;

	case 'joobb_category_view':
		TOOLBAR_joobb::_JOOBB_CATEGORY();
		break;			
	case 'joobb_category_new':
	case 'joobb_category_edit':
		TOOLBAR_joobb::_JOOBB_CATEGORY_EDIT();
		break;

	case 'joobb_config_view':
		TOOLBAR_joobb::_JOOBB_CONFIG();
		break;

	case 'joobb_template_view':
		TOOLBAR_joobb::_JOOBB_TEMPLATE();
		break;
	case 'joobb_template_edit':
		TOOLBAR_joobb::_JOOBB_TEMPLATE_EDIT();
		break;

	case 'joobb_emotionset_view':
		TOOLBAR_joobb::_JOOBB_EMOTIONSET();
		break;

	case 'joobb_buttonset_view':
		TOOLBAR_joobb::_JOOBB_BUTTONSET();
		break;

	case 'joobb_iconset_view':
		TOOLBAR_joobb::_JOOBB_ICONSET();
		break;

	case 'joobb_user_view':
		TOOLBAR_joobb::_JOOBB_USER();
		break;	
	case 'joobb_user_new':
	case 'joobb_user_edit':
		TOOLBAR_joobb::_JOOBB_USER_EDIT();
		break;

	case 'joobb_group_view':
		TOOLBAR_joobb::_JOOBB_GROUP();
		break;
	case 'joobb_group_new':
	case 'joobb_group_edit':
		TOOLBAR_joobb::_JOOBB_GROUP_EDIT();
		break;		

	case 'joobb_rank_view':
		TOOLBAR_joobb::_JOOBB_RANK();
		break;
	case 'joobb_rank_new':
	case 'joobb_rank_edit':
		TOOLBAR_joobb::_JOOBB_RANK_EDIT();
		break;		

	case 'joobb_usersync_view':
		TOOLBAR_joobb::_JOOBB_USERSYNC();
		break;

	case 'joobb_install_view':
		TOOLBAR_joobb::_JOOBB_INSTALL();
		break;

	case 'joobb_sitemap_view':
		TOOLBAR_joobb::_JOOBB_SITEMAP();
		break;

	case 'joobb_credits_view':
		TOOLBAR_joobb::_JOOBB_CREDITS();
		break;

	default:
		TOOLBAR_joobb::_DEFAULT();
		break;
}
?>