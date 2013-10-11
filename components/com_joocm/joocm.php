<?php
/**
 * @version $Id: joocm.php 110 2010-05-20 18:20:16Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM
 *
 * @package Joo!CM
 */
require_once('include.php');

require_once(JOOCM_BASEPATH.DS.'controller'.DS.'joocm.php');

// create the controller
$controller = new JoocmController();

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