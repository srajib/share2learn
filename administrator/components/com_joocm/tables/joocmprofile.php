<?php
/**
 * @version $Id: joocmprofile.php 48 2010-02-08 22:15:48Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
/**
 * Joocm Profile Table Class
 *
 * @package Joo!CM
 */
class JTableJoocmProfile extends JTable {
	/** @var int Unique id*/
	var $id	= null;
														
	/**
	 * @param database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__joocm_profiles', 'id', $db);
		
		// set dynamic fields
		$fields = JTableJoocmProfile::getProfileFields($db);
		foreach($fields as $field) {
			$fieldname = $field->name;
			$this->$fieldname = $field->default;
		}
	}
	
	/**
	 * @param database A database connector object
	 */
	function getProfileFields(&$db) {
		static $joocmProfileTable;
		
		if (empty($this->joocmProfileTable)) {
			$query = "SELECT f.*"
					. "\n FROM #__joocm_profiles_fields AS f"
					. "\n WHERE f.published = 1"
					. "\n ORDER BY f.ordering"
					;
			$db->setQuery($query);
			$joocmProfileTable = $db->loadObjectList();
		}
		return $joocmProfileTable;
	}	
}
?>