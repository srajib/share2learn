<?php
/**
 * @version $Id: joocmavatar.php 48 2010-02-08 22:15:48Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
/**
 * Joocm Avatar Table Class
 *
 * @package Joo!CM
 */
class JTableJoocmAvatar extends JTable {
	/** @var int Unique id*/
	var $id						= null;
	/** @var string */
	var $avatar_file			= null;
	/** @var int */
	var $published				= null;	
	/** @var int */
	var $checked_out			= null;	
	/** @var datetime */
	var $checked_out_time		= null;
	/** @var int */
	var $id_user				= null;
								
	/**
	 * @param database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__joocm_avatars', 'id', $db);
	}
	
}
?>