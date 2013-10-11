<?php
/**
 * @version $Id: forum.php 85 2010-04-17 18:05:14Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Forum Model
 *
 * @package Joo!BB
 */
class JoobbModelForum extends JoobbModel
{
	/**
	 * announcements data array
	 *
	 * @var array
	 */
	var $_announcements = null;
	
	/**
	 * topics data array
	 *
	 * @var array
	 */
	var $_topics = null;

	/**
	 * total count of topics
	 *
	 * @var integer
	 */
	var $_total = 0;
		
	/**
	 * get announcements
	 * 
	 * @access public
	 * @return array
	 */
	function getAnnouncements() {
	
		// load announcements
		if (empty($this->_announcements)) {	
			$query = $this->_buildTopicsQuery("\n AND t.type = 2");
			$this->_announcements = $this->_getList($query);
		}
		
		return $this->_announcements;
	}

	/**
	 * get topics
	 * 
	 * @access public
	 * @return array
	 */
	function getTopics() {
	
		// load topics
		if (empty($this->_topics)) {
			$joobbConfig =& JoobbConfig::getInstance();
			$limit = JRequest::getVar('limit', $joobbConfig->getBoardSettings('topics_per_page'), '', 'int');
			$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
			
			$query = $this->_buildTopicsQuery("\n AND t.type < 2");
			$this->_total = $this->_getListCount($query);
			$this->_topics = $this->_getList($query, $limitstart, $limit);
		}
		
		return $this->_topics;
	}

	function _buildTopicsQuery($type) {
		$forum = JRequest::getVar('forum', 0, '', 'int');
		$order = JRequest::getVar('order','desc');
		if ($order != "desc" && $order !=  "asc") {
		    $order = "DESC";
		} else {
			$order = strtoupper($order);
		}

		$sort_statement = array( 'views' => ", t.views ".$order.", lp.date_post DESC",
					 'replies' => ", t.replies ".$order.", lp.date_post DESC",
					 'author' => ", author ".$order.", lp.date_post DESC",
					 'lastpost' => ", lp.date_post ".$order,
					);

		$sort = JRequest::getVar('sort','lastpost');
		if ($sort_statement[$sort] == '')
		    $sort = 'lastpost';
			
		$query = "SELECT t.*, fp.subject, fp.date_post AS date_topic, fp.id_user AS id_author, fp.icon_function, fp.text,"
				. "\n ". $this->getUserAs('fu') ." AS author, fg.guest_name AS guest_author, lp.subject AS subject_last_post,"
				. "\n lp.date_post AS date_last_post, lp.id_user AS id_poster, ". $this->getUserAs('lu') ." AS poster,"
				. "\n lg.guest_name AS guest_poster"
				. "\n FROM #__joobb_topics AS t"
				. "\n INNER JOIN #__joobb_posts AS fp ON t.id_first_post = fp.id"
				. "\n LEFT JOIN #__users AS fu ON fp.id_user = fu.id"
				. "\n LEFT JOIN #__joobb_posts_guests AS fg ON fp.id = fg.id_post"
				. "\n INNER JOIN #__joobb_posts AS lp ON t.id_last_post = lp.id"
				. "\n LEFT JOIN #__users AS lu ON lp.id_user = lu.id"
				. "\n LEFT JOIN #__joobb_posts_guests AS lg ON lp.id = lg.id_post"
				. "\n WHERE t.id_forum = ". $forum
				. $type
				. "\n ORDER BY t.type DESC".$sort_statement[$sort];

		return $query;
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
} ?>