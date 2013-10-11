<?php
/**
 * @version $Id: joobbeditorstyles.php 144 2010-08-24 17:28:15Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

define('JOOBB_EDITOR_BASEPATH', JPATH_SITE.DS.'plugins'.DS.'editors-bb'.DS.'joobb');

/**
 * Joo!BB Editor Style Element
 *
 * @package Joo!BB
 */
class JElementJoobbEditorStyles extends JElement
{

	function fetchElement($name, $value, &$node, $control_name) {

		jimport('joomla.filesystem.folder');
				
		$rows = array();
		$fileList = JFolder::files(JOOBB_EDITOR_BASEPATH.DS.'css', '.css');
		foreach ($fileList as $styleFile) {
			$rows[] = JHTML::_('select.option', $styleFile, basename($styleFile, ".css"), 'stylefile', 'stylefilename');;
		}

		return JHTML::_('select.genericlist',  $rows, ''.$control_name.'['.$name.']', 'class="inputbox"', 'stylefile', 'stylefilename', $value, $control_name.$name);
	}

}