<?php
/**
 * @version $Id: board.php 30 2010-01-15 16:11:47Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Board Model
 *
 * @package Joo!BB
 */
class JoobbModelBoard extends JoobbModel
{
	/**
	 * categories data array
	 *
	 * @var array
	 */
	var $_categories = null;
	
	/**
	 * forums data array
	 *
	 * @var array
	 */
	var $_forums = null;

	/**
	 * get the categories
	 * 
	 * @access public
	 * @return array
	 */
	function getCategories() {
	
		// load the categories
		if (empty($this->_categories)) {	
			$query = $this->_buildCategoriesQuery();
			$this->_categories = $this->_getList($query);
		}
		
		return $this->_categories;
	}
	
	function _buildCategoriesQuery() {

		$query = "SELECT c.*"
				. "\n FROM #__joobb_categories AS c"
				. "\n WHERE c.published = 1"
				. "\n ORDER BY c.ordering"
				;
			
		return $query;
	}
	
	/**
	 * get the forums
	 * 
	 * @access public
	 * @return array
	 */
	function getForums() {
	
		// load the forums
		if (empty($this->_forums)) {	
			$query = $this->_buildForumsQuery();
			$this->_forums = $this->_getList($query);
		}
		
		return $this->_forums;
	}
		
	function _buildForumsQuery() {

		// Get the WHERE clause for the query
		$where	 = $this->_buildForumsWhere();
		
		$query = "SELECT f.*, lp.date_post, lp.id_topic, lp.id_user, lp.subject AS subject_last_post, ". $this->getUserAs('u') ." AS author, lg.guest_name AS guest_author"
				. "\n FROM #__joobb_forums AS f"
				. "\n LEFT JOIN #__joobb_posts AS lp ON f.id_last_post = lp.id"
				. "\n LEFT JOIN #__users AS u ON lp.id_user = u.id"
				. "\n LEFT JOIN #__joobb_posts_guests AS lg ON lp.id = lg.id_post"
				. $where
				. "\n ORDER BY f.ordering"
				;
	
		return $query;
	}

	function _buildForumsWhere() {
		$currentUser =& JoobbHelper::getJoobbUser();
		$currentUserId = $currentUser->get('id');	
		$category = JRequest::getVar('category', 0, '', 'int');
		
		$where = "\n WHERE f.status = 1"
				. "\n AND f.auth_view <= (SELECT IFNULL(u.role, 0)"
				. "\n FROM #__joobb_users AS u"
				. "\n WHERE u.id = $currentUserId)"	
				. "\n OR f.status = 1"
				. "\n AND f.auth_view <= (SELECT IFNULL(max(a.role), 0)"
				. "\n FROM #__joobb_forums_auth AS a"
				. "\n WHERE a.id_forum = f.id AND a.id_user = $currentUserId AND a.id_group = 0)"
				. "\n OR f.status = 1"
				. "\n AND f.auth_view <= (SELECT IFNULL(max(g.role), 0)"
				. "\n FROM #__joobb_groups_users AS gu"
				. "\n INNER JOIN #__joobb_groups AS g ON g.id = gu.id_group"
				. "\n WHERE gu.id_user = $currentUserId)"
				. "\n OR f.status = 1"
				. "\n AND f.auth_view <= (SELECT IFNULL(max(a.role), 0)"
				. "\n FROM #__joobb_groups_users AS gu"
				. "\n INNER JOIN #__joobb_forums_auth AS a ON a.id_group = gu.id_group"
				. "\n WHERE gu.id_user = $currentUserId AND a.id_forum = f.id)"
				;	
		
		if ($category != 0) {
			$where .= "\n AND f.id_cat = ". $category;
		}

		return $where;
	}
}
?>