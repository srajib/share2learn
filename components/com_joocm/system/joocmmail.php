<?php
/**
 * @version $Id: joocmmail.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Mail
 *
 * @package Joo!CM
 */
class JoocmMail
{
	/**
	 * mail from
	 * @var string
	 */
	var $mailFrom = null;
	
	/**
	 * from name
	 * @var string
	 */
	var $fromName = null;
	
	/**
	 * mail to
	 * @var string
	 */
	var $mailTo = null;
	
	/**
	 * subject
	 * @var string
	 */
	var $subject = null;
	
	/**
	 * message
	 * @var string
	 */
	var $message = null;
			
	/**
	 * site name
	 * @var string
	 */
	var $siteName = null;
			
	/**
	 * site url
	 * @var string
	 */
	var $siteURL = null;
			
	/**
	 * joocm mail
	 */	
	function JoocmMail() {
	
		// initialize variables
		$app =& JFactory::getApplication();
		
		// initialize object attributes
		$this->mailFrom = $app->getCfg('mailfrom');
		$this->fromName = $app->getCfg('fromname');
		$this->siteName = $app->getCfg('sitename');
		$this->siteURL = JURI::root();
	}
	
	/**
	 * get instance
	 *
	 * @access public
	 * @return object
	 */
	function &getInstance() {
	
		static $joocmMail;

		if (!is_object($joocmMail)) {
			$joocmMail = new JoocmMail();
		}

		return $joocmMail;
	}

	function sendRegistrationMail(&$joocmUser) {

		// initialize variables
		$db				=& JFactory::getDBO();		
		$joocmConfig	=& JoocmConfig::getInstance();
		
		$name = $joocmUser->get('name');
		$userName = $joocmUser->get('username');
		$password = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
		$password = preg_replace('/[\x00-\x1F\x7F]/', '', $password); // disallow control chars in the email

		$this->mailTo = $joocmUser->get('email');
		$this->subject = sprintf(JText::_('COM_JOOCM_ACCOUNTACTIVATIONSUBJECTUSER'), $this->siteName);
		
		switch($joocmConfig->getConfigSettings('account_activation')) {
			case 0:		// no activation needed
				$this->message = sprintf(JText::_('COM_JOOCM_ACCOUNTACTIVATIONNO'), $name, $this->siteName, $this->siteURL, $userName, $password);
				$this->_sendMail();
				break;
			case 1:		// activation by user
				$joocmUser->setActivation();
				
				$activationLink = $this->siteURL."index.php?option=com_joocm&task=joocmactivateprofile&activation=".$joocmUser->get('activation');
				$this->message = sprintf(JText::_('COM_JOOCM_ACCOUNTACTIVATIONUSER'), $name, $this->siteName, $activationLink, $this->siteURL, $userName, $password);
				$this->_sendMail();
				break;
			case 2:		// activation by admin
				$joocmUser->setActivation();
							
				// handle user email
				$this->message = sprintf(JText::_('COM_JOOCM_ACCOUNTACTIVATIONADMINUSER'), $name, $this->siteName, $this->siteURL, $userName, $password);
				$this->_sendMail();
				
				// handle system emails
				$query = "SELECT u.name, u.email"
						. "\n FROM #__users AS u"
						. "\n INNER JOIN #__joocm_users AS ju ON ju.id = u.id"
						. "\n WHERE ju.system_emails = 1";
				$db->setQuery($query);
				$rows = $db->loadObjectList();				
				
				$activationLink = $this->siteURL."index.php?option=com_joocm&task=joocmactivateprofile&activation=".$joocmUser->get('activation');
				$this->subject = sprintf(JText::_('COM_JOOCM_ACCOUNTACTIVATIONSUBJECTADMIN'), $this->siteName, $this->userName);

				foreach ($rows as $row) {
					$this->message = sprintf(JText::_('COM_JOOCM_ACCOUNTACTIVATIONADMINADMIN'), $row->name, $userName, $this->siteURL, $activationLink);
					$this->mailTo = $row->email;
					$this->_sendMail();
				}
				break;
		}
						
	}
	
	function sendConfirmationMail(&$joocmUser) {

		$this->mailTo = $joocmUser->get('email');
		$this->subject = sprintf(JText::_('COM_JOOCM_CONFIRMATIONSUBJECT'), $this->siteName);
		$this->message = sprintf(JText::_('COM_JOOCM_CONFIRMATIONMESSAGE'), $joocmUser->get('name'), $this->siteURL, $this->siteName);

		$this->_sendMail();			
	}
	
	function sendRequestLoginMail(&$joocmUser) {

		// initialize variables
		$Itemid	= JRequest::getVar('Itemid');

		$this->mailTo = $joocmUser->get('email');
		$this->subject = sprintf(JText::_('COM_JOOCM_LOGINREQUESTSUBJECT'), $this->siteName);
		$resetLoginLink = JRoute::_($this->siteURL.'index.php?option=com_joocm&view=resetlogin&activation='.$joocmUser->get('activation').'&Itemid='.$Itemid);
		$this->message = sprintf(JText::_('COM_JOOCM_LOGINREQUESTMESSAGE'), $joocmUser->get('name'), $resetLoginLink, $this->siteName);

		$this->_sendMail();
		
		return true;
	}
		
	function _sendMail() {
		JUtility::sendMail($this->mailFrom, $this->fromName, $this->mailTo, $this->subject, $this->message);
	}
}
?>