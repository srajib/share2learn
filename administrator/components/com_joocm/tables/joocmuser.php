<?php
/**
 * @version $Id: joocmuser.php 189 2010-10-19 13:08:35Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
/**
 * Joocm User Table Class
 *
 * @package Joo!CM
 */
class JTableJoocmUser extends JTable {
	/** @var int Unique id*/
	var $id					= null;
	/** @var int */
	var $views_count		= null;
	/** @var int */
	var $system_emails		= null;
	/** @var int */
	var $agreed_terms		= null;
	/** @var int */
	var $hide				= null;
	/** @var int */
	var $show_email			= null;
	/** @var int */
	var $show_online_state	= null;
	/** @var string */
	var $id_avatar			= null;
	/** @var string */
	var $signature			= null;
	/** @var string */
	var $time_format		= null;

	/**
	 * @param database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__joocm_users', 'id', $db);
	}
}
?>