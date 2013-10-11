<?php
/**
 * @version $Id: joobbheader.php 177 2010-10-02 10:23:14Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB JooBB Header
 *
 * @package Joo!BB
 */
class JElementJoobbHeader extends JElement
{

	function fetchTooltip($label, $description, &$node, $control_name, $name) {
		return '';
	}

	function fetchElement($name, $value, &$node, $control_name) {
		if ($value) {
			return '<p style="background: #f6f6f6; color: #666666; padding:5px; border-bottom: 1px solid #e9e9e9; border-right: 1px solid #e9e9e9;"><strong>' . JText::_($value) . '</strong></p>';
		} else {
			return '<hr />';
		}
	}
} ?>