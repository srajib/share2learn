<?php
/**
 * @version $Id: editprofile.php 22 2009-12-25 20:07:22Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Edit Profile Model
 *
 * @package Joo!CM
 */
class JoocmModelEditProfile extends JoocmModel
{
	/**
	 * profile field sets data array
	 *
	 * @var array
	 */
	var $_profilefieldsets = null;
	
	/**
	 * profile fields data array
	 *
	 * @var array
	 */
	var $_profilefields = null;

	/**
	 * get profile field sets
	 *
	 * @return array
	 */
	function getProfileFieldSets() {
				
		// load the profile field sets
		if (empty($this->_profilefieldsets)) {
			$db		=& JFactory::getDBO();
			$query	= "SELECT s.*"
					. "\n FROM #__joocm_profiles_fields_sets AS s"
					. "\n WHERE s.published = 1"
					. "\n ORDER BY s.ordering"
					;
			$db->setQuery($query);
			$this->_profilefieldsets = $db->loadObjectList();		
		}

		return $this->_profilefieldsets;
	}
	
	/**
	 * get profile fields
	 *
	 * @return array
	 */
	function getProfileFields($joocmUser) {
				
		// load the profile fields
		if (empty($this->_profilefields)) {
			$db		=& JFactory::getDBO();
			$query	= "SELECT f.*"
					. "\n FROM #__joocm_profiles_fields AS f"
					. "\n WHERE f.published = 1"
					. "\n ORDER BY f.ordering"
					;
			$db->setQuery($query);
			$fieldrows = $db->loadObjectList();
			
			$fields = array();
			foreach($fieldrows as $fieldrow) {
				$fields[] = JoocmHelper::createElement($fieldrow, $joocmUser->get($fieldrow->name));
			}
			$this->_profilefields = $fields;		
		}
		return $this->_profilefields;
	}

}
?>