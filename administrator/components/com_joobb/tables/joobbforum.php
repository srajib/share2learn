<?php
/**
 * @version $Id: joobbforum.php 194 2010-10-25 21:33:22Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
/**
 * Joobb Forum Table Class
 *
 * @package Joo!BB
 */
class JTableJoobbForum extends JTable {
	/** @var int Unique id*/
	var $id					= null;
	/** @var string */
	var $name				= null;
	/** @var string */
	var $description		= null;
	/** @var int */
	var $status				= null;
	/** @var int */
	var $locked				= null;
	/** @var int */
	var $ordering			= null;
	/** @var int */
	var $new_posts_time		= null;
	/** @var int */
	var $posts				= null;
	/** @var int */
	var $topics				= null;
	/** @var int */
	var $auth_view			= null;
	/** @var int */
	var $auth_read			= null;
	/** @var int */
	var $auth_post			= null;
	/** @var int */
	var $auth_post_all		= null;
	/** @var int */
	var $auth_reply			= null;
	/** @var int */
	var $auth_reply_all		= null;
	/** @var int */
	var $auth_edit			= null;
	/** @var int */
	var $auth_edit_all		= null;
	/** @var int */
	var $auth_delete		= null;
	/** @var int */
	var $auth_delete_all	= null;
	/** @var int */
	var $auth_move			= null;
	/** @var int */
	var $auth_reportpost	= null;
	/** @var int */
	var $auth_sticky		= null;
	/** @var int */
	var $auth_lock			= null;
	/** @var int */
	var $auth_lock_all		= null;
	/** @var int */
	var $auth_announce		= null;
	/** @var int */
	var $auth_attachments	= null;
	/** @var int */
	var $checked_out		= null;
	/** @var datetime */
	var $checked_out_time	= null;
	/** @var int */
	var $id_cat				= null;
	/** @var int */
	var $id_last_post		= null;
														
	/**
	 * @param database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__joobb_forums', 'id', $db);
	}
}
?>