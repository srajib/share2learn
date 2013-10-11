<?php
/**
 * @version $Id: joobbuser.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.parameter');

/**
 * Joo!BB User
 *
 * @package Joo!BB
 */
class JoobbUser extends JUser
{
	/**
	 * joobb user table
	 *
	 * @var object
	 */
	var $_joobbUserTable = null;
	
	/**
	 * joobb user table
	 *
	 * @var object
	 */
	var $_forumRoles = null;
	
	/**
	 * constructor
	 */
	function __construct($identifier = 0, $new = false) {

		// create the joobbuser table object
		$this->_joobbUserTable 	=& JTable::getInstance('JoobbUser');

		parent::__construct($identifier);
		
		// Load the user if it exists
		if (!empty($identifier)) {
			$this->load($identifier);
		} else {
			if ($new) {
				$joobbConfig =& JoobbConfig::getInstance();
				
				//initialise
				$this->_joobbUserTable->id = 0;
				$this->_joobbUserTable->role = $joobbConfig->getUserSettingsDefaults('role');
				$this->_joobbUserTable->enable_bbcode = $joobbConfig->getUserSettingsDefaults('enable_bbcode');
				$this->_joobbUserTable->enable_emotions = $joobbConfig->getUserSettingsDefaults('enable_emotions');
				$this->_joobbUserTable->notify_on_reply = $joobbConfig->getUserSettingsDefaults('auto_subscription');
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
				JError::raiseWarning('SOME_ERROR_CODE', 'JoobbUser::_load: User '.$id.' does not exist');
				return false;
			}
		}

		if (empty($instances[$id])) {
			$joobbUser = new JoobbUser($id);
			$instances[$id] = $joobbUser;
		}

		return $instances[$id];
	}

	/**
	 * set property
	 *
	 * @access	public
	 */
	function set($property, $value=null) {
	
		if(isset($this->_joobbUserTable->$property)) {
			$this->_joobbUserTable->$property = $value;
		}
		parent::set($property, $value);			
	}

	/**
	 * get property
	 *
	 * @access	public
	 */
	function get($property, $default=null) {

		if(isset($this->_joobbUserTable->$property)) {
			return $this->_joobbUserTable->$property;
		}
		
		$value = parent::get($property, $default);
		if(isset($value)) {
			return $value;
		}			
		return $default;
	}
	
	/**
	 * bind user
	 *
	 * @access 	public
	 */
	function bind(&$array) {
	
		if (!parent::bind($array)) {
			return false;
		} else if (!$this->_joobbUserTable->bind($array)) {
			$this->setError(JText::sprintf('COM_JOOBB_MSGACTIONFAILED', $this->_joobbUserTable->getError()));
			return false;
		} 

		return true;
	}

	/**
	 * save joobb user
	 *
	 * @access 	public
	 */
	function save($updateOnly = false) {
		$db   =& JFactory::getDBO();
		$isNew = $this->id ? false : true;
		
		if ($isNew) {
			$usersConfig = &JComponentHelper::getParams('com_users');
			$newUsertype = $usersConfig->get('new_usertype');
			$this->set('usertype', $newUsertype);
			
			$authorize =& JFactory::getACL();
			$this->set('gid', $authorize->get_group_id('', $newUsertype, 'ARO'));
			
			$this->set('registerDate', gmdate("Y-m-d H:i:s"));			
		}
		
		if (!parent::save($updateOnly)) {
			return false;
		}
		
		if ($isNew) {
			$row =& JTable::getInstance('JoobbUser');
			if (!$row->load($this->id)) {
			
				// try to create a dummy
				$db->setQuery("INSERT INTO #__joobb_users (id) VALUES ($this->id)");
				if ($db->query()) {
					$this->_joobbUserTable->id = $this->id;
				} else {
					JError::raiseWarning(JText::sprintf('COM_JOOBB_MSGACTIONFAILED', $db->getError()));
					return false;					
				}
			}
		}

		if ($this->getError()) {
			return false;			
		}
		
		if (!$this->_joobbUserTable->check()) {
			$this->setError(JText::sprintf('COM_JOOBB_MSGACTIONFAILED', $this->_joobbUserTable->getError()));
			return false;
		} else if (!$result = $this->_joobbUserTable->store()) {
			$this->setError(JText::sprintf('COM_JOOBB_MSGACTIONFAILED', $this->_joobbUserTable->getError()));
			return false;
		}
		
		return true;
	}

	/**
	 * delete joobb user
	 *
	 * @access 	public
	 */
	function delete() {
	
		if (!parent::delete()) {
			return false;
		} else if (!$result = $this->_joobbUserTable->delete($this->_id)) {
			$this->setError(JText::sprintf('COM_JOOBB_MSGACTIONFAILED', $this->_joobbUserTable->getError()));
			return false;
		}
		
		return true; 
	}

	/**
	 * load joobb user
	 *
	 * @access 	public
	 */
	function load($id) {
		$db   =& JFactory::getDBO();
		
		// first of all load the original joomla user data
		if (!parent::load($id)) {
			return false;
		}	

		if (!$this->_joobbUserTable->load($id)) {
		
			// try to create a dummy
			$db->setQuery("INSERT INTO #__joobb_users (id) VALUES ($id)");
			if (!$db->query()) {
				JError::raiseWarning(JText::sprintf('COM_JOOBB_MSGACTIONFAILED', $db->getError()));
				return false;
			} else if (!$this->_joobbUserTable->load($id)) {
				JError::raiseWarning(JText::sprintf('COM_JOOBB_MSGACTIONFAILED', $this->_joobbUserTable->getError()));
				return false;			
			}

		}

		return true;	
	}
	
	/**
	 * saves the settings
	 *
	 * @access 	public
	 */
	function saveSettings($data) {

		if (isset($this->_joobbUserTable)) {
		
			// we don't want allow the user to edit certain fields
			unset($data['role']);
	
			if (!$this->_joobbUserTable->bind($data)) {
				$this->setError($this->_joobbUserTable->getError()); return false;
			} else if (!$this->_joobbUserTable->save($data)) {
				$this->setError($this->_joobbUserTable->getError()); return false;
			}
		} else {
			return false;
		}

		return true;	
	}

	/**
	 * get role
	 *
	 * @access 	public
	 */	
	function getRole($forumId) {

		if (!isset($this->_forumRoles)) {
			$this->_forumRoles = array();
		}

		if (empty($this->_forumRoles[$forumId])) {
			$db   =& JFactory::getDBO();
			
			// get the role
			$query = "SELECT IFNULL(max(t.role), 0)"
					. "\n FROM (SELECT u.role AS role"
					. "\n FROM #__joobb_users AS u"
					. "\n WHERE u.id = ". $this->get('id')
					. "\n UNION SELECT max(a.role) AS role"
					. "\n FROM #__joobb_forums_auth AS a"
					. "\n WHERE a.id_forum = $forumId"
					. "\n AND a.id_user = ". $this->get('id')
					. "\n AND a.id_group = 0"
					. "\n UNION SELECT max(g.role) AS role"
					. "\n FROM #__joobb_groups_users AS gu"
					. "\n INNER JOIN #__joobb_groups AS g ON g.id = gu.id_group"
					. "\n WHERE gu.id_user = ". $this->get('id')
					. "\n UNION SELECT max(a.role) AS role"
					. "\n FROM #__joobb_groups_users AS gu"
					. "\n INNER JOIN #__joobb_forums_auth AS a ON a.id_group = gu.id_group"
					. "\n WHERE gu.id_user = ". $this->get('id')
					. "\n AND a.id_forum = $forumId)t"
					;		
			$db->setQuery($query);
			$this->_forumRoles[$forumId] = $db->loadResult();
		}

		return $this->_forumRoles[$forumId];
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
		$this->save();		
	}
	
	/**
	 * save guest user
	 *
	 * @access 	public
	 */	
	function saveGuestUser($idPost, $guestName) {
		$db				=& JFactory::getDBO();
		$messageQueue	=& JoobbMessageQueue::getInstance();

		if ($guestName != '') {
			$query = "SELECT pg.id_post"
					. "\n FROM  #__joobb_posts_guests AS pg"
					. "\n WHERE pg.id_post = ". $idPost
					;
			$db->setQuery($query);

			if (!$db->loadResult()) {
				$query = "INSERT INTO #__joobb_posts_guests"
						. "\n SET id_post = ". $idPost .", guest_name = ". $db->Quote($guestName)
						;
			} else {
				$query = "UPDATE #__joobb_posts_guests"
						. "\n SET guest_name = ". $db->Quote($guestName)
						. "\n WHERE id_post = " . $idPost
						;
			}
			
			$db->setQuery($query);

			if (!$db->query()) {
				$messageQueue->addMessage($db->getErrorMsg());
			}
		}	
	}
	
}
