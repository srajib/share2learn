<?php
/**
 * @version $Id: joocminterface.php 94 2010-05-08 11:16:43Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
/**
 * Joocm Interface Table Class
 *
 * @package Joo!CM
 */
class JTableJoocmInterface extends JTable {
	/** @var int Unique id*/
	var $id					= null;
	/** @var string */
	var $name				= null;
	/** @var string */
	var $com				= null;
	/** @var string */
	var $com_icon			= null;
	/** @var string */
	var $url				= null;
	/** @var int */
	var $client				= null;
	/** @var int */
	var $show_restriction	= null;
	/** @var int */
	var $ordering			= null;
	/** @var int */
	var $published			= null;
	/** @var int */
	var $system				= null;
	/** @var int */
	var $checked_out		= null;
	/** @var datetime */
	var $checked_out_time	= null;
	
	/**
	 * @param database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__joocm_interfaces', 'id', $db);
	}
}
?>