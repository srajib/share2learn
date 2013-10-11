<?php
/**
 * @version $Id: credits.php 132 2010-07-26 11:50:33Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'credits.php');

/**
 * Joo!CM Credits Controller
 *
 * @package Joo!CM
 */
class ControllerCredits extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joocm_credits_view', 'showCredits');
	}
	
	/**
	 * shows credits 
	 */
	function showCredits() {
		ViewCredits::showCredits( );
	}
}
?>