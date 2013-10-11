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
 * Joo!BB Move Topic View
 *
 * @package Joo!BB
 */
class JoobbViewMoveTopic extends JView
{

	function display($tpl = null) {

		// initialize variables
		$joobbAuth		=& JoobbAuth::getInstance();
		$topicId		= JRequest::getVar('topic', 0, '', 'int');

		if (!$topicId) {
			$this->messageQueue->addMessage(JText::_('COM_JOOBB_MSGREQUESTNOTPERFORMED'));
			$this->app->redirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $this->Itemid, false));
		}
		
		$model	=& $this->getModel();
		$topic = $model->getTopic($topicId);
		$this->assignRef('topic', $topic);
		$post = $model->getPost($topic->id_first_post);
		$this->assignRef('post', $post);
		
		if ($this->joobbUser->getRole($topic->id_forum) < 2) {
			$this->messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGNOPERMISSION'), JText::_('COM_JOOBB_MSGMOVETOPIC')));
			$this->app->redirect(JRoute::_('index.php?option=com_joobb&view=topic&topic='.$topicId.'&Itemid='. $this->Itemid, false));
		}
		
		$forums = $model->getForums($topic->id_forum);
		$this->assignRef('forums', $forums);
		
		// load form validation behavior
		JHTML::_('behavior.formvalidation');
		
		// handle page title
		$this->document->setTitle(JText::_('COM_JOOBB_MOVETOPIC'));
		
		// handle bread crumb
		$this->breadCrumbs->addBreadCrumb(JText::_('COM_JOOBB_MOVETOPIC'), '');
		
		$action = 'index.php?option=com_joobb&task=joobbmovetopic&topic='.$topicId.'&Itemid='. $this->Itemid;
		$this->assignRef('action', $action);
		
		// get buttons
		$joobbButtonSet	=& JoobbButtonSet::getInstance();
		$this->assignRef('buttonSubmit', $joobbButtonSet->buttonByFunction['buttonSubmit']);
		$this->assignRef('buttonCancel', $joobbButtonSet->buttonByFunction['buttonCancel']);
				
		parent::display($tpl);
	}
	
	function &getForum($index = 0) {
		$forum =& $this->forums[$index];
		return $forum;
	}
	
}
?>