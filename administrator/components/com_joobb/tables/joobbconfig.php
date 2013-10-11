<?php
/**
 * @version $Id: joobbconfig.php 208 2012-02-20 07:04:33Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
/**
 * Joobb Config Table Class
 *
 * @package Joo!BB
 */
class JTableJoobbConfig extends JTable {
	/** @var int Unique id*/
	var $id						= null;
	/** @var int */
	var $template				= null;
	/** @var string */
	var $emotion_set			= null;
	/** @var string */
	var $icon_set				= null;
	/** @var string */
	var $editor					= null;
	/** @var int */
	var $topic_icon_function	= null;
	/** @var int */
	var $post_icon_function		= null;
	/** @var string */	
	var $board_settings			= null;
	/** @var string */	
	var $latestpost_settings	= null;
	/** @var string */
	var $feed_settings			= null;
	/** @var string */
	var $view_settings			= null;
	/** @var string */
	var $user_settings_defaults	= null;
	/** @var string */
	var $attachment_settings	= null;
	/** @var string */
	var $image_settings			= null;
	/** @var string */
	var $captcha_settings		= null;
	/** @var string */
	var $parse_settings			= null;
	/** @var int */
	var $checked_out			= null;
	/** @var datetime */
	var $checked_out_time		= null;
								
	/**
	 * @param database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__joobb_configs', 'id', $db);
	}
}
?>