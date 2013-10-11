<?php
/**
 * @version $Id: userposts.php 178 2010-10-03 10:07:39Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB User Posts Model
 *
 * @package Joo!BB
 */
class JoobbModelUserPosts extends JoobbModel
{

	/**
	 * total count of user posts
	 *
	 * @var integer
	 */
	var $_total = 0;
	
	/**
	 * get search results
	 * 
	 * @access public
	 * @return array
	 */
	function getUserPosts($userId, $limitstart, $limit) {
		$currentUser =& JoobbHelper::getJoobbUser();
		$currentUserId = $currentUser->get('id');
	
		$query = "SELECT p.*, f.locked, t.id_first_post, t.status, ". $this->getUserAs('u') ." AS author, u.id AS id_user, u.registerDate, u.lastvisitDate,"
				. "\n ju.posts, lg.guest_name AS guest_author, cmu.show_online_state, cmu.signature, ". $this->getUserAs('ue') ." AS editor"	
				. "\n FROM #__joobb_posts AS p"
				. "\n INNER JOIN #__joobb_topics AS t ON t.id = p.id_topic"
				. "\n INNER JOIN #__joobb_forums AS f ON f.id = t.id_forum"
				. "\n LEFT JOIN #__users AS u ON p.id_user = u.id"
				. "\n LEFT JOIN #__joobb_users AS ju ON ju.id = u.id"
				. "\n LEFT JOIN #__joocm_users AS cmu ON cmu.id = u.id"
				. "\n LEFT JOIN #__joobb_posts_guests AS lg ON p.id = lg.id_post"
				. "\n LEFT JOIN #__users AS ue ON p.id_user_last_edit = ue.id"
				. "\n WHERE p.id_user = $userId"
				. "\n AND f.auth_read <= (SELECT IFNULL(u.role, 0)"
				. "\n FROM #__joobb_users AS u"
				. "\n WHERE u.id = $currentUserId)"	
				. "\n OR p.id_user = $userId"
				. "\n AND f.auth_read <= (SELECT IFNULL(max(a.role), 0)"
				. "\n FROM #__joobb_forums_auth AS a"
				. "\n WHERE a.id_forum = f.id AND a.id_user = $currentUserId AND a.id_group = 0)"
				. "\n OR p.id_user = $userId"
				. "\n AND f.auth_read <= (SELECT IFNULL(max(g.role), 0)"
				. "\n FROM #__joobb_groups_users AS gu"
				. "\n INNER JOIN #__joobb_groups AS g ON g.id = gu.id_group"
				. "\n WHERE gu.id_user = $currentUserId)"
				. "\n OR p.id_user = $userId"
				. "\n AND f.auth_read <= (SELECT IFNULL(max(a.role), 0)"
				. "\n FROM #__joobb_groups_users AS gu"
				. "\n INNER JOIN #__joobb_forums_auth AS a ON a.id_group = gu.id_group"
				. "\n WHERE gu.id_user = $currentUserId AND a.id_forum = f.id)"
				. "\n ORDER BY p.date_post DESC"
				;
				
		$this->_total = $this->_getListCount($query);
		
		return $this->_getList($query, $limitstart, $limit);
	}

	/**
	 * get total
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal() {
		return $this->_total;
	}
}
?>