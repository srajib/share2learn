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
 * Joo!BB Topic View
 *
 * @package Joo!BB
 */
class JoobbViewTopic extends JView
{

	function display($tpl = null) {

		// initialize variables
		$topicId = JRequest::getVar('topic', 0, '', 'int');
		
		if (!$topicId) {
			$this->messageQueue->addMessage(JText::_('COM_JOOBB_MSGREQUESTNOTPERFORMED'));
			$this->app->redirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $this->Itemid, false));
		}
		
		$model =& $this->getModel();
		$topic = $model->getTopic($topicId);

		if (!isset($topic)) {
			$this->messageQueue->addMessage(JText::_('COM_JOOBB_MSGREQUESTNOTPERFORMED'));
			$this->app->redirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $this->Itemid, false));			
		}

		$this->assignRef('topic', $topic);
		$forum = $model->getForum($topic->id_forum);
		
		// check if user can read this topic
		if ($forum->auth_read > $this->joobbUser->getRole($forum->id)) {
			$this->messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGNOPERMISSION'), JText::_('COM_JOOBB_MSGREADTOPIC')));
			$this->app->redirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $this->Itemid, false));
		}		
		
		$firstPost = $model->getFirstPost($topic->id_first_post);
		$this->assignRef('firstpost' , $firstPost);
		
		// request variables
		$limit		= JRequest::getVar('limit', $this->joobbConfig->getBoardSettings('posts_per_page'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');

		// handle page title
		$this->document->setTitle($forum->name.' - '.$firstPost->subject);
		
		// handle metadata
		$this->document->setDescription(JoocmHTML::_($forum->name.' - '.$firstPost->subject.' - '.substr($firstPost->text, 0, 50).'...'));
		$this->document->setMetadata('keywords', str_replace(' ', ', ', $firstPost->subject));
		
		// increment hit			
		$model->incrementHit($topic->id);
		
		// set data model
		$posts	= new JoobbPost($this->get('posts'));
		$this->assignRef('posts', $posts);

		// handle bread crumb
		$redirect = 'index.php?option=com_joobb&view=forum&forum='.$forum->id.'&Itemid='.$this->Itemid;
		$this->breadCrumbs->addBreadCrumb($forum->name, $redirect);
		$this->breadCrumbs->addBreadCrumb($firstPost->subject, '');
		
		// total posts
		$this->assignRef('total', $this->get('total'));
			
		$showPagination = false;
		if ($this->total > $limit) {
			$showPagination = true;
		}
		$this->assign('showPagination', $showPagination);
			
		jimport('joomla.html.pagination');
		$this->pagination = new JPagination($this->total, $limitstart, $limit);

		$joobbButtonSet	=& JoobbButtonSet::getInstance();
		
		$buttonNewPost = $joobbButtonSet->buttonByFunction['buttonPostReply'];
		$buttonNewPost->href = JRoute::_('index.php?option=com_joobb&view=editpost&topic='.$topic->id.'&post=0&Itemid='.$this->Itemid);		
		$this->assign('buttonNewPost', $buttonNewPost);
		
		$joobbAuth =& JoobbAuth::getInstance();
		$guestTime = $this->joobbConfig->getBoardSettings('guest_time') * 60;

		// lock topic authentification
		$buttonLockTopicToggle = null;
		if ($joobbAuth->getAuth('auth_lock', $topic->id_forum)) {
			if ($this->joobbUser->get('id') == $firstPost->id_user && $firstPost->id_user != 0 || 
				$firstPost->id_user == 0 && $firstPost->ip_poster == $_SERVER['REMOTE_ADDR'] && (strtotime(gmdate("Y-m-d H:i:s")) - strtotime($firstPost->date_post)) < $guestTime ||
				$joobbAuth->getAuth('auth_lock_all', $topic->id_forum)) {
				
				if (!$forum->locked || $joobbAuth->getAuth('auth_lock_all', $topic->id_forum)) {
					if ($topic->status != 1) {
						$buttonLockTopicToggle = $joobbButtonSet->buttonByFunction['buttonLockTopic'];
						$buttonLockTopicToggle->href = JRoute::_('index.php?option=com_joobb&task=joobblocktopic&topic='.$topic->id.'&Itemid='.$this->Itemid);
					} else {
						$buttonLockTopicToggle = $joobbButtonSet->buttonByFunction['buttonUnlockTopic'];
						$buttonLockTopicToggle->href = JRoute::_('index.php?option=com_joobb&task=joobbunlocktopic&topic='.$topic->id.'&Itemid='.$this->Itemid);
					}
				}
			}
		}
		$this->assign('buttonLockTopicToggle', $buttonLockTopicToggle);
		
		// move topic authentification
		$buttonMoveTopic = null;
		if ($joobbAuth->getAuth('auth_move', $topic->id_forum)) {
			$buttonMoveTopic = $joobbButtonSet->buttonByFunction['buttonMoveTopic'];
			$buttonMoveTopic->href = JRoute::_('index.php?option=com_joobb&view=movetopic&topic='.$topic->id.'&Itemid='.$this->Itemid);	
		}
		$this->assign('buttonMoveTopic', $buttonMoveTopic);

		$this->assign('attachmentPath', $this->joobbConfig->getAttachmentSettings('attachment_path'));
		
		$this->assign('sortByPost', JHTML::_('select.genericlist', JoobbHelper::getSortByPostOptions(), 'sortByPost', 'class="inputbox" size="1"', 'value', 'text', 'date_post'));
		
		$this->assign('orderBy', JHTML::_('select.genericlist', JoobbHelper::getOrderByOptions(), 'orderBy', 'class="inputbox" size="1"', 'value', 'text', 'ASC'));
		
		$buttonGo = $joobbButtonSet->buttonByFunction['buttonGo'];
		$buttonGo->href = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$topic->id.'&Itemid='.$this->Itemid);
		$this->assign('buttonGo', $buttonGo);
		
		parent::display($tpl);
	}
}
?>