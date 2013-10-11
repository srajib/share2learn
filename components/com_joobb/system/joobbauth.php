<?php
/**
 * @version $Id: joobbauth.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Authentification
 *
 * @package Joo!BB
 */
class JoobbAuth
{
	/**
	 * authentification option list
	 * @var array
	 */
	var $_authOptionList = null;

	/**
	 * user roles option list
	 * @var array
	 */
	var $_userRoleOptionList = null;
	
	/**
	 * user roles list
	 * @var array
	 */
	var $_userRoleList = null;
		
	function JoobbAuth() {	 
	}
	
	/**
	 * get a joobb authentification object
	 *
	 * @access public
	 * @return object of JoobbAuth
	 */
	function &getInstance() {
	
		static $joobbAuth;

		if (!is_object($joobbAuth)) {
			$joobbAuth = new JoobbAuth();
		}

		return $joobbAuth;
	}

	function getAuth($auth, $idForum) {
		$joobbUser =& JoobbHelper::getJoobbUser();
		$row =& JTable::getInstance('JoobbForum');
		
		if ($row->load($idForum)) {
			if ($row->$auth <= $joobbUser->getRole($idForum)) {
				return true;
			}
		}
		return false;
	}
	
	function getAuthOptionList() {
		
		if (empty($this->_authOptionList)) {
			$this->_authOptionList = array();
			$this->_authOptionList[] = JHTML::_('select.option', '0', JText::_('COM_JOOBB_ALL'));
			$this->_authOptionList[] = JHTML::_('select.option', '1', JText::_('COM_JOOBB_REGISTERED'));
			$this->_authOptionList[] = JHTML::_('select.option', '2', JText::_('COM_JOOBB_PRIVATE'));
			$this->_authOptionList[] = JHTML::_('select.option', '3', JText::_('COM_JOOBB_MODERATOR'));
			$this->_authOptionList[] = JHTML::_('select.option', '4', JText::_('COM_JOOBB_ADMINISTRATOR'));
		}
		
		return $this->_authOptionList;
	}
	
	function getUserRoleOptionList() {
		
		if (empty($this->_userRoleOptionList)) {
			$this->_userRoleOptionList = array();
			$this->_userRoleOptionList[] = JHTML::_('select.option', '0', JText::_('COM_JOOBB_NONE'));
			$this->_userRoleOptionList[] = JHTML::_('select.option', '1', JText::_('COM_JOOBB_REGISTERED'));
			$this->_userRoleOptionList[] = JHTML::_('select.option', '2', JText::_('COM_JOOBB_PRIVATE'));
			$this->_userRoleOptionList[] = JHTML::_('select.option', '3', JText::_('COM_JOOBB_MODERATOR'));		
			$this->_userRoleOptionList[] = JHTML::_('select.option', '4', JText::_('COM_JOOBB_ADMINISTRATOR'));
		}
		
		return $this->_userRoleOptionList;
	}
		
	function getUserRoleList() {
		
		if (empty($this->_userRoleList)) {
			$this->_userRoleList = array();
			$this->_userRoleList[0] = JText::_('COM_JOOBB_NONE');
			$this->_userRoleList[1] = JText::_('COM_JOOBB_REGISTERED');
			$this->_userRoleList[2] = JText::_('COM_JOOBB_PRIVATE');
			$this->_userRoleList[3] = JText::_('COM_JOOBB_MODERATOR');		
			$this->_userRoleList[4] = JText::_('COM_JOOBB_ADMINISTRATOR');
		}
		
		return $this->_userRoleList;
	}
	
	/**
	 * get authentification text
	 */	
	function getAuthText($auth) {

		$result = '';

		if (isset($auth)) {
			switch ($auth) {
				case 0:
					$result = JText::_('COM_JOOBB_ALL');
					break;
				case 1:
					$result = JText::_('COM_JOOBB_REG');
					break;
				case 2:
					$result = JText::_('COM_JOOBB_PRIVATE');
					break;
				case 3:
					$result = JText::_('COM_JOOBB_MODS');
					break;
				case 4:
					$result = JText::_('COM_JOOBB_ADMIN');
					break;																	
				default:
					$result = JText::_('COM_JOOBB_NONE');
					break;
			}
		} else {
			$result = '-';
		}
		return $result;
	}
}
?>