<?php
/**
 * @version $Id: joobb.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// we need to include some Joo!BB stuff
require_once(JPATH_SITE.DS.'components'.DS.'com_joobb'.DS.'include.php');

$mainframe->registerEvent( 'onSearch', 'plgSearchJoobb' );
$mainframe->registerEvent( 'onSearchAreas', 'plgSearchJoobbAreas' );

JPlugin::loadLanguage('plg_search_joobb');

/**
 * @return array An array of search areas
 */
function &plgSearchJoobbAreas()
{
	static $areas = array(
		'joobb' => 'PLG_JOOBB_SEARCH_FORUM'
	);
	return $areas;
}

/**
 * Forum search method
 *
 * @param string Target search string
 * @param string matching option, exact|any|all
 * @param string ordering option, newest|oldest|popular|alpha|category
 *
 * @return array needed culumns are href, title, section, created, text, browsernav
 */
function plgSearchJoobb($searchWords, $phrase='', $ordering='', $areas=null) {
	$db		=& JFactory::getDBO();
	$user	=& JFactory::getUser();
	$Itemid = JoocmHelper::getMenuId('com_joobb');

	if (is_array($areas)) {
		if (!array_intersect($areas, array_keys(plgSearchJoobbAreas()))) {
			return array();
		}
	}

	// load plugin params info
 	$plugin =& JPluginHelper::getPlugin('search', 'joobb');
 	$pluginParams = new JParameter($plugin->params);

	$limit = $pluginParams->def('search_limit', 50);
	$convertBBToHTML = $pluginParams->def('convert_bb_to_html', 50);
	
	if ($convertBBToHTML) {
		JPlugin::loadLanguage('com_joobb');
	}

	$searchWords = trim($searchWords);
	if ($searchWords == '') {
		return array();
	}

	$section = JText::_('PLG_JOOBB_SEARCH_FORUM');

	switch ($ordering) {
		case 'alpha':
			$order = 'p.subject ASC';
			break;
		case 'category':
			$order = 'c.name ASC, f.name ASC, p.subject ASC';
			break;
		case 'popular':
			$order = 't.views DESC';
		case 'newest':
			$order = 'p.date_post DESC';
		case 'oldest':
			$order = 'p.date_post ASC';
		default:
			$order = 'p.date_post DESC';
	}
	
	$where = _buildWhere($searchWords, $phrase);
	$query = "SELECT p.subject AS title, "
			. "\n CONCAT_WS(': ', ".$db->Quote($section).", CONCAT_WS(' / ', c.name, f.name)) AS section,"
			. "\n p.date_post AS created, p.text, "
			. "\n '2' AS browsernav, p.id_topic, p.id, p.enable_emotions, p.enable_bbcode"
			. "\n FROM #__joobb_posts AS p"
			. "\n INNER JOIN #__joobb_topics AS t ON t.id = p.id_topic"
			. "\n INNER JOIN #__joobb_forums AS f ON f.id = t.id_forum"
			. "\n INNER JOIN #__joobb_categories AS c ON c.id = f.id_cat"
			. "\n LEFT JOIN #__users AS u ON p.id_user = u.id"
			. "\n LEFT JOIN #__joobb_users AS ju ON ju.id = u.id"
			. "\n LEFT JOIN #__joocm_users AS cmu ON cmu.id = u.id"
			. "\n LEFT JOIN #__joobb_posts_guests AS lg ON p.id = lg.id_post"
			. $where
			. "\n ORDER BY ". $order
			;
			
	$db->setQuery($query, 0, $limit);
	$rows = $db->loadObjectList();

	$joobbEngine =& JoobbEngine::getInstance();

	// prepare the search results
	foreach($rows as $key => $row) {
		if ($convertBBToHTML) { 
			$joobbEngine->convertToHtml(&$rows[$key]);
		}
		$rows[$key]->href = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$row->id_topic.'&Itemid='.$Itemid, false).JoobbHelper::getLimitStart($row->id_topic, $row->id).'#p'.$row->id;
	}

	return $rows;
}

/**
 * Build Where Clouse
 *
 * @param string search string
 * @param string matching option exact|any|all
 *
 * @return string where clouse
 */
function _buildWhere($searchWords, $phrase) {
	$db =& JFactory::getDBO();
	$user =& JFactory::getUser();
	$currentUserId = $user->get('id');
	
	// prepare the search key words
	$searchWords = $db->Quote('%'.$db->getEscaped($searchWords, true ).'%', false);
	
	$where = "\n WHERE LOWER(p.subject) LIKE $searchWords"
				. "\n AND f.auth_read <= (SELECT IFNULL(u.role, 0)"
				. "\n FROM #__joobb_users AS u"
				. "\n WHERE u.id = $currentUserId)"	
			. "\n OR LOWER(p.subject) LIKE $searchWords"
				. "\n AND f.auth_read <= (SELECT IFNULL(max(a.role), 0)"
				. "\n FROM #__joobb_forums_auth AS a"
				. "\n WHERE a.id_forum = f.id AND a.id_user = $currentUserId AND a.id_group = 0)"
			. "\n OR LOWER(p.subject) LIKE $searchWords"
				. "\n AND f.auth_read <= (SELECT IFNULL(max(g.role), 0)"
				. "\n FROM #__joobb_groups_users AS gu"
				. "\n INNER JOIN #__joobb_groups AS g ON g.id = gu.id_group"
				. "\n WHERE gu.id_user = $currentUserId)"
			. "\n OR LOWER(p.subject) LIKE $searchWords"
				. "\n AND f.auth_read <= (SELECT IFNULL(max(a.role), 0)"
				. "\n FROM #__joobb_groups_users AS gu"
				. "\n INNER JOIN #__joobb_forums_auth AS a ON a.id_group = gu.id_group"
				. "\n WHERE gu.id_user = $currentUserId AND a.id_forum = f.id)"
			. "\n OR LOWER(p.text) LIKE $searchWords"
				. "\n AND f.auth_read <= (SELECT IFNULL(u.role, 0)"
				. "\n FROM #__joobb_users AS u"
				. "\n WHERE u.id = $currentUserId)"	
			. "\n OR LOWER(p.text) LIKE $searchWords"
				. "\n AND f.auth_read <= (SELECT IFNULL(max(a.role), 0)"
				. "\n FROM #__joobb_forums_auth AS a"
				. "\n WHERE a.id_forum = f.id AND a.id_user = $currentUserId AND a.id_group = 0)"
			. "\n OR LOWER(p.text) LIKE $searchWords"
				. "\n AND f.auth_read <= (SELECT IFNULL(max(g.role), 0)"
				. "\n FROM #__joobb_groups_users AS gu"
				. "\n INNER JOIN #__joobb_groups AS g ON g.id = gu.id_group"
				. "\n WHERE gu.id_user = $currentUserId)"
			. "\n OR LOWER(p.text) LIKE $searchWords"
				. "\n AND f.auth_read <= (SELECT IFNULL(max(a.role), 0)"
				. "\n FROM #__joobb_groups_users AS gu"
				. "\n INNER JOIN #__joobb_forums_auth AS a ON a.id_group = gu.id_group"
				. "\n WHERE gu.id_user = $currentUserId AND a.id_forum = f.id)"
			;

	return $where;
}
