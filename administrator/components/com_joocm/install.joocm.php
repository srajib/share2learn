<?php
/**
 * @version $Id: install.joocm.php 144 2010-08-24 17:28:15Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// load components language
$language = &JFactory::getLanguage();
$language->load('com_joocm');

$document =& JFactory::getDocument();
$document->setMetadata('Refresh', '0; URL='.JURI::base().'index.php?option=com_joocm&task=joocm_install_install', true); ?>