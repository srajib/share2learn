<?php
/**
 * @version $Id: credits.php 134 2010-08-13 08:03:09Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'credits.php');

/**
 * Joo!BB Credits Controller
 *
 * @package Joo!BB
 */
class ControllerCredits extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joobb_credits_view', 'showCredits');
	}
	
	/**
	 * shows credits 
	 */
	function showCredits() {
		ViewCredits::showCredits( );
	}
}
?>