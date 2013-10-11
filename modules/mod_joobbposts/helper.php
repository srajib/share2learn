<?php
/**
 * @version $Id: helper.php 223 2012-02-27 18:46:44Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2012 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class modJoobbPostHelper
{
	/**
	 * items data array
	 *
	 * @var array
	 */
	var $_items = null;
	
	/**
	 * params data array
	 *
	 * @var array
	 */
	var $_params = null;
	
	/**
	 * Itemid 
	 *
	 * @var integer
	 */
	var $Itemid = 0;
		
	/**
	 * count of items
	 *
	 * @var integer
	 */	
	var $count = 0;
	
	/**
	 * constructor
	 *
	 * @param array
	 */
	function modJoobbPostHelper($params) {
		$this->_params = $params;
		$this->Itemid = JoocmHelper::getMenuId('com_joobb');
		$this->loadJoobbPosts();
	}
	
	/**
	 * load latest posts
	 */	
	function loadJoobbPosts() {
		$db		=& JFactory::getDBO();

		// get the count of items to show
		$limit = (int)$this->_params->get('count', 5);
		
		// get current user
		$currentUser =& JoobbHelper::getJoobbUser();
		$currentUserId = $currentUser->get('id');
		
		$orderBy = "\n ORDER BY p.date_post DESC";
		
		// items to show. topics or posts?
		switch ((int)$this->_params->get('items', 0)) {
			case 0: // topics
				$innerJoin = "\n INNER JOIN #__joobb_topics AS t ON t.id_first_post = p.id";
		
				// change order by clause to popular?
				if ((int)$this->_params->get('items_type', 0) == 1) {
					$orderBy = "\n ORDER BY t.views DESC";
				}
				break;
			case 1: // posts
				$innerJoin = "\n INNER JOIN #__joobb_topics AS t ON t.id = p.id_topic";
				break;
			default:
				break;
		}
		
		$where = '';
		if ($this->_params->get('since_last_visit', 0) && $currentUserId) {
			// first of all we need to get the real last visit date from user
			//$where = "\n AND p.date_post >= ". $db->quote($currentUser->get('lastvisitDate'));
			//echo 'test: '.$where;
		}
		
		// are there any categories to be exclude from beeing showed?
		$categoryIds = $this->_params->get('exclude_categories', '');
		if (isset($categoryIds)) {
			if (is_array($categoryIds)) {
				$notIn = "('".implode("','",$categoryIds)."')";
			} else {
				$notIn = "('".$categoryIds."')";
			}
			$where = vsprintf("\n AND f.id_cat NOT IN %s", $notIn);
		}
		
		$query = "SELECT p.*, t.id_first_post, t.status, t.views, ". $this->getUserAs('u') ." AS author, pg.guest_name AS guest_author, "
				. "\n u.id AS id_user, u.registerDate, ju.posts"
				. "\n FROM #__joobb_posts AS p"
				. $innerJoin
				. "\n INNER JOIN #__joobb_forums AS f ON f.id = t.id_forum"
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
				. $orderBy
				. "\n LIMIT 0, $limit"
				;

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		if (!$db->getErrorNum()) {
			$this->_items = $rows;
			$this->itemsCount = count($rows);
		}
	}
		
	/**
	 * get user as
	 *
	 * @return string
	 */
	function getUserAs($alias) {
		$joocmConfig =& JoocmConfig::getInstance();
		return ($joocmConfig->getConfigSettings('show_user_as') == 0) ? "$alias.name" : "$alias.username";
	}
		
	/**
	 * get item
	 *
	 * @return object
	 */
	function getItem($index = 0) {
		$item =& $this->_items[$index];

		$item->itemLink = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$item->id_topic.'&Itemid='.$this->Itemid, false).JoobbHelper::getLimitStart($item->id_topic, $item->id).'#p'.$item->id;
		
		$item->authorLink = '';
		if ($item->author) {
			$item->authorLink = JoocmHelper::getLink('profile', '&id='.$item->id_user);
		} else {
			if ($item->guest_author) {
				$item->author = $item->guest_author;
			} else {
				$item->author = JText::_('GUEST');
			}
		}
		
		if ($item->date_post) {
			$item->date_post = JoocmHelper::Date($item->date_post);
		}
				
		return $item;
	}
}
?>