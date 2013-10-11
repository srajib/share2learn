<?php
/**
 * @version $Id: admin.joobb.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

// check if Joo!CM is installed. otherwise leave!
if (!is_file(JPATH_SITE.DS.'components'.DS.'com_joocm'.DS.'include.php')) {
	$app =& JFactory::getApplication();
	$app->redirect('index.php?option=com_installer', JText::_('COM_JOOBB_MSGJOOCMNOTINSTALLED'), 'notice');
}

// include joocm
require_once(JPATH_SITE.DS.'components'.DS.'com_joocm'.DS.'include.php');

// include joobb
require_once(JPATH_SITE.DS.'components'.DS.'com_joobb'.DS.'include.php');

// Backend
define('JOOBB_ADMINBASEPATH_LIVE', JURI::base().'components'.DL.'com_joobb');
define('JOOBB_ADMINCSS_LIVE', JOOBB_ADMINBASEPATH_LIVE.DL.'css');
define('JOOBB_ADMINIMAGES_LIVE', JOOBB_ADMINBASEPATH_LIVE.DL.'images');

define('JOOBB_ADMINBASEPATH', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joobb');
define('JOOBB_ADMINCONTROLLERS', JOOBB_ADMINBASEPATH.DS.'controllers');
define('JOOBB_ADMINPARAMS', JOOBB_ADMINBASEPATH.DS.'params');

// get the task
$task = JRequest::getCmd('task');

// JooBB controllers
jimport('joomla.application.component.controller');

// we just add the controller we need for this action
if (preg_match('/[_](.*)[_]/U', $task, $matches)) {
	require_once(JOOBB_ADMINCONTROLLERS.DS.$matches[1].'.php');
	$controllerName = 'Controller'.$matches[1];
} else {
	require_once(JOOBB_ADMINCONTROLLERS.DS.'joobb.php');
	$controllerName = 'ControllerJoobb';
}

$document =& JFactory::getDocument();
$document->addStyleSheet(JOOBB_ADMINCSS_LIVE.DL.'icon.css');

$controller = new $controllerName();
$controller->execute($task);
$controller->redirect(); ?>
<br />
<div align="center">
	<?php echo JText::_('COM_JOOBB_COPYRIGHT'); ?> &#169; 2007 - <?php echo date("Y"); ?> <a href="http://www.joobb.org" target="_blank"><?php echo JText::_('COM_JOOBB_JOOBBPROJECT'); ?></a> - <?php echo JText::_('COM_JOOBB_ALLRIGHTSRESERVED'); ?>
</div>