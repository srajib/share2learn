<?php
/**
 * @version $Id: router.php 104 2010-05-18 14:30:50Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

function JoobbBuildRoute(&$query)
{
	$segments = array();
	
	if(isset($query['view'])) {
		$segments[] = $query['view'];
		unset($query['view']);
	};
	
	if(isset($query['task'])) {
		$segments[] = $query['task'];
		unset($query['task']);
	};
	
	if(isset($query['forum'])) {
		$segments[] = JoobbHelper::getForumURL($query['forum']);
		unset($query['forum']);
	};
	
	if(isset($query['topic'])) {
		$segments[] = JoobbHelper::getTopicURL($query['topic']);
		unset($query['topic']);
	};
	
	if(isset($query['post'])) {
		$segments[] = $query['post'];
		unset($query['post']);
	};

	if(isset($query['user'])) {
		$segments[] = $query['user'];
		unset($query['user']);
	};
	
	if(isset($query['id'])) {
		$segments[] = $query['id'];
		unset($query['id']);
	};
				
	return $segments;
}

function JoobbParseRoute($segments)
{
	$vars = array();

	// count route segments
	$count = count($segments);
	
	$vars['view'] = $segments[0];
	
	switch($vars['view']) {
		case 'board' :
			$vars['board'] = $segments[$count-1];
			break;
		case 'forum' :
			$vars['forum'] = substr($segments[$count-1], 0, strpos($segments[$count-1], ':'));
			break;
		case 'topic' :
			$vars['topic'] = substr($segments[$count-1], 0, strpos($segments[$count-1], ':'));
			break;
		case 'edittopic' :
			if ($segments[$count-1] == 0) {
				$vars['forum'] = substr($segments[$count-2], 0, strpos($segments[$count-2], ':'));
				$vars['topic'] = substr($segments[$count-1], 0, strpos($segments[$count-1], ':'));
			} else {
				$vars['topic'] = substr($segments[$count-2], 0, strpos($segments[$count-2], ':'));
				$vars['post'] = $segments[$count-1];				
			}
			break;
		case 'editpost' :
			if ($count < 4) {
				$vars['topic'] = substr($segments[$count-2], 0, strpos($segments[$count-2], ':'));
				$vars['post'] = $segments[$count-1];			
			} else {
				$vars['topic'] = substr($segments[$count-3], 0, strpos($segments[$count-3], ':'));
				$vars['post'] = $segments[$count-2];
				$vars['quote'] = $segments[$count-1];				
			}
			break;
		case 'movetopic' :
			$vars['topic'] = substr($segments[$count-1], 0, strpos($segments[$count-1], ':'));
			break;
		case 'information' :
			if ($count == 2) {
				$vars['info'] = $segments[$count-1];
			} else {
				$vars['info'] = $segments[$count-2];
				$vars['user'] = $segments[$count-1];			
			}
			break;
		case 'reportpost' :
			$vars['post'] = $segments[$count-1];
			break;
		case 'userposts' :
			$vars['id'] = $segments[$count-1];
			break;
		case 'search' :
			$vars['forum'] = substr($segments[$count-1], 0, strpos($segments[$count-1], ':'));
			break;
		case 'latestposts' :
			break;
		case 'subscriptions' :
			break;
		case 'editsettings' :
			break;
		case 'joobbdeletetopic' :
			unset($vars['view']);
			$vars['task'] = $segments[0];
			$vars['topic'] = $segments[$count-1];
			break;
		case 'joobbdeletepost' :
			unset($vars['view']);
			$vars['task'] = $segments[0];
			$vars['post'] = $segments[$count-1];
			break;
		case 'joobblocktopic' || 'joobbunlocktopic':
			unset($vars['view']);
			$vars['task'] = $segments[0];
			$vars['topic'] = substr($segments[$count-1], 0, strpos($segments[$count-1], ':'));
			break;
		case 'joobbunsubscribetopic':
			unset($vars['view']);
			$vars['task'] = $segments[0];
			$vars['topic'] = substr($segments[$count-1], 0, strpos($segments[$count-1], ':'));
			break;
		case 'joobbsubscribetopic':
			unset($vars['view']);
			$vars['task'] = $segments[0];
			$vars['topic'] = substr($segments[$count-1], 0, strpos($segments[$count-1], ':'));
			break;
		default:
			$vars['view']  = $segments[0];
	}
	
	return $vars;
}
?>