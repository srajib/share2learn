<?php
/**
 * @version $Id: subscriptions.php 22 2009-12-25 20:07:22Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Subscriptions
 *
 * @package Joo!BB
 */
class JoobbModelSubscriptions extends JoobbModel
{
	/**
	 * subscriptions data array
	 *
	 * @var array
	 */
	var $_subscriptions = null;

	/**
	 * total count of subscriptions
	 *
	 * @var integer
	 */
	var $_total = 0;
	
	/**
	 * get user subscriptions
	 * 
	 * @access public
	 * @return array
	 */
	function getUserSubscriptions($userId, $limitstart, $limit) {

		// load topics
		if (empty($this->_subscriptions)) {
			$query = "SELECT ts.*, t.*, fp.subject, fp.date_post AS date_topic, fp.id_user AS id_author, fp.icon_function, fp.text,"
					. "\n ". $this->getUserAs('fu') ." AS author, fg.guest_name AS guest_author, "
					. "\n lp.subject AS subject_last_post, lp.date_post AS date_last_post, lp.id_user AS id_poster,"
					. "\n ". $this->getUserAs('lu') ." AS poster, lg.guest_name AS guest_poster"
					. "\n FROM #__joobb_topics_subscriptions AS ts"
					. "\n INNER JOIN #__joobb_topics AS t ON t.id = ts.id_topic"
					. "\n INNER JOIN #__joobb_posts AS fp ON fp.id = t.id_first_post"
					. "\n LEFT JOIN #__joobb_posts_guests AS fg ON fp.id = fg.id_post"
					. "\n LEFT JOIN #__users AS fu ON fu.id = fp.id_user"
					. "\n INNER JOIN #__joobb_posts AS lp ON lp.id = t.id_last_post"
					. "\n LEFT JOIN #__joobb_posts_guests AS lg ON lp.id = lg.id_post"
					. "\n LEFT JOIN #__users AS lu ON lu.id = lp.id_user"
					. "\n WHERE ts.id_user = $userId"
					. "\n ORDER BY fp.date_post DESC";
					;
			$this->_total = $this->_getListCount($query);
			$this->_subscriptions = $this->_getList($query, $limitstart, $limit);
		}
		
		return $this->_subscriptions;
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