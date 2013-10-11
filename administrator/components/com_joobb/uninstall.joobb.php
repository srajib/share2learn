<?php
/**
 * @version $Id: uninstall.joobb.php 144 2010-08-24 17:28:15Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// include joocm
require_once(JPATH_SITE.DS.'components'.DS.'com_joocm'.DS.'include.php');

// install plugins
$joocmInstaller =& JoocmInstaller::getInstance();
$joocmInstaller->uninstallPlugins($this->manifest); ?>