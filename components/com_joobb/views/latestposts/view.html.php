<?php
/**
 * @version $Id: view.html.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * Joo!BB Latest Posts View
 *
 * @package Joo!BB
 */
class JoobbViewLatestPosts extends JView
{

	function display($tpl = null) {

		// initialize variables
		$latestPostLinks = array();
		$latestPostLinks = $this->_getLatestPostOptions('hours');
		$latestPostLinks = array_merge($latestPostLinks, $this->_getLatestPostOptions('days'));
		$latestPostLinks = array_merge($latestPostLinks, $this->_getLatestPostOptions('weeks'));
		$latestPostLinks = array_merge($latestPostLinks, $this->_getLatestPostOptions('months'));
		$latestPostLinks = array_merge($latestPostLinks, $this->_getLatestPostOptions('years'));

		$this->assignRef('latestPostLinks', $latestPostLinks);
		
		$hours = JRequest::getVar('hours', 2, '', 'int');
		$this->assignRef('hours', $hours);

		// request variables
		$limit = JRequest::getVar('limit', $this->joobbConfig->getBoardSettings('posts_per_page'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		
		// enable latest posts filter
		$this->assignRef('enableFilter', $this->joobbConfig->getLatestPostSettings('enable_filter'));
		
		$model	=& $this->getModel();
		$posts	= new JoobbPost($model->getLatestPosts($this->enableFilter, $limitstart, $limit));
		$this->assignRef('posts', $posts);
		$this->assignRef('total', $model->getTotal());
		
		// get the board name
		$boardName = $this->joobbConfig->getBoardSettings('board_name');
		
		// handle page title
		$this->document->setTitle($boardName .' - '. JText::_('COM_JOOBB_LATESTPOSTS'));

		// handle metadata
		$this->document->setDescription(JText::sprintf('COM_JOOBB_LATESTPOSTSMETADESC', $boardName));
		$this->document->setMetadata('keywords', JText::_('COM_JOOBB_POSTS'). ', '. $boardName);
		
		// handle bread crumb
		$this->breadCrumbs->addBreadCrumb(JText::_('COM_JOOBB_LATESTPOSTS'), '');
		
		// handle pagination
		$showPagination = false;
		if ($this->total > $limit) {
			jimport('joomla.html.pagination');
			$this->pagination = new JPagination($this->total, $limitstart, $limit);
			$showPagination = true;
		}
		$this->assign('showPagination', $showPagination);
		
		$this->assign('attachmentPath', $this->joobbConfig->getAttachmentSettings('attachment_path'));

		parent::display($tpl);
	}
	
	function _getLatestPostOptions($var) {
		$latestPostLinks = array();
		
		$array = explode(',', $this->joobbConfig->getLatestPostSettings('latest_post_'.$var));
		foreach ($array as $value) {
			$latestPostLink = new StdClass();
			$latestPostLink->href = 'index.php?option=com_joobb&view=latestposts&'.$var.'='.$value.'&Itemid='.$this->Itemid;
			$latestPostLink->text = $value .' '. $this->_getLatestPostOptionName($var, $value);
			$latestPostLinks[] = $latestPostLink;
		}
		
		return $latestPostLinks;	
	}
	
	function _getLatestPostOptionName($var, $value) {
		$optionName = '';
		
		switch ($var) {
			case 'hours':
				$optionName = ($value == 1) ? JText::_('COM_JOOBB_HOUR') : JText::_('COM_JOOBB_HOURS');
				break;
			case 'days':
				$optionName = ($value == 1) ? JText::_('COM_JOOBB_DAY') : JText::_('COM_JOOBB_DAYS');
				break;
			case 'weeks':
				$optionName = ($value == 1) ? JText::_('COM_JOOBB_WEEK') : JText::_('COM_JOOBB_WEEKS');
				break;
			case 'months':
				$optionName = ($value == 1) ? JText::_('COM_JOOBB_MONTH') : JText::_('COM_JOOBB_MONTHS');
				break;
			case 'years':
				$optionName = ($value == 1) ? JText::_('COM_JOOBB_YEAR') : JText::_('COM_JOOBB_YEARS');
				break;
			default:
				$optionName = $var;
				break;
		}
		
		return $optionName;	
	}

}
?>