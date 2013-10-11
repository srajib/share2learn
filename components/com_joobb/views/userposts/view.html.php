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
 * Joo!BB User Posts View
 *
 * @package Joo!BB
 */
class JoobbViewUserPosts extends JView
{

	function display($tpl = null) {

		// initialize variables
		$joocmConfig =& JoocmConfig::getInstance();
		$userId = JRequest::getVar('id', 0, '', 'int');
		$joobbUserPosts	=& JoobbUser::getInstance($userId);
		$this->assignRef('joobbUserPosts', $joobbUserPosts);

		// request variables
		$limit = JRequest::getVar('limit', $this->joobbConfig->getBoardSettings('posts_per_page'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
				
		$model	=& $this->getModel();
		$posts	= new JoobbPost($model->getUserPosts($userId, $limitstart, $limit));
		$this->assignRef('posts', $posts);
		$this->assignRef('total', $model->getTotal());
		
		// get user name
		if ($joocmConfig->getConfigSettings('show_user_as') == 0) {
			$userName = $joobbUserPosts->get('name');
		} else {
			$userName = $joobbUserPosts->get('username');
		}
	
		// handle page title
		$this->document->setTitle(JText::sprintf('COM_JOOBB_USERPOSTSTITLE', $userName, $this->joobbConfig->getBoardSettings('board_name')));
		
		// handle metadata
		$this->document->setDescription(JText::sprintf('COM_JOOBB_USERPOSTSTITLE', $userName, $this->joobbConfig->getBoardSettings('board_name')));
		$this->document->setMetadata('keywords', $userName .', '. JText::_('COM_JOOBB_POSTS'));
				
		// handle bread crumb
		$this->breadCrumbs->addBreadCrumb(JText::sprintf('COM_JOOBB_POSTSBY', $userName));
		
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
}
?>