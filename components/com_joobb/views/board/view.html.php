<?php
/**
 * @version $Id: view.html.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * Joo!BB Board View
 *
 * @package Joo!BB
 */
class JoobbViewBoard extends JView
{

	function display($tpl = null) {

		// initialize variables
		$boardName = $this->joobbConfig->getBoardSettings('board_name');
		
		// handle page title
		$this->document->setTitle($boardName);
		
		// handle metadata
		$this->document->setDescription($this->joobbConfig->getBoardSettings('description'));
		$this->document->setMetadata('keywords', $this->joobbConfig->getBoardSettings('keywords'));
		
		// set data model
		$this->model =& $this->getModel();
		$tempcategories =& $this->get('categories');
		$forums =& $this->get('forums');
	
		// look up if there is a forum to display for the category
		$categories = array();
		foreach ($tempcategories as $category) {
			foreach ($forums as $forum) {
				if ($category->id == $forum->id_cat) {
					$categories[] = $category;
					break;
				}
			}
		}
		
		// board icons
		$joobbIconSet = new JoobbIconSet($this->joobbConfig->getIconSetFile());
		$this->assignRef('joobbIconSet', $joobbIconSet);
		
		// initialize latest items	
		if ($this->showBoxLatestItems) {
		
			// items to show. topics or posts?
			$latestItemsHeader = '';
			switch ((int)$this->joobbConfig->getBoardSettings('latest_items_type')) {
				case 0:
					$latestItemsHeader = JText::_('COM_JOOBB_LATESTTOPICS');
					break;
				case 1:
					$latestItemsHeader = JText::_('COM_JOOBB_LATESTPOSTS');
					break;
			}
			$this->assignRef('latestItemsHeader', $latestItemsHeader);
			$this->assignRef('latestItems', $this->get('latestitems'));
		}
		
		// initialize statistic variables
		if ($this->showBoxStatistic) {
			$this->assignRef('totalTopics', $this->get('totaltopics'));
			$this->assignRef('totalPosts', $this->get('totalposts'));
			$this->assignRef('totalMembers', $this->get('totalmembers'));
	
			// get latest members
			$latestMembers =& $this->model->getLatestMembers(1, 0, $this->joobbConfig->getBoardSettings('latest_members_count'));
			$this->assignRef('latestMembers', $latestMembers);
		}
		
		// initialize whos online variables
		if ($this->showBoxWhosOnline) {		
			$sessions = $this->get('sessions');
	
			// calculate number of guests and members
			$membersOnline = 0;
			$guestsOnline = 0;
			foreach ($sessions as $session) {
			
				// if guest increase guest count by 1
				if ($session->userid == 0) {
					$guestsOnline ++;
				}
				
				// if member increase member count by 1
				if ($session->userid != 0) {
					$membersOnline ++;
				}
			}		
			$this->assignRef('membersOnline', $membersOnline);
			$this->assignRef('guestsOnline', $guestsOnline);
		}
		
		$this->assignRef('categories', $categories);
		$this->assignRef('categoriesCount', count($categories));
		$this->assignRef('forums', $forums);
		
		// get online users without guests
		$onlineUsers =& $this->model->getOnlineUsers(1);
		$this->assignRef('onlineUsers', $onlineUsers);
			
		$this->assignRef('searchInputBoxText', JText::_('COM_JOOBB_SEARCHTHISBOARD'));
		
		// get buttons
		$joobbButtonSet	=& JoobbButtonSet::getInstance();
		$this->assignRef('buttonSearch', $joobbButtonSet->buttonByFunction['buttonSearch']);
		$this->assignRef('actionSearch', JRoute::_('index.php?option=com_joobb&view=search&Itemid='.$this->Itemid));
		
		// get feed settings
		$this->assignRef('enableFeeds', $this->joobbConfig->getFeedSettings('enable_feeds'));
		$this->assignRef('feedLink', JRoute::_('index.php?option=com_joobb&task=joobbfeed&Itemid='.$this->Itemid));
		
		if ($this->enableFeeds) {
			switch ((int)$this->joobbConfig->getFeedSettings('feed_items_type')) {
				case 0:
					$feedText = sprintf(JText::_('COM_JOOBB_GETFEEDTOPICS'), $boardName);
					break;
				case 1:
					$feedText = sprintf(JText::_('COM_JOOBB_GETFEEDPOSTS'), $boardName);
					break;
				default:
					break;
			}
			$this->assign('feedText', $feedText);
		}
		
		parent::display($tpl);
	}

	function &getCategory($index = 0) {

		$category =& $this->categories[$index];
		$category->categoryLink = JRoute::_('index.php?option=com_joobb&view=board&category='.$category->id.'&Itemid='.$this->Itemid);

		return $category;
	}
	
	function &getForum($forum) {

		$forum->forumLink = JRoute::_('index.php?option=com_joobb&view=forum&forum='.$forum->id.'&Itemid='.$this->Itemid);
		
		if (!$forum->locked) {
			if ((strtotime(gmdate("Y-m-d H:i:s")) - strtotime($forum->date_post)) > ($forum->new_posts_time * 60)) {
				$forum->forumIcon = $this->joobbIconSet->iconByFunction['forumNormal'];
			} else {
				$forum->forumIcon = $this->joobbIconSet->iconByFunction['forumNewPosts'];
			}
		} else {
			$forum->forumIcon = $this->joobbIconSet->iconByFunction['forumLocked'];
		}
		
		$forum->authorLink = '';
		if ($forum->author) {
			$forum->authorLink = JoocmHelper::getLink('profile', '&id='.$forum->id_user);
		} else {
			if ($forum->guest_author) {
				$forum->author = $forum->guest_author;
			} else {
				$forum->author = JText::_('COM_JOOBB_GUEST');
			}
		}

		if ($forum->date_post) {
			$forum->date_post = JoocmHelper::Date($forum->date_post);
		}
		$forum->lastPostLink = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$forum->id_topic.'&Itemid='.$this->Itemid).JoobbHelper::getLimitStart($forum->id_topic, $forum->id_last_post).'#p'.$forum->id_last_post;
		
		
		// get the forum moderators
		$moderators = array();
		$moderators = $this->model->getForumModerators($forum->id);		
		$forum->moderators = $moderators;
		
		return $forum;
	}
	
	function &getForums($categoryId) {

		$catagoryForums = array();
		foreach ($this->forums as $forum) {
			if ($forum->id_cat == $categoryId) {
				$catagoryForums[] = $forum;
			}
		}
		return $catagoryForums;
	}
	
	function &getOnlineUser($index = 0) {

		$onlineUser =& $this->onlineUsers[$index];
		
		$onlineUser->userLink = '';
		if ($onlineUser->name) {
			$onlineUser->userLink = JoocmHelper::getLink('profile', '&id='.$onlineUser->userid);
		} else {
			$onlineUser->name = JText::_('COM_JOOBB_GUEST');
		}

		return $onlineUser;
	}
	
	/**
	 * get latest item
	 *
	 * @return object
	 */
	function getLatestItem($index = 0) {
	
		$item =& $this->latestItems[$index];
		
		$item->itemLink = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$item->id_topic.'&Itemid='.$this->Itemid).JoobbHelper::getLimitStart($item->id_topic, $item->id).'#p'.$item->id;
		
		// get the topic icon
		$item->itemIcon = $this->joobbIconSet->iconByFunction[$item->icon_function];
		
		$item->authorLink = '';
		if ($item->author) {
			$item->authorLink = JoocmHelper::getLink('profile', '&id='.$item->id_user);
		} else {
			if ($item->guest_author) {
				$item->author = $item->guest_author;
			} else {
				$item->author = JText::_('COM_JOOBB_GUEST');
			}
		}
		
		if ($item->date_post) {
			$item->date_post = JoocmHelper::Date($item->date_post);
		}
				
		return $item;
	}
}
?>