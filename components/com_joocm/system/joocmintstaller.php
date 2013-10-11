<?php
/**
 * @version $Id: joocmintstaller.php 145 2010-08-24 17:39:41Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Installer
 *
 * @package Joo!CM
 */
class JoocmInstaller
{

	/**
	 * xml document root
	 * @var object
	 */
	var $root = null;

	/**
	 * constructor
	 */
	function JoocmInstaller() {
	}
	
	/**
	 * get instance
	 *
	 * @access 	public
	 * @return object
	 */
	function &getInstance() {
	
		static $joocmInstaller;

		if (!is_object($joocmInstaller)) {
			$joocmInstaller = new JoocmInstaller();
		}

		return $joocmInstaller;
	}
	
	/**
	 * install plugins
	 */	
	function installPlugins($sourcePath) {

		// initialize variables
		if (!$this->root) {
			return false;
		}
		
		$plugins = $this->root->getElementByPath('plugins');
		if (!$plugins) {
			return false;
		}

		$elements = $plugins->children();
		foreach ($elements as $plugin) {
			$name = $plugin->attributes('plugin');
			$group = $plugin->attributes('group');

			// leave to next plugin if we will be not able to install the plugin
			if ($name == '' || $group == '') {
				continue;
			}
			
			if (is_dir($sourcePath.DS.'plugins'.DS.$group)) {

				// use the Joomla installer to do the work for us
				$installer = new JInstaller;
				if ($installer->install($sourcePath.DS.'plugins'.DS.$group)) {
							
					// initialize variables
					$db	=& JFactory::getDBO();
		
					// at least set our plugin as published
					$query = "UPDATE #__plugins SET published=1 WHERE element=".$db->Quote($name)." AND folder=".$db->Quote($group);
					$db->setQuery($query);
					$db->query();
				}
			}
		}
		
		return true;
	}

	/**
	 * install plugins
	 */	
	function uninstallPlugins($root) {

		// initialize variables
		$plugins = $root->getElementByPath('plugins');
		if (!$plugins) {
			return false;
		}	

		$elements = $plugins->children();
		foreach ($elements as $plugin) {
			$name = $plugin->attributes('plugin');
			$group = $plugin->attributes('group');

			// initialize variables
			$db = & JFactory::getDBO();
			$query = 'SELECT `id` FROM #__plugins WHERE element = '.$db->Quote($name).' AND folder = '.$db->Quote($group);
			$db->setQuery($query);
			
			$pluginIds = $db->loadResultArray();
			foreach ($pluginIds as $pluginId) {
				$installer = new JInstaller;
				$result = $installer->uninstall('plugin', $pluginId, 0);
			}
		}

		return true;
	}
	
	/**
	 * remove database
	 */
	function removeFiles() {

		// initialize variables
		if (!$this->root) {
			return false;
		}
		
		$remove = $this->root->getElementByPath('remove');
		if (!$remove) {
			return false;
		}

		$component = $remove->attributes('component');
		if ($component == '') {
			return false;
		}

		$elements = $remove->children();
		foreach ($elements as $element) {
			$paths = array();
			switch ($element->name()) {
				case 'admin':
					$basePath = JPATH_ADMINISTRATOR.DS.'components'.DS.$component.DS;
					break;
				case 'site':
					$basePath = JPATH_SITE.DS.'components'.DS.$component.DS;
					break;
				case 'path':
					$basePath = JPATH_SITE.DS;
					break;
				default:
					continue;
				break;
			}
			
			$paths = $element->children();
			foreach ($paths as $path) {
				$this->deletePath($basePath.$path->data());
			}
		}
	}
	
	/**
	 * delete path
	 */
    function deletePath($str){
		if (is_file($str)){
			return @unlink($str);
		} else if (is_dir($str)) {
			$scan = glob(rtrim($str,'/').'/*');
			foreach ($scan as $index=>$path) {
				$this->deletePath($path);
			}
			return @rmdir($str);
		}
    }

	/**
	 * execute sql file
	 */	
	function executeSQLFile($sqlFile) {

		// initialize variables
		$app =& JFactory::getApplication();
		
		// if the sql file doesn't exists then leave
		if (!file_exists($sqlFile)) {
			$app->enqueueMessage($sqlFile, 'error'); return false;
		}
		$buffer = file_get_contents($sqlFile);

		// leave if the buffer is empty
		if (!$buffer) {
			return false;
		}

		// split all queries to an array
		jimport('joomla.installer.helper');
		$queries = JInstallerHelper::splitSql($buffer);
		
		// leave if there are no queries
		if (count($queries) == 0) {
			return false;
		}

		// execute each of the queries
		foreach ($queries as $query) {
			$query = trim($query);
			if ($query != '' && $query{0} != '#') {
				if (!$this->executeQuery($query)) {
					return false;
				}
			}
		}
		
		return true;
	}
	
	/**
	 * execute query
	 */	
	function executeQuery($query) {

		// initialize variables
		$db	=& JFactory::getDBO();
		
		$db->setQuery($query);
		if (!$db->query()) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * set document root
	 */	
	function setDocumentRoot($xmlFile) {

		// initialize variables
		$xml =& JFactory::getXMLParser('Simple');

		if ($xml->loadFile($xmlFile)) {
			$this->root =& $xml->document;
		}
		
		unset($xml);
	}
}
?>