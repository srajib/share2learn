<?php
/**
 * @version $Id: main.php 103 2010-05-17 17:05:37Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Main Model
 *
 * @package Joo!CM
 */
class JoocmModelMain extends JoocmModel
{
	/**
	 * interface data array
	 *
	 * @var array
	 */
	var $_interface = null;
	
	function getInterfaces() {
				
		// load the profile field sets
		if (empty($this->_interface)) {
			$db		=& JFactory::getDBO();
			$query = "SELECT i.*"
					. "\n FROM #__joocm_interfaces AS i"
					. "\n WHERE i.client = 0"
					. "\n AND i.published = 1"
					. "\n ORDER BY i.ordering"
					;
			$db->setQuery($query);
			$this->_interface = $db->loadObjectList();		
		}

		return $this->_interface;
	}
}
?>