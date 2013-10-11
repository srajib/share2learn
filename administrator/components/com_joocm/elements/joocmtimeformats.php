<?php
/**
 * @version $Id: joocmtimeformats.php 48 2010-02-08 22:15:48Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Time Formats Element
 *
 * @package Joo!CM
 */
class JElementJoocmTimeFormats extends JElement
{

	function fetchElement($name, $value, &$node, $control_name) {
		$db = &JFactory::getDBO();

		$query = "SELECT tf.*"
				. "\n FROM #__joocm_timeformats AS tf"
				. "\n ORDER BY tf.name"
				;
		$db->setQuery($query);

		return JHTML::_('select.genericlist',  $db->loadObjectList(), ''.$control_name.'['.$name.']', 'class="inputbox"', 'timeformat', 'name', $value, $control_name.$name );
	}

}
?>