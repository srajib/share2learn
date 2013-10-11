<?php
/**
 * @version $Id: editsettings.php 22 2009-12-25 20:07:22Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Edit Settings Model
 *
 * @package Joo!CM
 */
class JoocmModelEditSettings extends JoocmModel
{
	/**
	 * time formats data array
	 *
	 * @var array
	 */
	var $_timeFormats = null;

	/**
	 * get profile field sets
	 *
	 * @return array
	 */
	function getTimeFormats() {
				
		// load the profile field sets
		if (empty($this->_timeFormats)) {
			$db		=& JFactory::getDBO();
			$query = "SELECT f.*"
					. "\n FROM #__joocm_timeformats AS f"
					. "\n WHERE f.published = 1"
					. "\n ORDER BY f.name"
					;
			$db->setQuery($query);
			$this->_timeFormats = $db->loadObjectList();		
		}

		return $this->_timeFormats;
	}
}
?>