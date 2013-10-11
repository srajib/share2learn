<?php
/**
 * @version $Id: mod_joobbposts.php 223 2012-02-27 18:46:44Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2012 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Posts Module
 *
 * @package Joo!BB
 */
require_once(dirname(__FILE__).DS.'helper.php');

// check if Joo!CM is installed. otherwise leave!
if (!is_file(JPATH_SITE.DS.'components'.DS.'com_joocm'.DS.'include.php')) {
	echo JText::_('MOD_JOOBB_POSTS_JOOCMNOTINSTALLED'); return;
}

require_once(JPATH_SITE.DS.'components'.DS.'com_joocm'.DS.'include.php');

// check if Joo!BB is installed. otherwise leave!
if (!is_file(JPATH_SITE.DS.'components'.DS.'com_joobb'.DS.'include.php')) {
	echo JText::_('MOD_JOOBB_POSTS_JOOBBNOTINSTALLED'); return;
}

require_once(JPATH_SITE.DS.'components'.DS.'com_joobb'.DS.'include.php');

// show the latest items module
$modJoobbPostHelper = new modJoobbPostHelper($params);
require(JModuleHelper::getLayoutPath('mod_joobbposts'));
?>