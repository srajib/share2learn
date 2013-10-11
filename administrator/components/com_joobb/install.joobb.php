<?php
/**
 * @version $Id: install.joobb.php 144 2010-08-24 17:28:15Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// include joocm
require_once(JPATH_SITE.DS.'components'.DS.'com_joocm'.DS.'include.php');

// install plugins
$joocmInstaller =& JoocmInstaller::getInstance();
$joocmInstaller->setDocumentRoot(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joobb'.DS.'install.joobb.xml');
$joocmInstaller->installPlugins($this->parent->getPath('source'));

// load components language
$language = &JFactory::getLanguage();
$language->load('com_joobb');

$document =& JFactory::getDocument();
$document->setMetadata('Refresh', '0; URL='.JURI::base().'index.php?option=com_joobb&task=joobb_install_install', true); ?>