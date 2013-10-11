<?php
/**
 * @version $Id: router.php 104 2010-05-18 14:30:50Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

function JoocmBuildRoute(&$query)
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

	if(isset($query['info'])) {
		$segments[] = $query['info'];
		unset($query['info']);
	};
	
	if(isset($query['user'])) {
		$segments[] = $query['user'];
		unset($query['user']);
	};
	
	if(isset($query['id'])) {
		$segments[] = JoocmHelper::getProfileURL($query['id']);
		unset($query['id']);
	};
			
	return $segments;
}

function JoocmParseRoute($segments)
{
	$vars = array();

	// count route segments
	$count = count($segments);
	
	$vars['view'] = $segments[0];
	
	switch($vars['view']) {
		case 'information' :
			if ($count == 2) {
				$vars['info'] = $segments[$count-1];
			} else {
				$vars['info'] = $segments[$count-2];
				$vars['user'] = $segments[$count-1];			
			}
			break;
		case 'profile' :
			$vars['id'] = substr($segments[$count-1], 0, strpos($segments[$count-1], ':'));
			break;
		case 'register' :
			break;
		case 'terms' :
			break;
		case 'joocmlogout' :
			unset($vars['view']);
			$vars['task'] = $segments[0];
			break;
		case 'joocmagreedterms' :
			unset($vars['view']);
			$vars['task'] = $segments[0];
			break;
		case 'joocmdeleteavatar' :
			unset($vars['view']);
			$vars['task'] = $segments[0];
			break;
		default:
			$vars['view']  = $segments[0];
	}
	
	return $vars;
}
?>