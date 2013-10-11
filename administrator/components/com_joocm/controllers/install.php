<?php
/**
 * @version $Id: install.php 227 2012-03-04 19:55:53Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'install.php');

/**
 * Joo!CM Install Controller
 *
 * @package Joo!CM
 */
class ControllerInstall extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joocm_install_view', 'showInstall');
		$this->registerTask('joocm_install_install', 'install');
		$this->registerTask('joocm_install_reinstall', 'performInstall');
	}
	
	/**
	 * shows installation view
	 */
	function showInstall() {

		// initialize variables
		$db	=& JFactory::getDBO();
		
		// get database status
		$db->setQuery("SHOW TABLE STATUS LIKE '%joocm%'");
		$rows = $db->loadObjectList();

		// generate list of done updates 
		$query = "SELECT u.*"
				. "\n FROM #__joocm_updates AS u"
				. "\n WHERE u.status <> 0"
				;
		$db->setQuery($query);
		$updateRows = $db->loadObjectList();

		// parameter list
		$lists = array();

		// current installed version
		$lists['version'] = JoocmHelper::getVersion(JOOCM_ADMINBASEPATH.DS.'install.joocm.xml');
		
		// available version
		$lists['available_version'] = JoocmHelper::getAvailableVersion('www.joobb.org', 'http://www.joobb.org/version/joocm_version.txt');
		
		ViewInstall::showInstall($updateRows, $rows, $lists);	
	}

	/**
	 * install
	 */
	function install() {

		// initialize variables
		$db				=& JFactory::getDBO();
		$joocmInstaller =& JoocmInstaller::getInstance();
		
		// set install document root of the xml file
		$joocmInstaller->setDocumentRoot(JOOCM_ADMINBASEPATH.DS.'install.joocm.xml');
			
		// get database status
		$db->setQuery("SHOW TABLE STATUS LIKE '%joocm%'");
		$rows = $db->loadObjectList();
		
		// perform install or update action
		if (count($rows)) {
			$joocmInstaller->removeFiles();
			ControllerInstall::performUpdate();
		} else {
			ControllerInstall::performInstall();
		}
	}
	
	/**
	 * perform install
	 */
	function performInstall() {

		// initialize variables
		$app			=& JFactory::getApplication();
		$db				=& JFactory::getDBO();
		$joocmInstaller =& JoocmInstaller::getInstance();
		
		// drop all tables
		if ($joocmInstaller->executeSQLFile(JOOCM_ADMINBASEPATH.DS.'uninstall.joocm.sql')) {
		
			// create all tables and fill initial data
			if ($joocmInstaller->executeSQLFile(JOOCM_ADMINBASEPATH.DS.'install.joocm.sql')) {
			
				// update the installation date
				$query = "UPDATE #__joocm_updates"
						. "\n SET date_install = ".$db->Quote(gmdate("Y-m-d H:i:s"))
						. "\n WHERE status = 1"
						;
				$db->setQuery($query);
	
				if ($db->query()) {
					$app->enqueueMessage(JText::_('COM_JOOCM_MSGINSTALLSUCCESS'));
				} else {
					$app->enqueueMessage($db->getErrorMsg(), 'error');
					$app->enqueueMessage(JText::_('COM_JOOCM_MSGINSTALLFAILED'), 'error');
				}
			} else {
				$app->enqueueMessage($db->getErrorMsg(), 'error');
				$app->enqueueMessage(JText::_('COM_JOOCM_MSGINSTALLFAILED'), 'error');
			}
		} else {
			$app->enqueueMessage($db->getErrorMsg(), 'error');
			$app->enqueueMessage(JText::_('COM_JOOCM_MSGINSTALLFAILED'), 'error');
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_install_view');
	}
	
	/**
	 * perform update
	 */	
	function performUpdate() {

		// initialize variables
		$db				=& JFactory::getDBO();
		$app			=& JFactory::getApplication();
		$joocmInstaller =& JoocmInstaller::getInstance();

		// generate list of done updates 
		$query = "SELECT u.update_file"
				. "\n FROM #__joocm_updates AS u"
				;
		$db->setQuery($query);
		$updateList = $db->loadResultArray();

		// generate available update list
		jimport('joomla.filesystem.folder');
		$fileList = JFolder::files(JOOCM_ADMINBASEPATH.DS.'updates', '.sql');

		for ($i=0, $n=count($fileList); $i < $n; $i++) {
			if (!in_array($fileList[$i], $updateList)) {
				if ($joocmInstaller->executeSQLFile(JOOCM_ADMINBASEPATH.DS.'updates'.DS.$fileList[$i])) {
					$query = "UPDATE #__joocm_updates"
							. "\n SET status = 2, date_install = ".$db->Quote(gmdate("Y-m-d H:i:s"))
							. "\n WHERE update_file = '$fileList[$i]'"
							;
					$db->setQuery($query);
//$app->enqueueMessage($query, 'error');
					if ($db->query()) {
						$status = 1;
					} else {
						$status = 0;
					}
				} else {
					$status = 0;
				}
			}
		}

// ToDo: Quickhack for 1.0.0 Phobos Stable ONLY.
// remove...	
		// update the installation date
		$query = "UPDATE #__joocm_updates"
				. "\n SET date_install = ".$db->Quote(gmdate("Y-m-d H:i:s"))
				. "\n WHERE status = 1"
				;
		$db->setQuery($query);
		$db->query();
// ...remove

		if ($status == 0) {
			$app->enqueueMessage($db->getErrorMsg(), 'error');
			$app->enqueueMessage(JText::_('COM_JOOCM_MSGUPDATEFAILED'), 'error');
		} else {
			$app->enqueueMessage(JText::_('COM_JOOCM_MSGUPDATESUCCESS'));
		}

		$this->setRedirect('index.php?option=com_joocm&task=joocm_install_view');
	}
}
?>