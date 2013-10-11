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

class JElementAsset extends JElement
{
	var	$_name = 'Asset';
	
	function fetchTooltip($label, $description, &$node, $control_name, $name) {
		return;	
	}
	
	function fetchElement( $name, $value, &$node, $control_name ) {
		$doc = &JFactory::getDocument();
		$script = JURI::root(true).'/modules/mod_freeslider_sp1/elements/script.js';	
		return $doc->addScript($script);
	}
	
} 
?>