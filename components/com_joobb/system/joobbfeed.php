<?php
/**
 * @version $Id: joobbfeed.php 135 2010-08-13 10:03:14Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__) .'/../../../libraries/bitfolge/feedcreator.php');

/**
 * Joo!BB Feed
 *
 * @package Joo!BB
 */
class JoobbFeed
{

	/**
	 * Joo!BB Feed
	 */	
	function JoobbFeed() {
		$this->joobbConfig =& JoobbConfig::getInstance();
		$this->Itemid = JoocmHelper::getMenuId('com_joobb');
	}
	
	/**
	 * get a joobb authentification object
	 *
	 * @access public
	 * @return object of JoobbAuth
	 */
	function &getInstance() {
	
		static $joobbFeed;

		if (!is_object($joobbFeed)) {
			$joobbFeed = new JoobbFeed();
		}

		return $joobbFeed;
	}

	function createFeed() {
		$feedRootURL = JURI::root();
		
		$feed = new UniversalFeedCreator();
		$feed->useCached();
		$feed->title = $this->joobbConfig->getBoardSettings('board_name');
		$feed->description = $this->joobbConfig->getBoardSettings('description');

		$feed->descriptionTruncSize = $this->joobbConfig->getFeedSettings('feed_desc_trunk_size');
		$feed->descriptionHtmlSyndicated = $this->joobbConfig->getFeedSettings('feed_desc_html_syndicate');
		
		$feed->cssStyleSheet = "";
		//$feed->xslStyleSheet = "http://feedster.com/rss20.xsl";

		$feed->link = $feedRootURL;
		$feed->feedURL = $feedRootURL.$_SERVER['PHP_SELF'];
		
		// create the feed image
		$feedImage = new FeedImage();
		$feedImage->title = $this->joobbConfig->getFeedSettings('feed_image_title');
		$feedImage->url = $this->joobbConfig->getFeedSettings('feed_image_url');
		$feedImage->link = $this->joobbConfig->getFeedSettings('feed_image_link');
		$feedImage->description = $this->joobbConfig->getFeedSettings('feed_image_description');
		$feedImage->descriptionTruncSize = $this->joobbConfig->getFeedSettings('feed_image_desc_trunk_size');
		$feedImage->descriptionHtmlSyndicated = $this->joobbConfig->getFeedSettings('feed_image_desc_html_syndicate');

		$feed->image = $feedImage;
		
		// get items
		$descriptionTruncSize = $this->joobbConfig->getFeedSettings('feed_desc_trunk_size');
		$descriptionHtmlSyndicated = $this->joobbConfig->getFeedSettings('feed_desc_html_syndicate');
		$items = $this->getFeedItems();
		foreach ($items as $item) {
			$feedItem = new FeedItem();
			$feedItem->title = $item->subject;
			 
			$feedItem->link = JRoute::_($feedRootURL.'index.php?option=com_joobb&view=topic&topic='.$item->id_topic.'&Itemid='.$this->Itemid.'#p'.$item->id);
			$feedItem->description = $item->text;

			$feedItem->descriptionTruncSize = $descriptionTruncSize;
			$feedItem->descriptionHtmlSyndicated = $descriptionHtmlSyndicated;
		
			$feedItem->date = strtotime($item->date_post);
			$feedItem->source = $feedRootURL;
			$feedItem->author = $item->author;
		
			$feed->addItem($feedItem);
		}

		// valid format strings are: RSS0.91, RSS1.0, RSS2.0, PIE0.1, MBOX, OPML, ATOM0.3, HTML, JS
		return $feed->saveFeed("RSS2.0", JPATH_SITE.DS.'boardfeed.xml');
		//return $feed->createFeed();
	}
	
	/**
	 * get feed items
	 * 
	 * @access public
	 * @return array
	 */
	function getFeedItems() {
	
		// initialize variables
		$db				=& JFactory::getDBO();
		
		$currentUser =& JoobbHelper::getJoobbUser();
		$currentUserId = $currentUser->get('id');
				
		// get the count of items to show
		$limit = (int) $this->joobbConfig->getFeedSettings('feed_items_count');
		
		// items to show. topics or posts?
		switch ((int) $this->joobbConfig->getFeedSettings('feed_items_type')) {
			case 0:
				$innerJoin = "\n INNER JOIN #__joobb_topics AS t ON t.id_first_post = p.id";
				break;
			case 1:
				$innerJoin = "\n INNER JOIN #__joobb_topics AS t ON t.id = p.id_topic";
				break;
			default:
				break;
		}
						
		$query = "SELECT p.*, t.id_first_post, t.status, ". ($this->joobbConfig->getViewSettings('show_user_as') == 0 ? "u.name" : "u.username") . " AS author, pg.guest_name AS guest_author, "
				. "\n u.id AS id_user, u.registerDate, ju.posts, f.name AS forum_name, c.name AS category_name"
				. "\n FROM #__joobb_posts AS p"
				. $innerJoin
				. "\n INNER JOIN #__joobb_forums AS f ON f.id = p.id_forum"
				. "\n INNER JOIN #__joobb_categories AS c ON c.id = f.id_cat"
				. "\n LEFT JOIN #__users AS u ON p.id_user = u.id"
				. "\n LEFT JOIN #__joobb_users AS ju ON ju.id = u.id"
				. "\n LEFT JOIN #__joobb_posts_guests AS pg ON p.id = pg.id_post"
				. "\n WHERE f.auth_read <= (SELECT IFNULL(u.role, 0)"
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
				. "\n WHERE gu.id_user = $currentUserId AND a.id_forum = f.id)"
				. "\n ORDER BY p.date_post DESC LIMIT 0, $limit"
				;
		$db->setQuery($query);

		return $db->loadObjectList();
	}

}
?>