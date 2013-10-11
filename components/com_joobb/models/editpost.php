<?php
/**
 * @version $Id: editpost.php 22 2009-12-25 20:07:22Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Edit Post Model
 *
 * @package Joo!BB
 */
class JoobbModelEditPost extends JoobbModel
{

	/**
	 * get topic posts
	 * 
	 * @access public
	 * @return array
	 */
	function getTopicPosts($topicId, $limitstart, $limit) {

		$query = "SELECT p.*, f.locked, t.id_first_post, t.status, ". $this->getUserAs('u') ." AS author, u.id AS id_user, cmu.show_online_state,"
				. "\n u.registerDate, u.lastvisitDate, ju.posts, cmu.signature,"
				. "\n lg.guest_name AS guest_author"
				. "\n FROM #__joobb_posts AS p"
				. "\n INNER JOIN #__joobb_topics AS t ON t.id = p.id_topic"
				. "\n INNER JOIN #__joobb_forums AS f ON f.id = t.id_forum"
				. "\n LEFT JOIN #__users AS u ON p.id_user = u.id"
				. "\n LEFT JOIN #__joobb_users AS ju ON ju.id = u.id"
				. "\n LEFT JOIN #__joocm_users AS cmu ON cmu.id = u.id"
				. "\n LEFT JOIN #__joobb_posts_guests AS lg ON p.id = lg.id_post"
				. "\n WHERE p.id_topic = $topicId"
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