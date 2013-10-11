<?php
/**
 * @version $Id: joocm.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Joo!CM Controller
 *
 * @package Joo!CM
 */
class JoocmController extends JController
{
	/**
	 * display joocm
	 */
	function display() {

		// initialize variables
		$app			=& JFactory::getApplication();
		$document		=& JFactory::getDocument();
		$joocmConfig	=& JoocmConfig::getInstance();
		$breadCrumbs	=& $app->getPathway();
		$Itemid 		= JoocmHelper::getItemId('com_joocm');
		
		$viewName	= JRequest::getVar('view');
		$viewType	= $document->getType();

		$view = &$this->getView($viewName, $viewType);
		
		// set redirect url except login
		if ($viewName != 'login') {
			JoocmHelper::setRedirect();
		}

		// get current joocm user
		$joocmUser =& JoocmHelper::getJoocmUser();

		if ($joocmUser->id > 0 && !$joocmUser->get('agreed_terms') && $joocmConfig->getConfigSettings('enable_terms') && $viewName != 'terms') {
			if ($viewName != 'main') {
				$this->setRedirect(JRoute::_('index.php?option=com_joocm&view=main&Itemid='.$Itemid, false)); return;
			}
			$app->enqueueMessage(JText::_('COM_JOOCM_MSGTERMSOFAGREEMENTNEEDED'));
		}

		if ($joocmConfig->getConfigSettings('force_edit_profile') && $joocmUser->isRequiredFieldEmpty() && $joocmUser->get('agreed_terms')) {
			if ($viewName != 'editprofile') {
				$this->setRedirect(JRoute::_('index.php?option=com_joocm&view=editprofile&Itemid='.$Itemid, false)); return;
			}
			$app->enqueueMessage(JText::_('COM_JOOCM_MSGREQUIREDFIELDNEEDED'));
		}
		
		$model = &$this->getModel($viewName);
		if (!JError::isError($model)) {
			$view->setModel($model, true);
		}
		
		// handle bread crumbs
		if ($joocmConfig->getConfigSettings('enable_root_pathway')) {
			$breadCrumbs->addItem(JText::_($joocmConfig->getConfigSettings('community_name')), JRoute::_('index.php?option=com_joocm&view=main&Itemid='.$Itemid, false));
		}
		
		// assign application
		$view->assignRef('app', $app);
		
		// assign config
		$view->assignRef('joocmConfig', $joocmConfig);
	
		// assign document
		$view->assignRef('document', $document);

		// assign document
		$view->assignRef('breadCrumbs', $breadCrumbs);

		// assign item id
		$view->assign('Itemid', $Itemid);

		// assign current time
		$currentTime = JoocmHelper::Date(gmdate("Y-m-d H:i:s"));
		$view->assignRef('currentTime', $currentTime);
		
		// assign current time zone name
		$currentTimeZoneName = JoocmHelper::getCurrentTimeZoneName();
		$view->assignRef('currentTimeZoneName', $currentTimeZoneName);
				
		// assign current joocm user
		$view->assignRef('joocmUser', $joocmUser);
		
		// display the view
		$view->assign('error', $this->getError());
		$view->display();
	}
	
	/**
	 * login
	 */
	function joocmLogin() {

		// initialize variables
		$app			=& JFactory::getApplication();
		$joocmConfig	=& JoocmConfig::getInstance();
		$username		= JRequest::getVar('login_username', '', 'method', 'username');
		$password		= JRequest::getString('login_password', '', 'post', JREQUEST_ALLOWRAW);
		$Itemid			= JRequest::getVar('Itemid', 0);
		
		// get the request data
		$post = JRequest::get('post');
		
		if ($joocmConfig->getCaptchaSettings('captcha_login')) {
			$session =& JFactory::getSession();		
			if (md5($post['captcha_code']) != $session->get('captcha_code'))  {
				$this->setRedirect(JoocmHelper::getLink('login'), JText::_('COM_JOOCM_CAPTCHACODEDONOTMATCH')); return;
			}
		}

		if (empty($username)) {
			$this->setRedirect(JoocmHelper::getLink('login'), JText::_('COM_JOOCM_MSGENTERUSERNAME')); return;
		} else if (empty($password)) {
			$this->setRedirect(JoocmHelper::getLink('login'), JText::_('COM_JOOCM_MSGENTERPASS')); return;
		}

		$options = array();
		$options['remember'] = JRequest::getBool('remember', false);
		$options['return'] = '';

		$credentials = array();
		$credentials['username'] = $username;
		$credentials['password'] = $password;
	
		$error = $app->login($credentials, $options);
		
		if (JError::isError($error)) {
			$link = JoocmHelper::getLink('login');
		} else {
			$link = JoocmHelper::getRedirect();
		}

		$this->setRedirect($link);
	}
			
	/**
	 * logout
	 */
	function joocmLogout() {

		// initialize variables
		$app =& JFactory::getApplication();
	
		// get redirect which must be called before logout!
		$link = JoocmHelper::getRedirect();
		
		//preform the logout action
		$error = $app->logout();

		if(!JError::isError($error)) {
			$app->enqueueMessage(JText::_('COM_JOOCM_MSGLOGGEDOUT'));
		}

		$this->setRedirect($link);
	}
		
	/**
	 * saves the account
	 */
	function joocmSaveAccount() {

		// initialize variables
		$joocmUser		=& JoocmHelper::getJoocmUser();
		$Itemid			= JRequest::getVar('Itemid', 0);
		$msgType		= '';
		
		$link = JRoute::_('index.php?option=com_joocm&view=main&Itemid='.$Itemid, false);
		
		if ($joocmUser->get('id') < 1) {
			$msg = JText::_('COM_JOOCM_MSGNOPERMISSIONEDITACCOUNT'); $msgType = 'notice';
		} else {
			$msg = JText::_('COM_JOOCM_MSGACCOUNTSAVED');

			if (!$joocmUser->saveAccount(JRequest::get('post'))) {
				$link = JRoute::_('index.php?option=com_joocm&view=editaccount&Itemid='.$Itemid, false);
				$msg = JText::_($joocmUser->getError()); $msgType = 'error';
			}
		}

		$this->setRedirect($link, $msg, $msgType);				
	}
			
	/**
	 * saves the profile
	 */
	function joocmSaveProfile() {

		// initialize variables
		$joocmUser		=& JoocmHelper::getJoocmUser();
		$Itemid			= JRequest::getVar('Itemid', 0);
		$msgType		= '';

		$link = JRoute::_('index.php?option=com_joocm&view=main&Itemid='.$Itemid, false);

		if ($joocmUser->get('id') < 1) {
			$msg = JText::_('COM_JOOCM_MSGNOPERMISSIONEDITPROFILE'); $msgType = 'notice';
		} else {		
			$msg = JText::_('COM_JOOCM_MSGPROFILESAVED');
			
			if (!$joocmUser->saveProfile(JRequest::get('post'))) {
				$link = JRoute::_('index.php?option=com_joocm&view=editprofile&Itemid='.$Itemid, false);
				$msg = JText::_($joocmUser->getError()); $msgType = 'error';		
			}
		}

		$this->setRedirect($link, $msg, $msgType);				
	}
		
	/**
	 * saves the settings
	 */
	function joocmSaveSettings() {

		// initialize variables
		$joocmUser		=& JoocmHelper::getJoocmUser();
		$Itemid			= JRequest::getVar('Itemid', 0);
		$msgType		= '';
		
		$link = JRoute::_('index.php?option=com_joocm&view=main&Itemid='.$Itemid, false);
		
		if ($joocmUser->get('id') < 1) {
			$msg = JText::_('COM_JOOCM_MSGNOPERMISSIONEDITSETTINGS'); $msgType = 'notice';
		} else {
			$msg = JText::_('COM_JOOCM_MSGSETTINGSSAVED');
			
			if (!$joocmUser->saveSettings(JRequest::get('post', JREQUEST_ALLOWRAW))) {
				$link = JRoute::_('index.php?option=com_joocm&view=editsettings&Itemid='.$Itemid, false);
				$msg = JText::_($joocmUser->getError()); $msgType = 'error';		
			}
		}

		$this->setRedirect($link, $msg, $msgType);				
	}
	
	/**
	 * aggree terms
	 */
	function joocmAgreedTerms() {

		// initialize variables
		$joocmUser		=& JoocmHelper::getJoocmUser();
		$Itemid			= JRequest::getVar('Itemid', 0);
		$msgType		= '';
		
		$link = JRoute::_('index.php?option=com_joocm&view=main&Itemid='.$Itemid, false);
	
		if ($joocmUser->get('id') < 1) {
			$msg = JText::_('COM_JOOCM_MSGNOPERMISSIONAGREETERMS'); $msgType = 'notice';
		} else {
			$msg = JText::_('COM_JOOCM_MSGAGREEDTERMS');
			
			if (!$joocmUser->agreedTerms()) {
				$msg = JText::_($joocmUser->getError()); $msgType = 'error';		
			}
		}

		$this->setRedirect($link, $msg, $msgType);				
	}
	
	/**
	 * register account
	 */
	function joocmRegister() {

		// initialize variables
		$joocmConfig	=& JoocmConfig::getInstance();
		$joocmUser		= new JoocmUser(0, true);
		$Itemid			= JRequest::getVar('Itemid', 0);
		
		// get the request data
		$post = JRequest::get('post');
		
		if ($joocmConfig->getCaptchaSettings('captcha_register')) {
			$session =& JFactory::getSession();		
			if (md5($post['captcha_code']) != $session->get('captcha_code'))  {
				$session->set('joocmRegisterForm', $post);
				$this->setRedirect(JRoute::_('index.php?option=com_joocm&view=register&Itemid='.$this->Itemid, false), JText::_('COM_JOOCM_CAPTCHACODEDONOTMATCH'), 'notice'); return;
			}
		}

		if (!$joocmUser->registerAccount($post)) {
			$session =& JFactory::getSession();
			$session->set('joocmRegisterForm', $post);
			$this->setRedirect(JRoute::_('index.php?option=com_joocm&view=register&Itemid='.$this->Itemid, false), $joocmUser->getError(), 'error'); return;	
		}

		// send email
		$joocmMail =& JoocmMail::getInstance();
		$joocmMail->sendRegistrationMail($joocmUser);

		$this->setRedirect(JRoute::_('index.php?option=com_joocm&view=information&info=account_activation&user='.$joocmUser->get('id').'&Itemid='.$Itemid, false));		
	}	
	
	/**
	 * activate account
	 */
	function joocmActivateProfile() {

		// initialize variables
		$db				=& JFactory::getDBO();
		$joocmConfig	=& JoocmConfig::getInstance();
		$Itemid			= JRequest::getVar('Itemid', 0);

		$activation = JRequest::getVar('activation', '');

		if ($activation) {
			$query = "SELECT u.id"
					. "\n FROM #__users AS u"
					. "\n WHERE u.activation = '$activation'"
					;
			$db->setQuery($query);
			$userId = $db->loadResult();
			
			if ($userId) {
				$joocmUser = JoocmUser::getInstance($userId);
				
				if ($joocmUser) {
					$joocmUser->set('activation', '');
					$joocmUser->set('block', 0);
					$joocmUser->save();
					
					// ToDo: what if admin changes account activation meanwhile?!
					if ($joocmConfig->getConfigSettings('account_activation') == 2) {
					
						// send email
						$joocmMail =& JoocmMail::getInstance();
						$joocmMail->sendConfirmationMail($joocmUser);
					}
					
					$link = JRoute::_('index.php?option=com_joocm&view=information&info=account_activated&user='.$joocmUser->get('id').'&Itemid='.$Itemid, false);
				} else {
					$link = JRoute::_('index.php?option=com_joocm&view=information&info=account_activation_failed&Itemid='.$Itemid.'user'.$userId.'activation'.$activation, false);
				}			
			} else {
				$link = JRoute::_('index.php?option=com_joocm&view=information&info=account_activation_failed&Itemid='.$Itemid, false);
			}

		} else {
			// ToDo: what if there is no activation code?
		}

		$this->setRedirect($link);		
	}
	
	/**
	 * request login
	 */
	function joocmRequestLogin() {

		// initialize variables
		$db				=& JFactory::getDBO();
		$email		= JRequest::getVar('email');
		$Itemid 	= JRequest::getVar('Itemid', 0);
		$msgType 	= '';

		$query = "SELECT u.id"
				. "\n FROM #__users AS u"
				. "\n WHERE u.email = '$email'"
				;
		$db->setQuery($query);
		$userId = $db->loadResult();

		if ($userId) {
		
			// set activation code
			jimport('joomla.user.helper');
			$joocmUser =& JoocmUser::getInstance($userId);
			$joocmUser->set('activation', md5(JUserHelper::genRandomPassword()));
			
			// do not use joocm user object to save activation 
			// user::save() disallow saving admin users by non admin
			$query	= "UPDATE #__users"
					. "\n SET activation = ".$db->Quote($joocmUser->get('activation'))
					. "\n WHERE id = $userId"
					. "\n AND block = 0";
			$db->setQuery($query);
			
			if ($db->query()) {
			
				// send email
				$joocmMail =& JoocmMail::getInstance();
				if ($joocmMail->sendRequestLoginMail($joocmUser)) {
					$link = JRoute::_('index.php?option=com_joocm&view=information&info=request_login&Itemid='.$Itemid, false);
				} else {
					// ToDo: unreachable for now: see JoocmHelper::sendRequestLoginMail!!!
					$link = JRoute::_('index.php?option=com_joocm&view=requestlogin&Itemid='.$Itemid, false);
					$msg = JText::_('COM_JOOCM_MSGMAILNOTSENT'); $msgType = 'notice';
				}
			} else {
				$link = JRoute::_('index.php?option=com_joocm&view=requestlogin&Itemid='.$Itemid, false);
				$msg = $db->getError(); $msgType = 'error';
			}
		} else {
			$link = JRoute::_('index.php?option=com_joocm&view=requestlogin&Itemid='.$Itemid, false);
			$msg = JText::_('COM_JOOCM_MSGUSERNOTFOUND'); $msgType = 'notice';
		}


		$this->setRedirect($link, $msg, $msgType);
	}	
	
	/**
	 * request login
	 */
	function joocmResetLogin() {
	
		// initialize variables
		$db				=& JFactory::getDBO();
		$joocmUser		=& JoocmUser::getInstance(JRequest::getVar('id', 0, '', 'int'));
		$activation		= JRequest::getVar('activation', '');
		$Itemid			= JRequest::getVar('Itemid', 0);
		$msgType		= '';
	
		if ($joocmUser->get('activation') == $activation) {
		
			// get the request data
			$password = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
			$link = JRoute::_('index.php?option=com_joocm&view=information&info=reset_success&Itemid='. $Itemid, false);
			
			// do not use joocm user object to save activation. user::save() disallow saving admin users by non admin			
			jimport('joomla.user.helper');
			$salt		= JUserHelper::genRandomPassword(32);
			$crypt		= JUserHelper::getCryptedPassword($password, $salt);
			$password	= $crypt.':'.$salt;
	
			// Build the query
			$query 	= "UPDATE #__users"
					. "\n SET password = ".$db->Quote($password)
					. "\n , activation = ''"
					. "\n WHERE id = ".(int) $joocmUser->get('id')
					. "\n AND activation = ".$db->Quote($activation)
					. "\n AND block = 0"
					;
			$db->setQuery($query);

			if (!$db->query()) {
				$link = JRoute::_('index.php?option=com_joocm&view=information&info=reset_failure&Itemid='. $Itemid, false);
				$msg = $db->getError(); $msgType = 'error';
			}			
		} else {
			$link = JRoute::_('index.php?option=com_joocm&view=main&Itemid='. $Itemid, false);
			$msg = JText::_('JOOCM_MSGACTIVATIONFAILED'); $msgType = 'error';
		}
		
		$this->setRedirect($link, $msg, $msgType);		
	}
	
	/**
	 * save avatar
	 */
	function joocmSaveAvatar() {

		// initialize variables
		$joocmUser		=& JoocmHelper::getJoocmUser();
		$Itemid			= JRequest::getVar('Itemid', 0, '', 'int');
		$msgType		= '';
		
		$link = JRoute::_('index.php?option=com_joocm&view=main&Itemid='. $Itemid, false);
		
		if ($joocmUser->get('id') < 1) {
			$msg = JText::_('COM_JOOCM_MSGNOPERMISSIONMANAGEAVATARS'); $msgType = 'notice';
		} else {
			$msg = JText::_('COM_JOOCM_MSGAVATARSAVESUCCESS');
			
			if (!$joocmUser->saveAvatar(JRequest::getVar('id_avatar', 0, '', 'int')))  {
				$msg = JText::_($joocmUser->getError()); $msgType = 'error';
			}	
		}

		$this->setRedirect($link, $msg, $msgType);		
	}
	
	/**
	 * upload avatar
	 */
	function joocmUploadAvatar() {

		// initialize variables
		$joocmAvatar	=& JoocmAvatar::getInstance();
		$Itemid			= JRequest::getVar('Itemid', 0, '', 'int');
		$msg			= '';
		
		$link = JRoute::_('index.php?option=com_joocm&view=avatar&Itemid='. $Itemid, false);
		
		if ($joocmAvatar->uploadAvatar(JRequest::getVar('avatarfile', '', 'files', 'array'), JRequest::getVar('avatarurl', ''), JRequest::getVar('my_avatar_id', 0, '', 'int'))) {
			$msg = JText::_('COM_JOOCM_MSGAVATARUPLOADSUCCESS');
		}

		$this->setRedirect($link, $msg);		
	}
	
	/**
	 * delete avatar
	 */
	function joocmDeleteAvatar() {

		// initialize variables
		$joocmAvatar	=& JoocmAvatar::getInstance();
		$Itemid			= JRequest::getVar('Itemid', 0, '', 'int');
		$msg			= '';
		
		$link = JRoute::_('index.php?option=com_joocm&view=avatar&Itemid='. $Itemid, false);
		
		if ($joocmAvatar->deleteAvatar(JRequest::getVar('id_avatar', 0, '', 'int'))) {
			$msg = JText::_('COM_JOOCM_MSGAVATARDELETESUCCESS');
		}

		$this->setRedirect($link, $msg);
	}
}
?>