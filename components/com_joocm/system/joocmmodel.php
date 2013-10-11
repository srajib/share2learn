<?php
/**
 * @version $Id: joocmmodel.php 189 2010-10-19 13:08:35Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * Joo!CM Model
 *
 * @package Joo!CM
 */
class JoocmModel extends JModel
{

	/**
	 * total members
	 *
	 * @var integer
	 */
	var $_totalMembers = null;
	
	/**
	 * latest member
	 *
	 * @var string
	 */
	var $_latestMember = null;
	
	/**
	 * latest members
	 *
	 * @var string
	 */
	var $_latestMembers = null;

	/**
	 * online users
	 *
	 * @var array
	 */
	var $_onlineUsers = null;
	
	/**
	 * latest member
	 *
	 * @var string
	 */
	var $_recentOnlineMembers = null;

	/**
	 * online users
	 *
	 * @var integer
	 */
	var $_totalOnlineUsers = 0;
		
	/**
	 * sessions data array
	 *
	 * @var array
	 */
	var $_sessions = null;
	
	/**
	 * profile field sets data array
	 *
	 * @var array
	 */
	var $_profilefieldsets = null;
	
	/**
	 * profile fields data array
	 *
	 * @var array
	 */
	var $_profilefields = null;
	
	/**
	 * get total members
	 * 
	 * @access public
	 * @return integer
	 */
	function getTotalMembers() {
	
		// load total members
		if (empty($this->_totalMembers)) {
			$db =& $this->getDBO();	
			$query = "SELECT COUNT(*)"
					 . "\n FROM #__users AS u"
					 . "\n INNER JOIN #__joocm_users AS ju ON ju.id = u.id"
					 . "\n WHERE u.block = 0"
					 . "\n AND ju.hide = 0" // do not show special users
					 ;
			$db->setQuery($query);
			$this->_totalMembers = $db->loadResult();
		}
		
		return $this->_totalMembers;
	}
	
	/**
	 * get latest member
	 * 
	 * @access public
	 * @return integer
	 */
	function getLatestMember() {
	
		// load latest member
		if (empty($this->_latestMember)) {
			$db =& $this->getDBO();

			$query = "SELECT u.id"
					. "\n FROM #__users AS u"
					. "\n INNER JOIN #__joocm_users AS ju ON ju.id = u.id"
					. "\n WHERE u.block = 0"
					. "\n AND ju.hide = 0" // do not show special users
					. "\n ORDER BY u.registerDate DESC"
					;
			$db->setQuery($query);
			
			$this->_latestMember =& JoocmUser::getInstance($db->loadResult());
		}
		
		return $this->_latestMember;
	}
	
	/**
	 * get latest member
	 * 
	 * @access public
	 * @return array
	 */
	function getLatestMembers($filterGuests = 0, $limitstart = 0, $limit = 20) {
		
		// load latest members
		if (empty($this->_latestMembers)) {	
			$query = "SELECT u.id, ". $this->getUserAs('u') ." AS name"
					. "\n FROM #__users AS u"
					. "\n INNER JOIN #__joocm_users AS ju ON ju.id = u.id"
					. "\n WHERE u.block = 0"
					. "\n AND ju.hide = 0" // do not show special users
					. "\n ORDER BY u.registerDate DESC"
					;	
			//$this->_totalOnlineUsers = $this->_getListCount($query);
			$this->_latestMembers = $this->_getList($query, $limitstart, $limit);
		}
		
		return $this->_latestMembers;
	}
		
	/**
	 * get online users
	 * 
	 * @access public
	 * @return array
	 */
	function getOnlineUsers($filterGuests = 0, $limitstart = 0, $limit = 20) {
		
		$where = "";
		if ($filterGuests) {
			$where .= "\n AND s.userid <> 0";
		}
		
		// load online users
		if (empty($this->_onlineUsers)) {	
			$query = "SELECT s.userid, s.time, ". $this->getUserAs('u') ." AS name"
					. "\n FROM #__session AS s"
					. "\n LEFT JOIN #__users AS u ON u.id = s.userid"
					. "\n LEFT JOIN #__joocm_users AS ju ON ju.id = s.userid"
					. "\n WHERE s.client_id = 0"
					. "\n AND ju.hide = 0" // do not show special users
					. $where
					. "\n GROUP BY s.userid"
					;	
			$this->_totalOnlineUsers = $this->_getListCount($query);
			$this->_onlineUsers = $this->_getList($query, $limitstart, $limit);
		}
		
		return $this->_onlineUsers;
	}

	/**
	 * get total online users
	 *
	 * @access public
	 * @return integer
	 */
	function getTotalOnlineUsers() {
		return $this->_onlineUsers;
	}
	
	/**
	 * get recent online members
	 * 
	 * @access public
	 * @return array
	 */
	function getRecentOnlineMembers($filterGuests = 0, $limitstart = 0, $limit = 20) {
		
		// load latest members
		if (empty($this->_recentOnlineMembers)) {	
			$query = "SELECT u.id, ". $this->getUserAs('u') ." AS name"
					. "\n FROM #__users AS u"
					. "\n INNER JOIN #__joocm_users AS ju ON ju.id = u.id"
					. "\n LEFT JOIN #__session AS s ON s.userid = u.id"
					. "\n WHERE u.block = 0"
					. "\n AND ju.hide = 0" // do not show special users
					. "\n AND s.userid IS NULL"
					. "\n ORDER BY u.lastvisitDate DESC"
					;
			//$this->_recentOnlineMembers = $this->_getListCount($query);
			$this->_recentOnlineMembers = $this->_getList($query, $limitstart, $limit);
		}
		
		return $this->_recentOnlineMembers;
	}

	/**
	 * get sessions
	 * 
	 * @access public
	 * @return array
	 */
	function getSessions() {
	
		// load sessions
		if (empty($this->_sessions)) {	
			$query = "SELECT DISTINCT userid"
					. "\n FROM #__session"
					. "\n WHERE client_id = 0"
					;
			$this->_sessions = $this->_getList($query);
		}
		
		return $this->_sessions;
	}
	
	/**
	 * get user as
	 *
	 * @return string
	 */
	function getUserAs($alias) {
		$joocmConfig =& JoocmConfig::getInstance();
		return ($joocmConfig->getConfigSettings('show_user_as') == 0) ? "$alias.name" : "$alias.username";
	}

	/**
	 * get profile field sets
	 *
	 * @return array
	 */
	function getProfileFieldSets() {
				
		// load the profile field sets
		if (empty($this->_profilefieldsets)) {
			$db		=& JFactory::getDBO();
			$query	= "SELECT s.*"
					. "\n FROM #__joocm_profiles_fields_sets AS s"
					. "\n WHERE s.published = 1"
					. "\n ORDER BY s.ordering"
					;
			$db->setQuery($query);
			$this->_profilefieldsets = $db->loadObjectList();		
		}

		return $this->_profilefieldsets;
	}
	
	/**
	 * get profile fields
	 *
	 * @return array
	 */
	function getProfileFields($joobbUser) {
				
		// load the profile fields
		if (empty($this->_profilefields)) {
			$db		=& JFactory::getDBO();
			$query	= "SELECT f.*"
					. "\n FROM #__joocm_profiles_fields AS f"
					. "\n WHERE f.published = 1"
					. "\n ORDER BY f.ordering"
					;
			$db->setQuery($query);
			$fieldrows = $db->loadObjectList();
			
			$fields = array();
			foreach($fieldrows as $fieldrow) {
				switch ($fieldrow->element) {
					case '0':		// TextBox			
					case '1':		// TextArea				
					case '2':		// CheckBox
					case '3':		// RadioButton
					case '4':		// ListBox
						$fieldrow->value = $joobbUser->get($fieldrow->name);
						break;
					case '5':		// ComboBox
						// list profile field list values		
						$query = "SELECT v.name"
								. "\n FROM #__joocm_profiles_fields_lists_values AS v"
								. "\n WHERE v.id_profile_field_list = ". $fieldrow->id_profile_field_list
								. "\n AND v.value = ". $joobbUser->get($fieldrow->name)
								. "\n AND v.published = 1"
								;
						$db->setQuery( $query );
						$fieldrow->value = $db->loadResult();	
						break;															
					default:
						;
						break;
				}
				$fields[] = $fieldrow;
			}
			$this->_profilefields = $fields;		
		}
		return $this->_profilefields;
	}	
}
?>