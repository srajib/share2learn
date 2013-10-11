<?php
/**
 * @version $Id: joocmlink.php 48 2010-02-08 22:15:48Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
/**
 * Joocm Link Table Class
 *
 * @package Joo!CM
 */
class JTableJoocmLink extends JTable {
	/** @var int Unique id*/
	var $id					= null;
	/** @var string */
	var $name				= null;
	/** @var string */
	var $com				= null;
	/** @var string */
	var $url				= null;
	/** @var string */
	var $function			= null;
	/** @var string */
	var $replacement		= null;	
	/** @var int */
	var $published			= null;
	/** @var int */
	var $checked_out		= null;	
	/** @var datetime */
	var $checked_out_time	= null;		
	
	/**
	 * @param database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__joocm_links', 'id', $db);
	}
}
?>