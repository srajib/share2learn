<?php
/**
 * @version $Id: helper.php 23 2009-12-25 20:12:05Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2012 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Categories Element
 *
 * @package Joo!BB Posts
 */
class JElementJoobbCategories extends JElement
{

	function fetchElement($name, $value, &$node, $control_name) {
		$db = &JFactory::getDBO();

		$query = "SELECT c.*"
				. "\n FROM #__joobb_categories AS c"
				. "\n ORDER BY c.ordering"
				;
		$db->setQuery($query);

		return JHTML::_('select.genericlist',  $db->loadObjectList(), ''.$control_name.'['.$name.'][]', 'class="inputbox" size="5" multiple="multiple"', 'id', 'name', $value, $control_name.$name);
	}
} ?>