<?php
/**
 * @version $Id: joobbtopic.php 131 2010-07-24 09:09:45Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
/**
 * Joobb Topic Table Class
 *
 * @package Joo!BB
 */
class JTableJoobbTopic extends JTable {
	/** @var int Unique id*/
	var $id					= null;
	/** @var string */
	var $views				= null;
	/** @var string */
	var $replies			= null;		
	/** @var string */
	var $status				= null;
	/** @var string */
	var $vote				= null;		
	/** @var string */
	var $type				= null;
	/** @var int */
	var $id_forum			= null;
	/** @var int */
	var $id_first_post		= null;
	/** @var int */
	var $id_last_post		= null;
	/** @var int */
	var $id_user			= null;
																		
	/**
	 * @param database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__joobb_topics', 'id', $db);
	}
}
?>