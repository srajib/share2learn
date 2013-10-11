<?php
/**
 * @version $Id$
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementJoobbForum extends JElement
{
	function fetchElement($name, $value, &$node, $control_name) {

		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joobb'.DS.'tables');

		$db			=& JFactory::getDBO();
		$document	=& JFactory::getDocument();
		$fieldName	= $control_name.'['.$name.']';
		$joobbForum =& JTable::getInstance('JoobbForum');
		if ($value) {
			$joobbForum->load($value);
		} else {
			$joobbForum->name = JText::_('COM_JOOBB_SELECTFORUM');
		}

		$js = "
		function jSelectForum(forum, name, object) {
			document.getElementById(object + '_id').value = forum;
			document.getElementById(object + '_name').value = name;
			document.getElementById('sbox-window').close();
		}";
		$document->addScriptDeclaration($js);

		$link = 'index.php?option=com_joobb&amp;task=joobb_forumelement_view&amp;tmpl=component&amp;object='.$name;

		JHTML::_('behavior.modal', 'a.modal');
		$html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$name.'_name" value="'.htmlspecialchars($joobbForum->name, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('COM_JOOBB_SELECTFORUM').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">'.JText::_('COM_JOOBB_SELECT').'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.(int)$value.'" />';

		return $html;
	}
} ?>