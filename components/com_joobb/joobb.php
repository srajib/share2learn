<?php
/**
 * @version $Id: joobb.php 110 2010-05-20 18:20:16Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB
 *
 * @package Joo!BB
 */
require_once('include.php');

require_once(JOOBB_BASEPATH.DS.'controller'.DS.'joobb.php');

// create the controller
$controller = new JoobbController();

// we only want register the task we need
$task = JRequest::getVar('task', '');
if (isset($tasks[$task])) {
	$controller->registerTask($task, $tasks[$task]);
}

// perform the request task
$controller->execute($task);

// redirect if set by the controller
$controller->redirect();
?>