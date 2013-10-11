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
 * Joo!BB Search View
 *
 * @package Joo!BB
 */
class JoobbViewSearch extends JView
{

	function display($tpl = null) {

		// initialize variables
		$searchWords = JRequest::getString('searchwords', null, 'get');
		$searchWords = trim($searchWords);
		$searchWords = strtolower($searchWords);
		$this->assignRef('searchWords', $searchWords);

		// request variables
		$limit		= JRequest::getVar('limit', $this->joobbConfig->getBoardSettings('search_results_per_page'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		
		$model	=& $this->getModel();
		$posts	= new JoobbPost($model->getSearchResults($searchWords, $limitstart, $limit));
		$this->assignRef('posts', $posts);
		$this->assignRef('total', $model->getTotal());

		// load form validation behavior
		JHTML::_('behavior.formvalidation');
		
		// handle page title
		$this->document->setTitle($this->joobbConfig->getBoardSettings('board_name') .' - '. JText::_('COM_JOOBB_SEARCH'));
		
		// handle metadata
		$this->document->setDescription(JText::sprintf('COM_JOOBB_SEARCHMETADESC', $this->joobbConfig->getBoardSettings('board_name')));
		$this->document->setMetadata('keywords', JText::_('COM_JOOBB_SEARCH'));
		
		// handle bread crumb
		$this->breadCrumbs->addBreadCrumb(JText::_('COM_JOOBB_SEARCH'), '');

		// handle pagination
		$showPagination = false;
		if ($this->total > $limit) {
			jimport('joomla.html.pagination');
			$this->pagination = new JPagination($this->total, $limitstart, $limit);
			$showPagination = true;
		}
		$this->assign('showPagination', $showPagination);

		// get buttons
		$joobbButtonSet	=& JoobbButtonSet::getInstance();
		$this->assignRef('buttonSearch', $joobbButtonSet->buttonByFunction['buttonSearch']);
		$this->assignRef('actionSearch', JRoute::_('index.php?option=com_joobb&view=search&Itemid='.$this->Itemid));
		
		$this->assign('attachmentPath', $this->joobbConfig->getAttachmentSettings('attachment_path'));
		
		parent::display($tpl);
	}
}
?>