<?php
/**
 * @version $Id: joobb.php 199 2010-11-14 10:17:17Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

define('JOOBB_EDITOR_BASEPATH_LIVE', JURI::root().'plugins'.DL.'editors-bb'.DL.'joobb');
define('JOOBB_EDITOR_IMAGES_LIVE', JOOBB_EDITOR_BASEPATH_LIVE.DL.'images');
define('JOOBB_EDITOR_BASEPATH', JPATH_SITE.DS.'plugins'.DS.'editors-bb'.DS.'joobb');

jimport('joomla.plugin.plugin');

JPlugin::loadLanguage('plg_editors-bb_joobb');

require_once(JOOBB_EDITOR_BASEPATH.DS.'joobbeditorhtml.php');

/**
 * Joo!BB Editor Plugin
 *
 * @package Joo!BB
 */
class plgEditorJoobb extends JPlugin {

	/**
	 * constructor
	 */
	function plgEditorJoobb(&$subject, $config) {
		parent::__construct($subject, $config);
	}

	/**
	 * init editor
	 */
	function onInit() {
		$txt =	"<script type=\"text/javascript\">
					function insertAtCursor(myField, myValue) {
						if (document.selection) {
							// IE support
							myField.focus();
							sel = document.selection.createRange();
							sel.text = myValue;
						} else if (myField.selectionStart || myField.selectionStart == '0') {
							// MOZILLA/NETSCAPE support
							var startPos = myField.selectionStart;
							var endPos = myField.selectionEnd;
							myField.value = myField.value.substring(0, startPos)
								+ myValue
								+ myField.value.substring(endPos, myField.value.length);
						} else {
							myField.value += myValue;
						}
					}
				</script>";
		return $txt;
	}

	/**
	 * copy editor content to form field
	 */
	function onSave($editor) {
		return;
	}

	/**
	 * get the editor content
	 */
	function onGetContent($editor) {
		return "document.getElementById('$editor').value;\n";
	}

	/**
	 * set the editor content
	 */
	function onSetContent($editor, $content) {
		return "document.getElementById('$editor').value = $content;\n";
	}

	/**
	 * display the editor
	 */
	function onDisplay($name, $class, $content, $width, $height, $col, $row) {
		if (is_numeric($width)) {
			$width .= 'px';
		}
		if (is_numeric($height)) {
			$height .= 'px';
		}
		return "<textarea name=\"$name\" class= \"$class\" id=\"$name\" cols=\"$col\" rows=\"$row\" style=\"width: $width; height: $height;\">$content</textarea>";
	}
	
	function onGetButtons($name) {
		$document = & JFactory::getDocument();
		$document->addStyleSheet(JOOBB_EDITOR_BASEPATH_LIVE.DL.'css'.DL.$this->params->get('editor_style', 'joobbeditor_blue.css'));
		$document->addScript(JOOBB_EDITOR_BASEPATH_LIVE.DL.'js'.DL.'jscolor.js');
		$document->addScript(JOOBB_EDITOR_BASEPATH_LIVE.DL.'js'.DL.'joobb.js');

		return JoobbEditorHtml::getButtons($name, $this->params); 
	}
	
	function onGetEmotions($name) {
		return JoobbEditorHtml::getEmotions($name, $this->params); 
	}
	
	function onGetInsertMethod($name) {
		$document = & JFactory::getDocument();

		$js= "\tfunction jInsertEditorText(text) {
			insertAtCursor(document.adminForm.".$name.", text);
		}";
		$document->addScriptDeclaration($js);
		return true;
	}
}
?>