<?php
/**
 * @version $Id: joocmconfig.php 90 2010-05-02 17:07:07Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
/**
 * Joocm Config Table Class
 *
 * @package Joo!CM
 */
class JTableJoocmConfig extends JTable {
	/** @var int Unique id*/
	var $id						= null;
	/** @var string */
	var $config_settings		= null;
	/** @var string */
	var $user_settings_defaults	= null;
	/** @var string */
	var $avatar_settings		= null;
	/** @var string */
	var $captcha_settings		= null;
	/** @var int */
	var $checked_out			= null;
	/** @var datetime */
	var $checked_out_time		= null;
	
	/**
	 * @param database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__joocm_configs', 'id', $db);
	}
}
?>