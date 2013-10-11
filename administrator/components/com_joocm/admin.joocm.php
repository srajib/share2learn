<?php
/**
 * @version $Id: admin.joocm.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

// Joo!CM system
require_once(JPATH_SITE.DS.'components'.DS.'com_joocm'.DS.'include.php');

// Backend
define('JOOCM_ADMINBASEPATH_LIVE', JURI::base().'components'.DL.'com_joocm');
define('JOOCM_ADMINCSS_LIVE', JOOCM_ADMINBASEPATH_LIVE.DL.'css');
define('JOOCM_ADMINIMAGES_LIVE', JOOCM_ADMINBASEPATH_LIVE.DL.'images');

define('JOOCM_ADMINBASEPATH', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joocm');
define('JOOCM_ADMINCONTROLLERS', JOOCM_ADMINBASEPATH.DS.'controllers');
define('JOOCM_ADMINPARAMS', JOOCM_ADMINBASEPATH.DS.'params');

// get the task
$task = JRequest::getCmd('task');

// Joo!CM controllers
jimport('joomla.application.component.controller');

// we just add the controller we need for this action
if (preg_match('/[_](.*)[_]/U', $task, $matches)) {
	require_once(JOOCM_ADMINCONTROLLERS.DS.$matches[1].'.php');
	$controllerName = 'Controller'.$matches[1];
} else {
	require_once(JOOCM_ADMINCONTROLLERS.DS.'joocm.php');
	$controllerName = 'ControllerJoocm';
}

$controller = new $controllerName();
$controller->execute($task);
$controller->redirect(); ?>
<br />
<div align="center">
	<?php echo JText::_('COM_JOOCM_COPYRIGHT'); ?> &#169; 2007 - <?php echo date("Y"); ?> <a href="http://www.joobb.org" target="_blank"><?php echo JText::_('COM_JOOCM_JOOBBPROJECT'); ?></a> - <?php echo JText::_('COM_JOOCM_ALLRIGHTSRESERVED'); ?>
</div>