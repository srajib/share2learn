<?php
/**
 * @version $Id: joobbuser.php 131 2010-07-24 09:09:45Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
/**
 * Joobb User Table Class
 *
 * @package Joo!BB
 */
class JTableJoobbUser extends JTable {
	/** @var int Unique id*/
	var $id					= null;
	/** @var int */
	var $posts				= null;
	/** @var int */
	var $role				= null;
	/** @var int */
	var $enable_bbcode		= null;
	/** @var int */
	var $enable_emotions	= null;
	/** @var int */
	var $auto_subscription	= null;		
								
	/**
	 * @param database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__joobb_users', 'id', $db);
	}
}
?>