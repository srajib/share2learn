<?php
/**
 * @version $Id: joobbgeshi.php 135 2010-08-13 10:03:14Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(dirname( __FILE__ ).DL.'geshi'.DL.'geshi.php');

/**
 * Joo!BB Geshi
 *
 * @package Joo!BB
 */
class JoobbGeSHi extends GeSHi
{

	/**
	 * joobb geshi
	 */		
	function JoobbGeSHi() {
	}
	
	/**
	 * get joobb geshi object
	 *
	 * @access public
	 * @return object of JoobbGeSHi
	 */
	function &getInstance() {
		static $joobbGeSHi;

		if (!is_object($joobbGeSHi)) {
			$joobbGeSHi = new JoobbGeSHi();
		}

		return $joobbGeSHi;
	}
}
?>