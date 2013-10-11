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
 
class JElementMultiCategories extends JElement
{
	var	$_name = 'MultiCategories';

	function fetchElement($name, $value, &$node, $control_name){
        // Construct an array of the HTML OPTION statements.
        $options = array ();
        // creating database instance	
        $db =& JFactory::getDBO();
        // generating query
		$db->setQuery("SELECT c.title AS name, c.id AS id FROM #__categories AS c WHERE c.published = 1 AND c.section NOT LIKE 'com_%'");
 		// getting results
   		$results = $db->loadObjectList();
		if(count($results)){
			foreach ($results as $option)
			{
				$options[] = JHTML::_('select.option', $option->id, JText::_($option->name));
			}
			
			$output= JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]', 'class="inputbox" style="width:90%;" multiple="multiple" size="10"', 'value', 'text', $value );
			
		} else {
			$output='<strong>There is no category available.</strong>';		
		}
		
		return $output;
		
	}
}