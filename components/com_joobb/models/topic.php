<?php
/**
 * @version $Id: topic.php 178 2010-10-03 10:07:39Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Topic Model
 *
 * @package Joo!BB
 */
class JoobbModelTopic extends JoobbModel
{
	/**
	 * posts data array
	 *
	 * @var array
	 */
	var $_posts = null;

	/**
	 * total count of posts
	 *
	 * @var integer
	 */
	var $_total = 0;
	
	/**
	 * firstpost table object 
	 *
	 * @var object
	 */
	var $_firstpost = null;

	/**
	 * get firstpost object
	 *
	 * @return object
	 */
	function getFirstPost($id_firstpost) {

		// load the topic
		if (empty($this->_firstpost)) {
			$this->_firstpost =& JTable::getInstance('JoobbPost');
		}
		$this->_firstpost->load($id_firstpost);
			
		return $this->_firstpost;
	}
	
	/**
	 * get posts
	 * 
	 * @access public
	 * @return array
	 */
	function getPosts() {
	
		// load posts
		if (empty($this->_posts)) {
			$joobbConfig =& JoobbConfig::getInstance();
			$limit = JRequest::getVar('limit', $joobbConfig->getBoardSettings('posts_per_page'), '', 'int');
			$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');	
			$topicId	= JRequest::getVar('topic', 0, '', 'int');
	
			$query = "SELECT p.*, f.locked, t.id_first_post, t.status, ". $this->getUserAs('u') ." AS author, u.id AS id_user, cmu.show_online_state,"
					. "\n u.registerDate, u.lastvisitDate, ju.posts, cmu.signature, ". $this->getUserAs('ue') ." AS editor,"
					. "\n lg.guest_name AS guest_author"
					. "\n FROM #__joobb_posts AS p"
					. "\n INNER JOIN #__joobb_topics AS t ON t.id = p.id_topic"
					. "\n INNER JOIN #__joobb_forums AS f ON f.id = t.id_forum"
					. "\n LEFT JOIN #__users AS u ON p.id_user = u.id"
					. "\n LEFT JOIN #__joobb_users AS ju ON ju.id = u.id"
					. "\n LEFT JOIN #__joocm_users AS cmu ON cmu.id = u.id"
					. "\n LEFT JOIN #__joobb_posts_guests AS lg ON p.id = lg.id_post"
					. "\n LEFT JOIN #__users AS ue ON p.id_user_last_edit = ue.id"
					. "\n WHERE p.id_topic = $topicId"
					. "\n ORDER BY p.id"
					;
			$this->_total = $this->_getListCount($query);
			$this->_posts = $this->_getList($query, $limitstart, $limit);
		}
		
		return $this->_posts;
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
	
	/**
	 * increment hit
	 *
	 * @access public
	 * @return boolean
	 */
	function incrementHit($id_topic) {
		$session	=& JFactory::getSession();
		
		if (!empty($this->_topic)) {
		
			$topicHits = array();
			$topicHits = $session->get('_joobbTopicHits', $topicHits);
			if (in_array($id_topic, $topicHits)) {
				return false;
			}		
			$topicHits[] = $id_topic;
			$session->set('_joobbTopicHits', $topicHits);		

			$this->_topic->views++;
			if (!$this->_topic->store()) {
				JError::raiseError(500, $this->_topic->getError());
			}			
			return true;
		}
		return false;
	}
}
?>