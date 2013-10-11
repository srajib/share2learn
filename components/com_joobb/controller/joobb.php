<?php
/**
 * @version $Id: joobb.php 210 2012-02-20 20:37:58Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2011 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Joo!BB Controller
 *
 * @package Joo!BB
 */
class JoobbController extends JController
{
	/**
	 * display joobb
	 */
	function display() {

		// initialize variables
		$app			=& JFactory::getApplication();
		$document		=& JFactory::getDocument();
		$joobbConfig	=& JoobbConfig::getInstance();
		$messageQueue	=& JoobbMessageQueue::getInstance();
		$joobbTemplate	=& JoobbTemplate::getInstance();
		$Itemid 		= JoocmHelper::getItemId('com_joobb');
		
		if (!$joobbConfig->getBoardSettings('published') && JRequest::getVar('info') != 'board_offline') {
			$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=information&info=board_offline&Itemid='.$Itemid, false)); return;
		}
		
		// set redirect url 	
		JoocmHelper::setRedirect();
		
		$currentURL = JURI::current();
		if (strpos($currentURL, ' ')){
			$document->setMetadata('robots', 'noindex');
		}
				
		$viewName	= JRequest::getVar('view', 'board');
		$viewType	= $document->getType();

		$view = &$this->getView($viewName, $viewType);

		$model = &$this->getModel($viewName);
		if (!JError::isError($model)) {
			$view->setModel($model, true);
		}
		
		// assign application
		$view->assignRef('app', $app);

		// assign document
		$view->assignRef('document', $document);
		
		// add template paths
		$view->addTemplatePath($joobbTemplate->getTemplatePath().DS.'controller');	
		$view->addTemplatePath($joobbTemplate->getTemplatePath());
	
		// add style
		$document->addStyleSheet($joobbTemplate->getStyleSheet(true));

		// assign item params
		$menu =& JSite::getMenu();
		$item = $menu->getActive();
		$params = new JParameter($item->params);
		$view->assignRef('params', $params);

		// assign board name	
		$view->assign('boardName', $joobbConfig->getBoardSettings('board_name'));
		
		// assign allow user registration
		$usersConfig = &JComponentHelper::getParams('com_users');
		$view->assign('allowUserRegistration', $usersConfig->get('allowUserRegistration'));
		
		// assign some global variables
		$view->assign('showBoxLatestItems', $joobbConfig->getViewSettings('show_latestitems'));
		$view->assign('showBoxStatistic', $joobbConfig->getViewSettings('show_statistic'));
		$view->assign('showBoxWhosOnline', $joobbConfig->getViewSettings('show_whosonline'));
		$view->assign('showBoxLegend', $joobbConfig->getViewSettings('show_legend'));
		$view->assign('showBoxFooter', $joobbConfig->getViewSettings('show_footer'));
	
		// assign bread crumb
		$breadCrumbs =& JoobbBreadCrumbs::getInstance();
		$breadCrumbUrl = JRoute::_('index.php?option=com_joobb&Itemid='.$Itemid);
		$breadCrumbs->addBreadCrumb($joobbConfig->getBoardSettings('breadcrumb_index'), $breadCrumbUrl);
		$view->assignRef('breadCrumbs', $breadCrumbs);
		
		// assign current url
		$uri = JUri::getInstance();
		$view->assign('redirect', $uri->toString());

		// assign joobb config
		$view->assignRef('joobbConfig', $joobbConfig);

		// assign joobb message queue
		$view->assignRef('messageQueue', $messageQueue);
		
		// assign joobb template
		$view->assignRef('joobbTemplate', $joobbTemplate);
				
		// assign current time
		$currentTime = JoocmHelper::Date(gmdate("Y-m-d H:i:s"));
		$view->assignRef('currentTime', $currentTime);
		
		// assign current joobb user
		$joobbUser =& JoobbHelper::getJoobbUser();
		$view->assignRef('joobbUser', $joobbUser);

		// assign item id
		$view->assign('Itemid', $Itemid);

		// display the view
		$view->assign('error', $this->getError());

		$view->display();
	}
	
	/**
	 * preview post (topic or post)
	 */
	function joobbPreview() {
		global $Itemid;
		
		// initialize variables
		$task			= JRequest::getVar('task');
		$post			= JRequest::get('post');
		
		$session =& JFactory::getSession();
		$session->set('joobbPreviewPost', $post);

		switch ($task) {
			case 'joobbpreviewtopic':
				if ($post['id_post'] == 0) {
					$link = JRoute::_('index.php?option=com_joobb&view=edittopic&forum='.(int)$post['id_forum'].'&topic='.(int)$post['id_topic'].'&Itemid='.$Itemid, false);
				} else {
					$link = JRoute::_('index.php?option=com_joobb&view=edittopic&topic='.(int)$post['id_topic'].'&post='.(int)$post['id_post'].'&Itemid='.$Itemid, false);
				}
				break;
			case 'joobbpreviewpost':
				$link = JRoute::_('index.php?option=com_joobb&view=editpost&topic='.(int)$post['id_topic'].'&post='.(int)$post['id_post'].'&Itemid='.$Itemid, false);
				break;
			default:
				$link = JRoute::_('index.php?option=com_joobb&view=board&Itemid='.$Itemid, false);
				break;
		}

		$this->setRedirect($link);
	}
	
	/**
	 * save the topic
	 */
	function joobbSaveTopic() {
		global $Itemid;

		// initialize variables
		$db				=& JFactory::getDBO();
		$joobbConfig	=& JoobbConfig::getInstance();
		$joobbUser		=& JoobbHelper::getJoobbUser();
		$messageQueue	=& JoobbMessageQueue::getInstance();
		$task			= JRequest::getVar('task');
		$post			= JRequest::get('post');
		$topicId		= (int)$post['id_topic'];
		
		// check captcha if set before any other action
		if ($joobbConfig->getCaptchaSettings('captcha_editpost')) {
			$session =& JFactory::getSession();
			if (md5($post['captcha_code']) != $session->get('captcha_code')) {

				// save the post first
				$session->set('joobbPost', $post);
				$messageQueue->addMessage(JText::_('COM_JOOBB_MSGCAPTCHACODEDONOTMATCH'));
				$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=edittopic&forum='.(int)$post['id_forum'].'&topic='.$topicId.'&Itemid='.$Itemid, false)); return;
			}
		}
		
		// flood interval
		$time = JoobbHelper::getLastPostTimeByIP($_SERVER['REMOTE_ADDR']);
		if (isset($time)) {
			$floodInterval = $joobbConfig->getBoardSettings('flood_interval');
			if ((strtotime(gmdate("Y-m-d H:i:s")) - strtotime($time)) < $floodInterval) {
				
				// save the post first
				$session =& JFactory::getSession();
				$session->set('joobbPost', $post);
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGFLOODINTERVAL', $floodInterval));
				$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=edittopic&forum='.(int)$post['id_forum'].'&topic='.$topicId.'&Itemid='.$Itemid, false)); return;
			}
		}
		
		$joobbForum =& JTable::getInstance('JoobbForum');
		$joobbForum->load((int)$post['id_forum']);
		
		$isNew = ($topicId < 1);
			
		// determine authentification
		if ($isNew) {
			$authTopic = 'auth_post'; $authTopicAll = 'auth_post_all';
		} else {
			$authTopic = 'auth_edit'; $authTopicAll = 'auth_edit_all';
		}
		
		// create post object as we need it to check authorisation
		$joobbPost =& JTable::getInstance('JoobbPost');
		if ($isNew) {
			if (!$joobbPost->bind(JRequest::get('post'))) {
				$messageQueue->addMessage($joobbPost->getError()); return;
			}
		} else {
			if (!$joobbPost->load($post['id_post'])) {
				$messageQueue->addMessage($joobbPost->getError()); return;
			}
		}
						
		// check authorization to prevent spoofing and hacking
		$canPost = false;
		$joobbAuth =& JoobbAuth::getInstance();
		if ($joobbAuth->getAuth($authTopic, (int)$post['id_forum'])) {
			$guestTime = $joobbConfig->getBoardSettings('guest_time') * 60;

			// We have now general permission, but only when...
			if ($isNew ||																					// ... we are about to create a new topic
					$joobbUser->get('id') == $joobbPost->id_user && $joobbPost->id_user != 0 ||				// ... or we are editing our own topic
					$joobbPost->id_user == 0 && $joobbPost->ip_poster == $_SERVER['REMOTE_ADDR'] && 
					(strtotime(gmdate("Y-m-d H:i:s")) - strtotime($joobbPost->date_post)) < $guestTime ||	// ... or we are editing our own topic as guest
					$joobbAuth->getAuth($authTopicAll, (int)$post['id_forum'])) {							// ... or we have all permissions
		
				// if the forum is locked...
				if ($joobbForum->locked) {
					
					// ... and we do not have special permision...
					if (!$joobbAuth->getAuth($authTopicAll, (int)$post['id_forum'])) {
						
						// ... then we are leaving back to forum
						$messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGFORUMLOCKED'), $joobbForum->name));
						$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=forum&forum='. $joobbForum->id .'&Itemid='.$this->Itemid, false)); return;
					}
				}
				$canPost = true;
			}
		}
		
		if (!$canPost) {
			if ($isNew) {
				$link = JRoute::_('index.php?option=com_joobb&view=forum&forum='.(int)$post['id_forum'].'&Itemid='.$Itemid, false);
				$messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGNOPERMISSION'), JText::_('COM_JOOBB_MSGPOSTTOPIC')));
			} else {
				$link = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$topicId.'&Itemid='.$Itemid, false);
				$messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGNOPERMISSION'), JText::_('COM_JOOBB_MSGEDITTOPIC')));
			}
			$this->setRedirect($link); return;
		}

		$joobbTopic =& JTable::getInstance('JoobbTopic');
		if (!$joobbTopic->bind($post)) {
			$messageQueue->addMessage($joobbTopic->getError()); return;
		}
		
		$joobbTopic->id = $topicId;

		if (!$joobbTopic->store()) {
			$messageQueue->addMessage($joobbTopic->getError()); return;
		}

		// we need to request the text separetly, otherwise html tags will be not saved
		$joobbPost->text = JRequest::getVar('text', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		$joobbPost->id = $post['id_post'];
		
		if ($isNew) {
			$joobbPost->id_topic	= $joobbTopic->id;
			$joobbPost->date_post	= gmdate("Y-m-d H:i:s");
			$joobbPost->id_user		= $joobbUser->get('id');
			$joobbPost->ip_poster = $_SERVER['REMOTE_ADDR'];
		} else {
			$joobbPost->date_last_edit = gmdate("Y-m-d H:i:s");
			$joobbPost->id_user_last_edit = $joobbUser->get('id');
		}
		
		// save images
		if ($joobbConfig->getImageSettings('enable_images')) {
			$imageList = JRequest::getVar('imageFiles', array(), 'files', 'array');
			if (is_array($imageList) && $imageList['name'][0] != '') {
				$joobbImage =& JoobbImage::getInstance();
			
				for ($i = 0, $n = count($imageList['name']); $i < $n; $i++) {
					$image = array();
					$image['name'] = $imageList['name'][$i];
					$image['type'] = $imageList['type'][$i];
					$image['tmp_name'] = $imageList['tmp_name'][$i];
					$image['error'] = $imageList['error'][$i];
					$image['size'] = $imageList['size'][$i];
					
					if (strpos($joobbPost->text, $image['name']) === false) {
						continue;
					}
	
					if ($joobbImage->uploadImage($image)) {
						$joobbPost->text = str_replace($imageList['name'][$i], $image['name'], $joobbPost->text);
					}
				}
			} 
		}
			
		if (!$joobbPost->store()) {
			$messageQueue->addMessage($joobbPost->getError()); return;
		}
		
		// rework topic
		if ($isNew) {
			$joobbTopic->id_first_post = $joobbPost->id;
			$joobbTopic->id_last_post = $joobbPost->id;
		}
		
		if (!$joobbTopic->store()) {
			$messageQueue->addMessage($joobbTopic->getError()); return;
		}
		
		// rework forum
		if ($isNew) {
			$joobbForum->posts++;
			$joobbForum->topics++;
			$joobbForum->id_last_post = $joobbPost->id;
			
			if (!$joobbForum->store()) {
				$messageQueue->addMessage($joobbForum->getError()); return;
			}
		
			$userPosts = $joobbUser->get('posts');
			$userPosts++;
			$joobbUser->set('posts', $userPosts);
			$joobbUser->save();
	
		}
		
		// subscribe the topic?
		if ($joobbConfig->getBoardSettings('auto_subscription') && $post['subscribe'] &&
				$joobbPost->id_topic && $joobbPost->id_user) {
			$query = "INSERT INTO #__joobb_topics_subscriptions (id_topic, id_user)"
					. "\n VALUES (". $joobbPost->id_topic .", ". $joobbPost->id_user .")"
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$messageQueue->addMessage($db->getError());
			}
		}
		
		// save guest user
		if (isset($post['guest_name'])) {
			$joobbUser->saveGuestUser($joobbPost->id , $post['guest_name']);
		}
	
		// save attachments
		if ($joobbConfig->getAttachmentSettings('enable_attachments')) {
			$attachmentList = JRequest::getVar('attachmentFiles', array(), 'files', 'array');
			$attachmentResult = true;
			if (is_array($attachmentList) && $attachmentList['name'][0] != '') {
				$joobbAttachment =& JoobbAttachment::getInstance();
					
				// upload all selected attachments		
				for ($i=0, $n=count($attachmentList['name']); $i < $n; $i++) {
					$attachment = array();
					$attachment['name'] = $attachmentList['name'][$i];
					$attachment['type'] = $attachmentList['type'][$i];
					$attachment['tmp_name'] = $attachmentList['tmp_name'][$i];
					$attachment['error'] = $attachmentList['error'][$i];
					$attachment['size'] = $attachmentList['size'][$i];
					
					if (!$joobbAttachment->uploadAttachment($attachment, $joobbPost->id)) {
						$attachmentResult = false;
					}
				}
			}
		}

		// do we have any attachments to delete?		
		$attachments = JRequest::getVar('attachments', array(), 'post', 'array');
		if (count($attachments)) {
			$joobbAttachment =& JoobbAttachment::getInstance();
			foreach ($attachments as $attachment) {
				if (!$joobbAttachment->deleteAttachment($attachment)) {
					$attachmentResult = false;
				}
			}
		}
		
		if ($attachmentResult) {
			$messageQueue->addMessage(JText::_('COM_JOOBB_MSGTOPICSAVED'));
			$link = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$joobbTopic->id.'&Itemid='.$Itemid, false);
		} else {
			$link = JRoute::_('index.php?option=com_joobb&view=edittopic&topic='.(int)$post['id_topic'].'&post='.(int)$post['id_post'].'&Itemid='.$Itemid, false);
		}

		$this->setRedirect($link);		
	}
	
	/**
	 * saves the post
	 */
	function joobbSavePost() {
		global $Itemid;

		// initialize variables
		$db				=& JFactory::getDBO();
		$joobbConfig	=& JoobbConfig::getInstance();
		$joobbUser		=& JoobbHelper::getJoobbUser();
		$messageQueue	=& JoobbMessageQueue::getInstance();
		$task			= JRequest::getVar('task');
		$post			= JRequest::get('post');

		// load forum if it exists...
		$joobbForum =& JTable::getInstance('JoobbForum');
		if ($joobbForum->load((int)$post['id_forum'])) {
			
			// ... and check status...
			if (!$joobbForum->status) {
				
				// ... leave if forum is closed
				$messageQueue->addMessage(JText::_('COM_JOOBB_MSGFORUMCLOSED'));
				$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='.$Itemid, false)); return;
			}
		} else {
			
			// ... otherwise leave
			$messageQueue->addMessage(JText::_('COM_JOOBB_MSGFORUMNOTSPECIFIED'));
			$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='.$Itemid, false)); return;
		}

		// load topic if it exists...
		$joobbTopic =& JTable::getInstance('JoobbTopic');
		if (!$joobbTopic->load((int)$post['id_topic'])) {
			
			// ... otherwise leave...
			$messageQueue->addMessage(JText::_('COM_JOOBB_MSGTOPICNOTSPECIFIED'));
			$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=forum&forum='.$joobbForum->id.'&Itemid='.$Itemid, false)); return;	
		}
		
		// check captcha if set before any other action
		if ($joobbConfig->getCaptchaSettings('captcha_editpost')) {
			$session =& JFactory::getSession();
			if (md5($post['captcha_code']) != $session->get('captcha_code')) {

				// save post to session first
				$session->set('joobbPost', $post);
				$messageQueue->addMessage(JText::_('COM_JOOBB_MSGCAPTCHACODEDONOTMATCH'));
				$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=editpost&topic='.$joobbTopic->id.'&post='.(int)$post['id_post'].'&Itemid='.$Itemid, false)); return;
			}
		}
		
		// flood interval
		$time = JoobbHelper::getLastPostTimeByIP($_SERVER['REMOTE_ADDR']);
		if (isset($time)) {
			$floodInterval = $joobbConfig->getBoardSettings('flood_interval');
			if ((strtotime(gmdate("Y-m-d H:i:s")) - strtotime($time)) < $floodInterval) {
				
				// save the post first
				$session =& JFactory::getSession();
				$session->set('joobbPost', $post);
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGFLOODINTERVAL', $floodInterval));
				$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=editpost&topic='.$joobbTopic->id.'&post='.(int)$post['id_post'].'&Itemid='.$Itemid, false)); return;
			}
		}

		$isNew = ($post['id_post'] < 1);
			
		// determine authentification
		if ($isNew) {
			$authPost = 'auth_reply'; $authPostAll = 'auth_reply_all';
		} else {
			$authPost = 'auth_edit'; $authPostAll = 'auth_edit_all';
		}
		
		// create post object as we need it to check authorisation
		$joobbPost = & JTable::getInstance('JoobbPost');
		if ($isNew) {
			if (!$joobbPost->bind(JRequest::get('post'))) {
				$messageQueue->addMessage($joobbPost->getError()); return;
			}
		} else {
			if (!$joobbPost->load($post['id_post'])) {
				$messageQueue->addMessage($joobbPost->getError()); return;
			}
		}

		// check authorization to prevent spoofing and hacking
		$canPost = false;
		$joobbAuth =& JoobbAuth::getInstance();
		if ($joobbAuth->getAuth($authPost, $joobbForum->id)) {
			$guestTime = $joobbConfig->getBoardSettings('guest_time') * 60;
			
			// We have now general permission, but only when...
			if ($isNew ||																					// ... we are about to reply
					$joobbUser->get('id') == $joobbPost->id_user && $joobbPost->id_user != 0 ||				// ... or we are editing our own post
					$joobbPost->id_user == 0 && $joobbPost->ip_poster == $_SERVER['REMOTE_ADDR'] && 
					(strtotime(gmdate("Y-m-d H:i:s")) - strtotime($joobbPost->date_post)) < $guestTime ||	// ... or we are editing our own post as guest
					$joobbAuth->getAuth($authPostAll, $joobbForum->id)) {									// ... or we have all permissions
				
				// if the forum or the topic is locked...
				if ($joobbForum->locked || $joobbTopic->status == 1) {
					
					// ... and we do not have special permision...
					if (!$joobbAuth->getAuth($authPostAll, $joobbForum->id)) {
						
						// ... then we are leaving back to topic
						if ($joobbForum->locked) {
							$messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGFORUMLOCKED'), $joobbForum->name));
						} else if ($joobbTopic->status) {
							$messageQueue->addMessage(JText::_('COM_JOOBB_MSGTOPICLOCKED'));
						}
						$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=topic&topic='. $joobbTopic->id .'&Itemid='. $Itemid, false)); return;
					}
				}
				$canPost = true;
			}
		}
	
		if (!$canPost) {
			if ($isNew) {
				$messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGNOPERMISSION'), JText::_('COM_JOOBB_MSGREPLYPOST')).$joobbForum->id);
			} else {
				$messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGNOPERMISSION'), JText::_('COM_JOOBB_MSGEDITPOST').'user_id'.$joobbPost->id_user));
			}
			
			$link = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$joobbTopic->id.'&Itemid='.$Itemid, false);
			if ($joobbPost->id) {
				$link .= '#'.$joobbPost->id;
			}
			$this->setRedirect($link); return;
		}

		// we need to request the text separetly, otherwise html tags will be not saved
		$joobbPost->text = JRequest::getVar('text', '', 'post', 'string', JREQUEST_ALLOWRAW);
	
		$joobbPost->id = $post['id_post'];
		if ($isNew) {
			$joobbPost->date_post = gmdate("Y-m-d H:i:s");
			$joobbPost->id_user = $joobbUser->get('id');
			$joobbPost->ip_poster = $_SERVER['REMOTE_ADDR'];
		} else {
			$joobbPost->date_last_edit = gmdate("Y-m-d H:i:s");
			$joobbPost->id_user_last_edit = $joobbUser->get('id');
		}
		
		if (!$joobbPost->subject) {
			$joobbTopicPost = & JTable::getInstance('JoobbPost');
			$joobbTopicPost->load($joobbTopic->id_first_post);
			$joobbPost->subject = JText::_('COM_JOOBB_REPLYSHORT').' '.$joobbTopicPost->subject;		
		}
		
		// save images
		if ($joobbConfig->getImageSettings('enable_images')) {
			$imageList = JRequest::getVar('imageFiles', array(), 'files', 'array');
			if (is_array($imageList) && $imageList['name'][0] != '') {
				$joobbImage =& JoobbImage::getInstance();
			
				for ($i = 0, $n = count($imageList['name']); $i < $n; $i++) {
					$image = array();
					$image['name'] = $imageList['name'][$i];
					$image['type'] = $imageList['type'][$i];
					$image['tmp_name'] = $imageList['tmp_name'][$i];
					$image['error'] = $imageList['error'][$i];
					$image['size'] = $imageList['size'][$i];
					
					if (strpos($joobbPost->text, $image['name']) === false) {
						continue;
					}
	
					if ($joobbImage->uploadImage($image)) {
						$joobbPost->text = str_replace($imageList['name'][$i], $image['name'], $joobbPost->text);
					}
				}
			} 
		}
		
		if (!$joobbPost->store()) {
			$messageQueue->addMessage($post->getError()); return;
		}
	
		if ($isNew) {
		
			// rework topic
			$joobbTopic->replies++;
			$joobbTopic->id_last_post = $joobbPost->id;

			if (!$joobbTopic->store()) {
				$messageQueue->addMessage($joobbTopic->getError());
			}
			
			// rework forum
			$joobbForum->posts++;
			$joobbForum->id_last_post = $joobbPost->id;
			
			if (!$joobbForum->store()) {
				$messageQueue->addMessage($joobbForum->getError());
			}
				
			$userPosts = $joobbUser->get('posts');
			$userPosts++;
			$joobbUser->set('posts', $userPosts);
			$joobbUser->save();
	
			$joobbMail =& JoobbMail::getInstance();	
			$joobbMail->sendNotifyOnReplyMail($joobbPost);
		}
		
		// subscribe the topic?
		if ($joobbConfig->getBoardSettings('auto_subscription') && $post['subscribe'] &&
				$joobbPost->id_topic && $joobbPost->id_user) {
			$query = "INSERT INTO #__joobb_topics_subscriptions (id_topic, id_user)"
					. "\n VALUES (". $joobbPost->id_topic .", ". $joobbPost->id_user .")"
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$messageQueue->addMessage($db->getError());
			}
		}
		
		// save guest user
		if (isset($post['guest_name'])) {
			$joobbUser->saveGuestUser($joobbPost->id , $post['guest_name']);
		}

		// save attachments
		if ($joobbConfig->getAttachmentSettings('enable_attachments')) {
			$attachmentList = JRequest::getVar('attachmentFiles', array(), 'files', 'array');
			$attachmentResult = true;
			if (is_array($attachmentList) && $attachmentList['name'][0] != '') {
				$joobbAttachment =& JoobbAttachment::getInstance();
					
				// upload all selected attachments		
				for ($i=0, $n=count($attachmentList['name']); $i < $n; $i++) {
					$attachment = array();
					$attachment['name'] = $attachmentList['name'][$i];
					$attachment['type'] = $attachmentList['type'][$i];
					$attachment['tmp_name'] = $attachmentList['tmp_name'][$i];
					$attachment['error'] = $attachmentList['error'][$i];
					$attachment['size'] = $attachmentList['size'][$i];
					
					if (!$joobbAttachment->uploadAttachment($attachment, $joobbPost->id)) {
						$attachmentResult = false;
					}
				}
			}
		}

		// do we have any attachments to delete?		
		$attachments = JRequest::getVar('attachments', array(), 'post', 'array');
		if (count($attachments)) {
			$joobbAttachment =& JoobbAttachment::getInstance();
			foreach ($attachments as $attachment) {
				if (!$joobbAttachment->deleteAttachment($attachment)) {
					$attachmentResult = false;
				}
			}
		}
		
		if ($attachmentResult) {
			$messageQueue->addMessage(JText::_('COM_JOOBB_MSGPOSTSAVED'));
			$link = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$joobbTopic->id.'&Itemid='.$Itemid.'#p'.$joobbPost->id, false);
		} else {
			$link = JRoute::_('index.php?option=com_joobb&view=editpost&topic='.$joobbTopic->id.'&post='.$joobbPost->id.'&Itemid='.$Itemid, false);
		}

		$this->setRedirect($link);
	}
	
	/**
	 * delete the topic
	 */
	function joobbDeleteTopic() {
		global $Itemid;

		// initialize variables
		$db				=& JFactory::getDBO();
		$joobbConfig	=& JoobbConfig::getInstance();
		$joobbAuth		=& JoobbAuth::getInstance();
		$joobbUser		=& JoobbHelper::getJoobbUser();
		$messageQueue	=& JoobbMessageQueue::getInstance();
		
		// get the topic id
		$topicId = JRequest::getVar('topic', 0, '', 'int');

		$joobbTopic =& JTable::getInstance('JoobbTopic');
		
		if ($joobbTopic->load($topicId)) {
		
			// check captcha if set before any action
			if ($joobbConfig->getCaptchaSettings('captcha_deletetopic')) {
				$session =& JFactory::getSession();
				if (md5(JRequest::getVar('captcha_code', '')) != $session->get('captcha_code')) {
					$messageQueue->addMessage(JText::_('COM_JOOBB_MSGCAPTCHACODEDONOTMATCH'));
					$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=topic&topic='.$topicId.'&Itemid='.$Itemid, false)); return;
				}
			}
			
			$joobbPost =& JTable::getInstance('JoobbPost');

			if ($joobbPost->load($joobbTopic->id_first_post)) {

				// check general permission to delete a topic
				if ($joobbAuth->getAuth('auth_delete', $joobbPost->id_forum)) {
					$guestTime = $joobbConfig->getBoardSettings('guest_time') * 60;
					
					// check user dependent permission to delete a topic
					if ($joobbUser->get('id') == $joobbPost->id_user && $joobbPost->id_user != 0 || 
							$joobbPost->id_user == 0 && $joobbPost->ip_poster == $_SERVER['REMOTE_ADDR'] && 
							(strtotime(gmdate("Y-m-d H:i:s")) - strtotime($joobbPost->date_post)) < $guestTime ||
							$joobbAuth->getAuth('auth_delete_all', $joobbPost->id_forum)) {
					
						$forumId = $joobbTopic->id_forum;
						$topicLastPostId = $joobbTopic->id_last_post;
			
						// delete all topic depended posts first
						$query = "DELETE FROM #__joobb_posts"
								. "\n WHERE id_topic = $topicId"
								;
						$db->setQuery($query);
						
						if (!$db->query()) {
							$messageQueue->addMessage($db->getErrorMsg()); return;
						}
						
						$postsDeleted = $db->getAffectedRows();
			
						// delete the topic
						if (!$joobbTopic->delete()) {
							$messageQueue->addMessage($joobbTopic->getError()); return;
						}
						
						// rework forum
						$joobbForum =& JTable::getInstance('JoobbForum');
						if ($joobbForum->load($forumId)) {
							$joobbForum->posts -= $postsDeleted;
							$joobbForum->topics--;
							if ($joobbForum->id_last_post == $topicLastPostId) {
								$joobbForum->id_last_post = JoobbHelper::getLastPostIdForum($forumId);
							}
							
							if (!$joobbForum->store()) {
								$messageQueue->addMessage($joobbForum->getError()); return;
							}
						}		
						$msg =  JText::_('COM_JOOBB_MSGTOPICDELETED');
						$link = JRoute::_('index.php?option=com_joobb&view=forum&forum='.$forumId.'&Itemid='.$Itemid, false);
					} else {
						$msg = JText::sprintf('COM_JOOBB_MSGNOPERMISSION', 'COM_JOOBB_MSGDELETETOPIC');
						$link = JRoute::_('index.php?option=com_joobb&view=forum&forum='.$joobbPost->id_forum.'&Itemid='.$Itemid, false);
					}
				} else {
					$msg = JText::sprintf('COM_JOOBB_MSGNOPERMISSION', 'COM_JOOBB_MSGDELETETOPIC');
					$link = JRoute::_('index.php?option=com_joobb&view=forum&forum='.$joobbPost->id_forum.'&Itemid='.$Itemid, false);
				}
			} else {
				$msg = JText::_('COM_JOOBB_MSGPOSTNOTFOUND');
				$link = JRoute::_('index.php?option=com_joobb&view=board&Itemid='.$Itemid, false);
			}
		} else {
			$msg = JText::_('COM_JOOBB_MSGTOPICNOTFOUND');
			$link = JRoute::_('index.php?option=com_joobb&view=board&Itemid='.$Itemid, false);			
		}

		$messageQueue->addMessage($msg);
		$this->setRedirect($link);
		
	}
	
	/**
	 * delete the post
	 */
	function joobbDeletePost() {
		global $Itemid;

		// initialize variables
		$db				=& JFactory::getDBO();
		$joobbConfig	=& JoobbConfig::getInstance();
		$joobbAuth		=& JoobbAuth::getInstance();
		$joobbUser		=& JoobbHelper::getJoobbUser();
		$messageQueue	=& JoobbMessageQueue::getInstance();
		
		// get the post id		
		$postId = JRequest::getVar('post', 0, '', 'int');

		$joobbPost =& JTable::getInstance('JoobbPost');

		if ($joobbPost->load($postId)) {
		
			// check captcha if set before any action
			if ($joobbConfig->getCaptchaSettings('captcha_deletepost')) {
				$session =& JFactory::getSession();
				if (md5(JRequest::getVar('captcha_code', '')) != $session->get('captcha_code')) {
					$messageQueue->addMessage(JText::_('COM_JOOBB_MSGCAPTCHACODEDONOTMATCH'));
					$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=topic&topic='.$joobbPost->id_topic.'&Itemid='.$Itemid, false)); return;
				}
			}
			
			// check general permission to delete a post
			if ($joobbAuth->getAuth('auth_delete', $joobbPost->id_forum)) {
			
				$guestTime = $joobbConfig->getBoardSettings('guest_time') * 60;
			
				// check user dependent permission to delete a post
				if ($joobbUser->get('id') == $joobbPost->id_user && $joobbPost->id_user != 0 || 
						$joobbPost->id_user == 0 && $joobbPost->ip_poster == $_SERVER['REMOTE_ADDR'] && 
						(strtotime(gmdate("Y-m-d H:i:s")) - strtotime($joobbPost->date_post)) < $guestTime ||
						$joobbAuth->getAuth('auth_delete_all', $joobbPost->id_forum)) {
				
					$topicId = $joobbPost->id_topic;
					$forumId = $joobbPost->id_forum;
						
					// delete the post
					if (!$joobbPost->delete()) {
						$messageQueue->addMessage($joobbPost->getError()); return;
					}
		
					// rework topic		
					$joobbTopic =& JTable::getInstance('JoobbTopic');
					if ($joobbTopic->load($topicId)) {
						$joobbTopic->replies--;
						if ($joobbTopic->id_last_post == $postId) {
							$joobbTopic->id_last_post = JoobbHelper::getLastPostIdTopic($topicId);
						}
				
						if (!$joobbTopic->store()) {
							$messageQueue->addMessage($joobbTopic->getError()); return;
						}
					}
					
					// rework forum
					$joobbForum =& JTable::getInstance('JoobbForum');
					if ($joobbForum->load($forumId)) {
						$joobbForum->posts--;
						if ($joobbForum->id_last_post == $postId) {
							$joobbForum->id_last_post = JoobbHelper::getLastPostIdForum($forumId);
						}
						
						if (!$joobbForum->store()) {
							$messageQueue->addMessage($joobbForum->getError()); return;
						}
					}
					
					$msg = JText::_('COM_JOOBB_MSGPOSTDELETED');
					$link = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$topicId.'&Itemid='.$Itemid, false);
				} else {
					$msg = JText::sprintf('COM_JOOBB_MSGNOPERMISSION', 'COM_JOOBB_MSGDELETEPOST');
					$link = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$joobbPost->id_topic.'&Itemid='.$Itemid, false);
				}
			} else {
				$msg = JText::sprintf('COM_JOOBB_MSGNOPERMISSION', 'COM_JOOBB_MSGDELETEPOST');
				$link = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$joobbPost->id_topic.'&Itemid='.$Itemid, false);
			}
		} else {
			$msg = JText::_('COM_JOOBB_MSGPOSTNOTFOUND');
			$link = JRoute::_('index.php?option=com_joobb&view=board&Itemid='.$Itemid, false);
		}
		
		$messageQueue->addMessage($msg);
		$this->setRedirect($link);
	}

	/**
	 * report post
	 */
	function joobbReportPost() {
	
		// initialize variables
		$joobbMail		=& JoobbMail::getInstance();
		$reportComment	= JRequest::getVar('report_comment', '');
		$Itemid			= JRequest::getVar('Itemid');
		$link = JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $Itemid, false);
		
		$joobbPost =& JTable::getInstance('JoobbPost');
		if ($joobbPost->load(JRequest::getVar('post', 0, '', 'int'))) {
			$joobbAuth =& JoobbAuth::getInstance();
			
			// check general report post permission
			if ($joobbAuth->getAuth('auth_reportpost', $joobbPost->id_forum)) {
				if ($joobbMail->sendReportPostMail($joobbPost, $reportComment)) {
					$link = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$row->id_topic.'&Itemid='. $Itemid, false);
				}
			} else {
				$messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGNOPERMISSION'), JText::_('COM_JOOBB_MSGREPORT')));
			}
		} else {
			$messageQueue->addMessage(JText::_('COM_JOOBB_MSGPOSTTOREPORTNOTFOUND'));
		}
		
		$this->setRedirect($link);		
	}
	
	/**
	 * lock topic
	 */
	function joobbLockTopic() {
	
		// initialize variables
		$messageQueue	=& JoobbMessageQueue::getInstance();
		$topicId		= JRequest::getVar('topic', 0, '', 'int');
		$Itemid			= JRequest::getVar('Itemid');
		
		$link = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$topicId.'&Itemid='. $Itemid, false);

		$joobbTopic =& JTable::getInstance('JoobbTopic');
		if ($joobbTopic->load($topicId)) {
			$joobbAuth =& JoobbAuth::getInstance();
			if ($joobbAuth->getAuth('auth_lock', $joobbTopic->id_forum)) {
				$joobbPost =& JTable::getInstance('JoobbPost');
				if ($joobbPost->load($joobbTopic->id_first_post)) {
					$joobbConfig	=& JoobbConfig::getInstance();
					$joobbUser		=& JoobbHelper::getJoobbUser();
					
					$guestTime = $joobbConfig->getBoardSettings('guest_time') * 60;
					if ($joobbUser->get('id') == $joobbPost->id_user && $joobbPost->id_user != 0 || 
							$joobbPost->id_user == 0 && $joobbPost->ip_poster == $_SERVER['REMOTE_ADDR'] && 
							(strtotime(gmdate("Y-m-d H:i:s")) - strtotime($joobbPost->date_post)) < $guestTime ||
							$joobbAuth->getAuth('auth_lock_all', $joobbTopic->id_forum)) {
						$joobbTopic->status = 1;
						$joobbTopic->store();
					} else {
						$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGNOPERMISSION', 'COM_JOOBB_MSGLOCKTOPIC'));
					}
				}
			} else {
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGNOPERMISSION', 'COM_JOOBB_MSGLOCKTOPIC'));
			}
		} else {
			$link = JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $Itemid, false);
		}

		$this->setRedirect($link);		
	}
	
	/**
	 * unlock topic
	 */
	function joobbUnlockTopic() {
	
		// initialize variables
		$messageQueue	=& JoobbMessageQueue::getInstance();
		$topicId		= JRequest::getVar('topic', 0, '', 'int');
		$Itemid			= JRequest::getVar('Itemid');

		$link = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$topicId.'&Itemid='. $Itemid, false);

		$joobbTopic =& JTable::getInstance('JoobbTopic');
		if ($joobbTopic->load($topicId)) {
			$joobbAuth =& JoobbAuth::getInstance();
			if ($joobbAuth->getAuth('auth_lock', $joobbTopic->id_forum)) {
				$joobbPost =& JTable::getInstance('JoobbPost');
				if ($joobbPost->load($joobbTopic->id_first_post)) {
					$joobbConfig	=& JoobbConfig::getInstance();
					$joobbUser		=& JoobbHelper::getJoobbUser();
					
					$guestTime = $joobbConfig->getBoardSettings('guest_time') * 60;
					if ($joobbUser->get('id') == $joobbPost->id_user && $joobbPost->id_user != 0 || 
							$joobbPost->id_user == 0 && $joobbPost->ip_poster == $_SERVER['REMOTE_ADDR'] && 
							(strtotime(gmdate("Y-m-d H:i:s")) - strtotime($joobbPost->date_post)) < $guestTime ||
							$joobbAuth->getAuth('auth_lock_all', $joobbTopic->id_forum)) {
						$joobbTopic->status = 0;
						$joobbTopic->store();
					} else {
						$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGNOPERMISSION', 'COM_JOOBB_MSGUNLOCKTOPIC'));
					}
				}
			} else {
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGNOPERMISSION', 'COM_JOOBB_MSGUNLOCKTOPIC'));
			}
		} else {
			$link = JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $Itemid, false);
		}

		$this->setRedirect($link);	
	}
	
	/**
	 * move topic
	 */
	function joobbMoveTopic() {
	
		// initialize variables
		$db				= & JFactory::getDBO();
		$joobbUser		=& JoobbHelper::getJoobbUser();
		$messageQueue	=& JoobbMessageQueue::getInstance();
		$forumId		= JRequest::getVar('forum', 0, '', 'int');
		$topicId		= JRequest::getVar('topic', 0, '', 'int');
		$Itemid			= JRequest::getVar('Itemid');

		$joobbForumMoveTo =& JTable::getInstance('JoobbForum');
		if (!$joobbForumMoveTo->load($forumId)) {
			$messageQueue->addMessage(JText::_('COM_JOOBB_MSGFORUMNOTFOUND'));
			$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $Itemid, false)); return;	
		}
				
		// check if the user is authorized to make posts in the new forum
		if ($joobbForumMoveTo->auth_post <= $joobbUser->getRole($forumId)) {

			$joobbTopic =& JTable::getInstance('JoobbTopic');
			if (!$joobbTopic->load($topicId)) {
				$messageQueue->addMessage(JText::_('COM_JOOBB_MSGTOPICNOTFOUND'));
				$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $Itemid, false)); return;
			}
	
			$joobbForum =& JTable::getInstance('JoobbForum');
			if (!$joobbForum->load($joobbTopic->id_forum)) {
				$messageQueue->addMessage(JText::_('COM_JOOBB_MSGFORUMNOTFOUND'));
				$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $Itemid, false)); return;	
			}
			
			// check if the user is allowed to move a topic
			if ($joobbForum->auth_move <= $joobbUser->getRole($joobbTopic->id_forum)) {
				$query = "UPDATE #__joobb_topics"
					. "\n SET id_forum = $forumId"
					. "\n WHERE id = $topicId"
					;
				$db->setQuery($query);
		
				if (!$db->query()) {
					$msg = $db->getErrorMsg();
					$messageQueue->addMessage($msg);
				} else {
					$query = "UPDATE #__joobb_posts"
						. "\n SET id_forum = $forumId"
						. "\n WHERE id_topic = $topicId"
						;
					$db->setQuery($query);
					
					if (!$db->query()) {
						$msg = $db->getErrorMsg();
						$messageQueue->addMessage($msg);
					} else {
					
						// make some corrections
						$query = "SELECT max(p.id)"
							. "\n FROM `#__joobb_posts` AS p"
							. "\n WHERE p.id_forum = ". $joobbForum->id
							. "\n LIMIT 1"
							;
						$db->setQuery($query);
						
						$joobbForum->id_last_post = $db->loadResult();
						$joobbForum->posts--; 
						$joobbForum->posts = $joobbForum->posts - $joobbTopic->replies;
						$joobbForum->topics--;
						if (!$joobbForum->store()) {
							$messageQueue->addMessage($joobbForum->getError());
						} else {
							$query = "SELECT max(p.id)"
								. "\n FROM `#__joobb_posts` AS p"
								. "\n WHERE p.id_forum = ". $joobbForumMoveTo->id
								. "\n LIMIT 1"
								;
							$db->setQuery($query);
							
							$joobbForumMoveTo->id_last_post = $db->loadResult();
							$joobbForumMoveTo->posts = $joobbForumMoveTo->posts + $joobbTopic->replies + 1; 
							$joobbForumMoveTo->topics++;
							if (!$joobbForumMoveTo->store()) {
								$messageQueue->addMessage($joobbForumMoveTo->getError());
							} else {
								$messageQueue->addMessage(JText::_('COM_JOOBB_MSGTOPICMOVED'));	
							}
						}	
					}
				}
			} else {
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGNOPERMISSION', 'COM_JOOBB_MSGMOVETOPIC'));
			}
		} else {
			$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGNOPERMISSION', 'COM_JOOBB_MSGMOVETOPIC'));
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=topic&topic='.$topicId.'&Itemid='. $Itemid, false));
	}
	
	/**
	 * subscribe topic
	 */
	function joobbSubscribeTopic() {
	
		// initialize variables
		$db				=& JFactory::getDBO();
		$messageQueue	=& JoobbMessageQueue::getInstance();
		$joobbUser		=& JoobbHelper::getJoobbUser();
		$topicId		= JRequest::getVar('topic', 0, '', 'int');
		$Itemid			= JRequest::getVar('Itemid');
		
		if ($joobbUser->get('id') < 1) {
			$messageQueue->addMessage(JText::_('COM_JOOBB_MSGSUBSCRIPTIONNOTALLOWED'));
			$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $Itemid, false)); return;
		}
		
		$row =& JTable::getInstance('JoobbTopic');
		if ($topicId == 0 || !$row->load($topicId)) {
			$messageQueue->addMessage(JText::_('COM_JOOBB_MSGTOPICNOTFOUND'));
			$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $Itemid, false)); return;
		}
		
		$query = "INSERT INTO #__joobb_topics_subscriptions (id_topic, id_user)"
				. "\n VALUES ($topicId, ". $joobbUser->get('id') .")"
				;
		$db->setQuery($query);
		
		if (!$db->query()) {
			$messageQueue->addMessage($db->getError()); return false;
		}

		$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=topic&topic='.$topicId.'&Itemid='. $Itemid, false));		
	}
	
	/**
	 * unsubscribe topic
	 */
	function joobbUnsubscribeTopic() {
	
		// initialize variables
		$db				=& JFactory::getDBO();
		$messageQueue	=& JoobbMessageQueue::getInstance();
		$joobbUser		=& JoobbHelper::getJoobbUser();
		$topicId		= JRequest::getVar('topic', 0, '', 'int');
		$Itemid			= JRequest::getVar('Itemid');
		
		if ($joobbUser->get('id') < 1) {
			$messageQueue->addMessage(JText::_('COM_JOOBB_MSGUNSUBSCRIPTIONNOTALLOWED'));
			$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $Itemid, false)); return;
		}
		
		$row =& JTable::getInstance('JoobbTopic');
		if ($topicId == 0 || !$row->load($topicId)) {
			$messageQueue->addMessage(JText::_('COM_JOOBB_MSGTOPICNOTFOUND'));
			$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $Itemid, false)); return;
		}
	
		$query = "DELETE FROM #__joobb_topics_subscriptions"
				. "\n WHERE id_topic = $topicId"
				. "\n AND id_user = ". $joobbUser->get('id')
				;
		$db->setQuery($query);
		
		if (!$db->query()) {
			$messageQueue->addMessage($db->getError()); return false;
		}

		$this->setRedirect(JRoute::_('index.php?option=com_joobb&view=topic&topic='.$topicId.'&Itemid='. $Itemid, false));
	}
	
	/**
	 * save settings
	 */
	function joobbSaveSettings() {

		// initialize variables
		$messageQueue	=& JoobbMessageQueue::getInstance();
		$joobbUser		=& JoobbHelper::getJoobbUser();
		$Itemid			= JRequest::getVar('Itemid', 0);
		$link			= JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $Itemid, false);
		
		if ($joobbUser->get('id') < 1) {
			$msg = JText::_('COM_JOOBB_MSGNOPERMISSIONEDITSETTINGS');	
		} else {
			$msg = JText::_('COM_JOOBB_MSGSETTINGSSAVED');
			
			if (!$joobbUser->saveSettings(JRequest::get('post'))) {
				$link = JRoute::_('index.php?option=com_joobb&view=editsettings&Itemid='.$Itemid, false);
				$msg = JText::_($joobbUser->getError());		
			}
		}

		$messageQueue->addMessage($msg);
		$this->setRedirect($link);
	}
	
	/**
	 * feed
	 */
	function joobbFeed() {
	
		// initialize variables
		$joobbFeed = JoobbFeed::getInstance();
		
		echo $joobbFeed->createFeed();		
	}
}
?>