<?php
/**
 * @version $Id: latestposts.php 178 2010-10-03 10:07:39Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Latest Posts Model
 *
 * @package Joo!BB
 */
class JoobbModelLatestPosts extends JoobbModel
{

	/**
	 * total count of subscriptions
	 *
	 * @var integer
	 */
	var $_total = 0;
	
	/**
	 * get latest posts
	 * 
	 * @access public
	 * @return array
	 */
	function getLatestPosts($enableFilter, $limitstart, $limit) {
		$where = "\n WHERE 1";
		$where1 = "\n OR 1";
		
		if ($enableFilter) {
			$hours = JRequest::getVar('hours', NULL, '', 'int');
		
			// ToDo: find a better solution ;)
			$factor = 0;
			if (isset($hours)) {
				$factor = $hours * 3600; // 60 * 60
			} else {
				$days = JRequest::getVar('days', NULL, '', 'int');
				if (isset($days)) {
					$factor = $days * 86400; // 24 * 60 * 60
				} else {
					$weeks = JRequest::getVar('weeks', NULL, '', 'int');
					if (isset($weeks)) {
						$factor = $weeks * 604800; // 7 * 24 * 60 * 60
					} else {
						$months = JRequest::getVar('months', NULL, '', 'int');
						if (isset($months)) {
							$factor = $months * 2592000; // 30 * 24 * 60 * 60
						} else {
							$years = JRequest::getVar('years', NULL, '', 'int');
							if (isset($years)) {
								$factor = $years * 31536000; // 365 * 24 * 60 * 60
							}						
						}					
					}				
				}
			}
			
			if ($factor) {
				$dateTime = gmdate("Y-m-d H:i:s", time() - $factor);
				$where = "\n WHERE p.date_post > '$dateTime'";
				$where1 = "\n OR p.date_post > '$dateTime'";		
			}
		}
		
		$currentUser =& JoobbHelper::getJoobbUser();
		$currentUserId = $currentUser->get('id');
		
		$query = "SELECT p.*, f.locked, t.id_first_post, t.status, ". $this->getUserAs('u') ." AS author, u.id AS id_user, u.registerDate, "
				. "\n u.lastvisitDate, ju.posts, lg.guest_name AS guest_author, cmu.signature, cmu.show_online_state, ". $this->getUserAs('ue') ." AS editor"
				. "\n FROM #__joobb_posts AS p"
				. "\n INNER JOIN #__joobb_topics AS t ON t.id = p.id_topic"
				. "\n INNER JOIN #__joobb_forums AS f ON f.id = t.id_forum"
				. "\n LEFT JOIN #__users AS u ON p.id_user = u.id"
				. "\n LEFT JOIN #__joobb_users AS ju ON ju.id = u.id"
				. "\n LEFT JOIN #__joocm_users AS cmu ON cmu.id = u.id"
				. "\n LEFT JOIN #__joobb_posts_guests AS lg ON p.id = lg.id_post"
				. "\n LEFT JOIN #__users AS ue ON p.id_user_last_edit = ue.id"
				. $where
				. "\n AND f.auth_read <= (SELECT IFNULL(u.role, 0)"
				. "\n FROM #__joobb_users AS u"
				. "\n WHERE u.id = $currentUserId)"
				. $where1
				. "\n AND f.auth_read <= (SELECT IFNULL(max(a.role), 0)"
				. "\n FROM #__joobb_forums_auth AS a"
				. "\n WHERE a.id_forum = f.id AND a.id_user = $currentUserId AND a.id_group = 0)"
				. $where1
				. "\n AND f.auth_read <= (SELECT IFNULL(max(g.role), 0)"
				. "\n FROM #__joobb_groups_users AS gu"
				. "\n INNER JOIN #__joobb_groups AS g ON g.id = gu.id_group"
				. "\n WHERE gu.id_user = $currentUserId)"
				. $where1
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