<?php
/**
 * @version $Id: template.php 208 2012-02-20 07:04:33Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'template.php');

/**
 * Joo!BB Template Controller
 *
 * @package Joo!BB
 */
class ControllerTemplate extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joobb_template_view', 'showTemplates');
		$this->registerTask('joobb_template_edit', 'editTemplate');
		$this->registerTask('joobb_template_save', 'saveTemplate');
		$this->registerTask('joobb_template_apply', 'saveTemplate');
		$this->registerTask('joobb_template_cancel', 'cancelEditTemplate');
		$this->registerTask('joobb_template_default', 'defaultTemplate');
	}
	
	/**
	 * compiles a list of templates
	 */
	function showTemplates() {

		// initialize variables
		$app			=& JFactory::getApplication();
		$db				=& JFactory::getDBO();
		$context		= 'com_joobb.joobb_template_view';
		$limit			= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart		= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');

		// get default template
		$joobbConfig =& JoobbConfig::getInstance();
		$defaultTemplate = $joobbConfig->getTemplateFile();
		
		// list templates
		jimport('joomla.filesystem.folder');
		$templatesList = JFolder::folders(JOOBB_TEMPLATES);
		
		$rows = array();
		foreach ($templatesList as $templateFolder) {
			$fileList = JFolder::files(JOOBB_TEMPLATES.DS.$templateFolder, '.xml');
			foreach ($fileList as $templateFile) {
				if(!$data = JoobbHelper::parseXMLFile(JOOBB_TEMPLATES.DS.$templateFolder, $templateFile, 'template')){
					continue;
				} else {
					$data->default_template = ($data->file_name == $defaultTemplate);
					$rows[] = $data;
				}
			}
		}
		
		jimport('joomla.html.pagination');
		$pagination = new JPagination(count($rows), $limitstart, $limit);
		$rows = array_slice($rows, $pagination->limitstart, $pagination->limit);
		
		ViewTemplate::showTemplates($rows, $pagination);	
	}
	
	/**
	 * cancels edit template operation
	 */
	function cancelEditTemplate() {
		$this->setRedirect('index.php?option=com_joobb&task=joobb_template_view');
	}
	
	/**
	 * edit the template
	 */
	function editTemplate() {

		// initialize variables
		$db				=& JFactory::getDBO();
		$joobbConfig	=& JoobbConfig::getInstance();
		$cid			= JRequest::getVar('cid', array(0));
		
		if (!is_array($cid)) {
			$cid = array(0);
		}	

		if(!$data = JoobbHelper::parseXMLFile(JOOBB_TEMPLATES.DS.basename($cid[0], ".xml"), $cid[0], 'template')) {
			$this->setRedirect('index.php?option=com_joobb&task=joobb_template_view', sprintf(JText::_('COM_JOOBB_MSGFILENOTFOUND'), $cid[0]), 'error'); return;
		} else {
			$row = $data;
		}
		
		$lists = array();

		// build the html radio buttons for default template
		$lists['defaulttemplate'] = JHTML::_('select.booleanlist', 'defaulttemplate', '', ($row->file_name == $joobbConfig->getTemplateFile()));

		// list theme files
		$themePath = JOOBB_TEMPLATES.DS.basename($row->file_name, ".xml").DS.'themes';
		$themeList = JFolder::folders($themePath);
			
		$themes = array();
		foreach ($themeList as $themeFolder) {
			$fileList = JFolder::files($themePath.DS.$themeFolder, '.xml');
			foreach ($fileList as $themeFile) {
				if(!$data = JoobbHelper::parseXMLFile($themePath.DS.$themeFolder, $themeFile, 'theme')) {
					continue;
				} else {
					$themes[] = JHTML::_('select.option', $data->file_name, $data->name);
				}
			}
		}

		$lists['themes'] = JHTML::_('select.genericlist',  $themes, 'theme', 'class="inputbox" size="1"', 'value', 'text', $joobbConfig->getThemeFile());
			
		ViewTemplate::editTemplate($row, $lists);			
	}
	
	/**
	 * save the template
	 */	
	function saveTemplate() {

		// initialize variables
		$fileName = JRequest::getVar('file_name');
		
		if(!$template = JoobbHelper::parseXMLFile(JOOBB_TEMPLATES.DS.basename($fileName, ".xml"), $fileName, 'template')) {
			$this->setRedirect('index.php?option=com_joobb&task=joobb_template_view', sprintf(JText::_('COM_JOOBB_MSGFILENOTFOUND'), $fileName), 'error'); return;
		}
		
		// set the default template		
		if (JRequest::getVar('defaulttemplate')) {
			JoobbTemplate::setDefault($template->file_name);			
		}
		
		// set the template theme		
		if (JRequest::getVar('theme')) {
			JoobbTemplate::setTheme(JRequest::getVar('theme'));			
		}
										
		switch (JRequest::getCmd('task')) {
			case 'joobb_template_apply':
				$link = 'index.php?option=com_joobb&task=joobb_template_edit&cid[]='. $template->file_name .'&hidemainmenu=1';
				break;

			case 'joobb_template_save':
			default:
				$link = 'index.php?option=com_joobb&task=joobb_template_view';
				break;
		}

		$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOBB_TEMPLATE'), $template->name);
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * delete the template
	 */
	function deleteTemplete() {

		// ToDo: Source for delete the template
		
		$this->setRedirect('index.php?option=com_joobb&task=joobb_template_view');
	}
	
	/**
	 * set template as default
	 */	
	function defaultTemplate() {

		// initialize variables
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msg		= '';
		$msgType	= '';
		
		if (count($cid)) {

			// set the selected template to default
			JoobbTemplate::setDefault($cid[0]);	
		} else {
			$msg = JText::sprintf('COM_JOOBB_MSGNOSELECTION', JText::_('COM_JOOBB_TEMPLATE'), JText::_('COM_JOOBB_DEFAULT')); $msgType = 'notice';
		}

		$this->setRedirect('index.php?option=com_joobb&task=joobb_template_view', $msg, $msgType);
	}	
}
?>