<?php
/**
 * @version $Id: sitemap.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'sitemap.php');

/**
 * Joo!BB Sitemap Controller
 *
 * @package Joo!BB
 */
class ControllerSitemap extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joobb_sitemap_view', 'showSitemap');
		$this->registerTask('joobb_sitemap_perform', 'performSitemap');
	}
	
	/**
	 * shows sitemap
	 */
	function showSitemap() {

		// initialize variables
		$db				=& JFactory::getDBO();
		$joobbConfig	=& JoobbConfig::getInstance();
		
		// parameter list
		$lists = array();
		
		$priority = array();
		$priority[] = JHTML::_('select.option', 'none', JText::_('COM_JOOBB_NONE'));
		$priority[] = JHTML::_('select.option', 'autodetect', JText::_('COM_JOOBB_AUTODETECT'));
		$lists['priority'] = JHTML::_('select.genericlist',  $priority, 'priority', 'class="inputbox" size="1"', 'value', 'text', 'autodetect');

		$changeFreq = array();
		$changeFreq[] = JHTML::_('select.option', 'none', JText::_('COM_JOOBB_NONE'));
		$changeFreq[] = JHTML::_('select.option', 'autodetect', JText::_('COM_JOOBB_AUTODETECT'));
		$changeFreq[] = JHTML::_('select.option', 'always', JText::_('COM_JOOBB_ALWAYS'));
		$changeFreq[] = JHTML::_('select.option', 'hourly', JText::_('COM_JOOBB_HOURLY'));
		$changeFreq[] = JHTML::_('select.option', 'daily', JText::_('COM_JOOBB_DAILY'));
		$changeFreq[] = JHTML::_('select.option', 'weekly', JText::_('COM_JOOBB_WEEKLY'));
		$changeFreq[] = JHTML::_('select.option', 'monthly', JText::_('COM_JOOBB_MONTHLY'));
		$changeFreq[] = JHTML::_('select.option', 'yearly', JText::_('COM_JOOBB_YEARLY'));
		$changeFreq[] = JHTML::_('select.option', 'never', JText::_('COM_JOOBB_NEVER'));

		$lists['changefreq'] = JHTML::_('select.genericlist',  $changeFreq, 'changefreq', 'class="inputbox" size="1"', 'value', 'text', 'autodetect');

		ViewSitemap::showSitemap($lists);	
	}
	
	/**
	 * perform sitemap
	 */	
	function performSitemap() {
		
		// initialize variables
		$fileName = JRequest::getVar('filename', '');
		$priority = JRequest::getVar('priority', '');
		$changeFreq = JRequest::getVar('changefreq', '');
		$limit = JRequest::getVar('limit', 0, '', 'int');
		$msgType = '';
		
		$joobbSitemap =& JoobbSitemap::getInstance();
		
		if ($joobbSitemap->createSitemap($fileName, $priority, $changeFreq, $limit)) {
			$msg = JText::_('COM_JOOBB_MSGSITEMAPCREATESUCCESS');
		} else {
			$msg = JText::_('COM_JOOBB_MSGSITEMAPCREATEFAILED'); $msgType = 'error';
		}

		$this->setRedirect('index.php?option=com_joobb&task=joobb_sitemap_view', $msg, $msgType);
	}
}
?>