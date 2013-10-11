<?php
/**
 * @version $Id: joobbpost.php 178 2010-10-03 10:07:39Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
/**
 * Joobb Post Table Class
 *
 * @package Joo!BB
 */
class JTableJoobbPost extends JTable {
	/** @var int Unique id*/
	var $id					= null;
	/** @var string */
	var $subject			= null;
	/** @var string */
	var $text				= null;		
	/** @var string */
	var $date_post			= null;
	/** @var string */
	var $date_last_edit		= null;
	/** @var int */
	var $id_user_last_edit	= null;
	/** @var int */
	var $enable_bbcode		= null;	
	/** @var int */
	var $enable_emotions	= null;			
	/** @var string */
	var $ip_poster			= null;
	/** @var int */
	var $icon_function		= null;	
	/** @var string */
	var $id_topic			= null;
	/** @var int */
	var $id_forum			= null;
	/** @var int */
	var $id_user			= null;
																
	/**
	 * @param database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__joobb_posts', 'id', $db);
	}
}
?>