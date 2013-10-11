<?php
/**
 * @version $Id: joocmuser.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.parameter');

/**
 * Joo!CM User
 *
 * @package Joo!CM
 */
class JoocmUser extends JUser
{
	/**
	 * joocm user table
	 *
	 * @var object
	 */
	var $_joocmUserTable = null;

	/**
	 * joocm profile table
	 *
	 * @var object
	 */
	var $_joocmProfileTable = null;
	
	/**
	 * constructor
	 */
	function __construct($identifier = 0, $new = false) {
		$joocmConfig =& JoocmConfig::getInstance();
		
		// create the joocm user table object
		$this->_joocmUserTable =& JTable::getInstance('JoocmUser');

		if ($joocmConfig->getConfigSettings('enable_profiles')) {
		
			// create the joocm profile table object
			$this->_joocmProfileTable =& JTable::getInstance('JoocmProfile');
		}
		
		parent::__construct($identifier);
		
		// load the user if it exists
		if (!empty($identifier)) {
			$this->load($identifier);
		} else {
			if ($new) {

				// initialize
				$this->_joocmUserTable->id = 0;
				$this->_joocmUserTable->agreed_terms = $joocmConfig->getConfigSettings('enable_terms') ? 0 : 1;
				$this->_joocmUserTable->show_email = $joocmConfig->getUserSettingsDefaults('show_email');
				$this->_joocmUserTable->show_online_state = $joocmConfig->getUserSettingsDefaults('show_online_state');
				$this->_joocmUserTable->time_format = $joocmConfig->getUserSettingsDefaults('time_format');
			}
		}
	}

	/**
	 * get instance
	 *
	 * @access 	public
	 * @param int
	 * @return object
	 */
	function &getInstance($id = 0) {
	
		static $instances;

		if (!isset($instances)) {
			$instances = array();
		}

		if(!is_numeric($id)) {
			jimport('joomla.user.helper');
			if (!$id = JUserHelper::getUserId($id)) {
				JError::raiseWarning('SOME_ERROR_CODE', 'JoocmUser::_load: User '.$id.' does not exist');
				return false;
			}
		}

		if (empty($instances[$id])) {
			$joocmUser = new JoocmUser($id);
			$instances[$id] = $joocmUser;
		}

		return $instances[$id];
	}
	
	/**
	 * load joocm user
	 *
	 * @access 	public
	 */
	function load($id) {
		$db   =& JFactory::getDBO();
		
		// first of all load the original joomla user data
		if (!parent::load($id)) {
			return false;
		}	

		if (!$this->_joocmUserTable->load($id)) {
			$joocmConfig =& JoocmConfig::getInstance();
			
			// initialize
			$agreed_terms = $joocmConfig->getConfigSettings('enable_terms') ? 0 : 1;
			$show_email = $joocmConfig->getUserSettingsDefaults('show_email');
			$show_online_state = $joocmConfig->getUserSettingsDefaults('show_online_state');
			$time_format = $joocmConfig->getUserSettingsDefaults('time_format');
			
			$db->setQuery("INSERT INTO #__joocm_users (id, agreed_terms, show_email, show_online_state, time_format) VALUES ($id, $agreed_terms, $show_email, $show_online_state, '$time_format')");
			if (!$db->query()) {
				$this->setError($db->getError()); return false;
			} else if (!$this->_joocmUserTable->load($id)) {
				$this->setError($this->_joocmUserTable->getError()); return false;			
			}		
		}
		
		if (is_object($this->_joocmProfileTable) && !$this->_joocmProfileTable->load($id)) {
			$db->setQuery("INSERT INTO #__joocm_profiles (id) VALUES ($id)");
			if (!$db->query()) {
				$this->setError($db->getError()); return false;
			} else if (!$this->_joocmProfileTable->load($id)) {
				$this->setError($this->_joocmProfileTable->getError()); return false;			
			}
		}

		return true;	
	}
	
	/**
	 * bind user
	 *
	 * @access 	public
	 */
	function bind($data) {
	
		if (!parent::bind($data)) {
			return false;
		} else if (!$this->_joocmUserTable->bind($data)) {
			$this->setError($this->_joocmUserTable->getError()); return false;
		} else if (is_object($this->_joocmProfileTable) && !$this->_joocmProfileTable->bind($data)) {
			$this->setError($this->_joocmProfileTable->getError()); return false;
		}

		return true;
	}
	
	/**
	 * save user
	 *
	 * @access 	public
	 */
	function save() {

		$me =& JFactory::getUser();
		$acl =& JFactory::getACL();
		
		$objectID 	= $acl->get_object_id('users', $this->get('id'), 'ARO');
		$groups 	= $acl->get_object_groups($objectID, 'ARO' );
		$this_group = strtolower($acl->get_group_name($groups[0], 'ARO'));
		
		if ($this->get('id') == $me->get('id') && $this->get('block') == 1) {
			$this->setError(JText::_('You cannot block Yourself!')); return false;
		} else if ($this_group == 'super administrator' && $this->get('block') == 1) {
			$this->setError(JText::_('You cannot block a Super Administrator')); return false;
		} else if ($this_group == 'administrator' && $me->get('gid') == 24 && $user->get('block') == 1) {
			$this->setError(JText::_('WARNBLOCK')); return false;
		} else if ($this_group == 'super administrator' && $me->get('gid') != 25) {
			$this->setError(JText::_('You cannot edit a super administrator account')); return false;
		}
		
		$isNew = $this->id ? false : true;

		// handle new joomla user
		if ($isNew) {
			
			// set joomla user user type
			$usersConfig = &JComponentHelper::getParams('com_users');
			$newUsertype = $usersConfig->get('new_usertype');
			if (!$newUsertype) {
				$newUsertype = 'Registered';
			}
			$this->set('usertype', $newUsertype);
			
			$authorize =& JFactory::getACL();
			$this->set('gid', $authorize->get_group_id('', $newUsertype, 'ARO'));
			$this->set('registerDate', gmdate("Y-m-d H:i:s"));		
		}
		
		if (!parent::save()) {
			return false;
		}
		
		// handle new joocm user
		if ($isNew) {
			$db =& JFactory::getDBO();
			
			// we need a valid record. otherwise our data will be not stored.
			$db->setQuery("INSERT INTO #__joocm_users (id) VALUES ($this->id)");
			if (!$db->query()) {
				$this->setError($db->getError()); return false;
			}
			$this->_joocmUserTable->id = $this->id;
			
			if (is_object($this->_joocmProfileTable)) {
				$db->setQuery("INSERT INTO #__joocm_profiles (id) VALUES ($this->id)");
				if (!$db->query()) {
					$this->setError($db->getError()); return false;
				}
				$this->_joocmProfileTable->id = $this->id;
			}
		}
		
		if (!$this->_joocmUserTable->store()) {
			$this->setError($this->_joocmUserTable->getError()); return false;
		} else if (is_object($this->_joocmProfileTable) && !$this->_joocmProfileTable->store()) {
			$this->setError($this->_joocmProfileTable->getError()); return false;
		}

		return true;
	}
	
	/**
	 * delete user
	 *
	 * @access 	public
	 */
	function delete() {
	
		if (!parent::delete()) {
			return false;
		} else if (!$this->_joocmUserTable->delete()) {
			$this->setError($this->_joocmUserTable->getError()); return false;
		} else if (is_object($this->_joocmProfileTable) && !$this->_joocmProfileTable->delete()) {
			$this->setError($this->_joocmProfileTable->getError()); return false;
		}

		return true;
	}
		
	/**
	 * set property
	 *
	 * @access	public
	 */
	function set($property, $value=null) {
	
		if (isset($this->_joocmUserTable->$property)) {
			$this->_joocmUserTable->$property = $value;
		}
		if (is_object($this->_joocmProfileTable) && isset($this->_joocmProfileTable->$property)) {
			$this->_joocmProfileTable->$property = $value;
		}
		parent::set($property, $value);			
	}

	/**
	 * get property
	 *
	 * @access	public
	 */
	function get($property, $default=null) {

		if (isset($this->_joocmUserTable->$property)) {
			return $this->_joocmUserTable->$property;
		}
		if (is_object($this->_joocmProfileTable) && isset($this->_joocmProfileTable->$property)) {
			return $this->_joocmProfileTable->$property;
		}	
		
		$value = parent::get($property, $default);
		if(isset($value)) {
			return $value;
		}			
		return $default;
	}
	
	/**
	 * register account
	 *
	 * @access 	public
	 */
	function registerAccount($data) {

		// initialize variables
		$usersConfig = &JComponentHelper::getParams('com_users');

		if ($usersConfig->get('allowUserRegistration') == '0') {
			$this->setError(JText::_('COM_JOOCM_REGISTRATIONNOTALLOWED')); return false;
		}
		
		if (isset($data['id']) && $data['id'] > 0 || $this->get('id') > 0) {
			$this->setError(JText::_('COM_JOOCM_ALREADYREGISTERED')); return false;
		}
		
		// do a password safety check
		if(strlen($data['password']) || strlen($data['password2'])) {
			if($data['password'] != $data['password2']) {
				$this->setError(JText::_('COM_JOOCM_PASSWORDSDONOTMATCH')); return false;
			}
		}
		
		if (!parent::bind($data, 'usertype')) {
			$this->setError($this->getError()); return false;
		}
		
		$newUsertype = $usersConfig->get('new_usertype');
		if (!$newUsertype) {
			$newUsertype = 'Registered';
		}

		$this->set('id', 0);
		$this->set('usertype', $newUsertype);
		
		$authorize =& JFactory::getACL();
		$this->set('gid', $authorize->get_group_id( '', $newUsertype, 'ARO' ));
		$this->set('registerDate', gmdate("Y-m-d H:i:s"));

		if (!parent::save()) {
			$this->setError($this->getError()); return false;
		}
		
		// reload user data. this ensures that joocm user data will be saved.
		$this->load($this->id);
		
		if ($data['agreed']) {
			$this->agreedTerms();
		}
		
		return true;	
	}
	
	/**
	 * save account
	 *
	 * @access 	public
	 */
	function saveAccount($data) {

		if (isset($data['id']) && $this->get('id') != $data['id']) {
			$this->setError(JText::_('COM_JOOCM_NOWAYCHANGEOTHERUSERSDATA')); return false;
		}
		
		// do a password safety check
		if(strlen($data['password']) || strlen($data['password2'])) {
			if($data['password'] != $data['password2']) {
				$this->setError(JText::_('COM_JOOCM_PASSWORDSDONOTMATCH')); return false;
			}
		}

		// we don't want allow the user to edit certain fields
		unset($data['gid']);
		unset($data['block']);
		unset($data['sendEmail']);
		unset($data['usertype']);
		unset($data['registerDate']);
		unset($data['lastvisitDate']);
		unset($data['activation']);
		
		if (!parent::bind($data)) {
			$this->setError($this->getError()); return false;
		} else if (!parent::save()) {
			$this->setError($this->getError()); return false;
		}
		
		return true;	
	}

	/**
	 * saves the profile
	 *
	 * @access 	public
	 */
	function saveProfile($data) {

		if (!is_object($this->_joocmProfileTable)) {
			$this->setError(JText::_('COM_JOOCM_MSGJOOCMPROFILESNOTACTIVE')); return false;
		}
		
		// unset all 'disabled' fields
		$profileFields = $this->_joocmProfileTable->getProfileFields(JFactory::getDBO());
		foreach ($profileFields as $profileField) {
			if ($profileField->disabled) {
				unset($data[$profileField->name]);
			}
		}

		if (!$this->_joocmProfileTable->bind($data)) {
			$this->setError($this->_joocmProfileTable->getError()); return false;
		} else if (!$this->_joocmProfileTable->save($data)) {
			$this->setError($this->_joocmProfileTable->getError()); return false;
		}

		return true;	
	}
	
	/**
	 * saves the settings
	 *
	 * @access 	public
	 */
	function saveSettings($data) {

		if (isset($this->_joocmUserTable)) {
			
			// set the params
			if (is_array($data['params'])) {
				$txt = array();
				foreach ($data['params'] as $k=>$v) {
					$this->setParam($k, $v);
				}
			}
			parent::save();
			
			$this->_joocmUserTable->time_format = $data['time_format'];
			$this->_joocmUserTable->show_email = $data['show_email'];
			$this->_joocmUserTable->show_online_state = $data['show_online_state'];
			$this->_joocmUserTable->signature = $data['signature'];
			
			if (!$this->_joocmUserTable->check()) {
				$this->setError($this->_joocmUserTable->getError()); return false;
			} else if (!$this->_joocmUserTable->store()) {
				$this->setError($this->_joocmUserTable->getError()); return false;
			}
		}

		return true;	
	}
	
	/**
	 * saves the settings
	 *
	 * @access 	public
	 */
	function incrementViewsCount() {

		if (isset($this->_joocmUserTable)) {
			
			$this->_joocmUserTable->views_count++;

			if (!$this->_joocmUserTable->check()) {
				$this->setError($this->_joocmUserTable->getError()); return false;
			} else if (!$this->_joocmUserTable->store()) {
				$this->setError($this->_joocmUserTable->getError()); return false;
			}
		}

		return true;	
	}
		
	/**
	 * agreed terms
	 *
	 * @access 	public
	 */
	function agreedTerms() {

		if (isset($this->_joocmUserTable)) {
			$this->_joocmUserTable->agreed_terms = 1;
			
			if (!$this->_joocmUserTable->check()) {
				$this->setError($this->_joocmUserTable->getError()); return false;
			} else if (!$this->_joocmUserTable->store()) {
				$this->setError($this->_joocmUserTable->getError()); return false;
			}
		} else {
			return false;
		}

		return true;	
	}
	
	/**
	 * saves the avatar
	 *
	 * @access 	public
	 */
	function saveAvatar($avatarId) {

		// initialize variables
		$db	= & JFactory::getDBO();
		if (isset($this->_joocmUserTable)) {
			$this->_joocmUserTable->id_avatar = $avatarId;
			
			if (!$this->_joocmUserTable->check()) {
				$this->setError($this->_joocmUserTable->getError()); return false;
			} else if (!$this->_joocmUserTable->store()) {
				$this->setError($this->_joocmUserTable->getError()); return false;
			}
		} else {
			return false;
		}

		return true;	
	}
	
	/**
	 * set activation
	 *
	 * @access 	public
	 */	
	function setActivation() {
		jimport('joomla.user.helper');
		$this->set('activation', md5(JUserHelper::genRandomPassword()));
		$this->set('block', '1');
		parent::save();		
	}

	/**
	 * is required field empty
	 *
	 * @access 	public
	 */
	function isRequiredFieldEmpty() {

		if (!is_object($this->_joocmProfileTable)) {
			$this->setError(JText::_('COM_JOOCM_MSGJOOCMPROFILESNOTACTIVE')); return false;
		}
		
		if ($this->get('id') > 0) {
		
			// check if there is an empty required field
			$profileFields = $this->_joocmProfileTable->getProfileFields(JFactory::getDBO());
			foreach ($profileFields as $profileField) {
				if ($profileField->required) {
					$fieldValue = $this->get($profileField->name);
					if ($fieldValue == '' || $fieldValue == NULL) {
						return true;	
					}
				}
			}
		}

		return false;	
	}
}
?>