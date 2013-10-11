<?php
/**
 * @version $Id: joobbattachment.php 56 2010-02-14 17:43:07Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
/**
 * Joobb Attachments Table Class
 *
 * @package Joo!BB
 */
class JTableJoobbAttachment extends JTable {
	/** @var int Unique id*/
	var $id					= null;
	/** @var string */
	var $file_name			= null;
	/** @var string */
	var $original_name		= null;
	/** @var int */
	var $hits				= null;
	/** @var datetime */
	var $date_upload		= null;
	/** @var int */
	var $id_post			= null;	
	/** @var int */
	var $id_user			= null;
														
	/**
	 * @param database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__joobb_attachments', 'id', $db);
	}
}
?>