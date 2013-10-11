<?php
/**
 * @version $Id: joobbmodel.php 172 2010-09-19 09:40:34Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * Joo!BB Model
 *
 * @package Joo!BB
 */
class JoobbModel extends JoocmModel
{
	/**
	 * category table object
	 *
	 * @var object
	 */
	var $_category = null;
	
	/**
	 * forum table object
	 *
	 * @var object
	 */
	var $_forum = null;
	
	/**
	 * topic table object
	 *
	 * @var object
	 */
	var $_topic = null;

	/**
	 * post table object
	 *
	 * @var object
	 */
	var $_post = null;

	/**
	 * post table object
	 *
	 * @var object
	 */
	var $_postQuote = null;	
	
	/**
	 * ranks data array
	 *
	 * @var array
	 */
	var $_ranks = null;
	
	/**
	 * total topics
	 *
	 * @var integer
	 */
	var $_totalTopics = null;
	
	/**
	 * total posts
	 *
	 * @var integer
	 */
	var $_totalPosts = null;

	/**
	 * latest items
	 *
	 * @var array
	 */
	var $_latestItems = null;

	/**
	 * joobb user
	 *
	 * @var object
	 */
	var $_joobbUser = null;
	
	/**
	 * get the category object
	 *
	 * @access public
	 * @return object
	 */
	function getCategory($id_cat) {

		// load the category
		if (empty($this->_category)) {
			$this->_category =& JTable::getInstance('JoobbCategory');
		}
		$this->_category->load($id_cat);
			
		return $this->_category;
	}
				
	/**
	 * get the forum object
	 *
	 * @access public
	 * @return object
	 */
	function getForum($forumId) {

		// load the forum
		if (empty($this->_forum)) {
			$this->_forum =& JTable::getInstance('JoobbForum');
		}
		$this->_forum->load($forumId);
			
		return $this->_forum;
	}
	
	/**
	 * get the topic object
	 * 
	 * @access public
	 * @return object
	 */
	function getTopic($topicId) {

		// load the topic
		if (empty($this->_topic)) {
			$this->_topic =& JTable::getInstance('JoobbTopic');
		}
		$this->_topic->load($topicId);
		
		return $this->_topic;
	}
	
	/**
	 * get the post object
	 * 
	 * @access public
	 * @return object
	 */
	function getPost($postId) {

		// load the post
		if (empty($this->_post)) {
			$this->_post =& JTable::getInstance('JoobbPost');
		}
		$this->_post->load($postId);
		
		return $this->_post;
	}
	
	/**
	 * get the post quote object
	 * 
	 * @access public
	 * @return object
	 */
	function getPostQuote($postId) {

		// load the post
		if (empty($this->_postQuote)) {
			$this->_postQuote =& JTable::getInstance('JoobbPost');
		}
		$this->_postQuote->load($postId);
		
		return $this->_postQuote;
	}
	
	/**
	 * get the quouted user name
	 * 
	 * @access public
	 * @return string
	 */
	function getQuotedUserName($userId) {
		$db =& $this->getDBO();
		$query = "SELECT ". $this->getUserAs('u') ." AS author"
				. "\n FROM #__users AS u"
				. "\n WHERE u.id = $userId"
				;
		$db->setQuery($query);
		return $db->loadResult();
	}

	/**
	 * get ranks
	 * 
	 * @access public
	 * @return array
	 */
	function getRanks() {
	
		// load ranks
		if (empty($this->_ranks)) {	
			$query = "SELECT r.*"
					. "\n FROM #__joobb_ranks AS r"
					. "\n ORDER BY r.min_posts DESC"
					;
			$this->_ranks = $this->_getList($query);
		}
		
		return $this->_ranks;
	}

	/**
	 * get total topics
	 * 
	 * @access public
	 * @return integer
	 */
	function getTotalTopics() {
	
		// load total topics
		if (empty($this->_totalTopics)) {
			$db =& $this->getDBO();	
			$query = "SELECT SUM(topics)" .
					 "\n FROM #__joobb_forums"
					 ;
			$db->setQuery($query);
			$this->_totalTopics = $db->loadResult();
		}
		
		return $this->_totalTopics;
	}
	
	/**
	 * get total posts
	 * 
	 * @access public
	 * @return integer
	 */
	function getTotalPosts() {
	
		// load posts
		if (empty($this->_totalPosts)) {
			$db =& $this->getDBO();	
			$query = "SELECT SUM(posts)" .
					 "\n FROM #__joobb_forums"
					 ;
			$db->setQuery($query);
			$this->_totalPosts = $db->loadResult();
		}
		
		return $this->_totalPosts;
	}

	/**
	 * get joobb user
	 *
	 * @return object
	 */
	function getJoobbUser($id_user) {

		// load the joobb user
		if (empty($this->_joobbUser)) {
			$this->_joobbUser =& JTable::getInstance('JoobbUser');
		}
		$this->_joobbUser->load($id_user);
			
		return $this->_joobbUser;
	}

	/**
	 * get latest items
	 * 
	 * @access public
	 * @return array
	 */
	function getLatestItems() {
	
		// load latest posts
		if (empty($this->_latestItems)) {
			$joobbConfig =& JoobbConfig::getInstance();
			
			// get the count of items to show
			$limit = (int)$joobbConfig->getBoardSettings('latest_items_count');
		
			$currentUser =& JoobbHelper::getJoobbUser();
			$currentUserId = $currentUser->get('id');
					
			// items to show. topics or posts?
			switch ((int)$joobbConfig->getBoardSettings('latest_items_type')) {
				case 0:
					$innerJoin = "\n INNER JOIN #__joobb_topics AS t ON t.id_first_post = p.id";
					break;
				case 1:
					$innerJoin = "\n INNER JOIN #__joobb_topics AS t ON t.id = p.id_topic";
					break;
				default:
					break;
			}
			
			// are there any categories to be exclude from beeing showed?
			$categoryIds = $joobbConfig->getBoardSettings('exclude_categories', '');
			if (isset($categoryIds)) {
				if (is_array($categoryIds)) {
					$notIn = "('".implode("','",$categoryIds)."')";
				} else {
					$notIn = "('".$categoryIds."')";
				}
				$where = vsprintf("\n AND f.id_cat NOT IN %s", $notIn);
			}
		
			$query = "SELECT p.*, t.id_first_post, t.status, ". $this->getUserAs('u') ." AS author, pg.guest_name AS guest_author, "
					. "\n u.id AS id_user, u.registerDate, ju.posts, f.name AS forum_name, c.name AS category_name"
					. "\n FROM #__joobb_posts AS p"
					. $innerJoin
					. "\n INNER JOIN #__joobb_forums AS f ON f.id = p.id_forum"
					. "\n INNER JOIN #__joobb_categories AS c ON c.id = f.id_cat"
					. "\n LEFT JOIN #__users AS u ON p.id_user = u.id"
					. "\n LEFT JOIN #__joobb_users AS ju ON ju.id = u.id"
					. "\n LEFT JOIN #__joobb_posts_guests AS pg ON p.id = pg.id_post"
					. "\n WHERE (f.auth_read <= (SELECT IFNULL(u.role, 0)"
					. "\n FROM #__joobb_users AS u"
					. "\n WHERE u.id = $currentUserId)"
					. "\n OR f.auth_read <= (SELECT IFNULL(max(a.role), 0)"
					. "\n FROM #__joobb_forums_auth AS a"
					. "\n WHERE a.id_forum = f.id AND a.id_user = $currentUserId AND a.id_group = 0)"
					. "\n OR f.auth_read <= (SELECT IFNULL(max(g.role), 0)"
					. "\n FROM #__joobb_groups_users AS gu"
					. "\n INNER JOIN #__joobb_groups AS g ON g.id = gu.id_group"
					. "\n WHERE gu.id_user = $currentUserId)"
					. "\n OR f.auth_read <= (SELECT IFNULL(max(a.role), 0)"
					. "\n FROM #__joobb_groups_users AS gu"
					. "\n INNER JOIN #__joobb_forums_auth AS a ON a.id_group = gu.id_group"
					. "\n WHERE gu.id_user = $currentUserId AND a.id_forum = f.id))"
					. $where
					. "\n ORDER BY p.date_post DESC LIMIT 0, $limit"
					;

			$this->_latestItems = $this->_getList($query);
		}
		
		return $this->_latestItems;
	}
	
	/**
	 * get post guest name
	 * 
	 * @access public
	 * @return array
	 */
	function getPostGuestName($postId) {
		$db	=& JFactory::getDBO();
		
		$query = "SELECT pg.guest_name"
				. "\n FROM #__joobb_posts_guests AS pg"
				. "\n WHERE pg.id_post = $postId"
				;
		$db->setQuery($query);
		
		return $db->loadResult();
	}
	
	/**
	 * get subscription
	 * 
	 * @access public
	 * @return object
	 */
	function getSubscription($topicId, $userId) {
		$db =& JFactory::getDBO();
		
		$query = "SELECT s.*"
			. "\n FROM #__joobb_topics_subscriptions AS s"
			. "\n WHERE s.id_topic = $topicId"
			. "\n AND s.id_user = $userId"
			;
		$db->setQuery($query);
		
		return $db->loadObject();
	}
	
	/**
	 * get forum moderators
	 * 
	 * @access public
	 * @return string
	 */
	function getForumModerators($forumId) {
		$db =& $this->getDBO();
		$query = "SELECT u.id, ". $this->getUserAs('u') ." AS name"
				. "\n FROM #__users AS u"
				. "\n INNER JOIN #__joobb_users AS ju ON ju.id = u.id"
				. "\n WHERE ju.role = 3"
				;
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}
?>