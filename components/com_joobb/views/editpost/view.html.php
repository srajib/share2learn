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
 * Joo!BB Edit Post View
 *
 * @package Joo!BB
 */
class JoobbViewEditPost extends JView
{

	function display($tpl = null) {

		// initialize variables
		$joobbAuth =& JoobbAuth::getInstance();
		$model =& $this->getModel();
		
		// assign topic
		$this->assignRef('topic', $model->getTopic(JRequest::getVar('topic', 0, '', 'int')));
		
		// leave if there is no topic specified
		if ($this->topic->id == 0) {
			$this->messageQueue->addMessage(JText::_('COM_JOOBB_MSGTOPICNOTSPECIFIED'));
			$this->app->redirect(JRoute::_('index.php?option=com_joobb&view=main&Itemid='.$this->Itemid, false));
		}
	
		// assign post
		$this->assignRef('post', $model->getPost(JRequest::getVar('post', 0, '', 'int')));		
		
		// assign forum
		$this->assignRef('forum', $model->getForum($this->topic->id_forum));

		// determine authentification
		if ($this->post->id) {
			$authPost = 'auth_edit'; $authPostAll = 'auth_edit_all';
		} else {
			$authPost = 'auth_reply'; $authPostAll = 'auth_reply_all';
		}
		
		$canPost = false;
		if ($joobbAuth->getAuth($authPost, $this->forum->id)) {
			$guestTime = $this->joobbConfig->getBoardSettings('guest_time') * 60;
			
			// We have now general permission, but only when...
			if ($this->post->id == 0 ||																		// ... we are about to reply
					$this->joobbUser->get('id') == $this->post->id_user && $this->post->id_user != 0 ||		// ... or we are editing our own post
					$this->post->id_user == 0 && $this->post->ip_poster == $_SERVER['REMOTE_ADDR'] && 
					(strtotime(gmdate("Y-m-d H:i:s")) - strtotime($this->post->date_post)) < $guestTime ||	// ... or we are editing our own post as guest
					$joobbAuth->getAuth($authPostAll, $this->topic->id_forum)) {							// ... or we have all permissions
				
				// if the forum or the topic is locked...
				if ($this->forum->locked || $this->topic->status == 1) {
					
					// ... and we do not have special permision...
					if (!$joobbAuth->getAuth($authPostAll, $this->topic->id_forum)) {
						
						// ... then we are leaving back to topic
						if ($this->forum->locked) {
							$this->messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGFORUMLOCKED'), $this->forum->name));
						} else if ($this->topic->status) {
							$this->messageQueue->addMessage(JText::_('COM_JOOBB_MSGTOPICLOCKED'));
						}
						$this->app->redirect(JRoute::_('index.php?option=com_joobb&view=topic&topic='. $this->topic->id .'&Itemid='. $this->Itemid, false));
					}
				}
				$canPost = true;
			}
		}

		if (!$canPost) {
			if ($this->post->id) {
				$this->messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGNOPERMISSION'), JText::_('COM_JOOBB_MSGEDITPOST')));
			} else {
				$this->messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGNOPERMISSION'), JText::_('COM_JOOBB_MSGREPLYPOST')));
			}
			
			$link = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$this->topic->id.'&Itemid='.$this->Itemid, false);
			if ($this->post->id) {
				$link .= '#'.$this->post->id;
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
		
		// preview post
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

		$quote = JRequest::getVar('quote', 0, '', 'int');
		if ($quote != 0) {
			$quotePost = $model->getPostQuote($quote);
			$this->post->text = '[quote='.$model->getQuotedUserName($quotePost->id_user).']'. $quotePost->text .'[/quote]';
		}

		// request variables
		$limit = JRequest::getVar('limit', $this->joobbConfig->getBoardSettings('posts_per_page'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		
		// load posts associate with the topic for topic review 
		$posts = new JoobbPost($model->getTopicPosts($this->topic->id, $limitstart, $limit));
		$this->assignRef('posts', $posts);		
		$this->assignRef('total', $model->getTotal());
		
		// handle pagination
		$showPagination = false;
		if ($this->total > $limit) {
			jimport('joomla.html.pagination');
			$this->pagination = new JPagination($this->total, $limitstart, $limit);
			$showPagination = true;
		}
		$this->assign('showPagination', $showPagination);

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
		$joobbIconSet =& JoobbIconSet::getInstance($this->joobbConfig->getIconSetFile());
		$postIcons = $joobbIconSet->getIconsByGroup('iconPost');
		$this->assign('postIcons' , $postIcons);
		$this->post->id ? $iconFunction = $this->post->icon_function : $iconFunction = $this->joobbConfig->getDefaultPostIcon();
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

		$this->assignRef('lists', $lists);

		$action = JRoute::_('index.php?option=com_joobb');
		$this->assignRef('action', $action);
		
		// get buttons
		$joobbButtonSet	=& JoobbButtonSet::getInstance();
		$this->assignRef('buttonSubmit', $joobbButtonSet->buttonByFunction['buttonSubmit']);
		$this->assignRef('buttonPreview', $joobbButtonSet->buttonByFunction['buttonPreview']);
		$this->assignRef('buttonCancel', $joobbButtonSet->buttonByFunction['buttonCancel']);
		$this->assignRef('buttonAddFile', $joobbButtonSet->buttonByFunction['buttonAddFile']);
		$this->assignRef('buttonRemoveFile', $joobbButtonSet->buttonByFunction['buttonRemoveFile']);
		
		// attachments
		$this->assign('enableAttachments', $this->joobbConfig->getAttachmentSettings('enable_attachments'));
		$this->assign('attachments', JoobbAttachment::getAttachments($this->post->id));
		$this->assign('attachmentPath', $this->joobbConfig->getAttachmentSettings('attachment_path'));
		$this->assignRef('joobbAttachment', JoobbAttachment::getInstance());
			
		// handle CAPTCHA
		$this->assignRef('joocmCaptcha', JoocmCaptcha::getInstance());
		$this->joocmCaptcha->prepare($this->joobbConfig->getCaptchaSettings('captcha_editpost'));
				
		parent::display($tpl);
	}		
	
}
?>