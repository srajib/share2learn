<?php
/**
 * @version $Id: joocmavatar.php 212 2012-02-25 16:56:09Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Avatar
 *
 * @package Joo!CM
 */
class JoocmAvatar
{
			
	/**
	 * avatars max
	 *
	 * @var string
	 */
	var $avatarsMax;
	
	/**
	 * avatar width
	 *
	 * @var string
	 */
	var $avatarWidth;
			
	/**
	 * avatar height
	 *
	 * @var string
	 */
	var $avatarHeight;
					
	/**
	 * avatar path
	 *
	 * @var string
	 */
	var $avatarPath;
					
	/**
	 * avatar save path
	 *
	 * @var string
	 */
	var $avatarSavePath;
						
	/**
	 * standard avatars
	 *
	 * @var array
	 */
	var $standardAvatars;
	
	/**
	 * user avatars
	 *
	 * @var array
	 */
	var $userAvatars;
	
	/**
	 * avatar source
	 *
	 * @var string
	 */
	var $avatarSource;
			
	/**
	 * constructor
	 */
	function JoocmAvatar() {
		$joocmConfig			=& JoocmConfig::getInstance();
		$this->avatarSource		= $joocmConfig->getConfigSettings('avatar_source');
		$this->avatarsMax		= $joocmConfig->getAvatarSettings('avatars_max');
		$this->avatarWidth		= $joocmConfig->getAvatarSettings('avatar_width');
		$this->avatarHeight		= $joocmConfig->getAvatarSettings('avatar_height');
		$this->avatarSavePath	= $joocmConfig->getAvatarSettings('avatar_save_path');
		$this->avatarPath		= $joocmConfig->getAvatarSettings('avatar_path');
	}
	
	/**
	 * get instance
	 *
	 * @access 	public
	 * @return object
	 */
	function &getInstance() {
	
		static $joocmAvatar;

		if (!is_object($joocmAvatar)) {
			$joocmAvatar = new JoocmAvatar();
		}

		return $joocmAvatar;
	}
	
	/**
	 * get standard avatars
	 * 
	 * @access public
	 * @return array
	 */
	function getStandardAvatars() {
	
		if (empty($this->standardAvatars)) {
			$db	=& JFactory::getDBO();
			$query = "SELECT a.*"
					. "\n FROM #__joocm_avatars AS a"
					. "\n WHERE a.id_user = 0"
					;
			$db->setQuery($query);
			$this->standardAvatars = $db->loadObjectList();
		}
		
		return $this->standardAvatars;
	}
	
	/**
	 * get user avatars
	 * 
	 * @access public
	 * @return array
	 */
	function getUserAvatars($idUser) {
	
		if (empty($this->userAvatars)) {
			$db	=& JFactory::getDBO();
			$query = "SELECT a.*"
					. "\n FROM #__joocm_avatars AS a"
					. "\n WHERE a.id_user = $idUser"
					;
			$db->setQuery($query);
			$this->userAvatars = $db->loadObjectList();
		}
		
		return $this->userAvatars;
	}
	
	/**
	 * get user avatar
	 * 
	 * @access public
	 * @return array
	 */
	function getUserAvatar($idUser) {
		$db	=& JFactory::getDBO();
		
		// make sure we have an interger value
		$idUser = (int)$idUser;
		
		switch ($this->avatarSource) {		
			case 1:
				$query = "SELECT a.*"
						. "\n FROM #__joocm_avatars AS a"
						. "\n INNER JOIN #__joocm_users AS u ON u.id_avatar = a.id"
						. "\n WHERE u.id = $idUser"
						;
				break;
			case 2:
				$query = "SELECT cb.avatar"
						. "\n FROM #__comprofiler AS cb"
						. "\n WHERE cb.id = $idUser"
						;
				break;
			case 3:
				$query = "SELECT cbe.avatar"
						. "\n FROM #__cbe AS cbe"
						. "\n WHERE cbe.id = $idUser"
						;
				break;
			case 4:
				$query = "SELECT idb.avatar"
						. "\n FROM #__idoblog_users AS idb"
						. "\n WHERE idb.iduser = $idUser"
						;
				break;
			case 5:
				$query = "SELECT js.avatar"
						. "\n FROM #__community_users AS js"
						. "\n WHERE js.userid = $idUser"
						;
				break;
			case 6:
				$query = "SELECT k2.image AS avatar"
						. "\n FROM #__k2_users AS k2"
						. "\n WHERE k2.userID = $idUser"
						;
				break;
			case 7:
				$query = "SELECT aup.avatar"
						. "\n FROM #__alpha_userpoints AS aup"
						. "\n WHERE aup.userid = $idUser"
						;
				break;
			default:
				$query = "";
				break;
		}

		$db->setQuery($query);
		$this->userAvatar = $db->loadObject();
			
		return $this->userAvatar;
	}
	
	/**
	 * get avatar file
	 *
	 * @access 	public
	 */
	function getAvatarFile($idUser) {
		$avatarFile = '';
		
		$avatar = $this->getUserAvatar($idUser);
		
		if (is_object($avatar)) {
			switch ($this->avatarSource) {		
				case 1:		// Joo!CM
					$avatarFile = $this->getJoocmAvatarFile($avatar);
					break;
				case 2:		// Community Builder
					if ($avatar->avatar != '') {
						$avatarFile = JURI::root().'images'.DL.'comprofiler'.DL.$avatar->avatar;
					}
					break;
				case 3:		// Community Builder Extended
					if ($avatar->avatar != '') {
						$avatarFile = JURI::root().'images'.DL.'cbe'.DL.$avatar->avatar;
					}
					break;
				case 4:		// IDoBlog
					if ($avatar->avatar != '') {
						$avatarFile = JURI::root().'images'.DL.'idoblog'.DL.$avatar->avatar;
					}
					break;
				case 5:		// JomSocial
					if ($avatar->avatar != '') {
						$avatarFile = JURI::root().$avatar->avatar;
					}
					break;
				case 6:		// K2
					if ($avatar->avatar != '') {
						$avatarFile = JURI::root().'media'.DL.'k2'.DL.'users'.DL.$avatar->avatar;
					}
					break;
				case 7:		// Alpha User Points
					if ($avatar->avatar != '') {
						$avatarFile = JURI::root().'components'.DL.'com_alphauserpoints'.DL.'assets'.DL.'images'.DL.'avatars'.DL.$avatar->avatar;
					}
					break;
				default:
					break;
			}
		}
		
		// last chance to get an avatar image
		if ($avatarFile == '') {
			$avatarFile = JURI::root().$this->avatarPath.DL;
			if (!is_dir($avatarFile)) {
				$avatarFile = $this->avatarPath.DL;
			}
			$avatarFile .= 'standard'.DL.'_cm_noavatar.png';
			//$avatarFile = JURI::root().'media'.DL.'joocm'.DL.'avatars'.DL.'standard'.DL.'_cm_noavatar.png';
		}
	
		return $avatarFile;
	}
	
	/**
	 * get joocm avatar file
	 *
	 * @access 	public
	 */	
	function getJoocmAvatarFile($avatar) {
		$avatarFile = '';
		
		if (is_object($avatar)) {
			$pos = strpos($avatar->avatar_file, 'http://');
			if ($pos === false) {
				if ($avatar->avatar_file != '') {
					$avatarFile = JURI::root().$this->avatarPath.DL;
					if (!is_dir($avatarFile)) {
						$avatarFile = $this->avatarPath.DL;
					}
					if ($avatar->id_user) {
						$avatarFile .= $avatar->id_user.DL.$avatar->avatar_file;
					} else {
						$avatarFile .= 'standard'.DL.$avatar->avatar_file;
					}
				}
			} else {
				$avatarFile = $avatar->avatar_file;
			}
		}

		if ($avatarFile == '') {
		    $avatarFile = JURI::root().$this->avatarPath;
			if (!is_dir($avatarFile)) {
				$avatarFile = $this->avatarPath.DL;
			}
			$avatarFile .= 'standard'.DL.'_cm_noavatar.png';
		}
	
		return $avatarFile;
	}
					
	/**
	 * delete avatar
	 *
	 * @access 	public
	 */	
	function deleteAvatar($avatarId = 0) {

		// initialize variables
		$app			=& JFactory::getApplication();
		$joocmConfig	=& JoocmConfig::getInstance();
		$joocmUser		=& JoocmHelper::getJoocmUser();
		
		if ($avatarId == 0) {
			$app->enqueueMessage(JText::_('COM_JOOCM_MSGNOAVATARSELECTED')); return false;
		}
		
		if ($joocmUser->get('id') < 1) {
			return false;
		}
		
		$avatar =& JTable::getInstance('JoocmAvatar');
		$avatar->load($avatarId);
		
		if ($joocmUser->get('id') <> $avatar->id_user) {
			$app->enqueueMessage(JText::_('COM_JOOCM_MSGNOPERMISSIONMANAGEAVATARS')); return false;
		}
		
		if (!unlink(JPath::clean(JPATH_ROOT.DS.$this->avatarSavePath.DS.$joocmUser->get('id')).DS.$avatar->avatar_file)) {
			$app->enqueueMessage(JText::_('COM_JOOCM_MSGAVATARFILENOTDELETED')); return false;
		}
		
		$avatar->delete();
		
		return true;
	}

	/**
	 * upload avatar
	 *
	 * @access 	public
	 */	
	function uploadAvatar($avatarFile, $avatarURL = '', $avatarId = 0) {

		// initialize variables
		$app			=& JFactory::getApplication();
		$joocmConfig	=& JoocmConfig::getInstance();
		$joocmUser		=& JoocmHelper::getJoocmUser();
		
		if ($joocmUser->get('id') < 1) {
			return false;
		}
		
		if ($avatarURL == '' && $avatarFile['name'] == '') {
			$app->enqueueMessage(JText::_('COM_JOOCM_MSGNOAVATARTOUPLOAD'), 'notice'); return false;
		}
		
		$userAvatarsCount = count($this->getUserAvatars($joocmUser->get('id')));
		if ($userAvatarsCount >= $this->avatarsMax && $avatarId == 0) {
			$app->enqueueMessage(JText::sprintf('COM_JOOCM_MSGAVATARMAXREACHED', $this->avatarsMax), 'notice'); return false;	
		}

		$avatar =& JTable::getInstance('JoocmAvatar');
		$avatar->load($avatarId);

		if ($avatarFile != '') {
			$avatarUserPath = JPath::clean(JPATH_ROOT.DS.$this->avatarSavePath.DS.$joocmUser->get('id'));
		
			$pos = strpos($avatar->avatar_file, 'http://');
			if ($avatarId == 0 || $pos !== false) {
				$pathParts = pathinfo($avatarFile['name']);
				
				// generate a unique file name
				jimport('joomla.user.helper');
				$fileName = strtolower(JUserHelper::genRandomPassword(10).'.'.$pathParts['extension']);
				while (file_exists($avatarUserPath.DS.$fileName)) {
					$fileName = strtolower(JUserHelper::genRandomPassword(10).'.'.$pathParts['extension']);
				}
				$avatar->avatar_file = $fileName;
				$avatar->id_user = $joocmUser->get('id');
				
				if (!$avatar->store()) {
					return false;
				}
			}
			if (!$this->uploadAvatarFile($avatarFile, $avatarUserPath.DS.$avatar->avatar_file)) {
				return false;
			}
		}
		
		if ($avatarURL != '') {
			$avatar->avatar_file = $avatarURL;
			$avatar->id_user = $joocmUser->get('id');
			if (!$avatar->store()) {
				return false;
			}
		}
				
		return true;
	}

	/**
	 * upload avatar file
	 *
	 * @access 	public
	 */		
	function uploadAvatarFile($avatarFile, $fileName = '') {

		// initialize variables
		$app			=& JFactory::getApplication();
		$joocmConfig	=& JoocmConfig::getInstance();
		$joocmUser		=& JoocmHelper::getJoocmUser();
		$result			= false;
		
		jimport('joomla.filesystem.file');
		
		if (isset($avatarFile['name']) && $avatarFile['name'] != '') {
			$fileTypes = $joocmConfig->getAvatarSettings('avatar_file_types');
			
			// check file types
			$fileTypes = str_replace(",", "|", $fileTypes); // we have to use comma and replace it! joomla is using "|" since rc4
			if (preg_match("/$fileTypes/i", $avatarFile['name'])) {

				// here we go with automatic image resize
				$joocmGD =& JoocmGD::getInstance();
				if ($joocmConfig->getAvatarSettings('image_resize') && $joocmGD->isEnabled()) {
					if (!$joocmGD->resizeImage($avatarFile['tmp_name'], $this->avatarWidth, $this->avatarHeight)) {
						return false;
					}	
				} else {
				
					// check max file size
					if ($avatarFile['size'] > $joocmConfig->getAvatarSettings('avatar_max_file_size')) {
						$app->enqueueMessage(JText::sprintf('COM_JOOCM_MSGMAXIMALFILESIZE', $avatarFile['size'], $joocmConfig->getAvatarSettings('avatar_max_file_size')), 'notice'); return false;
					}

					// check image size
					$imageSize = getimagesize($avatarFile['tmp_name']);
					if ($imageSize[0] > $this->avatarWidth || $imageSize[1] > $this->avatarHeight) {	
						$app->enqueueMessage(JText::sprintf('COM_JOOCM_MSGREQUIREDWIDTHHEIGHT', $this->avatarWidth, $this->avatarHeight), 'notice'); return false;
					}
				}

				// upload avatar
				if (JFile::upload($avatarFile['tmp_name'], $fileName)) {
					$result = true;
				} else {
					$app->enqueueMessage(JText::sprintf('COM_JOOCM_MSGAVATARFILEUPLOADFAILED', $joocmUser->get('name')), 'error'); $result = false;
				}		
			} else {
				$app->enqueueMessage(JText::sprintf('COM_JOOCM_MSGFILETYPENOTSUPPORTED', $avatarFile['type']), 'notice'); $result = false;
			}
		}
		return $result;
	}			
} ?>