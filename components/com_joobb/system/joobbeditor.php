<?php
/**
 * @version $Id: joobbeditor.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Editor
 *
 * @package Joo!BB
 */
class JoobbEditor extends JObservable
{
	/**
	 * editor plugin object
	 */
	var $_editor = null;


	/**
	 * editor plugin name
	 */
	var $_name = null;

	/**
	 * constructor
	 *
	 * @access protected
	 * @param string
	 */
	function __construct($editor = 'none') {
		$this->_name = $editor;
	}

	/**
	 * get instance of an editor
	 */
	function &getInstance($editor = 'none') {
		static $instances;

		if (!isset($instances)) {
			$instances = array();
		}

		$signature = serialize($editor);

		if (empty($instances[$signature])) {
			$instances[$signature] = new JoobbEditor($editor);
		}

		return $instances[$signature];
	}

	/**
	 * initialize the editor
	 */
	function init() {
	
		if(is_null(($this->_editor))) {
			return;
		}

		$args['event'] = 'onInit';

		$return = '';
		$results[] = $this->_editor->update($args);
		foreach ($results as $result) {
			if (trim($result)) {
				//$return .= $result;
				$return = $result;
			}
		}

		return $return;
	}

	/**
	 * present a text area
	 */
	function display($name, $class, $content, $width, $height, $col, $row, $params = array()) {
		$this->_loadEditor($params);

		// initialize variables
		$return = null;

		$args['name']		= $name;
		$args['class']		= $class;
		$args['content']	= $content;
		$args['width']		= $width;
		$args['height']		= $height;
		$args['col']		= $col;
		$args['row']		= $row;
		$args['event']		= 'onDisplay';

		$results[] = $this->_editor->update($args);

		foreach ($results as $result) {
			if (trim($result)) {
				$return .= $result;
			}
		}
		return $return;
	}

	/**
	 * save the editor content
	 */
	function save($editor) {
		$this->_loadEditor();

		$args[] = $editor;
		$args['event'] = 'onSave';

		$return = '';
		$results[] = $this->_editor->update($args);
		foreach ($results as $result) {
			if (trim($result)) {
				$return .= $result;
			}
		}
		return $return;
	}
	
	/**
	 * get the editor contents
	 */
	function getContent($editor) {
		$this->_loadEditor();

		$args['name'] = $editor;
		$args['event'] = 'onGetContent';

		$return = '';
		$results[] = $this->_editor->update($args);
		foreach ($results as $result) {
			if (trim($result)) {
				$return .= $result;
			}
		}
		return $return;
	}

	/**
	 * get the editor extended buttons
	 */
	function getButtons($editor) {
		$this->_loadEditor();

		$args['name'] = $editor;
		$args['event'] = 'onGetButtons';

		$return = '';
		$results[] = $this->_editor->update($args);
		foreach ($results as $result) {
			if (trim($result)) {
				$return .= $result;
			}
		}

		return $return;
	}
	
	function getEmotions($editor) {
		$this->_loadEditor();

		$args['name'] = $editor;
		$args['event'] = 'onGetEmotions';

		$return = '';
		$results[] = $this->_editor->update($args);
		foreach ($results as $result) {
			if (trim($result)) {
				$return .= $result;
			}
		}

		return $return;
	}

	/**
	 * set the editor contents
	 */
	function setContent($editor, $html) {
		$this->_loadEditor();

		$args['name'] = $editor;
		$args['html'] = $html;
		$args['event'] = 'onSetContent';

		$return = '';
		$results[] = $this->_editor->update($args);
		foreach ($results as $result) {
			if (trim($result)) {
				$return .= $result;
			}
		}
		return $return;
	}

	/**
	 * load the editor
	 */
	function _loadEditor($config = array()) {
		if(!is_null(($this->_editor))) {
			return;
		}
		
		$messageQueue 	=& JoobbMessageQueue::getInstance();
		
		// path to editor plugin
		$editorFile = JPATH_SITE.DS.'plugins'.DS.'editors-bb'.DS.$this->_name.'.php';

		// warning if the file doesn´t exist and exit
		if (!is_file($editorFile)) {
			$messageQueue->addMessage(JText::_('COM_JOOBB_MSGNOBBEDITOR'));
			return false;
		}

		// require plugin file
		require_once($editorFile);
		
		// Get the plugin
		$plugin   =& JPluginHelper::getPlugin('editors-bb', $this->_name);
		$params   = new JParameter($plugin->params);
		$params->loadArray($config);
		$plugin->params = $params;
		
		// build editor plugin classname
		$name = 'plgEditor'.$this->_name;

		$this->_editor = new $name($this, (array)$plugin);
	}

}
?>