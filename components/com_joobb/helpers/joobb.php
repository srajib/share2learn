<?php
/**
 * @version $Id: joobb.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Helper
 *
 * @package Joo!BB
 */
class JoobbHelper
{

	/**
	 * get a joobb user object
	 */
	function &getJoobbUser() {
	
		// if there is a userid in the session, load the application user object with the logged in user.
		$user = &JFactory::getUser();
		
		$userId = 0;
		if (is_object($user)) {
			$userId = (int)$user->get('id');
		}
		
		$instance =& JoobbUser::getInstance($userId);
		return $instance;
	}
	
	/**
	 * check if a joobb user is logged in
	 */	
	function isUserLoggedIn() {
		$joobbUser =& JoobbHelper::getJoobbUser();
	    return ($joobbUser->get('id')) ? true : false;
	}
	
	/**
	 * get a JoobbEditor object
	 *
	 * @access public
	 * @return object JoobbEditor object
	 */
	function &getEditor() {
		jimport('joomla.html.editor');

		// get the editor configuration setting
		$joobbConfig =& JoobbConfig::getInstance();
		$editor = $joobbConfig->getEditor();

		$instance =& JoobbEditor::getInstance($editor);

		return $instance;
	}
	
	function formatDate($date, $timeformat, $timeZoneOffset=0) {
		jimport('joomla.utilities.date');

		$instance = new JDate($date);
		$instance->setOffset($timeZoneOffset);
		
		return $instance->toFormat($timeformat);
	}

	function getLocale() {
		static $joobbLocale;

		if (!isset($joobbLocale)) {
			$params = JComponentHelper::getParams('com_languages');
			$joobbLocale = $params->get('site', 'en-GB');
		}

		return $joobbLocale;
	}
	
	function getLastPostIdTopic($topicId) {
	
		// initialize variables
		$db		=& JFactory::getDBO();
		
		$query = "SELECT MAX(p.id)"
				. "\n FROM #__joobb_posts AS p"
				. "\n WHERE p.id_topic = $topicId"
				. "\n GROUP BY p.id_topic"
				;
		$db->setQuery($query);
		
		return $db->loadResult();
		
	}
	
	function getLastPostIdForum($forumId) {
	
		// initialize variables
		$db		=& JFactory::getDBO();
		
		$query = "SELECT MAX(p.id)"
				. "\n FROM #__joobb_posts AS p"
				. "\n WHERE p.id_forum = $forumId"
				. "\n GROUP BY p.id_forum"
				;
		$db->setQuery($query);
		
		return $db->loadResult();
		
	}
	
	function getLastPostTimeByIP($ip) {
	
		// initialize variables
		$db		=& JFactory::getDBO();
		
		$query = "SELECT MAX(p.date_post)"
				. "\n FROM #__joobb_posts AS p"
				. "\n WHERE p.ip_poster = '$ip'"
				. "\n GROUP BY p.ip_poster"
				;
		$db->setQuery($query);
		
		return $db->loadResult();
		
	}
	
	function setDefaultConfig($id_config) {
	
		// initialize variables
		$db		=& JFactory::getDBO();
		
		// set selected config to true
		$query = "UPDATE #__joobb_configs"
				. "\n SET default_config = 1"
				. "\n WHERE id = ". $id_config
				;
		$db->setQuery($query);
		
		if ($db->query()) {
				
			// set all other configs to false
			$query = "UPDATE #__joobb_configs"
					. "\n SET default_config = 0"
					. "\n WHERE id <> ". $id_config
					;
			$db->setQuery($query);		

			if (!$db->query()) {
				JError::raiseError(1001, $db->getErrorMsg());
			}						
		} else {
			JError::raiseError(1001, $db->getErrorMsg());
		}				
	}

	function parseXMLFile($folderName, $fileName, $type) {

		$xml = JApplicationHelper::parseXMLInstallFile($folderName.DS.$fileName);

		if ($xml['type'] != $type) {
			return false;
		}

		$data = new StdClass();
		
		foreach($xml as $key => $value) {
			$data->$key = $value;
		}
		
		$data->directory = $folderName;
		$data->checked_out = 0;
		$data->file_name = $fileName;

		return $data;	
	}

	function &getPostPreview(&$post) {

		// initialize variables		
		$joobbConfig	=& JoobbConfig::getInstance();
		$joobbUser		=& JoobbHelper::getJoobbUser();
		$joocmUser		=& JoocmHelper::getJoocmUser();
		$joobbIconSet 	=& JoobbIconSet::getInstance($joobbConfig->getIconSetFile());
				
		// duplicate the object so we do not manipulate original data
		$postPreview = clone $post;

		$postPreview->author = $joobbUser->get('name');
		$postPreview->postDate = JoocmHelper::Date($post->date_post);
		$postPreview->signature = $joocmUser->get('signature');
		
		$joobbEngine =& JoobbEngine::getInstance();
		$joobbEngine->convertToHtml($postPreview);
		
		// post icon
		$postPreview->postIcon = $joobbIconSet->iconByFunction[$postPreview->icon_function];
	
		return $postPreview;
	}
	
	function getForumURL($forumId) {

		// initialize variables		
		$forum =& JTable::getInstance('JoobbForum');
		$forum->load($forumId);
		
		$forumURL = $forum->id .'-'. preg_replace("{[\ ]+}", "_", preg_replace("{[^A-Za-z0-9-\ ]}U", "", $forum->name));

		return $forumURL;
	}
	
	function getTopicURL($topicId) {
	
		if ($topicId) {		
			$topic =& JTable::getInstance('JoobbTopic');
			$topic->load($topicId);
			$firstPost =& JTable::getInstance('JoobbPost');
			$firstPost->load($topic->id_first_post);
			
			$topicURL = $topic->id .'-'. preg_replace("{[\ ]+}", "_", preg_replace("{[^A-Za-z0-9-\ ]}U", "", $firstPost->subject));
		} else {
			$topicURL = $topicId .'-new';
		}
		
		return $topicURL;
	}
		
	/**
	 * get limit start
	 *
	 * @return integer
	 */
	function getLimitStart($topicId, $postId) {
	
		// initialize variables
		$db				=& JFactory::getDBO();
		$joobbConfig	=& JoobbConfig::getInstance();
		
		// make sure we have an interger value
		$topicId = (int)$topicId; $postId = (int)$postId;
				
		// initialize variables
		$postsPerPage	= $joobbConfig->getBoardSettings('posts_per_page');
		$result			= '';
		
		$query = "SELECT p.id"
				. "\n FROM #__joobb_posts AS p"
				. "\n INNER JOIN #__joobb_topics AS t ON t.id = p.id_topic"
				. "\n INNER JOIN #__joobb_forums AS f ON f.id = t.id_forum"
				. "\n WHERE p.id_topic = $topicId"
				. "\n ORDER BY p.id"
				;
		$db->setQuery($query);
		$rows = $db->loadResultArray();
		if (count($rows)) {
			$index = array_search($postId, $rows);
			if ($index) {
				$limitStart = (int)($index / $postsPerPage) * $postsPerPage;
				if ($limitStart > 0) {
					$result = '?start='.$limitStart;
				}
			}
		}
				
		return $result;
	}
	
	/**
	 * get order by options
	 *
	 * @return array
	 */	
	function getOrderByOptions() {
		$orderByOptions = array();

		$orderByOptions[] = JHTML::_('select.option', 'ASC', JText::_('COM_JOOBB_ASCENDING'));
		$orderByOptions[] = JHTML::_('select.option', 'DESC', JText::_('COM_JOOBB_DESCENDING'));

		return $orderByOptions;
	}
	
	/**
	 * get sort by post options
	 *
	 * @return array
	 */	
	function getSortByPostOptions() {
		$sortByPostOptions = array();

		$sortByPostOptions[] = JHTML::_('select.option', 'author', JText::_('COM_JOOBB_AUTHOR'));
		$sortByPostOptions[] = JHTML::_('select.option', 'date_post', JText::_('COM_JOOBB_POSTTIME'));
		$sortByPostOptions[] = JHTML::_('select.option', 'subject', JText::_('COM_JOOBB_SUBJECT'));

		return $sortByPostOptions;
	}
	
	/**
	 * set highlight
	 *
	 * @return array
	 */		
	function setHighlight($text, $search) {
		$search = preg_quote($search);
		$highlight = '<span class="highlight">\1</span>';
		
		// pattern for simple text (case insensitive)
		$pattern = '#(?!<.*?)(%s)(?![^<>]*?>)#i';
		
		// pattern for links (case insensitive)
		$link_pattern = '#<a\s(?:.*?)>(%s)</a>#i';
		
		// strip links
		$link_regex = sprintf($link_pattern, $search);
		$text = preg_replace($link_regex, '\1', $text);
		
		// simple text
		$regex = sprintf($pattern, $search);
		$text = preg_replace($regex, $highlight, $text);
		
		return $text;
	}
}
?>