<?php
/**
 * @version $Id: joobbpost.php 208 2012-02-20 07:04:33Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Post
 *
 * @package Joo!BB
 */
class JoobbPost
{
	/**
	 * posts
	 * @var array
	 */
	var $posts = null;
	
	/**
	 * avatar
	 * @var object
	 */	
	var $joocmAvatar;

	function JoobbPost($posts) {
		$this->Itemid = JRequest::getVar('Itemid', 0, '', 'int');
		$this->limitStart = JRequest::getVar('limitstart', 0, '', 'int');
		$this->posts = $posts;
		$this->joocmAvatar =& JoocmAvatar::getInstance();
		$this->joobbUser =& JoobbHelper::getJoobbUser();
		
		$joobbConfig =& JoobbConfig::getInstance();
		$this->postsPerPage = $joobbConfig->getBoardSettings('posts_per_page');
	}
	
	function getPost($index = 0) {
		$post = $this->posts[$index];
		
		// calculate the post number
		$post->postNumber = $index + $this->limitStart + 1;
		
		$this->preparePost($post, $index);
		$this->assignSubscription($post);
		$this->assignIcon($post);
		$this->assignButtons($post);
		$this->assignAvatar($post);
		$this->assignRank($post);
		$this->assignAuthor($post);
		$this->assignAuthorRole($post);
		$this->assignOnlineState($post);
		$this->assignAttachments($post);
		
		return $post;
	}
		
	/**
	 * prepare post
	 */
	function preparePost($post) {
		$joobbEngine =& JoobbEngine::getInstance();
		
		$joobbEngine->convertToHtml($post);
		$post->postDate = JoocmHelper::Date($post->date_post);
		
		if ($post->date_last_edit && $post->date_last_edit != 0) {
			$post->lastEditDate = JoocmHelper::Date($post->date_last_edit);
		} else {
			$post->lastEditDate = '';
		}
		
		if (!isset($post->editor)) {
			$post->editor = '';	
		}
						
		if ($post->registerDate) {
			$post->registerDate = JoocmHelper::Date($post->registerDate);
		} else {
			$post->registerDate = '';
		}
				
		if ($post->lastvisitDate) {
			$post->lastvisitDate = JoocmHelper::Date($post->lastvisitDate);
		} else {
			$post->lastvisitDate = '';
		}

		$post->pid = 'p'. $post->id;
		
		// prepare the post link
		$startPosition = $this->getStartPosition($post);
		$limitStart = '';
		if ($startPosition > 0) {
			$config =& JFactory::getConfig();
			if ($config->getValue('config.sef')) {
				$limitStart = '?limitstart='.$startPosition;
			} else {
				$limitStart = '&limitstart='.$startPosition;
			}
		}
		$post->postLink = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$post->id_topic.'&Itemid='.$this->Itemid).$limitStart.'#'.$post->pid;
	}
	
	/**
	 * get start position
	 *
	 * this is for sure not the fastest solution,
	 * but it works for now. it would be better 
	 * to solve it directly in SQL statement.
	 */
	function getStartPosition($post) {
		$db	=& JFactory::getDBO();
		
		$query = "SELECT p.*"
				. "\n FROM #__joobb_posts AS p"
				. "\n WHERE p.id_topic = ". $post->id_topic
				. "\n ORDER BY p.date_post"
				;
		$db->setQuery($query);
		$rows = $db->loadObjectList('id');

		$keys = array_keys($rows);
		$key = array_search($post->id, $keys);
		return floor($key / ($this->postsPerPage+1)) * $this->postsPerPage;
	}
			
	/**
	 * assign subscription
	 */
	function assignSubscription($post) {
	
		$showSubscription = 0;
		if ($post->id_first_post == $post->id && $this->joobbUser->get('id') > 0 ) {
			$showSubscription = 1;
			if (JoobbModel::getSubscription($post->id_topic, $this->joobbUser->get('id'))) {
				$topicSubscription = 0;
				$subscriptionLink = JRoute::_('index.php?option=com_joobb&task=joobbunsubscribetopic&topic='.$post->id_topic.'&Itemid='. $this->Itemid, true);
			} else {
				$topicSubscription = 1;
				$subscriptionLink = JRoute::_('index.php?option=com_joobb&task=joobbsubscribetopic&topic='.$post->id_topic.'&Itemid='. $this->Itemid, true);
			}
			$post->topicSubscription = $topicSubscription;
			$post->subscriptionLink = $subscriptionLink;
		}
		$post->showSubscription = $showSubscription;
	}
				
	/**
	 * assign post icon
	 */
	function assignIcon($post) {
		$joobbIconSet = JoobbIconSet::getInstance();
		$post->postIcon = $joobbIconSet->iconByFunction[$post->icon_function];
	}
	
	/**
	 * assign post buttons
	 */
	function assignButtons($post) {
		$joobbConfig	=& JoobbConfig::getInstance();
		$joobbAuth		=& JoobbAuth::getInstance();
		$joobbUser		=& JoobbHelper::getJoobbUser();
		$joobbButtonSet	=& JoobbButtonSet::getInstance();
		
		$guestTime = $joobbConfig->getBoardSettings('guest_time') * 60;
		
		// quote needs reply authentification
		$post->buttonQuote = null;
		if ($joobbAuth->getAuth('auth_reply', $post->id_forum)) {
			if (!$post->locked && $post->status != 1 ||
				$joobbAuth->getAuth('auth_reply_all', $post->id_forum)) {
				$post->buttonQuote = $joobbButtonSet->buttonByFunction['buttonQuote'];
				$post->buttonQuote->href = JRoute::_('index.php?option=com_joobb&view=editpost&topic='.$post->id_topic.'&post=0&quote='.$post->id.'&Itemid='.$this->Itemid);
			}	
		}

		// edit authentification
		$post->buttonEdit = null;
		if ($joobbAuth->getAuth('auth_edit', $post->id_forum)) {
			if ($joobbUser->get('id') == $post->id_user && $post->id_user != 0 || 
				$post->id_user == 0 && $post->ip_poster == $_SERVER['REMOTE_ADDR'] && (strtotime(gmdate("Y-m-d H:i:s")) - strtotime($post->date_post)) < $guestTime ||
				$joobbAuth->getAuth('auth_edit_all', $post->id_forum)) {
				
				$post->buttonEdit = $joobbButtonSet->buttonByFunction['buttonEdit'];
				
				if ($post->id_first_post != $post->id) {
					$post->buttonEdit->href = JRoute::_('index.php?option=com_joobb&view=editpost&topic='.$post->id_topic.'&post='.$post->id.'&Itemid='.$this->Itemid);
				} else {
					$post->buttonEdit->href = JRoute::_('index.php?option=com_joobb&view=edittopic&topic='.$post->id_topic.'&post='.$post->id.'&Itemid='.$this->Itemid);
				}
			}
		}
		
		// delete authentification
		$post->buttonDelete = null;
		if ($joobbAuth->getAuth('auth_delete', $post->id_forum)) {
			if ($joobbUser->get('id') == $post->id_user && $post->id_user != 0 || 
				$post->id_user == 0 && $post->ip_poster == $_SERVER['REMOTE_ADDR'] && (strtotime(gmdate("Y-m-d H:i:s")) - strtotime($post->date_post)) < $guestTime ||
				$joobbAuth->getAuth('auth_delete_all', $post->id_forum)) {
			
				$post->buttonDelete = $joobbButtonSet->buttonByFunction['buttonDelete'];

				if ($post->id_first_post != $post->id) {
					$post->buttonDelete->captcha = $joobbConfig->getCaptchaSettings('captcha_deletepost');
					if ($post->buttonDelete->captcha) {
						$post->buttonDelete->href = JRoute::_('index.php?option=com_joobb&view=captcha&do=joobbdeletepost&post='.$post->id.'&Itemid='.$this->Itemid);
					} else {
						$post->buttonDelete->href = JRoute::_('index.php?option=com_joobb&task=joobbdeletepost&post='.$post->id.'&Itemid='.$this->Itemid);
					}
				} else {
					$post->buttonDelete->captcha = $joobbConfig->getCaptchaSettings('captcha_deletetopic');
					if ($post->buttonDelete->captcha) {
						$post->buttonDelete->href = JRoute::_('index.php?option=com_joobb&view=captcha&do=joobbdeletetopic&topic='.$post->id_topic.'&Itemid='.$this->Itemid);
					} else {
						$post->buttonDelete->href = JRoute::_('index.php?option=com_joobb&task=joobbdeletetopic&topic='.$post->id_topic.'&Itemid='.$this->Itemid);
					}
				}
			}
		}
		
		// report authentification
		$post->buttonReportPost = null;
		if ($joobbAuth->getAuth('auth_reportpost', $post->id_forum)) {
			$post->buttonReportPost = $joobbButtonSet->buttonByFunction['buttonReportPost'];
			$post->buttonReportPost->href = JRoute::_('index.php?option=com_joobb&view=reportpost&post='.$post->id.'&Itemid='.$this->Itemid);	
		}
	}
		
	/**
	 * assign avatar
	 */
	function assignAvatar($post) {
		$post->avatarFile = $this->joocmAvatar->getAvatarFile($post->id_user);
		$post->avatarFileAlt = $post->author;
	}

	/**
	 * assign author
	 */
	function assignAuthor($post) {		
		$post->authorLink = '';
		$post->postsByAuthorLink = '';
		
		if ($post->author) {
			$post->authorLink = JoocmHelper::getLink('profile', '&id='.$post->id_user);
			$post->postsByAuthorLink = JRoute::_('index.php?option=com_joobb&view=userposts&id='.$post->id_user.'&Itemid='.$this->Itemid);
		} else {
			if ($post->guest_author) {
				$post->author = $post->guest_author;
			} else {
				$post->author = JText::_('COM_JOOBB_GUEST');
			}
		}
	}
			
	/**
	 * assign author role
	 */
	function assignAuthorRole($post) {		
		
		if (!isset($post->id_user)) {
			$post->id_user = 0;
		}

		$joobbUser =& JoobbUser::getInstance($post->id_user);
		
		if ($joobbUser) {
			$role = $joobbUser->getRole($post->id_forum);
	
			switch ($role) {
				case 0:
					$post->authorRole = JText::_('COM_JOOBB_GUEST');
					$post->authorClass = JText::_('jbGuest');
					break;
				case 1:
					$post->authorRole = JText::_('COM_JOOBB_REGISTERED');
					$post->authorClass = JText::_('jbRegistered');
					break;
				case 2:
					$post->authorRole = JText::_('COM_JOOBB_PRIVATE');
					$post->authorClass = JText::_('jbPrivate');
					break;
				case 3:
					$post->authorRole = JText::_('COM_JOOBB_MODERATOR');
					$post->authorClass = JText::_('jbModerator');
					break;
				case 4:
					$post->authorRole = JText::_('COM_JOOBB_ADMINISTRATOR');
					$post->authorClass = JText::_('jbAdministrator');
					break;
				default:
					$post->authorRole = '';
					break;		
			}
		}
	}
				
	/**
	 * assign rank
	 */
	function assignRank($post) {
		$joobbRank	=& JoobbRank::getInstance();
		
		$rank = $joobbRank->getRank($post->posts);
		if ($rank) {
			$post->userRank = $rank->name;
			$post->rankFile = $rank->rank_file;		
		} else {
			$post->userRank = null;
			$post->rankFile = null;
		}
	}
			
	/**
	 * assign online state
	 */
	function assignOnlineState($post) {
		$post->onlineState = false;
		$post->onlineStateFile = 'state_offline.png';
		$post->onlineStateAlt = JText::_('COM_JOOBB_OFFLINE');
		
		if ($post->show_online_state) {
			$db		=& JFactory::getDBO();
				
			$query = "SELECT s.*"
					. "\n FROM #__session AS s"
					. "\n WHERE s.userid = ".$post->id_user
					;
			$db->setQuery($query);
			
			if ($db->loadResult()) {
				$post->onlineState = true;
				$post->onlineStateFile = 'state_online.png';
				$post->onlineStateAlt = JText::_('COM_JOOBB_ONLINE');
			}
		}
	}
		
	/**
	 * assign attachments
	 */
	function assignAttachments($post) {
		$post->attachments = JoobbAttachment::getAttachments($post->id);
	}

	/**
	 * set property
	 *
	 * @access	public
	 */
	function set($property, $value = null) {
		if(isset($this->$property)) {
			$this->$property = $value;
		}			
	}

	/**
	 * get property
	 *
	 * @access	public
	 */
	function get($property, $default = null) {
		if(isset($this->$property)) {
			return $this->$property;
		}			
		return $default;
	}
}
?>