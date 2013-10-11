<?php
/**
 * @version $Id: userlist.php 125 2010-06-01 19:07:19Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM User List Model
 *
 * @package Joo!CM
 */
class JoocmModelUserList extends JoocmModel
{
	
	/**
	 * joocm users data array
	 *
	 * @var array
	 */
	var $_joocmUsers = null;

	/**
	 * total users
	 *
	 * @var integer
	 */
	var $_totalUsers = 0;
	
	/**
	 * get joocm users
	 * 
	 * @access public
	 * @return array
	 */
	function getJoocmUsers($limitstart = 0, $limit = 10, $filter = 'all', $searchUser = '', $orderBy = 'name', $orderByDir = 'ASC') {
	
		// load joocm users
		if (empty($this->_joocmUsers)) {
			
			$userAs = JoocmModel::getUserAs('u');
			
			$where = '';
			switch ($filter) {
				case 'online':
					$where = "\n AND s.userid IS NOT NULL";
					break;
				case 'offline':
					$where = "\n AND s.userid IS NULL";
					break;
				default:
					;
					break;
			}
			
			if ($searchUser != '') {
				$where .= "\n AND $userAs LIKE '%$searchUser%'";
			}
			
			if ($orderBy == 'name') {
				$orderBy = "\n ORDER BY ".$userAs." ".$orderByDir;
			} else {
				$orderBy = "\n ORDER BY u.".$orderBy." ".$orderByDir;
			}

			$query = "SELECT u.id, $userAs AS name, u.usertype, u.registerDate, u.lastvisitDate, s.userid"
					. "\n FROM #__users AS u"
					. "\n LEFT JOIN #__session AS s ON s.userid = u.id"
					. "\n WHERE u.id > 62" // do not show admin because of special user
					. "\n AND u.block = 0"
					. $where
					. "\n GROUP BY u.id"
					. $orderBy
					;
			$this->_totalUsers = $this->_getListCount($query);
			$this->_joocmUsers = $this->_getList($query, $limitstart, $limit);
		}

		return $this->_joocmUsers;
	}

	/**
	 * get total users
	 *
	 * @access public
	 * @return integer
	 */
	function getTotalUsers() {
		return $this->_totalUsers;
	}
}
?>