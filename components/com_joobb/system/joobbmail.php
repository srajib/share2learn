<?php
/**
 * @version $Id: joobbmail.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Mail
 *
 * @package Joo!BB
 */
class JoobbMail extends JoocmMail
{

	/**
	 * joobb mail
	 */	
	function JoobbMail() {
		parent::__construct();
	}
	
	/**
	 * get instance
	 *
	 * @access public
	 * @return object
	 */
	function &getInstance() {
	
		static $joobbMail;

		if (!is_object($joobbMail)) {
			$joobbMail = new JoobbMail();
		}

		return $joobbMail;
	}
	
	function sendNotifyOnReplyMail($joobbPost) {
		
		// initialize variables
		$db			=& JFactory::getDBO();
		$joobbUser	=& JoobbHelper::getJoobbUser();
		$Itemid		= JRequest::getVar('Itemid');
		
		$this->subject = sprintf(JText::_('COM_JOOBB_NOTIFYONREPLYSUBJECT'), $this->siteName);

		$topicLink = JRoute::_($this->siteURL.'index.php?option=com_joobb&view=topic&topic='.$joobbPost->id_topic.'&Itemid='.$Itemid.'#p'.$joobbPost->id);

		// get all notification requests
		$query = "SELECT *"
				. "\n FROM #__joobb_topics_subscriptions AS ts"
				. "\n INNER JOIN #__users AS u ON u.id = ts.id_user"
				. "\n WHERE ts.id_topic = " .$joobbPost->id_topic
				. "\n AND u.id <> " .$joobbUser->get('id')
				. "\n GROUP BY u.id"
				;
		$db->setQuery($query);
		$notifies = $db->loadObjectList();
	
		foreach ($notifies as $notify) {
			$this->mailTo = $notify->email;
			$this->message = sprintf(JText::_('COM_JOOBB_NOTIFYONREPLYMESSAGE'), $notify->name, $joobbPost->subject, $topicLink, $this->siteName);
			$this->_sendMail();
		}
	}
	
	function sendReportPostMail($joobbPost, $reportComment) {
		
		// initialize variables
		$db				=& JFactory::getDBO();
		$messageQueue	=& JoobbMessageQueue::getInstance();
		$joobbUser		=& JoobbHelper::getJoobbUser();
		$Itemid			= JRequest::getVar('Itemid');

		// subject
		$this->subject = sprintf(JText::_('COM_JOOBB_REPORTPOSTSUBJECT'), $this->siteName);

		$topicLink = JRoute::_($this->siteURL.'index.php?option=com_joobb&view=topic&topic='.$joobbPost->id_topic.'&Itemid='.$Itemid);
		
		// get all system message users
		$query = "SELECT *"
				. "\n FROM #__users AS u"
				. "\n INNER JOIN #__joocm_users AS ju ON ju.id = u.id"
				. "\n WHERE ju.system_emails = 1"
				. "\n GROUP BY u.id"
				;
		$db->setQuery($query);
		$systemUsers = $db->loadObjectList();
		
		if (count($systemUsers)) {
			foreach ($systemUsers as $systemUser) {
				$this->mailTo = $systemUser->email;
				$this->message = sprintf(JText::_('COM_JOOBB_REPORTPOSTMESSAGE'), $systemUser->name, $reportComment, $topicLink, $this->siteName);
				$this->_sendMail();
			}
		} else {
			$messageQueue->addMessage(JText::_('COM_JOOBB_MSGREPORTNOTSENT')); return false;
		}
		
		return true;
	}
}
?>