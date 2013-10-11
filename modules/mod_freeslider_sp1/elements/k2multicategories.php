<?php
/*
# ------------------------------------------------------------------------
# Free Slide SP1 - Slideshow module for Joomla 1.5
# ------------------------------------------------------------------------
# Copyright (C) 2010 JoomShaper.com. All Rights Reserved.
# @license - GNU/GPL, see LICENSE.php,
# Author: JoomShaper.com
# Websites:  http://www.joomshaper.com -  http://www.joomxpert.com
# ------------------------------------------------------------------------
*/
// access denied
defined('JPATH_BASE') or die();
 
class JElementK2MultiCategories extends JElement
{
	var	$_name = 'K2MultiCategories';

	function fetchElement($name, $value, &$node, $control_name){
		$db = &JFactory::getDBO();

		$query = 'SELECT m.* FROM #__k2_categories m WHERE published=1 AND trash = 0 ORDER BY parent, ordering';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		if (count($mitems)) {
		$children = array();
		if ($mitems){
			foreach ( $mitems as $v ){
				$pt 	= $v->parent;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		$mitems = array();

		foreach ( $list as $item ) {
			$mitems[] = JHTML::_('select.option',  $item->id, '&nbsp;&nbsp;&nbsp;'.$item->treename );
		}
			$output= JHTML::_('select.genericlist',  $mitems, ''.$control_name.'['.$name.'][]', 'class="inputbox" style="width:90%;" multiple="multiple" size="10"', 'value', 'text', $value );
		} else {
			$mitems[] = JHTML::_('select.option', 0, 'K2 is not installed or there is no K2 category available.');
			$output= JHTML::_('select.genericlist',  $mitems, ''.$control_name.'['.$name.'][]', 'class="inputbox" style="width:90%;" disabled="disabled" ', 'value', 'text', $value );
		}
		return $output;
	}
}