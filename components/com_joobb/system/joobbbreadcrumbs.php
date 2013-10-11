<?php
/**
 * @version $Id: joobbbreadcrumbs.php 135 2010-08-13 10:03:14Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Bread Crumbs
 *
 * @package Joo!BB
 */
class JoobbBreadCrumbs
{
	/**
	 * bread crumbs data
	 *
	 * @var array
	 */
	var $_breadCrumbs = null;
	
	/**
	 * bread crumb max length
	 *
	 * @var integer
	 */
	var $_maxLength;
	
	function JoobbBreadCrumbs() {

		// initialize variables
		$joobbConfig =& JoobbConfig::getInstance();
		$this->_maxLength = $joobbConfig->getBoardSettings('breadcrumb_max_length');
	}
	
	/**
	 * get instance
	 *
	 * @access public
	 * @return object
	 */
	function &getInstance() {
	
		static $joobbBreadCrumbs;

		if (!is_object($joobbBreadCrumbs)) {
			$joobbBreadCrumbs = new JoobbBreadCrumbs();
		}

		return $joobbBreadCrumbs;
	}
		
	function addBreadCrumb($name, $href = '') {

		if (strlen($name) > $this->_maxLength) {
			$name = substr($name, 0, $this->_maxLength).'...';
		}

		$breadCrumb->name = $name;
		$breadCrumb->href = $href;
		$this->_breadCrumbs[] = $breadCrumb;
	}
			
	function getBreadCrumbs() {
		return $this->_breadCrumbs;
	}
						
}
?>