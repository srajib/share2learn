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
 * Joo!BB Edit Topic View
 *
 * @package Joo!BB
 */
class JoobbViewEditTopic extends JView
{

	function display($tpl = null) {

		// initialize variables
		$joobbAuth =& JoobbAuth::getInstance();
		$model =& $this->getModel();
		
		// assign topic
		$this->assignRef('topic', $model->getTopic(JRequest::getVar('topic', 0, '', 'int')));
 
		// can we catch a forum where the topic should be saved to?
		$forumId = JRequest::getVar('forum', 0, '', 'int');
		if ($forumId == 0) {
			$forumId = $this->topic->id_forum;
			if ($forumId == 0) {
				$this->messageQueue->addMessage(JText::_('COM_JOOBB_MSGFORUMNOTSPECIFIED'));
				$this->app->redirect(JRoute::_('index.php?option=com_joobb&view=main&Itemid='.$this->Itemid, false));
			}
		}

		// assign post
		$this->assignRef('post', $model->getPost(JRequest::getVar('post', 0, '', 'int')));
	
		// assign forum
		$this->assignRef('forum', $model->getForum($forumId));

		// determine authentification
		if ($this->topic->id) {
			$authTopic = 'auth_edit'; $authTopicAll = 'auth_edit_all';
		} else {
			$authTopic = 'auth_post'; $authTopicAll = 'auth_post_all';
		}

		$canPost = false;
		if ($joobbAuth->getAuth($authTopic, $this->forum->id)) {
			$guestTime = $this->joobbConfig->getBoardSettings('guest_time') * 60;
			
			// We have now general permission, but only when...
			if ($this->topic->id == 0 ||																	// ... we are about to create a new topic
					$this->joobbUser->get('id') == $this->post->id_user && $this->post->id_user != 0 ||		// ... or we are editing our own topic
					$this->post->id_user == 0 && $this->post->ip_poster == $_SERVER['REMOTE_ADDR'] && 
					(strtotime(gmdate("Y-m-d H:i:s")) - strtotime($this->post->date_post)) < $guestTime ||	// ... or we are editing our own topic as guest
					$joobbAuth->getAuth($authTopicAll, $this->forum->id)) {									// ... or we have all permissions
				
				// if the forum is locked...
				if ($this->forum->locked) {
					
					// ... and we do not have special permision...
					if (!$joobbAuth->getAuth($authTopicAll, $this->forum->id)) {
						
						// ... then we are leaving back to forum
						$this->messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGFORUMLOCKED'), $this->forum->name));
						$this->app->redirect(JRoute::_('index.php?option=com_joobb&view=forum&forum='. $this->forum->id .'&Itemid='.$this->Itemid, false));
					}
				}
				$canPost = true;
			}
		}

		if (!$canPost) {
			if ($this->topic->id) {
				$link = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$this->topic->id.'&Itemid='.$this->Itemid, false);
				$this->messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGNOPERMISSION'), JText::_('COM_JOOBB_MSGEDITTOPIC')));
			} else {
				$link = JRoute::_('index.php?option=com_joobb&view=forum&forum='.$this->forum->id.'&Itemid='.$this->Itemid, false);
				$this->messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGNOPERMISSION'), JText::_('COM_JOOBB_MSGPOSTTOPIC')));
			}
			$this->app->redirect($link);
		}
		
		// restore post
		$session =& JFactory::getSession();
		$restorePost = $session->get('joobbPost');
		$session->set('joobbPost', null);
		
		if (isset($restorePost)) {
			$this->post->subject = $restorePost['subject'];
			$this->post->text = $restorePost['text'];
			$this->post->icon_function = $restorePost['icon_function'];
			$this->post->enable_emotions = $restorePost['enable_emotions'];
			$this->post->enable_bbcode = $restorePost['enable_bbcode'];		
		}
		
		// preview topic
		$session =& JFactory::getSession();
		$joobbPreviewPost = $session->get('joobbPreviewPost');
		$session->set('joobbPreviewPost', null);
		
		if (isset($joobbPreviewPost)) {
			$this->post->subject = $joobbPreviewPost['subject'];
			$this->post->text = $joobbPreviewPost['text'];
			$this->post->icon_function = $joobbPreviewPost['icon_function'];
			$this->post->enable_emotions = $joobbPreviewPost['enable_emotions'];
			$this->post->enable_bbcode = $joobbPreviewPost['enable_bbcode'];
			
			$postPreview =& JoobbHelper::getPostPreview($this->post);			
		} else {
			$postPreview = null;
		}
		$this->assignRef('postPreview', $postPreview);

		// disable guest name as default
		$this->assign('enableGuestName', 0);
		
		// this is only allowed when guests have permission to post or edit and we do not handle a registered user
		if ($this->forum->auth_post == 0 && !$this->joobbUser->get('id') || $this->forum->auth_edit == 0 && !$this->joobbUser->get('id')) {
			$this->assign('enableGuestName', $this->joobbConfig->getBoardSettings('enable_guest_name'));
			$this->assign('guestNameRequired', $this->joobbConfig->getBoardSettings('guest_name_required'));
			$this->post->guest_name = $model->getPostGuestName($this->post->id);
		}
		
		// load form validation behavior
		JHTML::_('behavior.formvalidation');
		
		// handle page title
		$this->document->setTitle($this->joobbConfig->getBoardSettings('board_name'));
		
		// handle bread crumb
		$this->breadCrumbs->addBreadCrumb($this->forum->name, JRoute::_('index.php?option=com_joobb&view=forum&forum='.$this->forum->id.'&Itemid='.$this->Itemid));
		$this->breadCrumbs->addBreadCrumb($this->post->subject);

		// load the joobb editor object
		$this->assign('editor', JoobbHelper::getEditor());
		
		// get post icons
		$joobbIconSet 	=& JoobbIconSet::getInstance($this->joobbConfig->getIconSetFile());
		$postIcons = $joobbIconSet->getIconsByGroup('iconPost');
		$this->assign('postIcons' , $postIcons);
		$this->post->id ? $iconFunction = $this->post->icon_function : $iconFunction = $this->joobbConfig->getDefaultTopicIcon();
		$this->assign('iconFunction' , $iconFunction);
		
		// assign options
		if ($this->post->id) {
			$enableBBCode = $this->post->enable_bbcode;
			$enableEmotions = $this->post->enable_emotions;
			$subscribe = count($model->getSubscription($this->post->id_topic, $this->post->id_user)) ? 1 : 0;		
		} else if ($this->joobbUser->get('id')) {
			$enableBBCode = $this->joobbUser->get('enable_bbcode');
			$enableEmotions = $this->joobbUser->get('enable_emotions');
			$subscribe = $this->joobbUser->get('auto_subscription');
		} else {
			$enableBBCode = $this->joobbConfig->getUserSettingsDefaults('enable_bbcode');
			$enableEmotions = $this->joobbConfig->getUserSettingsDefaults('enable_emotions');
			$subscribe = $this->joobbConfig->getUserSettingsDefaults('auto_subscription');				
		}
		
		$lists['enable_bbcode'] = JHTML::_('select.booleanlist', 'enable_bbcode', '', $enableBBCode, JText::_('COM_JOOBB_YES'), JText::_('COM_JOOBB_NO'));
		$lists['enable_emotions'] = JHTML::_('select.booleanlist', 'enable_emotions', '', $enableEmotions, JText::_('COM_JOOBB_YES'), JText::_('COM_JOOBB_NO'));
		$lists['subscribe'] = JHTML::_('select.booleanlist', 'subscribe', '', $subscribe, JText::_('COM_JOOBB_YES'), JText::_('COM_JOOBB_NO'));

		$topictype = array();
		$topictype[] = JHTML::_('select.option', 0, JText::_('COM_JOOBB_NORMAL'), 'value', 'text');
		if ($joobbAuth->getAuth('auth_sticky', $this->forum->id)) {
			$topictype[] = JHTML::_('select.option', 1, JText::_('COM_JOOBB_STICKY'), 'value', 'text');
		}
		if ($joobbAuth->getAuth('auth_announce', $this->forum->id)) {
			$topictype[] = JHTML::_('select.option', 2, JText::_('COM_JOOBB_ANNOUNCE'), 'value', 'text');
		}	
		$lists['type'] = JHTML::_('select.radiolist', $topictype, 'type', 'class="inputbox"', 'value', 'text', 0);

		$this->assignRef('lists', $lists);

		// get buttons
		$joobbButtonSet	=& JoobbButtonSet::getInstance();
		$this->assignRef('buttonSubmit', $joobbButtonSet->buttonByFunction['buttonSubmit']);
		$this->assignRef('buttonPreview', $joobbButtonSet->buttonByFunction['buttonPreview']);
		$this->assignRef('buttonCancel', $joobbButtonSet->buttonByFunction['buttonCancel']);
		
		// attachments
		$this->assign('enableAttachments', $this->joobbConfig->getAttachmentSettings('enable_attachments'));
		$this->assign('attachments', JoobbAttachment::getAttachments($this->post->id));
		$this->assign('attachmentPath', $this->joobbConfig->getAttachmentSettings('attachment_path'));
		$this->assignRef('joobbAttachment', JoobbAttachment::getInstance());
			
		// handle CAPTCHA
		$this->assignRef('joocmCaptcha', JoocmCaptcha::getInstance());
		$this->joocmCaptcha->prepare($this->joobbConfig->getCaptchaSettings('captcha_edittopic'));
		
		parent::display($tpl);
	}
}
?>